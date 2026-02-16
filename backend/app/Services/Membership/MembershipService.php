<?php
namespace App\Services\Membership;

use App\Models\Membership;
use App\Models\MembershipPlanBenefit;
use App\Models\MembershipBenefit;
use App\Models\MembershipTransactions;
use App\Models\UserMembership;
use App\Models\Transactions;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\TopupTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MembershipService
{
    public function list($search = null, $pagging = 10)
    {
        return Membership::with('benefits','benefits.benefitDetails')->search($search)->paginate($pagging);
    }

    public function create(array $data)
    {
        $membership = Membership::create([
            'odata'    => (string) Str::uuid(),
            'odata_setting' => 'd73e5120-5b9c-44c1-9154-b718794e8fc3',
            'title'    => $data['title'],
            'desc'     => $data['desc'],
            'price'    => $data['price'],
            'discount_percent' => $data['discount_percent'] ?? 0,
            'fee_admin' => $data['fee_admin'] ?? 0,
            'cancel_booking_fee' => $data['cancel_booking_fee'] ?? 0,
            'duration' => $data['duration'],
            'isActive' => $data['isActive'],
        ]);

        // Simpan benefit jika ada
        if (!empty($data['benefits']) && is_array($data['benefits'])) {
            foreach ($data['benefits'] as $benefit) {
                $benefits = MembershipBenefit::whereIn('odata', [$benefit['odata_benefit']])->first();
                MembershipPlanBenefit::create([
                    'odata' => (string) Str::uuid(),
                    'odata_membership' => $membership->odata,
                    'id_membership' => $membership->id,
                    'odata_benefit' => $benefit['odata_benefit'],
                    'id_benefit' => $benefits->id,
                    'value' => $benefit['value'],
                ]);
            }
        }
        activity()
            ->performedOn($membership)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created membership');
        return $membership;
    }

    public function update($odata, array $data)
    {
        $membership = Membership::where('odata', $odata)->first();
        if (!$membership) {
            throw new HttpResponseException(response()->json(['error' => 'Membership not found'], 404));
        }
        $membership->update([
            'title'    => $data['title'],
            'desc'     => $data['desc'],
            'price'    => $data['price'],
            'duration' => $data['duration'],
            'discount_percent' => $data['discount_percent'] ?? 0,
            'fee_admin' => $data['fee_admin'] ?? 0,
            'cancel_booking_fee' => $data['cancel_booking_fee'] ?? 0,
            'isActive' => $data['isActive'],
        ]);

        // Hapus benefit lama
        MembershipPlanBenefit::where('odata_membership', $membership->odata)->delete();
        // Simpan benefit baru
        if (!empty($data['benefits']) && is_array($data['benefits'])) {
            foreach ($data['benefits'] as $benefit) {
                $benefits = MembershipBenefit::whereIn('odata', [$benefit['odata_benefit']])->first();
                MembershipPlanBenefit::create([
                    'odata' => (string) Str::uuid(),
                    'odata_membership' => $membership->odata,
                    'id_membership' => $membership->id,
                    'odata_benefit' => $benefit['odata_benefit'],
                    'id_benefit' => $benefits->id,
                    'value' => $benefit['value'],
                ]);
            }
        }
        activity()
            ->performedOn($membership)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated membership');
        return $membership;
    }

    public function delete($odata)
    {
        $membership = Membership::where('odata', $odata)->first();
        MembershipPlanBenefit::where('odata_membership', $membership->odata)->delete();
        Membership::where('odata', $odata)->delete();
        if (!$membership) {
            throw new HttpResponseException(response()->json(['error' => 'Membership not found'], 404));
        }
        $membership->delete();
        activity()
            ->performedOn($membership)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted membership');
        return true;
    }

    public function subscribe(array $data)
    {
        $membership = Membership::where('odata', $data['membership_odata'])->first();
        if (!$membership) {
            throw new HttpResponseException(response()->json(['error' => 'Membership not found'], 404));
        }
        
        $transaction = MembershipTransactions::create([
            'user_id' => Auth::id(),
            'user_odata' => Auth::user()->odata,
            'membership_id' => $membership->id,
            'membership_odata' => $membership->odata,
            'invoice_code' => 'MEM-' . Str::ulid(),
            'amount' => $membership->price,
            'status' => 'pending',
            'invoice_id' => null,
            'external_id' => null,
            'xendit_invoice_id' => null,
            'payment_url' => null,
            'paid_at' => Carbon::now(),
        ]);

        return $transaction;
    }

    public function handleWebhook(array $data)
    {
        if($data['status'] !== 'PAID') {
            return response()->json(['message' => 'Payment not completed, ignoring webhook'], 200);
        }

        $trx = MembershipTransactions::where('external_id', $data['external_id'])->first();

        $transaction = MembershipTransactions::where('external_id', $data['external_id'])->first();
        if (!$transaction) {
            throw new HttpResponseException(response()->json(['error' => 'Transaction not found'], 404));
        }

        $transaction->update([
            'status' => $data['status'],
            'xendit_invoice_id' => $data['id'],
            'payment_url' => $data['invoice_url'],
            'paid_at' => Carbon::now(),
        ]);


        // Berikan akses membership ke user
        UserMembership::create([
            'user_id' => $transaction->user_id,
            'user_odata' => $transaction->user_odata,
            'membership_id' => $transaction->membership_id,
            'membership_odata' => $transaction->membership_odata,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addWeeks($transaction->membership->duration),
            'status' => 'active',
            'transaction_id' => $transaction->id,
            'transaction_odata' => $transaction->odata,
        ]);


        return $transaction;
    }

    public function getMyMembership()
    {
        $userMembership = UserMembership::with(['membership','membership.benefits','membership.benefits.benefitDetails'])->where('user_id', Auth::id())->first();
        if (!$userMembership) {
            return response()->json(['message' => 'No active membership found'], 404);
        }
        return $userMembership;
    }

    public function listMembership($dateFrom = null, $dateTo = null, $filter = null, $search = null, $paginate = 10, $keyActive = null)
    {
        $memberships = UserMembership::with(['membership','transactions'])
            ->orderBy('created_at', 'desc')
            ->where('user_id', Auth::id())
            ->when($keyActive === 'month', function ($query) use ($filter) {
                if ($filter == '30') {
                    // 30: bulan ini
                    $query->whereMonth('start_date', Carbon::now()->month)
                        ->whereYear('start_date', Carbon::now()->year);
                } else if ($filter == '60') {
                    // 60: bulan lalu
                    $lastMonth = Carbon::now()->subMonth();
                    $query->whereMonth('start_date', $lastMonth->month)
                        ->whereYear('start_date', $lastMonth->year);
                } else if ($filter == '90') {
                    // 90: 90 hari terakhir
                    $query->whereDate('start_date', '>=', Carbon::now()->subDays(90)->toDateString());
                }
            })
            ->when($keyActive === 'date' && $dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                $query->whereDate('start_date', '>=', $dateFrom)
                      ->whereDate('start_date', '<=', $dateTo);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('membership', function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%");
                });
            })
            ->paginate($paginate);
        return $memberships;
    }

    public function getMyTransactions()
    {
        $transactions = MembershipTransactions::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return $transactions;
    }

    public function listTransactions($dateFrom = null, $dateTo = null, $filter = null, $search = null, $paginate = 10, $keyActive = null, $type = null)
    {
        $query = Transactions::query()
            ->with(['user'])
            ->where('transactions.user_id', Auth::id())
            ->when($type, function ($q) use ($type) {
                $q->where('transactions.type', $type);
            })

            ->leftJoin('membership_transactions as mt', function ($join) {
                $join->on('transactions.reference_id', '=', 'mt.id')
                    ->where('transactions.type', 'MEMBERSHIP');
            })

            ->leftJoin('booking_payments as bp', function ($join) {
                $join->on('transactions.reference_id', '=', 'bp.id')
                    ->where('transactions.type', 'BOOKING');
            })

            ->leftJoin('topup_transactions as tt', function ($join) {
                $join->on('transactions.reference_id', '=', 'tt.id')
                    ->where('transactions.type', 'TOPUP');
            })

            ->leftJoin('membership as mp', 'mp.id', '=', 'mt.membership_id')
            ->leftJoin('bookings as bt', 'bt.id', '=', 'bp.booking_id')
            ->leftJoin('properties as p', 'p.id', '=', 'bt.property_id')

            

            ->select([
                'transactions.*',

                // dynamic label
                DB::raw("
                    CASE
                        WHEN transactions.type = 'MEMBERSHIP' THEN mp.title
                        WHEN transactions.type = 'BOOKING' THEN p.properties
                        WHEN transactions.type = 'TOPUP' THEN 'Top Up'
                        ELSE transactions.description
                    END as title
                "),

                DB::raw("
                    CASE
                        WHEN transactions.type = 'MEMBERSHIP' THEN mt.invoice_code
                        WHEN transactions.type = 'BOOKING' THEN bp.invoice_code
                        WHEN transactions.type = 'TOPUP' THEN tt.invoice_code   
                        ELSE NULL
                    END as invoice_number
                "),

                
            ])

            ->orderBy('transactions.paid_at', 'desc');

        $now = Carbon::now();

        $query->when($keyActive === 'month', function ($q) use ($filter, $now) {

            if ($filter == '30') {
                // bulan ini
                $q->whereBetween('transactions.paid_at', [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth()
                ]);
            }

            if ($filter == '60') {
                // bulan lalu
                $lastMonth = $now->copy()->subMonth();
                $q->whereBetween('transactions.paid_at', [
                    $lastMonth->startOfMonth(),
                    $lastMonth->endOfMonth()
                ]);
            }

            if ($filter == '90') {
                // ✅ 90 hari terakhir (rolling)
                $q->whereBetween('transactions.paid_at', [
                    $now->copy()->subDays(90)->startOfDay(),
                    $now->copy()->endOfDay()
                ]);
            }
        });


        $query->when($keyActive === 'date' && $dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('transactions.paid_at', [$dateFrom, $dateTo]);
        });

        $query->when($search, function ($q) use ($search) {
            $q->where(function ($x) use ($search) {
                $x->where('mp.title', 'like', "%$search%")          // membership
                // ->orWhere('bp.booking_code', 'like', "%$search%")// booking
                ->orWhere('transactions.description', 'like', "%$search%");
            });
        });

        return $query->paginate($paginate);
    }

    public function getMyBooking()
    {
        $bookings = Booking::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return $bookings;
    }

    public function listBooking($dateFrom = null, $dateTo = null, $filter = null, $search = null, $paginate = 10, $keyActive = null)
    {
        $query = Booking::query()
            ->with(['property', 'room', 'membership', 'payments'])
            ->where('bookings.user_id', Auth::id());

        $now = Carbon::now();

        $query->when($keyActive === 'month', function ($q) use ($filter, $now) {

            if ($filter == '30') {
                // bulan ini
                $q->whereBetween('bookings.created_at', [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth()
                ]);
            }

            if ($filter == '60') {
                // bulan lalu
                $lastMonth = $now->copy()->subMonth();
                $q->whereBetween('bookings.created_at', [
                    $lastMonth->startOfMonth(),
                    $lastMonth->endOfMonth()
                ]);
            }

            if ($filter == '90') {
                // ✅ 90 hari terakhir (rolling)
                $q->whereBetween('bookings.created_at', [
                    $now->copy()->subDays(90)->startOfDay(),
                    $now->copy()->endOfDay()
                ]);
            }
        });

        $query->when($keyActive === 'date' && $dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('bookings.created_at', [$dateFrom, $dateTo]);
        });

        $query->when($search, function ($q) use ($search) {
            $q->where(function ($x) use ($search) {
                $x->where('bookings.booking_code', 'like', "%$search%")
                ->orWhereHas('property', function ($p) use ($search) {
                    $p->where('name', 'like', "%$search%");
                })
                ->orWhereHas('room', function ($r) use ($search) {
                    $r->where('name', 'like', "%$search%");
                });
            });
        });

        return $query->paginate($paginate);
    }



    public function getInvoiceData($invoice_number)
    {
        $trx = Transactions::where('odata', $invoice_number)
            ->where('user_id', Auth::id())
            ->with(['user'])
            ->first();
        if (!$trx) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }
        $detail = $this->resolveReference($trx);
        
        return [
            'transaction' => $trx,
            'detail' => $detail
        ];
    }

    private function resolveReference($trx)
    {
        switch ($trx->type) {

            case 'BOOKING':
                return BookingPayment::with(['booking','booking.property', 'booking.room','booking.passengers'])->find($trx->reference_id);

            case 'MEMBERSHIP':
                return MembershipTransactions::with('membership','userMembership')->find($trx->reference_id);

            case 'TOPUP':
                return TopupTransactions::with('user')->find($trx->reference_id);

            default:
                return null;
        }
    }

}

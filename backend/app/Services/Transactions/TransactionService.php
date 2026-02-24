<?php

namespace App\Services\Transactions;

use App\Models\WalletLedger;
use App\Models\BookingPayment;
use App\Models\MembershipTransactions;
use App\Models\TopUpTransactions;
use App\Models\Transactions;
use App\Models\Booking;
use App\Models\UserMembership;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function list_wallet($search = null, $pagging = null, $filterType = null, $filterSource = null, $filterDate = null)
    {
        return WalletLedger::with('user')->when($search, function($q) use ($search) {
                $q->where('source', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                    });
            })
            ->when($filterType, function($q) use ($filterType) {
                $q->where('type', $filterType);
            })
            ->when($filterSource, function($q) use ($filterSource) {
                $q->where('source', $filterSource);
            })
            ->when($filterDate, function($q) use ($filterDate) {
                $q->whereBetween('created_at', [$filterDate[0], $filterDate[1]]);
            })
            ->orderByDesc('created_at')
            ->paginate($pagging);
    }

    public function detail($odata, $source)
    {
        $relation = null;
        if ($source === 'TOPUP') {
            $relation = 'topup_transactions';
        } elseif ($source === 'MEMBERSHIP') {
            $relation = 'membership_transactions';
        } elseif ($source === 'BOOKING') {
            $relation = 'booking_payments';
        }
        $query = WalletLedger::with('user');
        if ($relation) {
            $query = $query->with($relation);
        }
        return $query->where('reference_odata', $odata)->where('source', $source)->first();
    }

    public function list_booking_transactions($search = null, $pagging = null,  $filterDate = null, $status = null)
    {
        return BookingPayment::with(['booking','booking.user','booking.property','booking.room','booking.membership','booking.passengers'])
            ->when($search, function($q) use ($search) {
                $q->where('invoice_code', 'like', "%$search%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                    });
            })
            ->when($filterDate, function($q) use ($filterDate) {
                $q->whereBetween('created_at', [$filterDate[0], $filterDate[1]]);
            })
            ->when($status, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate($pagging);
    }

    public function list_membership_transactions($search = null, $pagging = null,  $filterDate = null)
    {
        return MembershipTransactions::with(['membership','user'])
            ->when($search, function($q) use ($search) {
                $q->where('invoice_code', 'like', "%$search%")
                    ->orWhereHas('userMembership.user', function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                    });
            })
            ->when($filterDate, function($q) use ($filterDate) {
                $q->whereBetween('created_at', [$filterDate[0], $filterDate[1]]);
            })
            ->orderByDesc('created_at')
            ->paginate($pagging);
    }

    public function list_top_up_transactions($search = null, $pagging = null,  $filterDate = null)
    {
        return TopUpTransactions::with(['user'])
            ->when($search, function($q) use ($search) {
                $q->where('invoice_code', 'like', "%$search%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                    });
            })
            ->when($filterDate, function($q) use ($filterDate) {
                $q->whereBetween('created_at', [$filterDate[0], $filterDate[1]]);
            })
            ->orderByDesc('created_at')
            ->paginate($pagging);
    }

    public function list_all_transactions($search = null, $pagging = null, $filterType = null, $filterStatus = null, $filterDate = null, $filterUnpaid = null)
    {
        return Transactions::with(['user'])
            ->when($search, function($q) use ($search) {
            $q->where('source', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->when($filterUnpaid === 'Y', function($q) {
            $q->where('status', 'PENDING')
                ->where('type', 'BOOKING')
                ->where('created_at', '<', now()->subHour());
            })
            ->when($filterType && $filterUnpaid !== 'Y', function($q) use ($filterType) {
                $q->where('type', $filterType);
            })
            ->when($filterStatus && $filterUnpaid !== 'Y', function($q) use ($filterStatus) {
                $q->where('status', $filterStatus);
            })
            ->when($filterDate, function($q) use ($filterDate) {
                $q->whereBetween('created_at', [$filterDate[0], $filterDate[1]]);
            })
            ->orderByDesc('created_at')
            ->paginate($pagging);
    }

    public function detail_transaction($odata, $type)
    {
        $query = Transactions::with('user');
        if ($type === 'TOPUP') {
            $query = $query->with('topup');
        } elseif ($type === 'MEMBERSHIP') {
            $query = $query->with([
                'membership',
                'membership.userMembership',
                'membership.userMembership.user',
                'user',
            ]);
        } elseif ($type === 'BOOKING') {
            $query = $query->with([
                'bookingPayments',
                'bookingPayments.booking',
                'bookingPayments.booking.user',
                'bookingPayments.booking.property',
                'bookingPayments.booking.room',
                'bookingPayments.booking.membership',
                'bookingPayments.booking.passengers'
            ]);
        }
        return $query->where('reference_odata', $odata)->where('type', $type)->first();
    }

    public function cancel_booking_transactions($odata)
    {
        $booking = Booking::where('odata', $odata)->first();
        if (!$booking) {
            throw new HttpResponseException(response()->json(['error' => 'Booking not found'], 404));
        }

        $bookingPayment = BookingPayment::where('booking_id', $booking->id)->first();
        if (!$bookingPayment) {
            throw new HttpResponseException(response()->json(['error' => 'Booking payment not found'], 404));
        }

        $transaction = Transactions::where('reference_odata', $odata)->where('type', 'BOOKING')->first();
        if (!$transaction) {
            throw new HttpResponseException(response()->json(['error' => 'Transaction not found'], 404));
        }

        // Prevent double cancellation/refund
        $cancelTransactionExists = Transactions::where('reference_id', $transaction->id)
            ->where('type', 'CANCEL')
            ->exists();

        if ($cancelTransactionExists) {
            throw new HttpResponseException(response()->json(['error' => 'Booking already cancelled/refunded'], 404));
        }

        DB::transaction(function () use ($booking, $transaction, $bookingPayment) {
            $booking->update(['status' => 'CANCELLED']);

            BookingPayment::where('booking_id', $booking->id)->update(['status' => 'REFUNDED']);

            Transactions::create([
                'odata' => (string) Str::uuid(),
                'user_id' => $transaction->user_id,
                'user_odata' => $transaction->user_odata,
                'type' => 'CANCEL',
                'reference_id' => $transaction->id,
                'reference_odata' => $transaction->reference_odata,
                'amount' => $transaction->amount,
                'fee' => $transaction->fee,
                'total_amount' => $transaction->total_amount,
                'payment_method' => $transaction->payment_method,
                'status' => 'REFUNDED',
                'description' => "Cancel booking {$bookingPayment->invoice_code}",
                'paid_at' => now(),
            ]);

            $lastLedger = WalletLedger::where('user_id', $transaction->user_id)
                ->latest('id')
                ->lockForUpdate()
                ->first();

            $before = $lastLedger ? $lastLedger->balance_after : 0;
            $after  = $before + $transaction->amount;

            WalletLedger::create([
                'odata' => (string) Str::uuid(),
                'user_id' => $transaction->user_id,
                'user_odata' => $transaction->user_odata,
                'type' => 'CREDIT',
                'source' => 'REFUND',
                'amount' => $transaction->amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'description' => "Refund booking {$bookingPayment->invoice_code}",
                'reference_id' => $transaction->id,
                'reference_odata' => $transaction->reference_odata,
            ]);

            Activity()
                ->performedOn($transaction)
                ->causedBy($transaction->user_id)
                ->withProperties(['attributes' => ['booking_id' => $booking->id]])
                ->event('cancel')
                ->log('cancelled booking transaction');
        });

        return $transaction;
    }

    public function get_booking($search = null, $pagging = null, $date = null, $status = null)
    {
        return Booking::with(['user','property','room','membership','passengers','payments'])
            ->when($search, function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->when($status, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($date, function($q) use ($date) {
                $q->whereBetween('created_at', [$date[0], $date[1]]);
            })
            ->when(isset($status) && $status === 'CONFIRMED', function($q) use ($date) {
                $startDate = $date[0] ?? null;
                $endDate = $date[1] ?? null;
                if ($startDate && $endDate) {
                    $q->where('checkin', 'Y')
                        ->whereBetween('checkout_date', [$startDate, $endDate]);
                }
            })
            ->when(isset($status) && $status === 'ACTIVE_BOOKING', function($q) use ($date) {
                $startDate = $date[0] ?? null;
                $endDate = $date[1] ?? null;
                if ($startDate && $endDate) {
                    $q->where('checkin', 'Y')
                        ->where('checkin_date', '<=', $endDate)
                        ->where('checkout_date', '>=', $startDate);
                }
            })
            ->when(isset($status) && $status === 'CHECK_IN_TODAY', function($q) {
                $q->whereDate('checkin_date', \Carbon\Carbon::today())
                  ->whereDate('check_out', \Carbon\Carbon::today());
            })
            ->orderByDesc('created_at')
            ->paginate($pagging);
    }

    public function membership_list($search = null, $pagging = null, $date = null, $status = null)
    {
        return UserMembership::with(['user','membership','transactions'])
            ->when($search, function($q) use ($search) {
                $q->WhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                    });
            })
            ->when($date, function($q) use ($date) {
                $q->whereBetween('created_at', [$date[0], $date[1]]);
            })
            ->when($status, function($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate($pagging);
    }

}

<?php
namespace App\Services\Member;

use App\Models\User;
use App\Models\City;

class MemberService
{
    public function list($search = null, $pagging = 10)
    {
        return User::with('userMemberships','userMemberships.membership','roles','wallet_point','point_transactions','bookings','transactions')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['users']);
            })
            ->withSum(['point_transactions as total_credit_amount' => function ($query) {
                $query->where('type', 'credit');
            }], 'amount')
            ->withCount(['bookings as total_stay' => function ($query) {
                $query->where('status', 'COMPLETED');
            }])
            ->withSum(['transactions as total_transaction_amount' => function ($query) {
                $query->where('status', 'PAID');
            }], 'total_amount')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('telp', 'like', "%$search%");
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            })
            ->whereHas('userMemberships') // hanya user yang punya membership
            ->paginate($pagging);
    }

    public function list_customers($search = null, $pagging = 10)
    {
        $users = User::with('userMemberships','userMemberships.membership','roles','wallet_point','point_transactions','bookings','transactions')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['users']);
            })
            ->withSum(['point_transactions as total_credit_amount' => function ($query) {
                $query->where('type', 'credit');
            }], 'amount')
            ->withCount(['bookings as total_stay' => function ($query) {
                $query->where('status', 'COMPLETED');
            }])
            ->withSum(['transactions as total_transaction_amount' => function ($query) {
                $query->where('status', 'PAID');
            }], 'total_amount')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('telp', 'like', "%$search%");
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            })
            ->paginate($pagging);

        // Add first and last booking date, total nights, and total redeemed points for each user
        $users->getCollection()->transform(function ($user) {
            $firstBooking = $user->bookings->sortBy('created_at')->first();
            $lastBooking = $user->bookings->sortByDesc('created_at')->first();
            $user->first_booking_date = $firstBooking ? $firstBooking->created_at : null;
            $user->last_booking_date = $lastBooking ? $lastBooking->created_at : null;

            // Calculate total nights (total_malam)
            $totalMalam = 0;
            foreach ($user->bookings as $booking) {
                if (isset($booking->check_in) && isset($booking->check_out)) {
                    $checkIn = strtotime($booking->check_in);
                    $checkOut = strtotime($booking->check_out);
                    if ($checkIn && $checkOut && $checkOut > $checkIn) {
                        $totalMalam += (int) round(($checkOut - $checkIn) / 86400);
                    }
                }
            }
            $user->total_malam = $totalMalam;

            // Calculate total redeemed points (total_point_redeem)
            $totalRedeem = 0;
            if ($user->point_transactions) {
                foreach ($user->point_transactions as $pt) {
                    if (isset($pt->type) && $pt->type === 'DEBIT') {
                        $totalRedeem += (int) $pt->amount;
                    }
                }
            }
            $user->total_point_redeem = $totalRedeem;

            return $user;
        });

        return $users;
    }

    public function getKota()
    {
        return City::all();
    }

    public function updateCustomer($odata, array $data)
    {
        $customer = User::where('odata', $odata)->firstOrFail();

        if (isset($data['birth_date'])) {
            $customer->birth_date = $data['birth_date'];
        }
        if (isset($data['gender'])) {
            $customer->gender = $data['gender'];
        }
        if (isset($data['city'])) {
            $customer->kota = $data['city'];
        }

        if (isset($data['catatan'])) {
            $customer->catatan = $data['catatan'];
        }

        $customer->save();
        return $customer;
    }
}

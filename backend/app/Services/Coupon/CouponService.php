<?php

namespace App\Services\Coupon;

use App\Models\Coupon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CouponService
{
    public function list($search = null, $paginate = 10, $type = 'all')
    {
        $query = Coupon::query();

        if ($search) {
            $query->where('title', 'like', "%$search%");
        }

        if ($type) {
            $query->where('type_coupon', $type);
        }

        return $query->paginate($paginate);
    }

    public function create(array $data)
    {
        $coupon = Coupon::create([
            'odata' => (string) Str::uuid(),
            'code' => $data['code'],
            'type_coupon' => $data['type_coupon'] ?? 'all',
            'jenis' => $data['jenis'] ?? 'all',
            'type' => $data['type'],
            'title' => $data['name'],
            'value' => $data['value'],
            'value_cashback' => $data['value_cashback'] ?? 0,
            'minimum_transaction' => $data['min_transaction'],
            'maximum_discount' => $data['max_discount'],
            'usage_limit' => $data['usage_limit'],
            'usage_per_user' => $data['usage_per_user'],
            'used_count' => $data['usage_count'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_active' => $data['status'],
            'is_show' => $data['is_show']
        ]);

        activity()
            ->performedOn($coupon)
            ->causedBy(Auth::user())
            ->event('create')
            ->log('created coupon');

        return $coupon;
    }

    public function update($odata, array $data)
    {
        $coupon = Coupon::where('odata', $odata)->first();
        if (!$coupon) {
            throw new HttpResponseException(response()->json(['error' => 'Coupon not found'], 404));
        }

        $coupon->update([
            'code' => $data['code'],
            'type_coupon' => $data['type_coupon'] ?? $coupon->type_coupon,
            'jenis' => $data['jenis'] ?? $coupon->jenis,
            'type' => $data['type'],
            'title' => $data['name'],
            'value' => $data['value'],
            'value_cashback' => $data['value_cashback'] ?? $coupon->value_cashback,
            'minimum_transaction' => $data['min_transaction'],
            'maximum_discount' => $data['max_discount'],
            'usage_limit' => $data['usage_limit'],
            'usage_per_user' => $data['usage_per_user'],
            'used_count' => $data['usage_count'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_active' => $data['status'],
            'is_show' => $data['is_show']
        ]);

        activity()
            ->performedOn($coupon)
            ->causedBy(Auth::user())
            ->event('update')
            ->log('updated coupon');
        return $coupon;
    }

}
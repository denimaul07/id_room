<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Coupon\CouponService;
use App\Http\Requests\Coupon\CreateCouponRequest;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    protected $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }


    public function index(Request $request)
    {
        $search = $request->search;
        $paginate = $request->paginate ?? 10;
        $type = $request->type ?? 'all';
        $data = $this->couponService->list($search, $paginate, $type);
        return response()->json(['data' => $data], 200);
    }

    public function store(CreateCouponRequest $request)
    {
        $data = $request->only([
            'code',
            'type_coupon',
            'jenis',
            'type',
            'name',
            'value',
            'value_cashback',
            'min_transaction',
            'max_discount',
            'usage_limit',
            'usage_per_user',
            'usage_count',
            'start_date',
            'end_date',
            'status',
            'is_show',
        ]);

        $coupon = $this->couponService->create($data);
        return response()->json(['message' => 'Coupon created successfully', 'data' => $coupon], 201);
    }


    public function update(Request $request)
    {
        $odata = $request->odata;
        $coupon = $this->couponService->update($odata, $request->only([
            'code',
            'type_coupon',
            'jenis',
            'type',
            'name',
            'value',
            'value_cashback',
            'min_transaction',
            'max_discount',
            'usage_limit',
            'usage_per_user',
            'usage_count',
            'start_date',
            'end_date',
            'status',
            'is_show'
        ]));
        return response()->json(['message' => 'Coupon updated successfully'], 200);
    }

}

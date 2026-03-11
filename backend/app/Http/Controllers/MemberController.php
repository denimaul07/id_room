<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Member\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{

    protected $memberService;
    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }


    public function index(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $data = $this->memberService->list($search, $pagging);
        return response()->json(['data' => $data], 200);
    }

    public function customers(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $data = $this->memberService->list_customers($search, $pagging);
        return response()->json(['data' => $data], 200);
    }

    public function getKota()
    {
        $data = $this->memberService->getKota();
        return response()->json(['data' => $data], 200);
    }

    public function updateCustomer(Request $request)
    {
        $odata = $request->odata;
        $birth_date = $request->birth_date;
        $gender = $request->gender;
        $city= $request->city;
        $catatan = $request->catatan;
        $customer = $this->memberService->updateCustomer($odata, $request->only(['birth_date', 'gender', 'city', 'catatan']));
        return response()->json(['message' => 'Customer data updated successfully'], 200);
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Referral\ReferralService;
use Illuminate\Http\Request;

class ReferralSettingController extends Controller
{

    protected $referralService;
    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }


    public function index(Request $request)
    {

        $data = $this->referralService->list();
        return response()->json(['data' => $data], 200);
    }


    public function update(Request $request)
    {
        $odata = $request->odata;
        $referral = $this->referralService->update($odata, $request->only([
            'reward_referrer',
            'reward_referred'
        ]));
        return response()->json(['message' => 'Referral updated successfully'], 200);
    }

}

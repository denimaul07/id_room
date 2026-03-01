<?php

namespace App\Services\Referral;

use App\Models\Referral_Setting;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ReferralService
{
    public function list()
    {
        $query = Referral_Setting::get();
        return $query;
    }


    public function update($odata, array $data)
    {
        $referral = Referral_Setting::where('odata', $odata)->first();
        if (!$referral) {
            throw new HttpResponseException(response()->json(['error' => 'Referral setting not found'], 404));
        }

        $referral->update($data);

        activity()
            ->performedOn($referral)
            ->causedBy(Auth::user())
            ->event('update')
            ->log('updated referral setting');

        return $referral;
    }

}
<?php

namespace App\Services\PpnFee;

use App\Models\Setting;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class PpnFeeService
{
    public function list()
    {
        $query = Setting::get();
        return $query;
    }


    public function update($odata, array $data)
    {
        $ppnFee = Setting::where('odata', $odata)->first();
        if (!$ppnFee) {
            throw new HttpResponseException(response()->json(['error' => 'PPN Tax Point not found'], 404));
        }

        $ppnFee->update($data);

        activity()
            ->performedOn($ppnFee)
            ->causedBy(Auth::user())
            ->event('update')
            ->log('updated PPN Tax Point with odata: ' . $odata);

        return $ppnFee;
    }

}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PpnFee\PpnFeeService;
use Illuminate\Http\Request;

class PpnTaxPointController extends Controller
{

    protected $ppnFee;
    public function __construct(PpnFeeService $ppnFee)
    {
        $this->ppnFee = $ppnFee;
    }


    public function index(Request $request)
    {

        $data = $this->ppnFee->list();
        return response()->json(['data' => $data], 200);
    }


    public function update(Request $request)
    {
        $odata = $request->odata;
        $ppnFee = $this->ppnFee->update($odata, $request->only([
            'ppn',
            'fee',
            'convert_point',
            'deposite',
        ]));
        return response()->json(['message' => 'PPN Tax Point updated successfully'], 200);
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Points\PointService;
use Illuminate\Http\Request;

class PointsController extends Controller
{

    protected $pointService;
    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }


    public function myPoints(Request $request)
    {
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $filter = $request->filter;
        $search = $request->search;
        $paginate = $request->paginate;
        $keyActive = $request->keyActive;

        $data = $this->pointService->list($dateFrom, $dateTo, $filter, $search, $paginate, $keyActive);
        return response()->json(['data' => $data], 200);
    }


}

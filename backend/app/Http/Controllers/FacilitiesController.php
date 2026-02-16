<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Facilities\FacilitiesRequest;
use App\Http\Requests\Facilities\FacilitiesUpdateRequest;
use App\Services\Facilities\FacilitiesService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class FacilitiesController extends Controller
{
    protected $FacilitiesService;

    public function __construct(FacilitiesService $FacilitiesService)
    {
        $this->FacilitiesService = $FacilitiesService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $data = $this->FacilitiesService->list($search, $pagging);
            $response = [
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(FacilitiesRequest $request)
    {
        try {
            $this->FacilitiesService->create($request->only([
                'name',
                'type',
                'icon'
            ]));

            $response = [
                'message' => 'Facility created successfully'
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(FacilitiesUpdateRequest $request)
    {
        try {
            $odata = $request->odata;
            $this->FacilitiesService->update($odata, $request->only([
                'name',
                'type',
                'icon'
            ]));

            $response = [
                'message' => 'Facility updated successfully'
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy(Request $request)
    {
        $odata = $request->odata;
        $this->FacilitiesService->delete($odata);
        return response()->json(['success' => true]);
    }
}

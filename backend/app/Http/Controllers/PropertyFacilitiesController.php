<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyFacilities\PropertyFacilitiesRequest;
use App\Http\Requests\PropertyFacilities\PropertyFacilitiesUpdateRequest;
use App\Services\PropertyFacilities\PropertyFacilitiesService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class PropertyFacilitiesController extends Controller
{
    protected $PropertyFacilitiesService;

    public function __construct(PropertyFacilitiesService $PropertyFacilitiesService)
    {
        $this->PropertyFacilitiesService = $PropertyFacilitiesService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $property_odata = $request->property_odata;
            $data = $this->PropertyFacilitiesService->list($search, $pagging, $property_odata);
            $response = [
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(PropertyFacilitiesRequest $request)
    {
        try {
            $this->PropertyFacilitiesService->create($request->only([
                'property_odata',
                'facility_odata'
            ]));

            $response = [
                'message' => 'Property facility created successfully'
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(PropertyFacilitiesUpdateRequest $request)
    {
        try {
            $odata = $request->odata;
            $this->PropertyFacilitiesService->update($odata, $request->only([
                'property_odata',
                'facility_odata'
            ]));

            $response = [
                'message' => 'Property facility updated successfully'
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy(Request $request)
    {
        $odata = $request->odata;
        $this->PropertyFacilitiesService->delete($odata);
        return response()->json(['success' => true]);
    }
}

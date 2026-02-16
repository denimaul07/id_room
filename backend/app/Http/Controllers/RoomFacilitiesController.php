<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomFacilities\RoomFacilitiesRequest;
use App\Http\Requests\RoomFacilities\RoomFacilitiesUpdateRequest;
use App\Services\RoomFacilities\RoomFacilitiesService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class RoomFacilitiesController extends Controller
{
    protected $RoomFacilitiesService;

    public function __construct(RoomFacilitiesService $RoomFacilitiesService)
    {
        $this->RoomFacilitiesService = $RoomFacilitiesService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $room_odata = $request->room_odata;
            $data = $this->RoomFacilitiesService->list($search, $pagging, $room_odata);
            $response = [
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(RoomFacilitiesRequest $request)
    {
        try {
            $this->RoomFacilitiesService->create($request->only([
                'room_odata',
                'facility_odata'
            ]));

            $response = [
                'message' => 'Room facility created successfully'
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(RoomFacilitiesUpdateRequest $request)
    {
        try {
            $odata = $request->odata;
            $this->RoomFacilitiesService->update($odata, $request->only([
                'room_odata',
                'facility_odata'
            ]));

            $response = [
                'message' => 'Room facility updated successfully'
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy(Request $request)
    {
        $odata = $request->odata;
        $this->RoomFacilitiesService->delete($odata);
        return response()->json(['success' => true]);
    }
}

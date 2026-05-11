<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rooms\RoomsRequest;
use App\Http\Requests\Rooms\RoomsUpdateRequest;
use App\Http\Requests\Rooms\RoomSubRoomRequest;
use App\Services\Rooms\RoomsService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class RoomController extends Controller
{
    protected $RoomsService;

    public function __construct(RoomsService $RoomsService)
    {
        $this->RoomsService = $RoomsService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $property_odata = $request->property_odata;
            $data = $this->RoomsService->list($search, $pagging, $property_odata);
            $response = [
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(RoomsRequest $request)
    {
        try {
            $this->RoomsService->create($request->only([
                'property_odata',
                'room_name',
                'room_type',
                'capacity',
                'luas',
                'image',
                'status'
            ]));

            $response = [
                'message' => 'Room created successfully'
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(RoomsUpdateRequest $request)
    {
        try {
            $odata = $request->odata;
            $this->RoomsService->update($odata, $request->only([
                'property_odata',
                'room_name',
                'room_type',
                'capacity',
                'luas',  
                'image',
                'status'
            ]));

            $response = [
                'message' => 'Room updated successfully'
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy(Request $request)
    {
        $odata = $request->odata;
        $this->RoomsService->delete($odata);
        return response()->json(['success' => true]);
    }

    public function getSubRoom(Request $request)
    {
        $odata_room = $request->odata_room;
        $data = $this->RoomsService->getSubRoom($odata_room);
        return response()->json(['data' => $data]);
    }

    public function storeSubRoom(RoomSubRoomRequest $request)
    {
        $data = $request->only([
            'odata_room',
            'name_room',
            'code_room',
            'include_breakfast',
            'room_type',
            'type_bad',
            'sale',
            'price',
            'price_month',
            'price_year',
            'smoking_area',
            'status',
            'total_room'
        ]);

        $this->RoomsService->createSubRoom($data);

        return response()->json(['message' => 'Sub Room created successfully']);
    }


    public function updateSubRoom(RoomSubRoomRequest $request)
    {
        $data = $request->only([
            'odata',
            'name_room',
            'code_room',
            'room_type',
            'type_bad',
            'price',
            'sale',
            'price_month',
            'price_year',
            'include_breakfast',
            'smoking_area',
            'status',
            'total_room'
        ]);

        $this->RoomsService->updateSubRoom($data);

        return response()->json(['message' => 'Sub Room updated successfully']);
    }
}

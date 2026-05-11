<?php

namespace App\Services\Rooms;

use App\Models\Properties;
use App\Models\Rooms;
use App\Models\RoomSub;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoomsService
{
    public function list($search = null, $pagging = null, $property_odata = null)
    {
        if (empty($property_odata)) {
            return Rooms::with('subRooms')->whereRaw('1 = 0')->paginate($pagging);
        }

        return Rooms::search($search)
            ->where('property_odata', $property_odata)
            ->with('subRooms')
            ->paginate($pagging);
    }

    public function create(array $data)
    {
        $property_odata = Properties::where('odata', $data['property_odata'])->first();
        if (!$property_odata) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }

        if (isset($data['image'])) {
            $imagePath = $data['image']->store('rooms', 'public');
            $data['image'] = $imagePath;
        }

        $room = Rooms::create([
            'odata' => (string) Str::uuid(),
            'property_id' => $property_odata->id,
            'property_odata' => $data['property_odata'],
            'room_name' => $data['room_name'],
            'room_type' => $data['room_type'],
            'capacity' => $data['capacity'],
            'luas' => $data['luas'],
            'image' => $data['image'] ?? null,
            'status' => $data['status']
        ]);

        activity()
            ->performedOn($room)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created room');

        return $room;
    }

    public function update($odata, array $data)
    {
        $room = Rooms::where('odata', $odata)->first();
        if (!$room) {
            throw new HttpResponseException(response()->json(['error' => 'Room not found'], 404));
        }

        $property_odata = Properties::where('odata', $data['property_odata'])->first();
        if (!$property_odata) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }

        if (isset($data['image'])) {
            $imagePath = $data['image']->store('rooms', 'public');
            $data['image'] = $imagePath;
        }

        $room->property_id = $property_odata->id;
        $room->property_odata = $data['property_odata'];
        $room->room_name = $data['room_name'];
        $room->room_type = $data['room_type'];
        $room->capacity = $data['capacity'];
        $room->luas = $data['luas'];
        $room->image = $data['image'] ?? null;
        $room->status = $data['status'];

        $room->save();

        activity()
            ->performedOn($room)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated room');

        return $room;
    }

    public function delete($odata)
    {
        $room = Rooms::where('odata', $odata)->first();
        if (!$room) {
            throw new HttpResponseException(response()->json(['error' => 'Room not found'], 404));
        }
        $room->delete();

        activity()
            ->performedOn($room)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted room');

        return true;
    }

    public function show($odata)
    {
        $room = Rooms::where('odata', $odata)->first();
        if (!$room) {
            throw new HttpResponseException(response()->json(['error' => 'Room not found'], 404));
        }
        return $room;
    }

    public function getSubRoom($odata_room)
    {
        $room = RoomSub::where('odata_room', $odata_room)->get();

        return $room;
    }

    public function createSubRoom(array $data)
    {
        $room = Rooms::where('odata', $data['odata_room'])->first();
        if (!$room) {
            throw new HttpResponseException(response()->json(['error' => 'Room not found'], 404));
        }

        $subRoom = RoomSub::create([
            'odata' => (string) Str::uuid(),
            'odata_room' => $data['odata_room'],
            'id_room' => $room->id,
            'name_room' => $data['name_room'],
            'code_room' => $data['code_room'],
            'room_type' => $data['room_type'] ?? null,
            'include_breakfast' => $data['include_breakfast'],
            'smoking_area' => $data['smoking_area'],
            'type_bed' => $data['type_bad'],
            'price' => $data['price'],
            'sale' => $data['sale'],
            'price_month' => $data['price_month'] ?? null,
            'price_year' => $data['price_year'] ?? null,
            'total_room' => $data['total_room'] ?? null,
            'status' => $data['status']
        ]);

        activity()
            ->performedOn($subRoom)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created sub room');

        return $subRoom;
    }

    public function updateSubRoom(array $data)
    {
        $subRoom = RoomSub::where('odata', $data['odata'])->first();
        if (!$subRoom) {
            throw new HttpResponseException(response()->json(['error' => 'Sub Room not found'], 404));
        }

        $subRoom->name_room = $data['name_room'];
        $subRoom->code_room = $data['code_room'];
        $subRoom->room_type = $data['room_type'] ?? null;
        $subRoom->include_breakfast = $data['include_breakfast'];
        $subRoom->smoking_area = $data['smoking_area'];
        $subRoom->type_bed = $data['type_bad'];
        $subRoom->price = $data['price'];
        $subRoom->sale = $data['sale'];
        $subRoom->price_month = $data['price_month'] ?? null;
        $subRoom->price_year = $data['price_year'] ?? null;
        $subRoom->total_room = $data['total_room'] ?? null;
        $subRoom->status = $data['status'];

        $subRoom->save();


        activity()
            ->performedOn($subRoom)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated sub room');
        return $subRoom;
    }
}

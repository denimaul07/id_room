<?php

namespace App\Services\Rooms;

use App\Models\Properties;
use App\Models\Rooms;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class RoomsService
{
    public function list($search = null, $pagging = null, $property_odata = null)
    {
        if (empty($property_odata)) {
            return Rooms::whereRaw('1 = 0')->paginate($pagging);
        }

        return Rooms::search($search)
            ->where('property_odata', $property_odata)
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
            'price' => $data['price'],
            'price_month' => $data['price_month'] ?? null,
            'price_year' => $data['price_year'] ?? null,
            'total_room' => $data['total_room'],
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
        $room->price = $data['price'];
        $room->price_month = $data['price_month'] ?? null;
        $room->price_year = $data['price_year'] ?? null;
        $room->total_room = $data['total_room'];
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
}

<?php

namespace App\Services\RoomFacilities;

use App\Models\Facilities;
use App\Models\Rooms;
use App\Models\RoomFacilities;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class RoomFacilitiesService
{
    public function list($search = null, $pagging = 10, $room_odata = null)
    {
        $query = RoomFacilities::with('facility');

        if (!empty($room_odata)) {
            $query->where('room_odata', $room_odata);
        }

        if ($search) {
            $query->whereHas('facility', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%");
            });
        }

        return $query->paginate($pagging);
    }

    public function create(array $data)
    {
        $room = Rooms::where('odata', $data['room_odata'])->first();
        if (!$room) {
            throw new HttpResponseException(response()->json(['error' => 'Room not found'], 404));
        }

        $facility = Facilities::where('odata', $data['facility_odata'])->first();
        if (!$facility) {
            throw new HttpResponseException(response()->json(['error' => 'Facility not found'], 404));
        }

        $roomFacility = RoomFacilities::create([
            'odata' => (string) Str::uuid(),
            'room_id' => $room->id,
            'room_odata' => $room->odata,
            'facility_id' => $facility->id,
            'facility_odata' => $facility->odata
        ]);

        activity()
            ->performedOn($roomFacility)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created room facility');

        return $roomFacility;
    }

    public function update($odata, array $data)
    {
        $roomFacility = RoomFacilities::where('odata', $odata)->first();
        if (!$roomFacility) {
            throw new HttpResponseException(response()->json(['error' => 'Room facility not found'], 404));
        }

        $room = Rooms::where('odata', $data['room_odata'])->first();
        if (!$room) {
            throw new HttpResponseException(response()->json(['error' => 'Room not found'], 404));
        }

        $facility = Facilities::where('odata', $data['facility_odata'])->first();
        if (!$facility) {
            throw new HttpResponseException(response()->json(['error' => 'Facility not found'], 404));
        }

        $roomFacility->room_id = $room->id;
        $roomFacility->room_odata = $room->odata;
        $roomFacility->facility_id = $facility->id;
        $roomFacility->facility_odata = $facility->odata;
        $roomFacility->save();

        activity()
            ->performedOn($roomFacility)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated room facility');

        return $roomFacility;
    }

    public function delete($odata)
    {
        $roomFacility = RoomFacilities::where('odata', $odata)->first();
        if (!$roomFacility) {
            throw new HttpResponseException(response()->json(['error' => 'Room facility not found'], 404));
        }
        $roomFacility->delete();

        activity()
            ->performedOn($roomFacility)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted room facility');

        return true;
    }
}

<?php

namespace App\Services\Facilities;

use App\Models\Facilities;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class FacilitiesService
{
    public function list($search = null, $pagging = 10)
    {
        return Facilities::search($search)->paginate($pagging);
    }

    public function create(array $data)
    {
        $type = $data['type'];
        if (is_array($type)) {
            $type = json_encode($type);
        }
        $facility = Facilities::create([
            'odata' => (string) Str::uuid(),
            'name' => $data['name'],
            'type' => $type,
            'icon' => $data['icon']
        ]);

        activity()
            ->performedOn($facility)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created facility');

        return $facility;
    }

    public function update($odata, array $data)
    {
        $facility = Facilities::where('odata', $odata)->first();
        if (!$facility) {
            throw new HttpResponseException(response()->json(['error' => 'Facility not found'], 404));
        }

        $type = $data['type'];
        if (is_array($type)) {
            $type = json_encode($type);
        }

        $facility->name = $data['name'];
        $facility->type = $type;
        $facility->icon = $data['icon'];
        $facility->save();

        activity()
            ->performedOn($facility)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated facility');

        return $facility;
    }

    public function delete($odata)
    {
        $facility = Facilities::where('odata', $odata)->first();
        if (!$facility) {
            throw new HttpResponseException(response()->json(['error' => 'Facility not found'], 404));
        }
        $facility->delete();

        activity()
            ->performedOn($facility)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted facility');

        return true;
    }

    public function show($odata)
    {
        $facility = Facilities::where('odata', $odata)->first();
        if (!$facility) {
            throw new HttpResponseException(response()->json(['error' => 'Facility not found'], 404));
        }
        return $facility;
    }
}

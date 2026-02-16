<?php

namespace App\Services\PropertyFacilities;

use App\Models\Facilities;
use App\Models\Properties;
use App\Models\PropertyFacilities;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class PropertyFacilitiesService
{
    public function list($search = null, $pagging = 10, $property_odata = null)
    {
        if (empty($property_odata)) {
            return PropertyFacilities::whereRaw('1 = 0')->paginate($pagging);
        }

        $query = PropertyFacilities::with('facility')
            ->where('property_odata', $property_odata);

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
        $property = Properties::where('odata', $data['property_odata'])->first();
        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }

        $facility = Facilities::where('odata', $data['facility_odata'])->first();
        if (!$facility) {
            throw new HttpResponseException(response()->json(['error' => 'Facility not found'], 404));
        }

        $propertyFacility = PropertyFacilities::create([
            'odata' => (string) Str::uuid(),
            'property_id' => $property->id,
            'property_odata' => $property->odata,
            'facility_id' => $facility->id,
            'facility_odata' => $facility->odata
        ]);

        activity()
            ->performedOn($propertyFacility)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created property facility');

        return $propertyFacility;
    }

    public function update($odata, array $data)
    {
        $propertyFacility = PropertyFacilities::where('odata', $odata)->first();
        if (!$propertyFacility) {
            throw new HttpResponseException(response()->json(['error' => 'Property facility not found'], 404));
        }

        $property = Properties::where('odata', $data['property_odata'])->first();
        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }

        $facility = Facilities::where('odata', $data['facility_odata'])->first();
        if (!$facility) {
            throw new HttpResponseException(response()->json(['error' => 'Facility not found'], 404));
        }

        $propertyFacility->property_id = $property->id;
        $propertyFacility->property_odata = $property->odata;
        $propertyFacility->facility_id = $facility->id;
        $propertyFacility->facility_odata = $facility->odata;
        $propertyFacility->save();

        activity()
            ->performedOn($propertyFacility)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated property facility');

        return $propertyFacility;
    }

    public function delete($odata)
    {
        $propertyFacility = PropertyFacilities::where('odata', $odata)->first();
        if (!$propertyFacility) {
            throw new HttpResponseException(response()->json(['error' => 'Property facility not found'], 404));
        }
        $propertyFacility->delete();

        activity()
            ->performedOn($propertyFacility)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted property facility');

        return true;
    }
}

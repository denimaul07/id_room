<?php

namespace App\Services\Properties;
use App\Models\Properties;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class PropertiesService
{
    public function list($search = null, $pagging = 10)
    {
        return Properties::search($search)->paginate($pagging);
    }

    public function create(array $data)
    {
        if (isset($data['images'])) {
            $imagePath = $data['images']->store('properties', 'public');
        }

        $property = Properties::create([
            'odata' => (string) Str::uuid(),
            'properties' => $data['properties'],
            'type' => $data['type'],
            'listing_type' => $data['listing_type'],
            'address' => $data['address'],
            'city' => $data['city'],
            'province' => $data['province'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'description' => $data['description'],
            'information' => $data['information'],
            'price_per_night' => $data['price_per_night'],
            'price_per_monthly' => $data['price_per_monthly'],
            'price_per_year' => $data['price_per_year'],
            'sale_price' => $data['sale_price'],
            'total_rooms' => $data['total_rooms'],
            'isActive' => $data['isActive'],
            'image' => $imagePath,
        ]);

        activity()
            ->performedOn($property)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created property');
        return $property;

    }

    public function update($odata, array $data)
    {
        $property = Properties::where('odata', $odata)->first();
        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }

        $property->properties = $data['properties'];
        $property->type = $data['type'];
        $property->listing_type = $data['listing_type'];
        $property->address = $data['address'];
        $property->city = $data['city'];
        $property->province = $data['province'];
        $property->latitude = $data['latitude'];
        $property->longitude = $data['longitude'];
        $property->description = $data['description'];
        $property->information = $data['information'];
        $property->price_per_night = $data['price_per_night'];
        $property->price_per_monthly = $data['price_per_monthly'];
        $property->price_per_year = $data['price_per_year'];
        $property->sale_price = $data['sale_price'];
        $property->total_rooms = $data['total_rooms'];
        $property->isActive = $data['isActive'];


        if (isset($data['images'])) {
            $imagePath = $data['images']->store('properties', 'public');
            $property->image = $imagePath;
        }

        $property->save();

        activity()
            ->performedOn($property)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated property');
        return $property;
    }

    public function delete($odata)
    {
        $property = Properties::where('odata', $odata)->first();
        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }
        $property->delete();
        return true;
    }

    public function show($odata)
    {
        $property = Properties::where('odata', $odata)->first();
        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }
        return $property;
    }
}

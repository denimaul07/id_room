<?php

namespace App\Services\Properties;

use App\Models\City;
use App\Models\PopularCity;
use App\Models\Properties;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PropertiesService
{
    public function list($search = null, $pagging = 10)
    {
        return Properties::search($search)->orderBy('created_at', 'desc')->paginate($pagging);
    }

    public function create(array $data)
    {
        if (isset($data['images'])) {
            $imagePath = $data['images']->store('properties', 'public');
        }

        if (isset($data['banner'])) {
            $bannerPath = $data['banner']->store('properties', 'public');
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
            'slug' => $data['slug'],
            'information' => $data['information'],
            'price_per_night' => $data['price_per_night'],
            'price_per_monthly' => $data['price_per_monthly'],
            'price_per_year' => $data['price_per_year'],
            'sale_price' => $data['sale_price'],
            'total_rooms' => $data['total_rooms'],
            'isActive' => $data['isActive'],
            'image' => $imagePath,
            'banner' => $bannerPath,
            'url_video' => $data['url_video']
        ]);

        $city = City::where('odata', $data['city'])->first();
        $popularCity = PopularCity::where('odata_city', $data['city'])->first();
        if (!$popularCity) {
            PopularCity::create(
                [
                    'odata' => (string) Str::uuid(),
                    'odata_city' => $data['city'],
                    'id_city' => $city->id,
                ]
            );
        }

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
        $property->slug = $data['slug'];
        $property->information = $data['information'];
        $property->price_per_night = $data['price_per_night'];
        $property->price_per_monthly = $data['price_per_monthly'];
        $property->price_per_year = $data['price_per_year'];
        $property->sale_price = $data['sale_price'];
        $property->total_rooms = $data['total_rooms'];
        $property->isActive = $data['isActive'];
        $property->url_video = $data['url_video'];

        if (isset($data['images'])) {
            $imagePath = $data['images']->store('properties', 'public');
            $property->image = $imagePath;
        }

        if (isset($data['banner'])) {
            $bannerPath = $data['banner']->store('properties', 'public');
            $property->banner = $bannerPath;
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

    public function getListCity($search = null, $pagging = 10)
    {
        $query = PopularCity::with('city');

        if ($search) {
            $query->whereHas('city', function ($q) use ($search) {
                $q->where('city', 'like', '%' . $search . '%');
            });
        }

        return $query->paginate($pagging);
    }

    public function updatePopularCity(array $data)
    {
        $city = City::where('odata', $data['city'])->first();
        $popularCity = PopularCity::where('odata_city', $data['city'])->first();
        if (!$popularCity) {
            PopularCity::create(
                [
                    'odata' => (string) Str::uuid(),
                    'odata_city' => $data['city'],
                    'id_city' => $city->id,
                ]
            );
        }
    }

    public function updatePopularCityByOdata($odata, array $data)
    {
        $popularCity = PopularCity::where('odata', $odata)->first();
        if (!$popularCity) {
            throw new HttpResponseException(response()->json(['error' => 'Popular City not found'], 404));
        }

        if (isset($data['image'])) {
            $imagePath = $data['image']->store('properties', 'public');
            $popularCity->image = $imagePath;
        }
        $popularCity->odata_city = $data['city'];
        $popularCity->save();

        return $popularCity;
    }

    public function getProperties()
    {
        return Properties::where('isActive', 0)->get();
    }
}

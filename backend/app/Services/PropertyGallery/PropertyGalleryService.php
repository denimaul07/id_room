<?php

namespace App\Services\PropertyGallery;

use App\Models\Properties;
use App\Models\PropertyGallery;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class PropertyGalleryService
{
    public function list($search = null, $pagging = 10, $property_odata = null)
    {
        if (empty($property_odata)) {
            return PropertyGallery::whereRaw('1 = 0')->paginate($pagging);
        }

        $query = PropertyGallery::query()->where('property_odata', $property_odata);

        if ($search) {
            $query->where('image', 'like', "%$search%");
        }

        return $query->paginate($pagging);
    }

    public function create(array $data)
    {
        $property = Properties::where('odata', $data['property_odata'])->first();
        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }

        $imagePath = null;
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('property_gallery', 'public');
        }

        $gallery = PropertyGallery::create([
            'odata' => (string) Str::uuid(),
            'property_id' => $property->id,
            'property_odata' => $property->odata,
            'image' => $imagePath
        ]);

        activity()
            ->performedOn($gallery)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created property gallery');

        return $gallery;
    }

    public function update($odata, array $data)
    {
        $gallery = PropertyGallery::where('odata', $odata)->first();
        if (!$gallery) {
            throw new HttpResponseException(response()->json(['error' => 'Property gallery not found'], 404));
        }

        $property = Properties::where('odata', $data['property_odata'])->first();
        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }

        $gallery->property_id = $property->id;
        $gallery->property_odata = $property->odata;

        if (isset($data['image'])) {
            $imagePath = $data['image']->store('property_gallery', 'public');
            $gallery->image = $imagePath;
        }

        $gallery->save();

        activity()
            ->performedOn($gallery)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated property gallery');

        return $gallery;
    }

    public function delete($odata)
    {
        $gallery = PropertyGallery::where('odata', $odata)->first();
        if (!$gallery) {
            throw new HttpResponseException(response()->json(['error' => 'Property gallery not found'], 404));
        }
        $gallery->delete();

        activity()
            ->performedOn($gallery)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted property gallery');

        return true;
    }
}

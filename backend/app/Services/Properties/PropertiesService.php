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
        return Properties::create($data);
    }

    public function update($odata, array $data)
    {
        $property = Properties::where('odata', $odata)->first();
        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }
        $property->update($data);
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

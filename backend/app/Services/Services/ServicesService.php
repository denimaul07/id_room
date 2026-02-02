<?php
namespace App\Services\Services;

use App\Models\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class ServicesService
{
    public function list()
    {
        return Services::get();
    }

    public function create(array $data)
    {


        return Services::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'title'        => $data['title'],
            'desc'         => $data['desc'],
            'type'         => $data['type'],
            'icon'         => $data['icon'],
            'isActive'      => $data['isActive'],
        ]);

    }

    public function update($odata, array $data)
    {
        $Services = Services::where('odata', $odata)->first();
        if (!$Services) {
            throw new HttpResponseException(response()->json(['error' => 'Services not found'], 404));
        }

        $Services->title = $data['title'];
        $Services->desc = $data['desc'];
        $Services->icon = $data['icon'];
        $Services->type = $data['type'];
        $Services->isActive = $data['isActive'];
        $Services->save();

        return $Services;
    }

    public function delete($odata)
    {
        $Services = Services::where('odata', $odata)->first();
        if (!$Services) {
            throw new HttpResponseException(response()->json(['error' => 'Services not found'], 404));
        }

        return $Services->delete();
    }
}
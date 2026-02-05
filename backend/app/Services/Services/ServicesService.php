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
        $service = Services::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'title'        => $data['title'],
            'desc'         => $data['desc'],
            'type'         => $data['type'],
            'icon'         => $data['icon'],
            'isActive'      => $data['isActive'],
        ]);
        activity()
            ->performedOn($service)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created service');
        return $service;
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
        activity()
            ->performedOn($Services)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated service');
        return $Services;
    }

    public function delete($odata)
    {
        $Services = Services::where('odata', $odata)->first();
        if (!$Services) {
            throw new HttpResponseException(response()->json(['error' => 'Services not found'], 404));
        }
        $Services->delete();
        activity()
            ->performedOn($Services)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted service');
        return true;
    }
}
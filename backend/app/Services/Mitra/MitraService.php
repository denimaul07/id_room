<?php
namespace App\Services\Mitra;

use App\Models\Mitra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class MitraService
{
    public function list()
    {
        return Mitra::get();
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('cms', 'public');
        }
        $mitra = Mitra::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'nama'        => $data['nama'],
            'image'           => $imagePath,
            'isActive'      => $data['isActive'],
        ]);
        activity()
            ->performedOn($mitra)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created mitra');
        return $mitra;
    }

    public function update($odata, array $data)
    {
        $Mitra = Mitra::where('odata', $odata)->first();
        if (!$Mitra) {
            throw new HttpResponseException(response()->json(['error' => 'Mitra not found'], 404));
        }
        $Mitra->nama = $data['nama'];
        $Mitra->isActive = $data['isActive'];
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('cms', 'public');
            $Mitra->image = $imagePath;
        }
        $Mitra->save();
        activity()
            ->performedOn($Mitra)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated mitra');
        return $Mitra;
    }

    public function delete($odata)
    {
        $Mitra = Mitra::where('odata', $odata)->first();
        if (!$Mitra) {
            throw new HttpResponseException(response()->json(['error' => 'Mitra not found'], 404));
        }
        $Mitra->delete();
        activity()
            ->performedOn($Mitra)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted mitra');
        return true;
    }
}
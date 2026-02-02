<?php
namespace App\Services\Mitra;

use App\Models\Mitra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

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

        return Mitra::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'nama'        => $data['nama'],
            'image'           => $imagePath,
            'isActive'      => $data['isActive'],
        ]);

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

        return $Mitra;
    }

    public function delete($odata)
    {
        $Mitra = Mitra::where('odata', $odata)->first();
        if (!$Mitra) {
            throw new HttpResponseException(response()->json(['error' => 'Mitra not found'], 404));
        }

        return $Mitra->delete();
    }
}
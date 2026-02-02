<?php
namespace App\Services\Testimoni;

use App\Models\Testimoni;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;

class TestimoniService
{
    public function list()
    {
        return Testimoni::get();
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('cms', 'public');
        }

        if (is_array($data['type'])) {
            $data['type'] = json_encode($data['type']);
        }

        return Testimoni::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'nama'         => $data['nama'],
            'location'     => $data['location'],
            'image'        => $imagePath ?? '',
            'type'         => $data['type'],
            'rate'         => $data['rate'],
            'desc'         => $data['desc'],
            'isActive'     => $data['isActive'],
        ]);


    }

    public function update($odata, array $data)
    {
        $item = Testimoni::where('odata', $odata)->first();
        if (!$item) {
            throw new HttpResponseException(response()->json(['error' => 'Testimoni not found'], 404));
        }
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('cms', 'public');
            $item->image = $imagePath;
        }


        if (is_array($data['type'])) {
            $data['type'] = json_encode($data['type']);
        }

        $item->nama = $data['nama'];
        $item->location = $data['location'];
        $item->rate = $data['rate'];
        $item->type = $data['type'];
        $item->desc = $data['desc'];
        $item->isActive = $data['isActive'];
        $item->save();
        return $item;
    }

    public function delete($odata)
    {
        $item = Testimoni::where('odata', $odata)->first();
        if (!$item) {
            throw new HttpResponseException(response()->json(['error' => 'Testimoni not found'], 404));
        }
        return $item->delete();
    }
}

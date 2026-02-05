<?php
namespace App\Services\Testimoni;

use App\Models\Testimoni;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

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

        $testimoni = Testimoni::create([
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
        activity()
            ->performedOn($testimoni)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created testimoni');
        return $testimoni;
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
        activity()
            ->performedOn($item)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated testimoni');
        return $item;
    }

    public function delete($odata)
    {
        $item = Testimoni::where('odata', $odata)->first();
        if (!$item) {
            throw new HttpResponseException(response()->json(['error' => 'Testimoni not found'], 404));
        }
        $item->delete();
        activity()
            ->performedOn($item)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted testimoni');
        return true;
    }
}

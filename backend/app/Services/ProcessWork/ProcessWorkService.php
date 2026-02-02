<?php
namespace App\Services\ProcessWork;

use App\Models\ProcessWork;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProcessWorkService
{
    public function list()
    {
        return ProcessWork::get();
    }

    public function create(array $data)
    {
        return ProcessWork::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'title'        => $data['title'],
            'desc'         => $data['desc'],
            'icon'         => $data['icon'],
            'isActive'     => $data['isActive'],
        ]);
    }

    public function update($odata, array $data)
    {
        $item = ProcessWork::where('odata', $odata)->first();
        if (!$item) {
            throw new HttpResponseException(response()->json(['error' => 'ProcessWork not found'], 404));
        }
        $item->title = $data['title'];
        $item->desc = $data['desc'];
        $item->icon = $data['icon'];
        $item->isActive = $data['isActive'];
        $item->save();
        return $item;
    }

    public function delete($odata)
    {
        $item = ProcessWork::where('odata', $odata)->first();
        if (!$item) {
            throw new HttpResponseException(response()->json(['error' => 'ProcessWork not found'], 404));
        }
        return $item->delete();
    }
}

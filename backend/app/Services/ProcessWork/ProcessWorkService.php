<?php
namespace App\Services\ProcessWork;

use App\Models\ProcessWork;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ProcessWorkService
{
    public function list()
    {
        return ProcessWork::get();
    }

    public function create(array $data)
    {
        $item = ProcessWork::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'title'        => $data['title'],
            'desc'         => $data['desc'],
            'icon'         => $data['icon'],
            'isActive'     => $data['isActive'],
        ]);
        activity()
            ->performedOn($item)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created process work');
        return $item;
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
        activity()
            ->performedOn($item)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated process work');
        return $item;
    }

    public function delete($odata)
    {
        $item = ProcessWork::where('odata', $odata)->first();
        if (!$item) {
            throw new HttpResponseException(response()->json(['error' => 'ProcessWork not found'], 404));
        }
        $item->delete();
        activity()
            ->performedOn($item)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted process work');
        return true;
    }
}

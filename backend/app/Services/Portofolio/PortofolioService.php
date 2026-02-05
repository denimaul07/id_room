<?php
namespace App\Services\Portofolio;

use App\Models\Portofolio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class PortofolioService
{
    public function list()
    {
        return Portofolio::get();
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('cms', 'public');
        }
        $portofolio = Portofolio::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'nama'        => $data['nama'],
            'image'           => $imagePath,
            'isActive'      => $data['isActive'],
        ]);
        activity()
            ->performedOn($portofolio)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created portofolio');
        return $portofolio;
    }

    public function update($odata, array $data)
    {
        $Portofolio = Portofolio::where('odata', $odata)->first();
        if (!$Portofolio) {
            throw new HttpResponseException(response()->json(['error' => 'Portofolio not found'], 404));
        }
        $Portofolio->nama = $data['nama'];
        $Portofolio->isActive = $data['isActive'];
        if (isset($data['image'])) {
            $imagePath = $data['image']->store('cms', 'public');
            $Portofolio->image = $imagePath;
        }
        $Portofolio->save();
        activity()
            ->performedOn($Portofolio)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated portofolio');
        return $Portofolio;
    }

    public function delete($odata)
    {
        $Portofolio = Portofolio::where('odata', $odata)->first();
        if (!$Portofolio) {
            throw new HttpResponseException(response()->json(['error' => 'Portofolio not found'], 404));
        }
        $Portofolio->delete();
        activity()
            ->performedOn($Portofolio)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted portofolio');
        return true;
    }
}
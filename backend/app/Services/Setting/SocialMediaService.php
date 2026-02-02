<?php
namespace App\Services\Setting;

use App\Models\Socials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class SocialMediaService
{
    public function list()
    {
        return Socials::get();
    }

    public function create(array $data)
    {
        return Socials::create([
            'odata' => (string) Str::uuid(),
            'odata_setting' => $data['odata_setting'],
            'social'        => $data['social'],
            'url'           => $data['url'],
            'icon'          => $data['icon'],
            'isActive'      => $data['isActive'],
        ]);
    }

    public function update($odata, array $data)
    {
        $socialMedia = Socials::where('odata', $odata)->first();
        if (!$socialMedia) {
            throw new HttpResponseException(response()->json(['error' => 'Social Media not found'], 404));
        }

        $socialMedia->social = $data['social'];
        $socialMedia->url = $data['url'];
        $socialMedia->icon = $data['icon'];
        $socialMedia->isActive = $data['isActive'];

        $socialMedia->save();

        return $socialMedia;
    }

    public function delete($odata)
    {
        $socialMedia = Socials::where('odata', $odata)->first();
        if (!$socialMedia) {
            throw new HttpResponseException(response()->json(['error' => 'Social Media not found'], 404));
        }

        return $socialMedia->delete();
    }
}
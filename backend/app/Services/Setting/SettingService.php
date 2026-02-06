<?php
namespace App\Services\Setting;

use App\Models\Setting;
use App\Models\ContactMe;
use App\Models\Province;
use App\Models\City;
use App\Models\Properties;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class SettingService
{
    // Tambahkan trait jika diperlukan pada model, bukan di service
    public function list()
    {
        return Setting::with('socials')->get();
    }

    public function update($odata, array $data)
    {
        $setting = Setting::where('odata', $odata)->first();
        if (!$setting) {
            throw new HttpResponseException(response()->json(['error' => 'Setting not found'], 404));
        }

        $setting->siteName = $data['siteName'];
        $setting->primaryColor = $data['primaryColor'];
        $setting->primaryColorHover = $data['primaryColorHover'];
        $setting->primaryTextColor = $data['primaryTextColor'];
        $setting->secondColor = $data['secondColor'];
        $setting->secondColorHover = $data['secondColorHover'];
        $setting->secondTextColor = $data['secondTextColor'];

        if (isset($data['logo'])) {
            $logoPath = $data['logo']->store('cms', 'public');
            $setting->logo = $logoPath;
        }

        if (isset($data['favicon'])) {
            $faviconPath = $data['favicon']->store('cms', 'public');
            $setting->favicon = $faviconPath;
        }

        if (isset($data['imageBanner'])) {
            $imageBannerPath = $data['imageBanner']->store('cms', 'public');
            $setting->imageBanner = $imageBannerPath;
        }

        $setting->titleBanner = $data['titleBanner'];
        $setting->subTitleBanner = $data['subTitleBanner'];
        $setting->colorTitleBanner = $data['colorTitleBanner'];
        $setting->navBarColor = $data['navBarColor'];
        $setting->navBarTextColor = $data['navBarTextColor'];
        $setting->footerDesk = $data['footerDesk'];
        $setting->footerColor = $data['footerColor'];
        $setting->footerTextColor = $data['footerTextColor'];
        $setting->syaratketentuan = $data['syaratketentuan'];
        $setting->privacypolicy = $data['privacypolicy'];

        $setting->save();

        activity()
            ->performedOn($setting)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('update setting');

        return $setting;
    }


    public function update_contact_me($odata, array $data)
    {
        $setting = Setting::where('odata', $odata)->first();
        if (!$setting) {
            throw new HttpResponseException(response()->json(['error' => 'Setting not found'], 404));
        }

        $setting->colorContactMe = $data['colorContactMe'];
        $setting->alamat = $data['alamat'];
        $setting->jam = $data['jam'];
        $setting->cs = $data['cs'];
        $setting->maps = $data['maps'];
        $setting->email = $data['email'];
        $setting->email1 = $data['email1'];
        $setting->email2 = $data['email2'];
        $setting->phone = $data['phone'];
        $setting->phone1 = $data['phone1'];
        $setting->wa = $data['wa'];
        $setting->wa1 = $data['wa1'];
        $setting->deptContact = $data['deptContact'];

        if (isset($data['bannerContactMe'])) {
            $bannerContactMePath = $data['bannerContactMe']->store('cms', 'public');
            $setting->bannerContactMe = $bannerContactMePath;
        }

        if (isset($data['imageContactMe'])) {
            $imageContactMePath = $data['imageContactMe']->store('cms', 'public');
            $setting->imageContactMe = $imageContactMePath;
        }

        if (isset($data['imageForm'])) {
            $imageFormPath = $data['imageForm']->store('cms', 'public');
            $setting->imageForm = $imageFormPath;
        }

        $setting->save();

        activity()
            ->performedOn($setting)
            ->causedBy(Auth::user())
            ->event('update')
            ->withProperties(['attributes' => $data])
            ->log('update contact me setting');

        return $setting;
    }
    
    public function contact_me(array $data)
    {
        $contact = new ContactMe();
        $contact->odata = (string) Str::uuid();
        $contact->nama = $data['nama'];
        $contact->email = $data['email'];
        $contact->no_telp = $data['phone'];
        $contact->subject = $data['subject'];
        $contact->description = $data['description'];
        $contact->save();

        activity()
            ->performedOn($contact)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('create contact me');

        return $contact;
    }

    public function update_about_me($odata, array $data)
    {
        $setting = Setting::where('odata', $odata)->first();
        if (!$setting) {
            throw new HttpResponseException(response()->json(['error' => 'Setting not found'], 404));
        }

        $setting->colorAboutMe = $data['colorAboutMe'];
        $setting->aboutme = $data['aboutme'];
        $setting->visi = $data['visi'];
        $setting->misi = $data['misi'];
        $setting->sectionTitleAboutme = $data['sectionTitleAboutme'];
        $setting->descTitleAboutme = $data['descTitleAboutme'];

        if (isset($data['bannerAboutMe'])) {
            $bannerAboutMePath = $data['bannerAboutMe']->store('cms', 'public');
            $setting->bannerAboutMe = $bannerAboutMePath;

        activity()
            ->performedOn($setting)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('update about me setting');
        }

        if (isset($data['image1aboutme'])) {
            $image1aboutmePath = $data['image1aboutme']->store('cms', 'public');
            $setting->image1aboutme = $image1aboutmePath;
        }

        if (isset($data['image2aboutme'])) {
            $image2aboutmePath = $data['image2aboutme']->store('cms', 'public');
            $setting->image2aboutme = $image2aboutmePath;
        }

        if (isset($data['image3aboutme'])) {
            $image3aboutmePath = $data['image3aboutme']->store('cms', 'public');
            $setting->image3aboutme = $image3aboutmePath;
        }

        if (isset($data['imageSectionAboutme'])) {
            $imageSectionAboutmePath = $data['imageSectionAboutme']->store('cms', 'public');
            $setting->imageSectionAboutme = $imageSectionAboutmePath;
        }

        if (isset($data['bgSectionAboutme'])) {
            $bgSectionAboutmePath = $data['bgSectionAboutme']->store('cms', 'public');
            $setting->bgSectionAboutme = $bgSectionAboutmePath;
        }

        $setting->save();

        activity()
            ->performedOn($setting)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('update about me setting');

        return $setting;
    }

    public function update_renovasi($odata, array $data)
    {
        $setting = Setting::where('odata', $odata)->first();
        if (!$setting) {
            throw new HttpResponseException(response()->json(['error' => 'Setting not found'], 404));
        }

        $setting->colorRenov = $data['colorRenov'];
        $setting->titleRenov = $data['titleRenov'];
        $setting->subTitleRenov = $data['subTitleRenov'];
        $setting->titleSectionRenov = $data['titleSectionRenov'];
        $setting->descSectionRenov = $data['descSectionRenov'];
        $setting->urlRenov = $data['urlRenov'];

        if (isset($data['bannerRenov'])) {
            $bannerRenovPath = $data['bannerRenov']->store('cms', 'public');
            $setting->bannerRenov = $bannerRenovPath;
        }

        $setting->save();

        activity()
            ->performedOn($setting)
            ->causedBy(Auth::user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('update renovasi setting');

        return $setting;
    }

     //Public
    public function public_info()
    {
        return Setting::with(['socials', 'faqs','mitras','servicesHome','services','servicesHomeAll','Portofolio','processwork','testimoni','testimoniHome','membership','membership.benefits','membership.benefits.benefitDetails'])->get([
            'odata',
            'siteName', 
            'primaryColor', 
            'primaryColorHover',
            'primaryTextColor', 
            'secondColor', 
            'secondColorHover', 
            'secondTextColor', 
            'logo', 
            'favicon', 
            'imageBanner', 
            'titleBanner', 
            'subTitleBanner', 
            'colorTitleBanner',
            'navBarColor', 
            'navBarTextColor', 
            'footerDesk', 
            'footerColor', 
            'footerTextColor',
            'syaratketentuan',
            'privacypolicy',
            'colorContactMe',
            'alamat',
            'bannerContactMe',
            'jam',
            'cs',
            'maps',
            'deptContact',
            'imageContactMe',
            'imageForm',
            'email',
            'email1',
            'email2',
            'phone',
            'phone1',
            'wa',
            'wa1',
            'colorAboutMe',
            'aboutme',
            'visi',
            'misi',
            'bannerAboutMe',
            'image1aboutme',
            'image2aboutme',
            'image3aboutme',
            'sectionTitleAboutme',
            'descTitleAboutme',
            'imageSectionAboutme',
            'bgSectionAboutme',
            'bannerRenov',
            'colorRenov',
            'titleRenov',
            'subTitleRenov',
            'titleSectionRenov',
            'descSectionRenov',
            'urlRenov',

        ]);
    }

    public function getProvinces()
    {
        return Province::all(['odata', 'province']);
    }

    public function getCities()
    {
        return City::all(['odata', 'city', 'province_odata']);
    }

    public function listCities()
    {
        return City::select('odata', 'city')
            ->whereHas('properties')
            ->get();
    }

}   
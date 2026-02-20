<?php
namespace App\Services\Setting;

use App\Models\City;
use App\Models\ContactMe;
use App\Models\KodeNegara;
use App\Models\PopularCity;
use App\Models\Properties;
use App\Models\Province;
use App\Models\Setting;
use App\Models\Rooms;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function update_jual_sewa($odata, array $data)
    {
        $setting = Setting::where('odata', $odata)->first();
        if (!$setting) {
            throw new HttpResponseException(response()->json(['error' => 'Setting not found'], 404));
        }

        $setting->colorSewa = $data['colorSewa'];
        $setting->colorJual = $data['colorJual'];
        $setting->colorSewaDetail = $data['colorSewaDetail'];
        $setting->colorJualDetail = $data['colorJualDetail'];

        if (isset($data['bannerSewa'])) {
            $bannerSewaPath = $data['bannerSewa']->store('cms', 'public');
            $setting->bannerSewa = $bannerSewaPath;
        }

        if (isset($data['bannerSewaDetail'])) {
            $bannerSewaDetailPath = $data['bannerSewaDetail']->store('cms', 'public');
            $setting->bannerSewaDetail = $bannerSewaDetailPath;
        }

        if (isset($data['bannerJual'])) {
            $bannerJualPath = $data['bannerJual']->store('cms', 'public');
            $setting->bannerJual = $bannerJualPath;
        }

        if (isset($data['bannerJualDetail'])) {
            $bannerJualDetailPath = $data['bannerJualDetail']->store('cms', 'public');
            $setting->bannerJualDetail = $bannerJualDetailPath;
        }

        $setting->save();

        activity()
            ->performedOn($setting)
            ->causedBy(Auth::user())
            ->event('update')
            ->log('update jual & sewa setting');

        return $setting;
    }

    // Public

    public function public_info()
    {
        return Setting::with(['socials', 'faqs', 'mitras', 'servicesHome', 'services', 'servicesHomeAll', 'Portofolio', 'processwork', 'testimoni', 'testimoniHome', 'membership', 'membership.benefits', 'membership.benefits.benefitDetails', 'promo'])->get([
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
            'bannerSewa',
            'colorSewa',
            'bannerJual',
            'colorJual',
            'bannerSewaDetail',
            'colorSewaDetail',
            'bannerJualDetail',
            'colorJualDetail',
            'ppn',
            'fee'
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

    public function listType()
    {
        return Properties::select('type')
            ->distinct()
            ->get()
            ->pluck('type');
    }

    public function listTypeCounts()
    {
        return Properties::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->orderBy('type')
            ->get();
    }

    public function listPropertiesPublic($limit = 9, $listingType = null)
    {
        $query = Properties::with(['facilities.facility'])->select(
            'odata',
            'properties',
            'type',
            'listing_type',
            'address',
            'city',
            'province',
            'price_per_night',
            'price_per_monthly',
            'price_per_year',
            'sale_price',
            'total_rooms',
            'image',
            'created_at'
        );

        if (!empty($listingType)) {
            $query->where('listing_type', $listingType);
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function listPropertiesSewa(
        $search = null,
        $pagging = 10,
        $city = null,
        $type = null,
        $rent_type = null,
        $facilities = null,
        $min_price = null,
        $max_price = null,
        $sort = null,
        $price_sort = null
    ) {
        return Properties::with(['facilities.facility'])
            ->where('listing_type', 'like', '%Rent%')
            ->when($city, function ($query, $city) {
                return $query->where('city', $city);
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($facilities, function ($query, $facilities) {
                return $query->whereHas('facilities', function ($facilityQuery) use ($facilities) {
                    $facilityQuery->whereIn('facility_odata', $facilities);
                });
            })
            ->when($min_price || $max_price, function ($query) use ($min_price, $max_price) {
                $min = is_numeric($min_price) ? (float) $min_price : null;
                $max = is_numeric($max_price) ? (float) $max_price : null;

                return $query->where(function ($priceQuery) use ($min, $max) {
                    $fields = ['price_per_night', 'price_per_monthly', 'price_per_year'];

                    foreach ($fields as $field) {
                        if ($min !== null && $max !== null) {
                            $priceQuery->orWhereBetween($field, [$min, $max]);
                        } elseif ($min !== null) {
                            $priceQuery->orWhere($field, '>=', $min);
                        } elseif ($max !== null) {
                            $priceQuery->orWhere($field, '<=', $max);
                        }
                    }
                });
            })
            ->when($rent_type, function ($query, $rent_type) {
                if ($rent_type === 'monthly') {
                    return $query->where('price_per_monthly', '>', 0);
                } elseif ($rent_type === 'yearly') {
                    return $query->where('price_per_year', '>', 0);
                }
                return $query;
            })
            ->search($search)
            ->when($price_sort, function ($query, $price_sort) {
                $direction = $price_sort === 'price_desc' ? 'DESC' : 'ASC';
                $expression = 'LEAST(NULLIF(price_per_night, 0), NULLIF(price_per_monthly, 0), NULLIF(price_per_year, 0), 999999999999)';

                return $query->orderByRaw($expression . ' ' . $direction);
            }, function ($query) use ($sort) {
                if ($sort === 'oldest') {
                    return $query->orderBy('created_at', 'asc');
                }
                return $query->orderBy('created_at', 'desc');
            })
            ->paginate($pagging);
    }

    public function listPropertiesJual(
        $search = null,
        $pagging = 10,
        $city = null,
        $type = null,
        $rent_type = null,
        $facilities = null,
        $min_price = null,
        $max_price = null,
        $sort = null,
        $price_sort = null
    ) {
        return Properties::with(['facilities.facility'])
            ->where('listing_type', 'like', '%Sale%')
            ->when($city, function ($query, $city) {
                return $query->where('city', $city);
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($facilities, function ($query, $facilities) {
                return $query->whereHas('facilities', function ($facilityQuery) use ($facilities) {
                    $facilityQuery->whereIn('facility_odata', $facilities);
                });
            })
            ->when($min_price || $max_price, function ($query) use ($min_price, $max_price) {
                $min = is_numeric($min_price) ? (float) $min_price : null;
                $max = is_numeric($max_price) ? (float) $max_price : null;

                return $query->when($min !== null, function ($q) use ($min) {
                    $q->where('sale_price', '>=', $min);
                })->when($max !== null, function ($q) use ($max) {
                    $q->where('sale_price', '<=', $max);
                });
            })
            ->search($search)
            ->when($price_sort, function ($query, $price_sort) {
                $direction = $price_sort === 'price_desc' ? 'DESC' : 'ASC';
                return $query->orderBy('sale_price', $direction);
            }, function ($query) use ($sort) {
                if ($sort === 'oldest') {
                    return $query->orderBy('created_at', 'asc');
                }
                return $query->orderBy('created_at', 'desc');
            })
            ->paginate($pagging);
    }

    public function popularCity()
    {
        return PopularCity::with('city')
            ->withCount('properties as property_count')
            ->get();
    }

    public function listPropertiesFacilities()
    {
        return Properties::with(['facilities.facility'])
            ->get()
            ->flatMap(function ($property) {
                return $property->facilities->map(function ($propertyFacility) {
                    return $propertyFacility->facility;
                });
            })
            ->unique('odata')
            ->values();
    }

    public function getPropertyDetail($odata)
    {
        $property = Properties::with([
            'facilities.facility',
            'city',
            'province',
            'gallery',
            'rooms',
            'rooms.facilities.facility',
        ])->where('slug', $odata)->first();

        if (!$property) {
            throw new HttpResponseException(response()->json(['error' => 'Property not found'], 404));
        }

        return $property;
    }

    public function kodeNegara()
    {
        $kodeNegara = KodeNegara::all();
        return $kodeNegara;
    }

    public function properties_booking($property_id)
    {
        $room = Rooms::with(['property', 'facilities.facility'])
            ->where('odata', $property_id)
            ->get();
        return $room;
    }
}

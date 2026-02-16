<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\ContactMeRequest;
use App\Services\Setting\SettingService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class PublicController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function info()
    {
        try {
            $settings = $this->settingService->public_info();
            $response = [
                'data' => $settings
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function contactMe(ContactMeRequest $request)
    {
        try {
            $settings = $this->settingService->contact_me($request->only([
                'nama',
                'email',
                'phone',
                'subject',
                'description',
            ]));

            $response = [
                'message' => 'Terima kasih telah menghubungi kami. Kami akan merespons secepatnya.',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function getProvince()
    {
        try {
            $provinces = $this->settingService->getProvinces();
            $response = [
                'data' => $provinces
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function getCity()
    {
        try {
            $cities = $this->settingService->getCities();
            $response = [
                'data' => $cities
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function listCity(Request $request)
    {
        try {
            $cities = $this->settingService->listCities();
            $type = $this->settingService->listType();
            $typeCounts = $this->settingService->listTypeCounts();
            $response = [
                'data' => $cities,
                'type_properties' => $type,
                'type_properties_count' => $typeCounts
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function listProperties(Request $request)
    {
        try {
            $limit = (int) ($request->limit ?? 9);
            if ($limit <= 0) {
                $limit = 9;
            }
            $listingType = $request->listing_type ?? null;
            $properties = $this->settingService->listPropertiesPublic($limit, $listingType);
            $response = [
                'data' => $properties
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function listPropertiesSewa(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $city = $request->city;
            $type = $request->type;
            $rent_type = $request->rent_type;
            $facilities = $request->facilities;
            $min_price = $request->min_price;
            $max_price = $request->max_price;
            $sort = $request->sort;
            $price_sort = $request->price_sort;
            if (is_string($facilities)) {
                $facilities = array_filter(explode(',', $facilities));
            }
            $properties = $this->settingService->listPropertiesSewa(
                $search,
                $pagging,
                $city,
                $type,
                $rent_type,
                $facilities,
                $min_price,
                $max_price,
                $sort,
                $price_sort
            );
            $response = [
                'data' => $properties
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function listPropertiesJual(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $city = $request->city;
            $type = $request->type;
            $rent_type = $request->rent_type;
            $facilities = $request->facilities;
            $min_price = $request->min_price;
            $max_price = $request->max_price;
            $sort = $request->sort;
            $price_sort = $request->price_sort;
            if (is_string($facilities)) {
                $facilities = array_filter(explode(',', $facilities));
            }
            $properties = $this->settingService->listPropertiesJual(
                $search,
                $pagging,
                $city,
                $type,
                $rent_type,
                $facilities,
                $min_price,
                $max_price,
                $sort,
                $price_sort
            );
            $response = [
                'data' => $properties
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function popularCity(Request $request)
    {
        try {
            $cities = $this->settingService->popularCity();
            $response = [
                'data' => $cities
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function listPropertiesFacilities(Request $request)
    {
        try {
            $facilities = $this->settingService->listPropertiesFacilities();
            $response = [
                'data' => $facilities
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function propertyDetail(Request $request)
    {
        try {
            $odata = $request->odata;
            $property = $this->settingService->getPropertyDetail($odata);
            $response = [
                'data' => $property
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function kodeNegara(Request $request)
    {
        try {
            $kodeNegara = $this->settingService->kodeNegara();
            $response = [
                'data' => $kodeNegara
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }
}

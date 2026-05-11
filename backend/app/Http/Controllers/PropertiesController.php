<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Properties\PropertiesRequest;
use App\Http\Requests\Properties\PropertiesUpdateRequest;
use App\Services\Properties\PropertiesService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class PropertiesController extends Controller
{
    protected $PropertiesService;

    public function __construct(PropertiesService $PropertiesService)
    {
        $this->PropertiesService = $PropertiesService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $data = $this->PropertiesService->list($search, $pagging);
            $response = [
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(PropertiesRequest $request)
    {
        try {
            $this->PropertiesService->create($request->only([
                'properties',
                'type',
                'listing_type',
                'address',
                'city',
                'province',
                'latitude',
                'longitude',
                'description',
                'slug',
                'information',
                'price_per_night',
                'price_per_monthly',
                'price_per_year',
                'sale_price',
                'furnished',
                'isActive',
                'images',
                'banner',
                'url_video',
            ]));

            $this->PropertiesService->updatePopularCity($request->only([
                'city',
            ]));

            $response = [
                'message' => 'Properties created successfully',
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(PropertiesUpdateRequest $request)
    {
        try {
            $odata = $request->odata;
            $this->PropertiesService->update($odata, $request->only([
                'properties',
                'type',
                'listing_type',
                'address',
                'city',
                'province',
                'latitude',
                'longitude',
                'description',
                'information',
                'price_per_night',
                'price_per_monthly',
                'price_per_year',
                'slug',
                'sale_price',
                'furnished',
                'images',
                'banner',
                'url_video',
                'isActive',
            ]));

            $this->PropertiesService->updatePopularCity($request->only([
                'city',
            ]));

            $response = [
                'message' => 'Properties updated successfully',
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy(Request $request)
    {
        $odata = $request->odata;
        $this->PropertiesService->delete($odata);
        return response()->json(['success' => true]);
    }

    public function getCity(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $data = $this->PropertiesService->getListCity($search, $pagging);
            $response = [
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function updatePopularCity(Request $request)
    {
        try {
            $this->PropertiesService->updatePopularCityByOdata($request->odata, $request->only([
                'city',
                'image',
            ]));

            $response = [
                'message' => 'Popular city updated successfully',
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getProperties(Request $request)
    {
        try {

            $data = $this->PropertiesService->getProperties();
            $response = [
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }
}

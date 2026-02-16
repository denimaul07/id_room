<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyGallery\PropertyGalleryRequest;
use App\Http\Requests\PropertyGallery\PropertyGalleryUpdateRequest;
use App\Services\PropertyGallery\PropertyGalleryService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class PropertyGalleryController extends Controller
{
    protected $PropertyGalleryService;

    public function __construct(PropertyGalleryService $PropertyGalleryService)
    {
        $this->PropertyGalleryService = $PropertyGalleryService;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $pagging = $request->pagging ?? 10;
            $property_odata = $request->property_odata;
            $data = $this->PropertyGalleryService->list($search, $pagging, $property_odata);
            $response = [
                'data' => $data
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(PropertyGalleryRequest $request)
    {
        try {
            $this->PropertyGalleryService->create($request->only([
                'property_odata',
                'image'
            ]));

            $response = [
                'message' => 'Property gallery created successfully'
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(PropertyGalleryUpdateRequest $request)
    {
        try {
            $odata = $request->odata;
            $this->PropertyGalleryService->update($odata, $request->only([
                'property_odata',
                'image'
            ]));

            $response = [
                'message' => 'Property gallery updated successfully'
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy(Request $request)
    {
        $odata = $request->odata;
        $this->PropertyGalleryService->delete($odata);
        return response()->json(['success' => true]);
    }
}

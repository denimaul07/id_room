<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Properties\PropertiesRequest;
use App\Services\Properties\PropertiesService;
use Illuminate\Http\Request;

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

    public function show(Request $request)
    {
        $odata = $request->odata;
        $data = $this->PropertiesService->show($odata);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(PropertiesRequest $request)
    {
        $data = $this->PropertiesService->create($request->validated());
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function update(PropertiesRequest $request)
    {
        $odata = $request->odata;
        $data = $this->PropertiesService->update($odata, $request->validated());
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function destroy(Request $request)
    {
        $odata = $request->odata;
        $this->PropertiesService->delete($odata);
        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promo\PromoRequest;
use App\Http\Requests\Promo\PromoUpdateRequest;
use App\Services\Promo\PromoService;
use Illuminate\Http\Request;

class PromoController extends Controller
{

    protected $promoService;
    public function __construct(PromoService $promoService)
    {
        $this->promoService = $promoService;
    }


    public function index(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $data = $this->promoService->list($search, $pagging);
        return response()->json(['data' => $data], 200);
    }

    public function store(PromoRequest $request)
    {
        $promo = $this->promoService->create($request->only(['banner', 'isActive']));
        return response()->json(['message' => 'Promo created successfully'], 201);
    }

    public function update(PromoUpdateRequest $request)
    {
        $id = $request->id;
        $promo = $this->promoService->update($id, $request->only(['banner', 'isActive']));
        return response()->json(['message' => 'Promo updated successfully'], 200);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $promo = $this->promoService->delete($id);
        return response()->json(['message' => 'Promo deleted successfully'], 200);
    }
}

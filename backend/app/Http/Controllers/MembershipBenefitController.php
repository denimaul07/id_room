<?php

namespace App\Http\Controllers;

use App\Http\Requests\Membership\MembershipBenefitRequest;
use App\Services\Membership\MembershipBenefitService;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class MembershipBenefitController extends Controller
{

    protected $service;

    public function __construct(MembershipBenefitService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $benefits = $this->service->list($request->search, $request->pagging);
            $response = [
                'data' => $benefits
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(MembershipBenefitRequest $request)
    {
        try {
            $this->service->create($request->only([
                'odata',
                'name',
                'description',
                'benefit_type',
            ]));
            return response()->json(['message' => 'Membership benefit created successfully'], 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function show($id)
    {
        try {
            $benefit = $this->service->getById($id);
            $response = [
                'data' => $benefit
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function update(MembershipBenefitRequest $request)
    {
        try {
            $this->service->update($request->odata, $request->only([
                'odata',
                'name',
                'description',
                'benefit_type',
            ]));
            return response()->json(['message' => 'Membership benefit updated successfully'], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->service->delete($request->odata);
            return response()->json(['message' => 'Membership benefit deleted successfully'], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }
}

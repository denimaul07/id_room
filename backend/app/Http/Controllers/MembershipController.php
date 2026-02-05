<?php
namespace App\Http\Controllers;

use App\Http\Requests\Membership\MembershipRequest;
use App\Services\Membership\MembershipService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class MembershipController extends Controller
{
    protected $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function index(Request $request)
    {
        try {
            $memberships = $this->membershipService->list($request->search, $request->pagging);
            $response = [
                'data' => $memberships
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(MembershipRequest $request)
    {
        try {
            $this->membershipService->create($request->only([
                'odata',
                'title',
                'desc',
                'price',
                'discount_percent',
                'duration',
                'fee_admin',
                'cancel_booking_fee',
                'isActive',
                'benefits', // tambahkan benefits agar diteruskan ke service
            ]));
            return response()->json(['message' => 'Membership created successfully'], 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function update(MembershipRequest $request)
    {
        try {
            $this->membershipService->update($request->odata, $request->only([
                'title',
                'desc',
                'price',
                'duration',
                'discount_percent',
                'fee_admin',
                'cancel_booking_fee',
                'isActive',
                'benefits', // tambahkan benefits agar diteruskan ke service
            ]));
            return response()->json(['message' => 'Membership updated successfully'], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function destroy($odata)
    {
        try {
            $this->membershipService->delete($odata);
            return response()->json(['message' => 'Membership deleted successfully'], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }
}

<?php
namespace App\Http\Controllers;
use App\Http\Requests\Services\ServicesRequest;
use App\Services\Services\ServicesService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class ServiceMeController extends Controller
{
    protected $ServicesService;

    public function __construct(ServicesService $ServicesService)
    {
        $this->ServicesService = $ServicesService;
    }

    public function index()
    {
        try {
            $settings = $this->ServicesService->list();
            $response=[
                'data' => $settings
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(ServicesRequest $request)
    {
        try {
            
            $this->ServicesService->create($request->only([
                'odata_setting',
                'title',
                'desc',
                'type',
                'icon',
                'isActive',
            ]));

            $response = [
                'message' => 'Services updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function update(ServicesRequest $request)
    {
        try {
            
            $this->ServicesService->update($request->odata, $request->only([
                'title',
                'desc',
                'type',
                'icon',
                'isActive',
            ]));

            $response = [
                'message' => 'Services updated successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function delete(Request $request)
    {
        try {
            $Services = $this->ServicesService->delete($request->odata);

            $response = [
                'message' => 'Services deleted successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }
    
}
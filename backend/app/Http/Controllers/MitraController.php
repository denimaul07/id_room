<?php
namespace App\Http\Controllers;
use App\Http\Requests\Mitra\MitraRequest;
use App\Http\Requests\Mitra\MitraUpdateRequest;
use App\Services\Mitra\MitraService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    protected $MitraService;

    public function __construct(MitraService $MitraService)
    {
        $this->MitraService = $MitraService;
    }

    public function index()
    {
        try {
            $mitras = $this->MitraService->list();
            $response=[
                'data' => $mitras
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(MitraRequest $request)
    {
        try {
            
            $this->MitraService->create($request->only([
                'odata_setting',
                'nama',
                'image',
                'isActive',
            ]));

            $response = [
                'message' => 'Mitra updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function update(MitraUpdateRequest $request)
    {
        try {
            
            $this->MitraService->update($request->odata, $request->only([
                'nama',
                'image',
                'isActive',
            ]));

            $response = [
                'message' => 'Mitra updated successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function delete(Request $request)
    {
        try {
            $Mitra = $this->MitraService->delete($request->odata);

            $response = [
                'message' => 'Mitra deleted successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }
    
}
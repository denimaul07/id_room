<?php
namespace App\Http\Controllers;
use App\Http\Requests\Portofolio\PortofolioRequest;
use App\Http\Requests\Portofolio\PortofolioUpdateRequest;
use App\Services\Portofolio\PortofolioService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class PortofolioController extends Controller
{
    protected $PortofolioService;

    public function __construct(PortofolioService $PortofolioService)
    {
        $this->PortofolioService = $PortofolioService;
    }

    public function index()
    {
        try {
            $Portofolios = $this->PortofolioService->list();
            $response=[
                'data' => $Portofolios
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(PortofolioRequest $request)
    {
        try {
            
            $this->PortofolioService->create($request->only([
                'odata_setting',
                'nama',
                'image',
                'isActive',
            ]));

            $response = [
                'message' => 'Portofolio updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function update(PortofolioUpdateRequest $request)
    {
        try {
            
            $this->PortofolioService->update($request->odata, $request->only([
                'nama',
                'image',
                'isActive',
            ]));

            $response = [
                'message' => 'Portofolio updated successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function delete(Request $request)
    {
        try {
            $Portofolio = $this->PortofolioService->delete($request->odata);

            $response = [
                'message' => 'Portofolio deleted successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }
    
}
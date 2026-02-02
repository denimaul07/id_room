<?php
namespace App\Http\Controllers;
use App\Http\Requests\Testimoni\TestimoniRequest;
use App\Http\Requests\Testimoni\UpdateTestimoniRequest;
use App\Services\Testimoni\TestimoniService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    protected $TestimoniService;

    public function __construct(TestimoniService $TestimoniService)
    {
        $this->TestimoniService = $TestimoniService;
    }

    public function index()
    {
        try {
            $settings = $this->TestimoniService->list();
            $response=[
                'data' => $settings
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(TestimoniRequest $request)
    {
        try {
            
            $this->TestimoniService->create($request->only([
                'odata_setting',
                'nama',
                'location',
                'image',
                'type',
                'rate',
                'desc',
                'isActive',
            ]));

            $response = [
                'message' => 'Testimoni created successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function update(UpdateTestimoniRequest $request)
    {
        try {
            
            $this->TestimoniService->update($request->odata, $request->only([
                'nama',
                'location',
                'image',
                'rate',
                'type',
                'desc',
                'isActive',
            ]));

            $response = [
                'message' => 'Testimoni updated successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function destroy(Request $request)
    {
        try {
            $Testimoni = $this->TestimoniService->delete($request->odata);

            $response = [
                'message' => 'Testimoni deleted successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }
    
}
<?php
namespace App\Http\Controllers;
use App\Http\Requests\Faq\FaqRequest;
use App\Services\Faq\FaqService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    protected $FaqService;

    public function __construct(FaqService $FaqService)
    {
        $this->FaqService = $FaqService;
    }

    public function index()
    {
        try {
            $settings = $this->FaqService->list();
            $response=[
                'data' => $settings
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(FaqRequest $request)
    {
        try {
            
            $this->FaqService->create($request->only([
                'odata_setting',
                'pertanyaan',
                'jawaban',
                'isActive',
            ]));

            $response = [
                'message' => 'FAQ updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function update(FaqRequest $request)
    {
        try {
            
            $this->FaqService->update($request->odata, $request->only([
                'pertanyaan',
                'jawaban',
                'isActive',
            ]));

            $response = [
                'message' => 'FAQ updated successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function delete(Request $request)
    {
        try {
            $Faq = $this->FaqService->delete($request->odata);

            $response = [
                'message' => 'FAQ deleted successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }
    
}
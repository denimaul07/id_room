<?php
namespace App\Http\Controllers;
use App\Http\Requests\Setting\SocialMediaRequest;
use App\Services\Setting\SocialMediaService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    protected $socialMediaService;

    public function __construct(SocialMediaService $socialMediaService)
    {
        $this->socialMediaService = $socialMediaService;
    }

    public function index()
    {
        try {
            $settings = $this->socialMediaService->list();
            $response=[
                'data' => $settings
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(SocialMediaRequest $request)
    {
        try {
            
            $this->socialMediaService->create($request->only([
                'odata_setting',
                'social',
                'url',
                'icon',
                'isActive',
            ]));

            $response = [
                'message' => 'Social media updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function update(SocialMediaRequest $request)
    {
        try {
            
            $this->socialMediaService->update($request->odata, $request->only([
                'social',
                'url',
                'icon',
                'isActive',
            ]));

            $response = [
                'message' => 'Social media updated successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }

    public function delete(Request $request)
    {
        try {
            $socialMedia = $this->socialMediaService->delete($request->odata);

            $response = [
                'message' => 'Social media deleted successfully',
            ];
            
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
        
    }
    
}
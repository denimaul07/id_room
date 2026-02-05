<?php
namespace App\Http\Controllers;
use App\Http\Requests\Setting\ContactMeRequest;
use App\Services\Setting\SettingService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function info()
    {
        try {
            $settings = $this->settingService->public_info();
            $response=[
                'data' => $settings
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function contactMe(ContactMeRequest $request)
    {
        try {
            $settings = $this->settingService->contact_me($request->only([
                'nama',
                'email',
                'phone',
                'subject',
                'description',
            ]));

            $response = [
                'message' => 'Terima kasih telah menghubungi kami. Kami akan merespons secepatnya.',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function getProvince()
    {
        try {
            $provinces = $this->settingService->getProvinces();
            $response = [
                'data' => $provinces
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function getCity()
    {
        try {
            $cities = $this->settingService->getCities();
            $response = [
                'data' => $cities
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }
}
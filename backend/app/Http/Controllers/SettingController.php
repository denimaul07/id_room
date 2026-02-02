<?php
namespace App\Http\Controllers;
use App\Http\Requests\Setting\SettingRequest;
use App\Http\Requests\Setting\AboutMeRequest;
use App\Http\Requests\Setting\RenovasiRequest;
use App\Services\Setting\SettingService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        try {
            $settings = $this->settingService->list();
            $response=[
                'data' => $settings
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function update(SettingRequest $request)
    {
        try {
            
            $this->settingService->update($request->odata, $request->only([
                'siteName',
                'primaryColor',
                'primaryColorHover',
                'primaryTextColor',
                'secondColor',
                'secondColorHover',
                'secondTextColor',
                'logo',
                'favicon',
                'imageBanner',
                'titleBanner',
                'subTitleBanner',
                'colorTitleBanner',
                'navBarColor',
                'navBarTextColor',
                'footerDesk',
                'footerColor',
                'footerTextColor',
                'syaratketentuan',
                'privacypolicy',
            ]));

            $response = [
                'message' => 'Settings updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function update_contact_me(Request $request)
    {
        try {
            
            $this->settingService->update_contact_me($request->odata, $request->only([
                'colorContactMe',
                'alamat',
                'bannerContactMe',
                'jam',
                'cs',
                'maps',
                'deptContact',
                'imageContactMe',
                'imageForm',
                'email',
                'email1',
                'email2',
                'phone',
                'phone1',
                'wa',
                'wa1',
            ]));

            $response = [
                'message' => 'Contact Me settings updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function update_about_me(AboutMeRequest $request)
    {
        try {
            
            $this->settingService->update_about_me($request->odata, $request->only([
                'bannerAboutMe',
                'colorAboutMe',
                'aboutme',
                'image1aboutme',
                'image2aboutme',
                'image3aboutme',
                'visi',
                'misi',
                'sectionTitleAboutme',
                'descTitleAboutme',
                'imageSectionAboutme',
                'bgSectionAboutme'
            ]));

            $response = [
                'message' => 'About Me settings updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function update_renovasi(RenovasiRequest $request)
    {
        try {
            
            $this->settingService->update_renovasi($request->odata, $request->only([
                'bannerRenov',
                'colorRenov',
                'titleRenov',
                'subTitleRenov',
                'titleSectionRenov',
                'descSectionRenov',
                'urlRenov',

            ]));

            $response = [
                'message' => 'Renovasi settings updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }
    
}
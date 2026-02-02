<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->get('/', function () {
        return 'Hello Api';
    });
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('/signup', 'App\Http\Controllers\UserController@store');
        $api->group(['middleware' => 'login.attempt.limiter'], function ($api) {
            $api->post('/login', 'App\Http\Controllers\Auth\AuthController@login');
        });

        $api->post('/token/refresh', 'App\Http\Controllers\Auth\RefreshTokenController@refresh');
        $api->post('/logout', 'App\Http\Controllers\Auth\RefreshTokenController@logout');


    });

    $api->group(['prefix' => 'public'], function ($api) {
        $api->get('/info', 'App\Http\Controllers\PublicController@info');
        $api->post('/contact-me', 'App\Http\Controllers\PublicController@contactMe');
    });


    $api->group(['prefix' => 'master', 'middleware' => ['jwt.auth']], function ($api) {
        $api->get('/get_department', 'App\Http\Controllers\Admin\DepartmentController@index');
        $api->get('/department', 'App\Http\Controllers\Admin\DepartmentController@getDepartment');
        $api->get('/roles', 'App\Http\Controllers\Admin\RoleController@index');
    });

    $api->group(['prefix' => 'activity_logs', 'middleware' => ['jwt.auth', 'role:superAdmin']], function ($api) {
            $api->get('/index','App\Http\Controllers\ActivityLogController@index');
            $api->get('/statistics','App\Http\Controllers\ActivityLogController@statistics');
            $api->get('/user_logs','App\Http\Controllers\ActivityLogController@userLogs');
            $api->get('/show','App\Http\Controllers\ActivityLogController@show');
            $api->get('/by_log_name','App\Http\Controllers\ActivityLogController@byLogName');
            $api->get('/critical','App\Http\Controllers\ActivityLogController@critical');
            $api->delete('/destroy','App\Http\Controllers\ActivityLogsController@destroy');
            $api->delete('/clear_all','App\Http\Controllers\ActivityLogsController@clearAll');

    });

    $api->group(['prefix' => 'setting', 'middleware' => ['jwt.auth']], function ($api) {


        // Akses Admin
        $api->group(['middleware' => ['jwt.auth', 'role:superAdmin']], function ($api) {

            $api->get('/site_setting', 'App\Http\Controllers\SettingController@index');
            $api->post('/site_setting', 'App\Http\Controllers\SettingController@update');
            $api->post('/site_setting_contact_me', 'App\Http\Controllers\SettingController@update_contact_me');
            $api->post('/site_setting_about_me', 'App\Http\Controllers\SettingController@update_about_me');
            $api->post('/site_setting_renovasi', 'App\Http\Controllers\SettingController@update_renovasi');

            $api->get('/faq', 'App\Http\Controllers\FaqController@index');
            $api->post('/faq', 'App\Http\Controllers\FaqController@store');
            $api->put('/faq', 'App\Http\Controllers\FaqController@update');
            $api->delete('/faq', 'App\Http\Controllers\FaqController@delete');

            $api->get('/mitra', 'App\Http\Controllers\MitraController@index');
            $api->post('/mitra_store', 'App\Http\Controllers\MitraController@store');
            $api->post('/mitra_update', 'App\Http\Controllers\MitraController@update');
            $api->delete('/mitra', 'App\Http\Controllers\MitraController@delete');

            $api->get('/social_media', 'App\Http\Controllers\SocialMediaController@index');
            $api->post('/social_media', 'App\Http\Controllers\SocialMediaController@store');
            $api->put('/social_media', 'App\Http\Controllers\SocialMediaController@update');
            $api->delete('/social_media', 'App\Http\Controllers\SocialMediaController@delete');

            $api->get('/services', 'App\Http\Controllers\ServiceMeController@index'); 
            $api->post('/services', 'App\Http\Controllers\ServiceMeController@store');
            $api->put('/services', 'App\Http\Controllers\ServiceMeController@update');
            $api->delete('/services', 'App\Http\Controllers\ServiceMeController@delete');

            $api->get('/portofolio', 'App\Http\Controllers\PortofolioController@index');
            $api->post('/portofolio_store', 'App\Http\Controllers\PortofolioController@store');
            $api->post('/portofolio_update', 'App\Http\Controllers\PortofolioController@update');
            $api->delete('/portofolio', 'App\Http\Controllers\PortofolioController@delete');

            $api->get('/process', 'App\Http\Controllers\ProcessWorkController@index'); 
            $api->post('/process', 'App\Http\Controllers\ProcessWorkController@store');
            $api->put('/process', 'App\Http\Controllers\ProcessWorkController@update');
            $api->delete('/process', 'App\Http\Controllers\ProcessWorkController@delete');

            $api->get('/testimoni', 'App\Http\Controllers\TestimoniController@index');
            $api->post('/testimoni_store', 'App\Http\Controllers\TestimoniController@store');
            $api->post('/testimoni_update', 'App\Http\Controllers\TestimoniController@update');
            $api->delete('/testimoni', 'App\Http\Controllers\TestimoniController@destroy');

            $api->get('/users', 'App\Http\Controllers\Admin\AdminUserController@index');
            $api->post('/users', 'App\Http\Controllers\Admin\AdminUserController@store');
            $api->put('/users', 'App\Http\Controllers\Admin\AdminUserController@update');

            $api->get('/store', 'App\Http\Controllers\Admin\DepartmentController@index');
            $api->post('/store', 'App\Http\Controllers\Admin\DepartmentController@store');
            $api->put('/store', 'App\Http\Controllers\Admin\DepartmentController@update');

    
            

        });

    });
    

    


});

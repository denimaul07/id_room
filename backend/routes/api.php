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
        // $api->group(['middleware' => 'api.auth'], function($api){
        $api->post('/token/refresh', 'App\Http\Controllers\Auth\RefreshTokenController@refresh');
        $api->post('/logout', 'App\Http\Controllers\Auth\RefreshTokenController@logout');
        // });
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
        $api->group(['middleware' => ['jwt.auth', 'role:superAdmin|admin']], function ($api) {
            $api->get('/index', 'App\Http\Controllers\SettingController@index');
            $api->post('/index', 'App\Http\Controllers\SettingController@store');
            $api->put('/index', 'App\Http\Controllers\SettingController@update');

            $api->get('/type', 'App\Http\Controllers\SettingController@type');
            $api->post('/type', 'App\Http\Controllers\SettingController@storeType');
            $api->put('/type', 'App\Http\Controllers\SettingController@updateType');

            $api->get('/users', 'App\Http\Controllers\Admin\AdminUserController@index');
            $api->post('/users', 'App\Http\Controllers\Admin\AdminUserController@store');
            $api->put('/users', 'App\Http\Controllers\Admin\AdminUserController@update');

            $api->get('/store', 'App\Http\Controllers\Admin\DepartmentController@index');
            $api->post('/store', 'App\Http\Controllers\Admin\DepartmentController@store');
            $api->put('/store', 'App\Http\Controllers\Admin\DepartmentController@update');
            

        });

    });
    

    


});

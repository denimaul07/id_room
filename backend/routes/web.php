<?php



use Illuminate\Support\Facades\Route;

use Intervention\Image\Facades\Image as Image;

/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/

$api= app('Dingo\Api\Routing\Router');

// Route demo activity log
Route::get('/activity-log-demo', [\App\Http\Controllers\ActivityLogDemoController::class, 'demo']);

Route::get('/', function () {

    return view('welcome');

});





Route::get('storage/foto/{filename}', function ($filename)

{

    $path = storage_path('app/public/foto/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);



    return $response;

});



Route::get('storage/logo/{filename}', function ($filename)

{

    $path = storage_path('app/public/logo/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);



    return $response;

});



Route::get('storage/cms/{filename}', function ($filename)

{

    $path = storage_path('app/public/cms/' . $filename);
    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);



    return $response;

});

Route::get('storage/properties/{filename}', function ($filename)

{

    $path = storage_path('app/public/properties/' . $filename);
    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);
    if (!File::exists($path)) {

        abort(404);

    }

    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

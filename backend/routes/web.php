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



Route::get('storage/barang/{filename}', function ($filename)

{

    $path = storage_path('app/public/barang/' . $filename);

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



Route::get('storage/sumbangan/{filename}', function ($filename)

{

    $path = storage_path('app/public/sumbangan/' . $filename);

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





// Route::get('storage/tagihan/{filename}', function ($filename)

//     {

        

//     // auth()->user()->hasAccessToFile($filename)

//     // if (!auth()->check()) {

//     //     abort(403, 'Unauthorized access.');

//     // }



//     $path = storage_path('app/public/tagihan/' . $filename);

//     //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



//     if (!File::exists($path)) {

//         abort(404);

//     }



//     $file = File::get($path);

//     $type = File::mimeType($path);



//     $response = Response::make($file, 200);

//     $response->header("Content-Type", $type);



//     return $response;

// });

    

    Route::get('storage/uangsaku/{filename}', function ($filename)

{

    $path = storage_path('app/public/uangsaku/' . $filename);

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



Route::get('storage/pdf/{filename}', function ($filename) {

    

    $path = storage_path('app/public/pdf/' . $filename);



    if (!file_exists($path)) {

        abort(404);

    }



    $file = file_get_contents($path);

    $type = Storage::disk('public')->mimeType('pdf/' . $filename);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");





    return $response;

});



Route::get('storage/itcare/{filename}', function ($filename)

{

    $path = storage_path('app/public/itcare/' . $filename);

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



Route::get('storage/bukti/{filename}', function ($filename)

{

    $path = storage_path('app/public/bukti/' . $filename);

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



Route::get('storage/pms/{filename}', function ($filename)

{

    $path = storage_path('app/public/pms/' . $filename);

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





Route::get('storage/video/{filename}', function ($filename)

{

    $path = storage_path('app/public/video/' . $filename);

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



Route::get('storage/lms/{filename}', function ($filename)

{

    $path = storage_path('app/public/lms/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});



Route::get('storage/room/{filename}', function ($filename)

{

    $path = storage_path('app/public/room/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});



Route::get('storage/surat/{filename}', function ($filename)

{

    $path = storage_path('app/public/surat/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});



Route::get('storage/cis_barang/{filename}', function ($filename)

{

    $path = storage_path('app/public/cis_barang/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});



Route::get('storage/gds_absen/{filename}', function ($filename)

{

    $path = storage_path('app/public/gds_absen/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});



Route::get('storage/gds_pertanyaan/{filename}', function ($filename)

{

    $path = storage_path('app/public/gds_pertanyaan/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});

Route::get('storage/gds_result/{filename}', function ($filename)

{

    $path = storage_path('app/public/gds_result/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});

Route::get('storage/gds_file/{filename}', function ($filename)

{

    $path = storage_path('app/public/gds_file/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});

Route::get('storage/penawaranharga/{filename}', function ($filename)

{

    $path = storage_path('app/public/penawaranharga/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});

Route::get('storage/gds_divisi/{filename}', function ($filename)

{

    $path = storage_path('app/public/gds_divisi/' . $filename);

    //$path =  Storage::disk('public')->makeDirectory('foto/'. $filename);



    if (!File::exists($path)) {

        abort(404);

    }



    $file = File::get($path);

    $type = File::mimeType($path);



    $response = Response::make($file, 200);

    $response->header("Content-Type", $type);

    $response->header("Access-Control-Allow-Origin", "https://portal.hondaimora.com/iss");



    return $response;

});
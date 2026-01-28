<?php

namespace App\Providers;



use App\Models\LogsError;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Str;

use Illuminate\Validation\ValidationException;

use Spatie\Permission\Exceptions\UnauthorizedException;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

use Symfony\Component\HttpKernel\Exception\GoneHttpException;

use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;

use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

use Throwable;



class AppServiceProvider extends ServiceProvider

{

    /**

     * Register any application services.

     */

    public function register()

    {

        //

    }



    /**

     * Bootstrap any application services.

     */

    public function boot()

    {

        // Tangkap error secara global menggunakan Dingo API

        app('api.exception')->register(function (Throwable $exception) {

            $request    = request();

            $errorId    = Str::uuid();

            $statusCode = $this->getStatusCode($exception);



            // Simpan Debugging ke tabel logs_error

            $dataLog = [

                'odata'       => $errorId,

                'message'     => $exception->getMessage(),

                'url'         => $request->fullUrl(),

                'method'      => $request->method(),

                'file'        => $exception->getFile(),

                'line'        => $exception->getLine(),

                'trace'       => json_encode($exception->getTrace(), JSON_PRETTY_PRINT),

                'status_code' => $statusCode,

                'ip_address'  => $request->ip(),

                'user_agent'  => $request->header('User-Agent'),

                'user'        => auth()->check() ? auth()->user()->name : 'System',

            ];



            LogsError::create($dataLog);



            $errorTitle      = $this->getErrorTitle($statusCode);

            $responseMessage = $this->getFriendlyMessage($exception);



            return response()->json([

                'success'  => false,

                'message'  => $responseMessage,

                'error_id' => $errorId,

                'title'    => $errorTitle,

            ], $statusCode);

        });

    }



    /**

     * Berikan pesan error yang lebih manusiawi.

     */

    protected function getFriendlyMessage(Throwable $exception)

    {

        Log::error('Exception Class: ' . get_class($exception));



        return match (true) {

            $exception instanceof QueryException => 'Terjadi kesalahan pada sistem database. Silakan coba lagi nanti.',

            $exception instanceof UnauthorizedHttpException => 'Anda tidak memiliki izin untuk mengakses halaman ini.',

            $exception instanceof AccessDeniedHttpException => 'Akses ditolak. Anda tidak memiliki hak untuk melakukan tindakan ini.',

            $exception instanceof UnauthorizedException => 'Akses ditolak. Anda tidak memiliki hak untuk melakukan tindakan ini.',

            $exception instanceof NotFoundHttpException,

            $exception instanceof ModelNotFoundException => 'Data yang Anda cari tidak ditemukan.',

            $exception instanceof MethodNotAllowedHttpException => 'Metode request ini tidak diizinkan.',

            $exception instanceof ValidationException => 'Data yang Anda masukkan tidak valid.',

            $exception instanceof ConflictHttpException => 'Terjadi konflik dalam permintaan Anda.',

            $exception instanceof GoneHttpException => 'Sumber daya yang diminta sudah tidak tersedia.',

            $exception instanceof PreconditionFailedHttpException => 'Prasyarat permintaan tidak terpenuhi.',

            $exception instanceof PreconditionRequiredHttpException => 'Prasyarat diperlukan untuk melanjutkan permintaan.',

            $exception instanceof TooManyRequestsHttpException => 'Terlalu banyak permintaan dalam waktu singkat. Silakan coba lagi nanti.',

            $exception instanceof ServiceUnavailableHttpException => 'Layanan sedang tidak tersedia, silakan coba lagi nanti.',

            $exception instanceof UnsupportedMediaTypeHttpException => 'Tipe media yang dikirimkan tidak didukung oleh server.',

            default => 'Terjadi kesalahan pada sistem. Silakan coba lagi nanti.',

        };

    }



    /**

     * Dapatkan status code berdasarkan tipe error.

     */

    protected function getStatusCode(Throwable $exception)

    {

        return match (true) {

            $exception instanceof BadRequestHttpException => 400,

            $exception instanceof UnauthorizedHttpException => 401,

            $exception instanceof AccessDeniedHttpException => 403,

            $exception instanceof UnauthorizedException => 403,

            $exception instanceof NotFoundHttpException,

            $exception instanceof ModelNotFoundException => 404,

            $exception instanceof MethodNotAllowedHttpException => 405,

            $exception instanceof NotAcceptableHttpException => 406,

            $exception instanceof ConflictHttpException => 409,

            $exception instanceof GoneHttpException => 410,

            $exception instanceof LengthRequiredHttpException => 411,

            $exception instanceof PreconditionFailedHttpException => 412,

            $exception instanceof PreconditionRequiredHttpException => 428,

            $exception instanceof TooManyRequestsHttpException => 429,

            $exception instanceof UnsupportedMediaTypeHttpException => 415,

            $exception instanceof ServiceUnavailableHttpException => 503,

            default => 500,

        };

    }



    /**

     * Dapatkan title error berdasarkan status code.

     */

    protected function getErrorTitle($statusCode)

    {

        return match ($statusCode) {

            400 => "Permintaan Tidak Valid",

            401 => "Akses Ditolak",

            403 => "Akses Dilarang",

            404 => "Data Tidak Ditemukan",

            405 => "Metode Tidak Diizinkan",

            406 => "Permintaan Tidak Dapat Diterima",

            409 => "Terjadi Konflik",

            410 => "Sumber Daya Tidak Tersedia",

            411 => "Panjang Konten Diperlukan",

            412 => "Prasyarat Gagal",

            415 => "Tipe Media Tidak Didukung",

            428 => "Prasyarat Diperlukan",

            429 => "Terlalu Banyak Permintaan",

            503 => "Layanan Tidak Tersedia",

            500 => "Kesalahan Server",

            default => "Terjadi Kesalahan",

        };

    }

}


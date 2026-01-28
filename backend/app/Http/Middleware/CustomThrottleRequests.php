<?php
namespace App\Http\Middleware;

use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Middleware\ThrottleRequests;

class CustomThrottleRequests extends ThrottleRequests
{
    /**
     * Override response saat permintaan terlalu banyak.
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = 60; // Tetapkan waktu jeda ke 60 detik.

        return response()->json([
            'message' => 'Anda Telah Salah Email atau Password Lebih Dari 3 Kali, Akun Anda TerSuspend 1 Menit',
            'status_code' => 429,
            'retry_after' => $retryAfter, // Tambahkan waktu jeda di respons.
        ], 429);
    }

    /**
     * Override exception handling untuk menambahkan pesan kustom.
     */
    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $retryAfter = 10; // Tetapkan waktu jeda ke 60 detik.

        $headers = $this->buildHeaders($maxAttempts, $retryAfter);

        throw new ThrottleRequestsException(
            'Anda Telah Salah Email atau Password Lebih Dari 3 Kali, Akun Anda TerSuspend 10 Detik', // Pesan kustom
            null,
            $headers
        );
    }

    /**
     * Membuat header throttling secara manual.
     */
    protected function buildHeaders($maxAttempts, $retryAfter)
    {
        return [
            'Retry-After' => $retryAfter,
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => 0,
            'X-RateLimit-Reset' => now()->addSeconds($retryAfter)->timestamp,
        ];
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class LoginAttemptLimiter
{
    protected $maxAttempts = 3; // Maksimal percobaan
    protected $decayMinutes = 1; // Waktu blokir (menit)

    public function handle($request, Closure $next)
    {
        $key = $this->getRateLimitKey($request);

        // Cek apakah pengguna telah melampaui batas percobaan
        if (Cache::has($key) && Cache::get($key) >= $this->maxAttempts) {
            $retryAfter = $this->decayMinutes * 60; // dalam detik
            return response()->json([
                'message' => 'Anda telah mencoba terlalu banyak. Silakan coba lagi dalam ' . $this->decayMinutes . ' menit.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        $response = $next($request);

        // Jika login gagal, tambahkan percobaan
        if ($response->getStatusCode() === Response::HTTP_UNAUTHORIZED) {
            Cache::increment($key);
            Cache::put($key, Cache::get($key), now()->addMinutes($this->decayMinutes));
        }

        return $response;
    }

    protected function getRateLimitKey($request)
    {
        // Gunakan kombinasi IP dan email untuk membuat key unik
        return 'login_attempts:' . $request->ip() . ':' . $request->input('email');
    }
}

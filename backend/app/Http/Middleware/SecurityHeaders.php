<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // Hapus header yang expose technology
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        
        // Tambahkan security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Only set HSTS for HTTPS connections
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security','max-age=31536000; includeSubDomains; preload');
        }

        // Allow CORS headers to pass through
        if ($request->getMethod() === 'OPTIONS') {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            $response->headers->set('Access-Control-Max-Age', '86400');
        }

        return $response;
    }
}
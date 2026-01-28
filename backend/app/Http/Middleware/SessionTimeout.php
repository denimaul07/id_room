<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        $now = now()->timestamp;

        // ==== PENGATURAN ====
        $idleLifetime = 60 * 60;       // 1 jam tidak ada aktivitas
        $absoluteLifetime = 4 * 60 * 60; // 4 jam absolute timeout
        // =====================

        $loginTime = session('loginTime');
        $lastActivity = session('lastActivityTime');

        // Set login time awal
        if (!$loginTime) {
            session(['loginTime' => $now]);
        }

        // Cek Absolute Timeout
        if ($loginTime && ($now - $loginTime > $absoluteLifetime)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Session expired (absolute timeout)'
            ], 401);
        }

        // Cek Idle Timeout
        if ($lastActivity && ($now - $lastActivity > $idleLifetime)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Session expired (idle timeout)'
            ], 401);
        }

        // Update last activity
        session(['lastActivityTime' => $now]);

        return $next($request);
    }
}

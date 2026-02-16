<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RefreshTokenController extends Controller
{
    public function refresh(Request $request)
    {
        // Ambil refresh token dari HttpOnly cookie
        $rt = $request->cookie('refresh_token');
        if (!$rt)
            return response()->json(['message' => 'refresh token missing'], 401);

        $hashed = hash('sha256', $rt);

        $record = RefreshToken::where('token', $hashed)
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record)
            return response()->json(['message' => 'Invalid refresh token'], 401);

        // ROTATE refresh token: revoke yang lama
        $record->update(['revoked' => true]);

        // generate refresh token baru
        $newRefresh = Str::random(64);
        $newExpiry = now()->addDays(1);

        RefreshToken::create([
            'user_id' => $record->user_id,
            'token' => hash('sha256', $newRefresh),
            'expires_at' => $newExpiry
        ]);

        $user = User::find($record->user_id);

        // generate access token baru dengan TTL 5 menit
        $newAccess = auth()->login($user);
        $accessExpiry = now()->addMinutes(1)->timestamp;

        // Set refresh token baru ke HttpOnly cookie
        $cookie = cookie('refresh_token', $newRefresh, 60 * 24, null, null, true, true, false, 'Strict');

        return response()->json([
            'token' => $newAccess,
            'expired_in' => $accessExpiry,
            'refresh_expired_in' => $newExpiry->timestamp,
            'refresh_exp' => $newExpiry->timestamp,
            'oldtoken' => $hashed,
            'record' => $record,
        ])->cookie($cookie);
    }

    public function logout(Request $request)
    {
        $rt = $request->refresh_token;

        if ($rt) {
            RefreshToken::where('token', hash('sha256', $rt))->update(['revoked' => true]);
        }

        // auth()->logout();

        return response()->json(['message' => 'Logged out']);
    }
}

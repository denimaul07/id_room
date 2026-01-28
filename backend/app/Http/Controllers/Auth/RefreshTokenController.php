<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;

class RefreshTokenController extends Controller
{
    public function refresh(Request $request)
    {
        $rt = $request->refresh_token;
        if (!$rt) return response()->json(['message' => 'refresh token missing'], 401);

        $hashed = hash('sha256', $rt);


        $record = RefreshToken::where('token', $hashed)
                    ->where('revoked', false)
                    ->where('expires_at', '>', now())
                    ->first();

        if (!$record) return response()->json(['message' => 'Invalid refresh token'], 401);
        

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

        return response()->json([
            'token'               => $newAccess,
            'expired_in'          => $accessExpiry,
            'refresh_token'       => $newRefresh,
            'refresh_expired_in'  => $newExpiry->timestamp,
            'refresh_exp'         => $newExpiry->timestamp,
            'oldtoken'            => $hashed,
            'record'              => $record,
        ]);
    }

    public function logout(Request $request)
    {
        $rt = $request->refresh_token;

        if ($rt) {
            RefreshToken::where('token', hash('sha256', $rt))->update(['revoked' => true]);
        }

        //auth()->logout();

        return response()->json(['message' => 'Logged out']);
    }


}

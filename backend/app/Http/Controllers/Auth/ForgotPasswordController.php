<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendWAForgotPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;



class ForgotPasswordController extends Controller

{

    public function sendResetLinkEmail(Request $request)

    {

        $request->validate(['email' => 'required|email']);



        $status = Password::sendResetLink(

            $request->only('email'),

            function ($user, $token) {

                // Sesuaikan URL reset password untuk API/Frontend

                $frontendUrl = config('app.frontend_url') . "/forgot_password?token=$token&email=" . urlencode($user->email);

                $user->notify(new \App\Notifications\ResetPasswordNotification($frontendUrl));

                // Kirim WA jika user punya nomor telepon
                if ($user->phone) {
                    try {
                        SendWAForgotPassword::dispatch($user->email, $frontendUrl);
                    } catch (\Exception $e) {
                        Log::warning('Gagal dispatch SendWAForgotPassword', ['error' => $e->getMessage()]);
                    }
                }
            }

        );



        try {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => 'Kami telah mengirimkan link reset password. Jika tidak menerima email, periksa folder spam atau hubungin TIM IT.'], 200);
            } else {
                return response()->json(['message' => __($status)], 401);
            }
        } catch (\Exception $th) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengirim email reset password.', 'error' => $th->getMessage()], 500);
        }

    }

}


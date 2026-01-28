<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

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

            }

        );



        return $status === Password::RESET_LINK_SENT

            ? response()->json(['message' => 'Kami telah mengirimkan link reset password. Jika tidak menerima email, periksa folder spam atau hubungin TIM IT.'], 200)

            : response()->json(['message' => 'Kami telah mengirimkan link reset password. Jika tidak menerima email, periksa folder spam atau hubungin TIM IT.'], 401);

    }

}


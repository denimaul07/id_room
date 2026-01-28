<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Mail;
use App\Models\User;
use App\Mail\Forgot;
use Illuminate\Support\Facades\Http;



class AuthController extends Controller
{
    public function login(Request $request){
        $rules = [
            'email' => [
                'required',
                'max:255'
            ],
            'password' => [
                'required',
                'string',
            ]
        
            
            ];
        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors(),
                'message' => 'Failed Login',
            ];
            return response()->json($response, 400);
        }

        $credential= $request->only('email','password');


        try {
            if(!$token = auth()->attempt($credential)){
                $data = ['Email or Password is incorrect'];
                
                $response = [
                    'message' => $data,
                ];
                return response()->json($response, 401);
    
                
            }
        }catch(JWTException $e){
            throw $e;
        }

        $user = auth()->user();

        RefreshToken::where('user_id', $user->id)->delete();

        return $this->respondWithToken($token, $user);

    }

    private function respondWithToken($token, $user)
    {
        // TTL access token fix 5 menit
        $ttl = 1; // 5 menit
        $expiry = now()->timestamp; // pastikan ini sekarang
        $expiry = now()->addMinutes($ttl)->timestamp; // tambah 5 menit dari sekarang


        // 👇 generate refresh token
        $refreshToken = Str::random(64);
        $refreshExpiry = now()->addDays(1);

        // simpan ke DB
        RefreshToken::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $refreshToken),
            'expires_at' => $refreshExpiry,
        ]);

        // update last login
        $user->update(['last_login' => now()]);

        return response()->json([
            'users'         => auth()->user(),
            'permissions'   => auth()->user()->getPermissionsViaRoles()->pluck('name'),
            'token'         => $token,
            'refresh_token' => $refreshToken,
            'token_type'    => 'bearer',
            'expired_in'    => $expiry,
            'refresh_exp'   => $refreshExpiry->timestamp,
        ]);
    }



    private function respondRefreshWithToken($token){
        // Mendapatkan waktu kedaluwarsa token dalam menit dari konfigurasi JWT
        $ttl = config('jwt.ttl');

        // Menghitung waktu kedaluwarsa token dalam detik
        $expiry = Carbon::now()->addSeconds($ttl * 60)->timestamp;
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expired_in' => $expiry

        ]);
    }

    public function getUser()
    {   
        return response()->json([
            'data' => Auth::user()
        ], 200);
    }

    public function getMenu()
    {   
        return response()->json([
            'users' => auth()->user(),
            'permissions' => Auth::user()->getPermissionsViaRoles()->pluck('name')
        ], 200);
    }

    public function refresh(){
        try {
            if (!$token = auth()->getToken()) {
                throw new NotFoundHttpException("Token Does Not Exist");
            }

            return $this->respondRefreshWithToken(auth()->refresh($token));
        } catch(JWTException $e){
            throw $e;
        }
    
    }

    public function logout(){
        try {
            auth()->logout();
        } catch(JWTException $e){
            throw $e;
        }
        return response()->json(['message' => 'Users Logged out success']);
    
    }

    public function forgot(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email',
            ]
        ];

        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors(),
                'message' => 'Failed Input',
            ];
            return response()->json($response, 400);
        }

        $email = $request->input('email');
        $user = User::where('email', $email)->count();

        if ($user > 0) {
            $users = User::where('email', $email)->first();
            $random = random_int(100000, 999999);
            $password = $random;

            $update = User::findOrFail($users->id);
            $update->update([
                'password'  => $password,
                'change_password' => 0,
            ]);
            
            $data = [
                'name' => $users->name,
                'email' => $users->email,
                'password' => $random
            ];
            
           
        
            Mail::to($email)->send(new Forgot($data));
                
            $nowa=ltrim($users->wa, '0');
            $key='5e8e66d600e40cb88ae4b5545eb65d9e618c3a47c81a2383'; //this is demo key please change with your own key
            $url='http://116.203.191.58/api/send_message';
            $data = array(
              "phone_no"  => '+62'.$nowa,
              "key"       => $key,
              "message"   =>  "Hallo ".$users->name." Kami Telah Menerima Request Reset Password Anda, Berikut adalah Password Baru Anda ". $random,
              "deliveryFlag" => True, // This optional for get status in message use api `check_delivery_status`
            );
            $data_string = json_encode($data);
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 360);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'Content-Length: ' . strlen($data_string))
            );
            echo $res=curl_exec($ch);
            curl_close($ch);
          
            
            
            
    
    
            return response()->json(['success' => true]);
        }else{
            $response = [
                'data' => [
                    'email' => [
                        'Harap Hubungi IT yaa'
                    ],
                ],
                'message' => 'Failed Input',
            ];
            return response()->json($response, 400);
        }

    }

    
}

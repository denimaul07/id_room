<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:6'
            ]
        
            
            ];
        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'validation error' => $validate->errors()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        // Log activity create user
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['attributes' => $user->toArray()])
            ->log('User created');

        $token = auth()->login($user);

        try{
            $token = auth()->login($user);
        }catch(JWTException $e){
            throw $e;
        }

        return $this->respondWithToken($token);
    }

    private function respondWithToken($token){
        // Mendapatkan waktu kedaluwarsa token dalam menit dari konfigurasi JWT
        $ttl = config('jwt.ttl');

        // Menghitung waktu kedaluwarsa token dalam detik
        $expiry = Carbon::now()->addSeconds($ttl * 60)->timestamp;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expired_in' => $expiry

        ]);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->fill($request->only(['name', 'email', 'password']));
        $user->save();

        // Log activity update user
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['attributes' => $user->toArray()])
            ->log('User updated');

        return response()->json(['message' => 'User updated', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Log activity delete user
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['attributes' => $user->toArray()])
            ->log('User deleted');

        return response()->json(['message' => 'User deleted']);
    }
}

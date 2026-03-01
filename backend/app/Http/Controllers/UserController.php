<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use App\Models\PropertieInquire;
use App\Models\Properties;
use App\Models\Referral_Setting;
use App\Models\PointTransactions;
use App\Models\WalletPoint;
use App\Models\InvoiceReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

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
            'kode' => [
                'required',
                'string'
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
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'data' => $validate->errors()
            ], 400);
        }

        $user = User::create([
            'odata' => (string) Str::uuid(),
            'kode' => $request->kode,
            'phone' => $request->phone,
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

        try {
            $token = auth()->login($user);
        } catch (JWTException $e) {
            throw $e;
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
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
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'confirm_password' => [
                'required',
                'same:password'
            ],
            'country_code' => [
                'required',
                'string'
            ],
            'phone' => [
                'required',
                'string'
            ],
            'birth_date' => [
                'nullable',
                'date'
            ],
            'referral_code' => [
                'nullable',
                'string'
            ]
        ];

        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 30 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email Anda Sudah Terdaftar Silahlan Login.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.mixedCase' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol.',
            'confirm_password.required' => 'Konfirmasi password wajib diisi.',
            'confirm_password.same' => 'Konfirmasi password harus sama dengan password.',
            'country_code.string' => 'Kode negara harus berupa teks.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'birth_date.date' => 'Tanggal lahir harus berupa tanggal.',
            'referral_code.string' => 'Kode referral harus berupa teks.'
        ];
        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            $errors = $validate->errors()->toArray();
            $translatedErrors = [];
            foreach ($errors as $field => $messages) {
                $translatedErrors[$field] = array_map(function ($msg) {
                    // Translate each error message to Indonesian
                    return __($msg, [], 'id');
                }, $messages);
            }
            return response()->json([
                'message' => 'Validasi gagal',
                'data' => $translatedErrors
            ], 400);
        }

        $uniqueCode = $request->country_code . $request->phone;
        if (User::where('phone', $uniqueCode)->exists()) {
            return response()->json([
                'message' => 'Nomor telepon sudah terdaftar'
            ], 400);
        }

        try {
            $referral_code = User::where('referral_code', $request->referral_code)->first();
            if ($request->referral_code && !$referral_code) {
                return response()->json([
                    'message' => 'Kode referral tidak valid'
                ], 400);
            }

            $birthDate = $request->birth_date ? Carbon::parse($request->birth_date)->format('Y-m-d') : null;

            $user = User::create([
                'odata' => (string) Str::uuid(),
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->country_code . $request->phone,
                'birth_date' => $birthDate,
                'referrer_id' => $referral_code ? $referral_code->id : null,
                'status_users' => 2,
                'change_password' => 1
            ]);

            User::where('id', $user->id)->update([
                'referral_code' =>  strtoupper(substr(Str::slug($user->name, ''), 0, 4)). str_pad($user->id, 4, '0', STR_PAD_LEFT) . strtoupper(Str::random(2))
            ]);

            // assign role
            $user->assignRole('users');

            $reward = Referral_Setting::first();

            DB::transaction(function() use ($referral_code, $reward, $user) {

                $wallet = WalletPoint::where('user_id', $referral_code->id)->lockForUpdate()->first();
                if (!$wallet) {
                    $wallet = WalletPoint::create([
                        'odata' => (string) Str::uuid(),
                        'user_id' => $referral_code->id,
                        'user_odata' => $referral_code->odata,
                        'coin_balance' => 0
                    ]);
                }
                $wallet->coin_balance += $reward->reward_referrer;
                $wallet->save();

                $invoiceReferral = InvoiceReferral::orderBy('no', 'desc')->first();
                $newNo = $invoiceReferral ? str_pad($invoiceReferral->no + 1, 6, '0', STR_PAD_LEFT) : '000001';

                PointTransactions::create([
                    'odata' => (string) Str::uuid(),
                    'invoice_code' => 'REF-' . $newNo,
                    'user_id' => $referral_code->id,
                    'user_odata' => $referral_code->odata,
                    'type' => 'credit',
                    'amount' => $reward->reward_referrer,
                    'source' => 'referral',
                    'reference_id' => $wallet->id,
                    'reference_odata' => $wallet->odata,
                    'description' => 'Reward referral untuk ' . $user->name
                ]);

                InvoiceReferral::create([
                    'no' => $newNo,
                ]);

                $walletReferer = WalletPoint::where('user_id', $user->id)->lockForUpdate()->first();
                if (!$walletReferer) {
                    $walletReferer = WalletPoint::create([
                        'odata' => (string) Str::uuid(),
                        'user_id' => $user->id,
                        'user_odata' => $user->odata,
                        'coin_balance' => 0
                    ]);
                }
                $walletReferer->coin_balance += $reward->reward_referred;
                $walletReferer->save();

                $invoiceReferralReferred = InvoiceReferral::orderBy('no', 'desc')->first();
                $newNoReferred = $invoiceReferralReferred ? str_pad($invoiceReferralReferred->no + 1, 6, '0', STR_PAD_LEFT) : '000001';

                PointTransactions::create([
                    'odata' => (string) Str::uuid(),
                    'invoice_code' => 'REF-' . $newNoReferred,
                    'user_id' => $user->id,
                    'user_odata' => $user->odata,
                    'type' => 'credit',
                    'amount' => $reward->reward_referred,
                    'source' => 'referral',
                    'reference_id' => $walletReferer->id,
                    'reference_odata' => $walletReferer->odata,
                    'description' => 'Reward referral untuk pendaftaran'
                ]);

                InvoiceReferral::create([
                    'no' => $newNoReferred,
                ]);
            });

            // Log activity create user
            activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['attributes' => $user->toArray()])
            ->log('User registered');

            try {
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e) {
                // Jika gagal kirim email, skip dan lanjutkan
            }

            $response = [
                'message' => 'Registrasi berhasil. Silakan cek email untuk verifikasi.'
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        // 1️⃣ VALIDASI SIGNATURE URL
        if (!$request->hasValidSignature()) {
            return response()->json([
                'message' => 'Link verifikasi tidak valid atau sudah kedaluwarsa'
            ], 403);
        }

        // 2️⃣ CARI USER
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // 3️⃣ VALIDASI HASH EMAIL
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Hash verifikasi tidak valid'], 403);
        }

        // 4️⃣ CEK SUDAH VERIFIKASI
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email sudah terverifikasi'], 200);
        }

        // 5️⃣ VERIFIKASI EMAIL
        $user->markEmailAsVerified();
        $user->update(['status_users' => 0]);

        // Redirect ke halaman login website dengan pesan sukses
        return redirect()->away(env('FRONTEND_URL', 'http://localhost:5174') . '/verified');
    }

    private function respondWithToken($token)
    {
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

    public function contactAgent(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string'
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'data' => $validate->errors()
            ], 400);
        }

        try {
            $property = Properties::where('odata', $request->property_odata)->first();
            if (!$property) {
                return response()->json(['message' => 'Properti tidak ditemukan'], 404);
            }

            PropertieInquire::create([
                'odata' => (string) Str::uuid(),
                'property_id' => $property->id,
                'property_odata' => $property->odata,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message
            ]);

             // Log activity contact agent
            activity()
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => $request->all()])
            ->log('Contact agent');

            return response()->json(['message' => 'Pesan berhasil dikirim. Tunggu kontak dari agen kami.'], 200);
        } catch (JWTException $th) {
        throw $th;
        }
    }  
}

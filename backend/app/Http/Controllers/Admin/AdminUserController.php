<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Http;

class AdminUserController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    
        //return $users;
        try {
        
            $data = User::selectRaw('*, users.id as id,  users.kode, name, deptname, email, status_users')
            ->with('roles')
            ->leftJoin('departments','departments.kode','=','users.kode')
            ->where(function ($query) use ($request) {
                $query->where('email', "like", "%" . $request->search . "%");
                $query->orWhere('name', "like", "%" . $request->search . "%");
            })
            ->where(function ($query) use ($request) {
                if($request->status){
                    $query->where('status_users', 'like', $request->status . "%");
                }
            })
            ->where(function ($query) use ($request) {
                if($request->dept){
                    $query->where('users.kode', $request->dept);
                }
                
            })
            ->orderBy('users.id','desc')->paginate(10);

            $response=[
                'data' => $data
            ];
            
            return response()->json($response, 201);
        
            
        } catch (JWTException $th) {
            throw $th;
        }


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
                        'min:2',
                        'max:30'
                    ],
                    'email' => [
                        'required',
                        'unique:users'
                    ],
                    'password' => [
                        'required'
                    ],
                    'kode' => [
                        'required'
                    ],
                    'roles' => [
                        'required'
                    ],
                    'status_users' => [
                        'required'
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

        $user = User::create([
            // 'odata' => (string) Str::uuid(),
            'name'      => $request->input('name'),
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
            'kode'     => $request->input('kode'),
            'status_users' => $request->input('status_users')
        ]);

        // Log activity create user
        activity()
            ->causedBy(auth()->user())
            ->event('created') 
            ->performedOn($user)
            ->withProperties(['attributes' => $user->toArray()])
            ->log('Menambahkan user baru');

        //assign role
        $user->assignRole($request->input('roles'));

        $token = auth()->login($user);

        try{
            $token = auth()->login($user);
        }catch(JWTException $e){
            throw $e;
        }

        return $this->respondWithToken($token);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! $user = User::find($id)) {
            throw new NotFoundHttpException("USers Does Not Exist");
        }
        //return $users;
        return $this->collection($users, new UserTransformer)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:30'
            ],
            'email' => [
                'required',
            ],
            'kode' => [
                'required'
            ],
            'roles' => [
                'required'
            ],
            'status_users' => [
                'required'
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


        $user = User::findOrFail($request->input('id'));
    
        if($request->input('password') == "") {
            $user->update([
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'kode'     => $request->input('kode'),
                'status_users' => $request->input('status_users')
            ]);
        } else {
            $user->update([
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'password'  => $request->input('password'),
                'kode'     => $request->input('kode'),
                'status_users' => $request->input('status_users')
            ]);
        }

        // Log activity update user
        activity()
            ->causedBy(auth()->user())
            ->event('updated') 
            ->performedOn($user)
            ->withProperties(['attributes' => $user->toArray()])
            ->log('Update data user');

        //assign role
        $user->syncRoles($request->input('roles'));

        $response = [
            'data' => 'User Updated',
            'message' => 'Suceess',
        ];

        return response()->json($response, 200);

    }
    
    public function usersGroup(Request $request)
    {
        $rules = [
            'kode' => [
                'required'
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


        $kode = implode(",", $request->kode);

        User::where('id', $request->id)->update([
            'group_divisi' => $kode
        ]);
     
        $response = [
            'data' => 'Password Updated',
            'message' => 'Suceess',
        ];
        return response()->json($response, 200);
    }
    
    public function usersNotif(Request $request)
    {
        $rules = [
            'kode' => [
                'required'
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


        $kode = implode(",", $request->kode);

        User::where('id', $request->id)->update([
            'notif_divisi' => $kode
        ]);
     
        $response = [
            'data' => 'Password Updated',
            'message' => 'Suceess',
        ];
        return response()->json($response, 200);
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
            ->event('deleted') 
            ->performedOn($user)
            ->withProperties(['attributes' => $user->toArray()])
            ->log('Menghapus user');

        return response()->json(['message' => 'User deleted']);
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

    public function update_profile(Request $request)
    {
        $rules = [
            'oldpass' => [
                'required',
            ],
            'newpass' => [
                'required',
                Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            ]
        ];

        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors()->all(),
                'message' => 'Failed Input',
            ];
            return response()->json($response, 400);
        }


        $hashedPassword = Auth::user()->password;
        if (\Hash::check($request->oldpass , $hashedPassword)) {
            $user = User::findOrFail(Auth::id());
            $user->update([
                'password' => $request->input('newpass')
            ]);

            $response = [
                'data' => 'Password Updated',
                'message' => 'Suceess',
            ];
            return response()->json($response, 200);
        
        }
        else{
            $response = [
                'data' => [
                    'data' => [
                        'old password doesnt matched'
                    ],
                ],
                'message' => 'Upss Error',
            ];
            return response()->json($response, 400);
        }

        $token = auth()->login($user);

        try{
            $token = auth()->login($user);
        }catch(JWTException $e){
            throw $e;
        }

        return $this->respondWithToken($token);
    }
    
    public function update_wa(Request $request)
    {
     

 
   
        
        $user = User::where('id',$request->input('id'));
        $user->update([
            'wa' => $request->input('wa'),
            'status_notif' => $request->input('status_notif')
        ]);

        $response = [
            'data' => 'Password Updated',
            'message' => 'Suceess',
        ];
        return response()->json($response, 200);
    
      

    }
    
    public function send_wa(Request $request)
    {
        
        
       
        // $dataSending = Array();
        // $dataSending["api_key"] = "N5PVJPLOPXK477VR";
        // $dataSending["number_key"] = "DzcV1OAUXImn4Atm";
        // $dataSending["phone_no"] = $request->input('wa');
        // $dataSending["message"] = "Hallo ".auth()->user()->name." Anda Telah Sukses Mengaktifkan Notification System Imora. Jangan Lupa Save No ini ya";
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://api.watzap.id/v1/send_message',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'POST',
        //   CURLOPT_POSTFIELDS => json_encode($dataSending),
        //   CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/json'
        //   ),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);
        
        $nowa=ltrim($request->input('wa'), '0');
        $key='5e8e66d600e40cb88ae4b5545eb65d9e618c3a47c81a2383'; //this is demo key please change with your own key
        $url='http://116.203.191.58/api/send_message';
        $data = array(
          "phone_no"  => '+62'.$nowa,
          "key"       => $key,
          "message"   =>  "Hallo ".auth()->user()->name." Anda Telah Sukses Mengaktifkan Notification System Imora. Jangan Lupa Save No ini ya",
          "deliveryFlag" => True, // This optional for get status in message use api `check_delivery_status`
        );
        
        // Konversi data menjadi format JSON
        $jsonData = json_encode($data);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        echo $res=curl_exec($ch);
        curl_close($ch);

        
 
     
        $response = [
            'message' => 'Suceess',
        ];
    
        return response()->json($response, 200);
    
      

    }

    public function change_profile(Request $request){
        $rules = [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];

        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors(),
                'message' => 'Failed Input',
            ];
            return response()->json($response, 400);
        }

        $name = time()."-".$request->file('image')->getClientOriginalName();

        $request->file('image')->move(storage_path('app/public/foto'), $name); 
    
        $user = User::findOrFail($request->input('id'));
        $user->update([
            'foto'      =>   $name,
        ]);
        
        return response()->json([ 
            'data'   => $name,
            'message' => 'Gambar baru berhasil disimpan.'
        ]); 
    
    }

    public function change_password(Request $request)
    {
        $rules = [
            'new_password' => [
                'required',
                Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            ],
            'confirmasi_password' => [
                'required',
            
            ]
        ];

        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors()->all(),
                'message' => 'Failed Input',
            ];
            return response()->json($response, 400);
        }

        if($request->input('new_password') != $request->input('confirmasi_password')){
            $response = [
                'data' => [
                    'data' => [
                        'new password & comfirmasi password doesnt matched'
                    ],
                ],
                'message' => 'Upss Error',
            ];
            return response()->json($response, 400);
        }else{
            $user = User::findOrFail($request->input('id'));
            $user->update([
                'password' => $request->input('new_password'),
                'change_password' => 1
            ]);

            $response = [
                'data' => 'Password Updated',
                'message' => 'Suceess',
            ];
            return response()->json($response, 200);
        }

    }
    
    public function updateOdata(Request $request)
    {

        try {
           
            $data = User::get();
            
            foreach($data as $data){
                User::where('id', $data->id)->update(['odata' => (string) Str::uuid()]);
            }
             
             
      
            $response=[
                'data' => 'sukses'
            ];
            
            return response()->json($response, 201);
                
        } catch (JWTException $th) {
            throw $th;
        }

    }


}

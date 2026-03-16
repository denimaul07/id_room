<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Exceptions\JWTException;

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
        
            $data = User::selectRaw('*, users.id as id, users.kode, name, deptname, properties, email, status_users')
            ->with('roles')
            ->leftJoin('departments','departments.kode','=','users.kode')
            ->leftJoin('properties','properties.odata','=','users.kode')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'superAdmin','properties','receptionis','admincro']);
            })
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
            'odata' => (string) Str::uuid(),
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

        $response = [
            'data' => 'User Created',
            'message' => 'Success',
        ];

        return response()->json($response, 201);
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


    public function updateProfile(Request $request)
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
                'email',
            ],
            'country_code' => [
                'required',
            ],
            'phone' => [
                'required',
            ],
            'birth_date' => [
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

        if($request->input('password') && $request->input('confirm_password')) {

            $rules = [
                'password' => [
                ],
                'confirm_password' => [
                    Password::min(6)
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

            $user = User::findOrFail(Auth::user()->id);

            if (\Hash::check($request->password , $hashedPassword)) {
                $user = User::findOrFail(Auth::id());
                $user->update([
                    'password' => $request->input('confirm_password')
                ]);

               if ($request->hasFile('photo')) {
                    $imagePath = $request->file('photo')->store('foto', 'public');
                    $user->foto = $imagePath;
                }
                $user->update([
                    'name'      => $request->input('name'),
                    'email'     => $request->input('email'),
                    'password'  => $request->input('password'),
                    'country_code' => $request->input('country_code'),
                    'phone' => $request->input('phone'),
                    'birth_date' => $request->input('birth_date'),
                    'address' => $request->input('address'),
                    'foto' => $user->foto ?? null,
                ]);


                $response = [
                    'data' => 'Profile Updated',
                    'message' => 'Success',
                ];

                return response()->json($response, 200);
            }else{
                $response = [
                    'data' => 'Current Password Not Match',
                    'message' => 'Failed',
                ];
                return response()->json($response, 400);
            }
        }else{
            $user = User::findOrFail(Auth::id());

            if ($request->hasFile('photo')) {
                $imagePath = $request->file('photo')->store('foto', 'public');
                $user->foto = $imagePath;
            }


            $user->update([
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'country_code' => $request->input('country_code'),
                'phone' => $request->input('phone'),
                'birth_date' => $request->input('birth_date'),
                'address' => $request->input('address'),
                'foto' =>  $user->foto ?? null,

            ]);

            $response = [
                'data' => 'Profile Updated',
                'message' => 'Suceess',
            ];

            return response()->json($response, 200);
        }
    }

    public function updateWa(Request $request)
    {
        $rules = [
            'wa' => [
                'required',
            ],

        ];

        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors()->all(),
                'message' => 'Failed Input',
            ];
            return response()->json($response, 400);
        }

        $user = User::findOrFail(Auth::id());
        $user->update([
            'wa' => $request->input('wa'),
        ]);

        $response = [
            'data' => 'WhatsApp Updated',
            'message' => 'Suceess',
        ];

        return response()->json($response, 200);
    }
    
    public function sendWa(Request $request)
    {
        $rules = [
            'wa' => [
                'required',
            ],

        ];

        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors()->all(),
                'message' => 'Failed Input',
            ];
            return response()->json($response, 400);
        }

        $user = User::findOrFail(Auth::id());

        // Send WhatsApp message using external API
        $response = Http::post('https://api.whatsapp.com/send', [
            'phone' => $request->input('wa'),
            'message' => 'This is a test message from ID Room.',
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'WhatsApp message sent successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to send WhatsApp message'], 500);
        }
    }

    public function update_Profile(Request $request)
    {
        $rules = [
            'oldpass' => ['required'],
            'newpass' => ['required', 'min:6'],
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors(),
                'message' => 'Failed Input',
            ];
            return response()->json($response, 400);
        }

        $user = User::findOrFail(Auth::id());

        if (!\Hash::check($request->input('oldpass'), $user->password)) {
            return response()->json(['message' => 'Old password does not match'], 400);
        }

        $user->update([
            'password' => $request->input('newpass'),
        ]);

        $response = [
            'data' => 'Profile Updated',
            'message' => 'Success',
        ];

        return response()->json($response, 200);
    }

}

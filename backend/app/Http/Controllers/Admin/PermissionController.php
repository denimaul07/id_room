<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $id = $request->id;
            if($id===null){
                $data = Permission::orderBy('id','desc')->get();
            }else{
                $data = Permission::where('id', $id)->get();
            }
            
        } catch (JWTException $th) {
            throw $th;
        }

        $response=[
            'data' => $data
        ];

        return response()->json($response, 201);
    }

    public function menu()
    {
        try {
            $data = Auth::user()->getPermissionsViaRoles()->pluck('name');
        } catch (JWTException $th) {
            throw $th;
        }
        return $data;
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
                'max:30',
                'unique:permissions'
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
        try {
            if (!$user = auth()->user()) {
                throw new NotFoundHttpException("User Does Not Exist");
            }

            $Permission = Permission::create([
                'name' => $request->input('name').'.'.$request->input('functions'),
            ]);

            $response=[
                'message' => 'Permission  Success Added',
                'id' =>  $Permission->id
            ];

            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


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
                'max:30',
            ]
        
            
        ];

        $validate= Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json([
                'validation error' => $validate->errors()
            ]);
        }

        try {
            if (!$user = auth()->user()) {
                throw new NotFoundHttpException("User Does Not Exist");
            }

            $Permission = Permission::findOrFail($request->input('id'));
            $Permission->update([
                'name' => $request->input('name').'.'.$request->input('functions'),
            ]);
    

            $response=[
                'message' => 'Permission  Success Updated',
                'id' =>  $Permission->id
            ];

            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

 
}

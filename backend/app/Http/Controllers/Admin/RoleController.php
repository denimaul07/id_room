<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use App\Transformers\UserTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RoleController extends Controller
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
                $data = Role::orderBy('id','desc')->get();
            }else{
                $data = Role::where('id', $id)->get();
            }
            
        } catch (JWTException $th) {
            throw $th;
        }

        $response=[
            'data' => $data
        ];

        return response()->json($response, 201);
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
                'unique:roles'
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

            $role = Role::create([
                'name' => $request->input('name')
            ]);

            //assign permission to role
            $role->syncPermissions($request->input('permissions'));

            $response=[
                'message' => 'Roles Success Added',
                'id' =>  $role->id
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

            $role = Role::findOrFail($request->input('id'));
            $role->update([
                'name' => $request->input('name')
            ]);
    
            //assign permission to role
            $role->syncPermissions($request->input('permissions'));

            $response=[
                'message' => 'Roles Success Updated',
                'id' =>  $role->id
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

    public function getPermissionRole(Request $request)
    {

        $response=[
            'data' => Role::with('permissions')->where('id', $request->id)->get()
        ];

        return response()->json($response, 201);
    }
}

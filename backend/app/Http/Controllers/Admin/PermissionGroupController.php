<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Models\PermissionGroup;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use App\Http\Requests\StorePermissionGroupRequest;
use App\Http\Requests\UpdatePermissionGroupRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PermissionGroupController extends Controller
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
                $data = PermissionGroup::orderBy('id','desc')->get();
            }else{
                $data = PermissionGroup::where('id', $id)->get();
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
                'group' => [
                'required',
                'min:2',
                'max:30',
                'unique:permission_groups'
            ],
            'active' => [
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

        try {
            if (!$user = auth()->user()) {
                throw new NotFoundHttpException("User Does Not Exist");
            }

            $data = PermissionGroup::updateOrCreate(['id' => $request->id],
                [
                    'group' => $request->group,
                    'active' => $request->active
                ]
            );

            $response=[
                'message' => 'Permission Groups Success Added',
                'id' =>  $data->id
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
                'group' => [
                'required',
                'string',
                'min:2',
                'max:30',
            ],
            'active' => [
                'required'
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

            $data = PermissionGroup::updateOrCreate(['id' => $request->id],
                [
                    'group' => $request->group,
                    'active' => $request->active
                ]
            );

            $response=[
                'message' => 'Permission Groups Success Updated',
                'id' =>  $data->id
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

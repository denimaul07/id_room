<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use App\Transformers\UserTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        

        try {
           
            $data = Department::where(function ($query) use ($request) {
                $query->where('active', "like", "%" . $request->status . "%");
            })->
            where(function ($query) use ($request) {
                $query->where('kode', "like", "%" . $request->q . "%");
                $query->orWhere('deptname', "like", "%" . $request->q . "%");
            })->orderBy('kode','asc')->paginate(10);
          
            
        } catch (JWTException $th) {
            throw $th;
        }

        $response=[
            'data' => $data
        ];

        return response()->json($response, 201);
    }
    

    
    public function getDepartment(Request $request)
    {


        try {
            $data = Department::orderBy('kode','asc')->get();
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
     * @param  \App\Http\Requests\StoreDepartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'kode' => [
                'required',
                'string',
                'min:2',
                'max:30',
                'unique:departments'
            ],
            'deptname' => [
                'required',
                'string',
                'max:255',
            ],
            'group_dept' => [
                'required',
                'string',
                'max:255',
            ],
            'active' => [
                'required'
            ]
        
            
        ];

        $validate= Validator::make($request->all(), $rules);

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

            $dept = Department::updateOrCreate(['id' => $request->id],
                [
                    // 'odata' => (string) Str::uuid(),
                    'kode' => $request->kode,
                    'deptname' => $request->deptname,
                    'group_dept' => $request->group_dept,
                    'active' => $request->active
                ]
            );

            $response=[
                'message' => 'Departement Success Added',
                'id' =>  $dept->id
            ];

            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDepartmentRequest  $request
     * @param  \App\Models\Department  $Department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'kode' => [
                'required',
                'string',
                'min:2',
                'max:30'
            ],
            'deptname' => [
                'required',
                'string',
                'max:255',
            ],
            'group_dept' => [
                'required',
                'string',
                'max:255',
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

            $dept = Department::updateOrCreate(['id' => $request->id],
                [
                    'kode' => $request->kode,
                    'deptname' => $request->deptname,
                    'group_dept' => $request->group_dept,
                    'active' => $request->active
                ]
            );

            $response=[
                'message' => 'Departement Success Updated',
                'id' =>  $dept->id
            ];

            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $Department
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
    
    public function get_divisi(Request $request)
    {


        try {
            $area = $request->area;
            
            if($area===null){
                $data = Department::where('area', 'TIDAK ADA')->orderBy('deptname','asc')->get();
            }else{
                $data = Department::selectRaw('departments.*, COUNT(lms_materi.id_dept) as totalMateri, SUM(CASE WHEN baca = 0 THEN 1 ELSE 0 END) as totalMateriBaca')
                        ->leftJoin('lms_materi','lms_materi.id_dept','=','departments.odata')
                        ->leftJoin('lms_baca_materi','lms_materi.odata','=','lms_baca_materi.id_materi')
                        ->where('area', $area)->groupBy('departments.odata')->orderBy('deptname','asc')->get();
            }
          
            $response=[
                'data' => $data
            ];
    
            return response()->json($response, 201);
                
        } catch (JWTException $th) {
            throw $th;
        }

       
    }
    
    // public function updateOdata(Request $request)
    // {

    //     try {
           
    //         $data = Department::orderBy('deptname','asc')->get();
            
    //         foreach($data as $data){
    //             Department::where('id', $data->id)->update(['odata' => (string) Str::uuid()]);
    //         }
             
             
      
    //         $response=[
    //             'data' => 'sukses'
    //         ];
            
    //         return response()->json($response, 201);
                
    //     } catch (JWTException $th) {
    //         throw $th;
    //     }

    // }
    
    public function setting_divisi(Request $request)
    {

        try {
           
            $data = Department::where('odata', auth()->user()->departments->odata)->orderBy('deptname','asc')->get();
            
            $response=[
                'data' => $data
            ];
    
            return response()->json($response, 201);
                
        } catch (JWTException $th) {
            throw $th;
        }

    }
    
     public function update_divisi(Request $request)
    {

        try {
           
         
            Department::where('odata', $request->id)->update(['desc_divisi' => $request->desc]);
            
             
      
            $response=[
                'data' => 'sukses'
            ];
            
            return response()->json($response, 201);
                
        } catch (JWTException $th) {
            throw $th;
        }

    }



    
}

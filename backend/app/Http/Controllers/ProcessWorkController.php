<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProcessWork\ProcessWorkRequest;
use App\Services\ProcessWork\ProcessWorkService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class ProcessWorkController extends Controller
{
    protected $ProcessWorkService;

    public function __construct(ProcessWorkService $ProcessWorkService)
    {
        $this->ProcessWorkService = $ProcessWorkService;
    }

    public function index()
    {
        try {
            $data = $this->ProcessWorkService->list();
            $response=[
                'data' => $data
            ];
            return response()->json($response, 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(ProcessWorkRequest $request)
    {
        try {
            $this->ProcessWorkService->create($request->only([
                'odata_setting',
                'title',
                'desc',
                'icon',
                'isActive',
            ]));

            $response = [
                'message' => 'ProcessWork created successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function update(ProcessWorkRequest $request)
    {
        try {
            $this->ProcessWorkService->update($request->odata, $request->only([
                'title',
                'desc',
                'icon',
                'isActive',
            ]));

            $response = [
                'message' => 'ProcessWork updated successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->ProcessWorkService->delete($request->odata);
            $response = [
                'message' => 'ProcessWork deleted successfully',
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }
}

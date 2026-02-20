<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function overview(Request $request)
    {
        $data = $this->dashboardService->getOverview(
            $request->input('page'),
            $request->input('selectedFilter'),
            $request->input('customStart'),
            $request->input('customEnd'),
            $request->input('pagging')
        );
        return response()->json($data);
    }
}

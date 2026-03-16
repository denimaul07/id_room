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

    public function bookingAvailability(Request $request)
    {


        $property = $request->property_id ?? null;

        $data = $this->dashboardService->getBookingAvailability(
            $property
        );

        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }

    public function bookingDetail(Request $request)
    {
        $bookingId = $request->booking_odata ?? null;

        $data = $this->dashboardService->getBookingDetail($bookingId);

        $response=[
            'data' => $data
        ];

        return response()->json($response, 201);
    }

    //properties

    public function overviewProperties(Request $request)
    {
        $data = $this->dashboardService->getOverviewProperties(
            $request->input('selectedFilter'),
            $request->input('customStart'),
            $request->input('customEnd')
        );

        return response()->json($data);
    }

    public function checkinBooking(Request $request)
    {
        $bookingId = $request->booking_odata ?? null;

        $data = $this->dashboardService->checkinBooking($bookingId);

        $response=[
            'message' => 'Check-in successful',
        ];

        return response()->json($response, 201);
    }

    public function checkoutBooking(Request $request)
    {
        $bookingId = $request->booking_odata ?? null;

        $data = $this->dashboardService->checkoutBooking($bookingId);

        $response=[
            'message' => 'Check-out successful',
        ];

        return response()->json($response, 201);
    }

    public function blockRoom(Request $request)
    {
        $roomId = $request->room_odata ?? null;
        $subRoomOdata = $request->sub_room_odata ?? null;
        $checkinDate = $request->checkin_date ?? null;
        $checkoutDate = $request->checkout_date ?? null;

        $data = $this->dashboardService->blockRoom($roomId, $subRoomOdata, $checkinDate, $checkoutDate);

        $response=[
            'message' => 'Room blocked successfully',
        ];

        return response()->json($response, 201);
    }

    public function prepareRoom(Request $request)
    {
        $roomId = $request->room_odata ?? null;
        $subRoomOdata = $request->sub_room_odata ?? null;
        $checkinDate = $request->checkin_date ?? null;
        $checkoutDate = $request->checkout_date ?? null;

        $data = $this->dashboardService->prepareRoom($roomId, $subRoomOdata, $checkinDate, $checkoutDate);

        $response=[
            'message' => 'Room prepared successfully',
        ];

        return response()->json($response, 201);
    }

    public function openRoom(Request $request)
    {
        $bookingId = $request->booking_odata ?? null;

        $data = $this->dashboardService->openRoom($bookingId);

        $response=[
            'message' => 'Room opened successfully',
        ];

        return response()->json($response, 201);
    }

    public function crm(Request $request)
    {
        $users = $this->dashboardService->getCRMData($request->month);

        return response()->json($users);
    }
}

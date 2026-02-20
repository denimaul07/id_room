<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Transactions\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index_wallet(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $filterType = $request->type;
        $filterSource = $request->source;
        $filterDate = $request->start_date && $request->end_date ? [$request->start_date, $request->end_date] : null;
        $data = $this->transactionService->list_wallet($search, $pagging, $filterType, $filterSource, $filterDate);
        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }

    public function detail(Request $request)
    {
        $odata = $request->odata;
        $source = $request->source;
        $data = $this->transactionService->detail($odata, $source);
        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }

    public function index_booking_transactions(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $filterDate = $request->start_date && $request->end_date ? [$request->start_date, $request->end_date] : null;
        $data = $this->transactionService->list_booking_transactions($search, $pagging, $filterDate);
        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }

    public function index_membership_transactions(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $filterDate = $request->start_date && $request->end_date ? [$request->start_date, $request->end_date] : null;
        $data = $this->transactionService->list_membership_transactions($search, $pagging, $filterDate);
        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }

    public function index_top_up_transactions(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $filterDate = $request->start_date && $request->end_date ? [$request->start_date, $request->end_date] : null;
        $data = $this->transactionService->list_top_up_transactions($search, $pagging, $filterDate);
        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }

    public function index_all_transactions(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $filterType = $request->type;
        $filterStatus = $request->status;
        $filterDate = $request->start_date && $request->end_date ? [$request->start_date, $request->end_date] : null;
        $data = $this->transactionService->list_all_transactions($search, $pagging, $filterType, $filterStatus, $filterDate);
        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }

    public function detail_transaction(Request $request)
    {
        $odata = $request->odata;
        $type = $request->type;
        $data = $this->transactionService->detail_transaction($odata, $type);
        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }

    public function cancel_booking_transactions(Request $request)
    {
        $odata = $request->odata;
        $data = $this->transactionService->cancel_booking_transactions($odata);
        $response=[
            'message' => 'Booking transaction cancelled successfully',
        ];
        return response()->json($response, 201);
    }

    public function get_booking(Request $request)
    {
        $pagging = $request->pagging ?? 10;
        $search = $request->search;
        $date = $request->start_date && $request->end_date ? [$request->start_date, $request->end_date] : null;
        $data = $this->transactionService->get_booking($search, $pagging, $date);
        $response=[
            'data' => $data
        ];
        return response()->json($response, 201);
    }
}

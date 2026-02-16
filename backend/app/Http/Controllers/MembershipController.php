<?php
namespace App\Http\Controllers;

use App\Http\Requests\Membership\MembershipRequest;
use App\Services\Membership\MembershipService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use PDF;

class MembershipController extends Controller
{
    protected $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function index(Request $request)
    {
        try {
            $memberships = $this->membershipService->list($request->search, $request->pagging);
            $response = [
                'data' => $memberships
            ];
            return response()->json($response, 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function store(MembershipRequest $request)
    {
        try {
            $this->membershipService->create($request->only([
                'odata',
                'title',
                'desc',
                'price',
                'discount_percent',
                'duration',
                'fee_admin',
                'cancel_booking_fee',
                'isActive',
                'benefits', // tambahkan benefits agar diteruskan ke service
            ]));
            return response()->json(['message' => 'Membership created successfully'], 201);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function update(MembershipRequest $request)
    {
        try {
            $this->membershipService->update($request->odata, $request->only([
                'title',
                'desc',
                'price',
                'duration',
                'discount_percent',
                'fee_admin',
                'cancel_booking_fee',
                'isActive',
                'benefits', // tambahkan benefits agar diteruskan ke service
            ]));
            return response()->json(['message' => 'Membership updated successfully'], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function destroy($odata)
    {
        try {
            $this->membershipService->delete($odata);
            return response()->json(['message' => 'Membership deleted successfully'], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function subscribe(Request $request)
    {
        try {
            $userMembership = $this->membershipService->subscribe($request->only([
                'membership_id'
            ]));
            return response()->json([
                'message' => 'Yeyy! Membership subscribed successfully',
                'data' => $userMembership
            ], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function webhook(Request $request)
    {
        try {
            $this->membershipService->handleWebhook($request->all());
            return response()->json(['message' => 'Webhook handled successfully'], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function myMembership()
    {
        try {
            $userMembership = $this->membershipService->getMyMembership();
            return response()->json([
                'data' => $userMembership
            ], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function listMembership(Request $request)
    {
        try {
            $dateFrom = $request->dateFrom;
            $dateTo = $request->dateTo;
            $filter = $request->filter;
            $search = $request->search;
            $paginate = $request->paginate;
            $keyActive = $request->keyActive;

            $memberships = $this->membershipService->listMembership($dateFrom, $dateTo, $filter, $search, $paginate, $keyActive);
            return response()->json([
                'data' => $memberships
            ], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function myTransactions()
    {
        try {
            $transactions = $this->membershipService->getMyTransactions();
            return response()->json([
                'data' => $transactions
            ], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function listTransactions(Request $request)
    {
        try {
            $dateFrom = $request->dateFrom;
            $dateTo = $request->dateTo;
            $filter = $request->filter;
            $search = $request->search;
            $paginate = $request->paginate;
            $keyActive = $request->keyActive;
            $type = $request->type;

            $transactions = $this->membershipService->listTransactions($dateFrom, $dateTo, $filter, $search, $paginate, $keyActive, $type);
            return response()->json([
                'data' => $transactions
            ], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function myBooking()
    {
        try {
            $bookings = $this->membershipService->getMyBooking();
            return response()->json([
                'data' => $bookings
            ], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function listBooking(Request $request)
    {
        try {
            $dateFrom = $request->dateFrom;
            $dateTo = $request->dateTo;
            $filter = $request->filter;
            $search = $request->search;
            $paginate = $request->paginate;
            $keyActive = $request->keyActive;

            $bookings = $this->membershipService->listBooking($dateFrom, $dateTo, $filter, $search, $paginate, $keyActive);
            return response()->json([
                'data' => $bookings
            ], 200);
        } catch (JWTException $th) {
            throw $th;
        }
    }

    public function printInvoice(Request $request)
    {
        try {
            // Increase max execution time for PDF generation
            set_time_limit(300); // 5 minutes
            $invoiceNumber = $request->id;
            $transactions = $this->membershipService->getInvoiceData($invoiceNumber);
            $data = [
                'transaction' => $transactions['transaction'],
                'detail' => $transactions['detail']
            ];
            $pdf = PDF::loadView('invoice.template', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdfContent = $pdf->output();
            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="invoice_' . $invoiceNumber . '.pdf"');
        } catch (JWTException $th) {
            throw $th;
        }
    }
}

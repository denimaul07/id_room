<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Campaigns\CampaignService;
use App\Http\Requests\Campaigns\CampaignRequest;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected $campaignService;
    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $status = $request->status;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $data = $this->campaignService->list($search, $pagging, $status, $start_date, $end_date);
        $response = [
            'data' => $data
        ];
        return response()->json($response, 200);
    }

    public function store(CampaignRequest $request)
    {
        $this->campaignService->create($request->only([
            'name',
            'template_name',
            'schedule_time',
            'images',
            'status'
        ]));

        $response = [
            'message' => 'Campaign created successfully'
        ];

        return response()->json($response, 201);
    }

    public function update(CampaignRequest $request)
    {
        $odata = $request->odata;
        $this->campaignService->update($odata, $request->only([
            'name',
            'template_name',
            'schedule_time',
            'images',
            'status'
        ]));

        $response = [
            'message' => 'Campaign updated successfully'
        ];

        return response()->json($response, 200);
    }

    public function destroy(Request $request)
    {
        $odata = $request->odata;
        $this->campaignService->delete($odata);

        $response = [
            'message' => 'Campaign deleted successfully'
        ];

        return response()->json($response, 200);
    }

    public function exportTemplate()
    {
        return $this->campaignService->exportTemplate();
    }

    public function uploadContacts(Request $request)
    {
        $odata = $request->odata;
        $data = $request->contacts;
        $this->campaignService->uploadContacts($odata, $data);

        $response = [
            'message' => 'Contacts uploaded successfully'
        ];

        return response()->json($response, 200);
    }

    public function listMembers(Request $request)
    {
        $data = $this->campaignService->listMembers($request->search);

        $response = [
            'data' => $data
        ];

        return response()->json($response, 200);
    }

    public function addMember(Request $request)
    {
        $odata = $request->odata;
        $memberId = $request->member_id;
        $this->campaignService->addMember($odata, $memberId);

        $response = [
            'message' => 'Member added to campaign successfully'
        ];

        return response()->json($response, 200);
    }

    public function getContacts(Request $request)
    {
        $odata = $request->odata;
        $search = $request->search;
        $contacts = $this->campaignService->getContacts($odata, $search);

        $response = [
            'data' => $contacts
        ];

        return response()->json($response, 200);
    }

    public function addMembersBulk(Request $request)
    {
        $odata = $request->odata;
        $memberIds = $request->member_ids;
        foreach ($memberIds as $memberId) {
            $this->campaignService->addMember($odata, $memberId);
        }

        $response = [
            'message' => 'Members added to campaign successfully'
        ];

        return response()->json($response, 200);
    }

    public function deleteContact(Request $request)
    {
        $contactId = $request->contact_id;
        $this->campaignService->deleteContact($contactId);

        $response = [
            'message' => 'Contact deleted successfully'
        ];

        return response()->json($response, 200);
    }

    public function sendWA(Request $request)
    {
        $campaignOdata = $request->odata;
        $extraParams   = $request->extra_params ?? [];
        $coupon_code = $request->coupon_code;

        $result = $this->campaignService->sendWA($campaignOdata, $extraParams, $coupon_code);

        // Langsung return response dari service
        return $result;
    }

    public function sendWAProgress(Request $request)
    {
        $batchKey = $request->query('batch_key');
        return $this->campaignService->sendWAProgress($batchKey); // langsung return, hapus yang lama
    }

    
}
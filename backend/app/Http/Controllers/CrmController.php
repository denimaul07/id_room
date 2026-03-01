<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Crm\CrmService;
use App\Http\Requests\Crm\CrmRequest;
use Illuminate\Http\Request;

class CrmController extends Controller
{

    protected $crmService;
    public function __construct(CrmService $crmService)
    {
        $this->crmService = $crmService;
    }


    public function index(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $status = $request->status;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $data = $this->crmService->list($search, $pagging, $status, $start_date, $end_date);
        return response()->json(['data' => $data['data'], 'total_leads' => $data['total_leads'], 'need_followup' => $data['need_followup'], 'process_followup' => $data['process_followup'], 'closing_leads' => $data['closing_leads'], 'lost_leads' => $data['lost_leads']], 200);
    }

    public function store(CrmRequest $request)
    {
        $name = $request->name;
        $telp = $request->telp;
        $kodenegara= $request->kodenegara;
        $email = $request->email;
        $source = $request->source;
        $remaks = $request->remaks;
        $status = $request->status;

        $crm = $this->crmService->create($request->only(['name', 'telp', 'kodenegara', 'email', 'source', 'remaks', 'status']));
        return response()->json(['message' => 'CRM data created successfully'], 201);
    }

    public function update(CrmRequest $request)
    {
            $odata = $request->odata;
            $name = $request->name;
            $telp = $request->telp;
            $kodenegara= $request->kodenegara;
            $email = $request->email;
            $source = $request->source;
            $remaks = $request->remaks;
            $status = $request->status;
    
            $crm = $this->crmService->update($odata, $request->only(['name', 'telp', 'kodenegara', 'email', 'source', 'remaks', 'status']));
            return response()->json(['message' => 'CRM data updated successfully'], 200);
    }

    public function process(Request $request)
    {
        $odata = $request->odata;
        $status = $request->status;

        $crm = $this->crmService->process($odata, $status);
        return response()->json(['message' => 'CRM data processed successfully'], 200);
    }

    public function process_followup(Request $request)
    {
        $odata = $request->odata;
        $status = $request->status;
        $remaks = $request->remaks;
        $ket_remarks = $request->ket_remarks;

        $crm = $this->crmService->process_followup($odata, $status, $remaks, $ket_remarks);
        return response()->json(['message' => 'CRM data processed successfully'], 200);
    }

    public function get_source()
    {
        $data = $this->crmService->get_source();
        return response()->json(['data' => $data], 200);
    }

    public function get_remark()
    {
        $data = $this->crmService->get_remark();
        return response()->json(['data' => $data], 200);
    }

    public function get_history(Request $request)
    {
        $leads_odata = $request->leads_odata;
        $data = $this->crmService->get_history($leads_odata);
        return response()->json(['data' => $data], 200);
    }

}

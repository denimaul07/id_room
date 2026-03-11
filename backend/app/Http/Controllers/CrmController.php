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
        $tgl_lahir = $request->tgl_lahir;
        $jenis_kelamin = $request->jenis_kelamin;
        $remaks = $request->remaks;
        $status = $request->status;

        $crm = $this->crmService->create($request->only(['name', 'telp', 'kodenegara', 'email', 'source', 'tgl_lahir', 'jenis_kelamin', 'remaks', 'status']));
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
            $tgl_lahir = $request->tgl_lahir;
            $jenis_kelamin = $request->jenis_kelamin;
    
            $crm = $this->crmService->update($odata, $request->only(['name', 'telp', 'kodenegara', 'email', 'source', 'tgl_lahir', 'jenis_kelamin', 'remaks', 'status']));
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

    public function index_source(Request $request)
    {
        $search = $request->search;
        $pagging = $request->pagging ?? 10;
        $data = $this->crmService->list_source($search, $pagging);
        
        $response = [
            'data' => $data
        ];
        return response()->json($response, 200);
    }       

    public function store_source(Request $request)
    {
        $source = $request->source;
        $status = $request->status;
        $data = $this->crmService->store_source($source, $status);
        return response()->json(['message' => 'CRM source created successfully'], 201);
    }

    public function update_source(Request $request)
    {
        $odata = $request->odata;
        $source = $request->source;
        $status = $request->status;
        $data = $this->crmService->update_source($odata, $source, $status);
        return response()->json(['message' => 'CRM source updated successfully'], 200);
    }

    public function delete_source(Request $request)
    {
        $odata = $request->odata;
        $data = $this->crmService->delete_source($odata);
        return response()->json(['message' => 'CRM source deleted successfully'], 200);
    }

    public function index_parameter(Request $request)
    {

        $data = $this->crmService->list_parameter();
        
        $response = [
            'data' => $data
        ];
        return response()->json($response, 200);
    }

    public function update_parameter(Request $request)
    {
        $rate_komisi = $request->rate_komisi;
        $bonus_repeat = $request->bonus_repeat;
        $point = $request->point;
        $unit_aktif = $request->unit_aktif;
        $target_occupancy = $request->target_occupancy;
        $data = $this->crmService->update_parameter($rate_komisi, $bonus_repeat, $point, $unit_aktif, $target_occupancy);
        return response()->json(['message' => 'CRM parameter updated successfully'], 200);
    }


}

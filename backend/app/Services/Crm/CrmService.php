<?php
namespace App\Services\Crm;
use Carbon\Carbon;
use App\Models\Crm;
use App\Models\CrmSource;
use App\Models\CrmRemarks;
use App\Models\CrmHistory;
use App\Models\Parameter;
use App\Models\User;
use Illuminate\Support\Str;

class CrmService
{
    public function list($search = null, $pagging = 10, $status = null, $start_date = null, $end_date = null)
    {
        $start_date = $start_date ?? Carbon::now()->startOfDay();
        $end_date = $end_date ?? Carbon::now()->endOfDay();

        $crm = Crm::with('history')
        ->when($search, function ($query) use ($search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('notelp', 'like', "%$search%")
                ->orWhere('source', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
        })->filter([
            'status' => $status,
            'start_date' => $start_date,
            'end_date' => $end_date
        ])
        ->paginate($pagging);

        $total = Crm::filter(['start_date' => $start_date, 'end_date' => $end_date])->count();
        $needFollowUp = Crm::filter(['status' => 'NEEDFU', 'start_date' => $start_date, 'end_date' => $end_date])->count();
        $processFollowUp = Crm::filter(['status' => 'FOLLOWUP', 'start_date' => $start_date, 'end_date' => $end_date])->count();
        $closingLeads = Crm::filter(['status' => 'CLOSING', 'start_date' => $start_date, 'end_date' => $end_date])->count();
        $lostLeads = Crm::filter(['status' => 'LOST', 'start_date' => $start_date, 'end_date' => $end_date])->count();

        return [
            'data' => $crm,
            'total_leads' => $total,
            'need_followup' => $needFollowUp,
            'process_followup' => $processFollowUp,
            'closing_leads' => $closingLeads,
            'lost_leads' => $lostLeads
        ];
    }

    public function create(array $data)
    {
        activity()
            ->performedOn(new Crm())
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => $data])
            ->event('create')
            ->log('created crm');

        return Crm::create([
            'odata' => (string) Str::uuid(),
            'tanggal_leads' => now()->format('Y-m-d'),
            'nama' => $data['name'],
            'notelp' => $data['kodenegara'] . $data['telp'],
            'email' => $data['email'],
            'source' => $data['source'],
            'tgl_lahir' => $data['tgl_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'remaks' => $data['remaks'] ?? null,
            'assigned_id' => auth()->id(),
            'assigned_odata' => auth()->user()->odata
        ]);
    }

    public function update($odata, array $data)
    {
        $crm = Crm::where('odata', $odata)->firstOrFail();
        $crm->update([
            'nama' => $data['name'],
            'notelp' => $data['kodenegara'] . $data['telp'],
            'email' => $data['email'],
            'source' => $data['source'],
            'tgl_lahir' => $data['tgl_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'remaks' => $data['remaks'] ?? null,
        ]);

        activity()
            ->performedOn($crm)
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => $data])
            ->event('update')
            ->log('updated crm');
        return $crm;
    }

    public function process($odata, $status)
    {
        $crm = Crm::where('odata', $odata)->firstOrFail();
        $crm->update([
            'status' => $status
        ]);

        CrmHistory::create([
            'odata' => (string) Str::uuid(),
            'leads_id' => $crm->id,
            'leads_odata' => $crm->odata,
            'remarks' => 'Follow up by ' . auth()->user()->name,
            'assigned_id' => auth()->id(),
            'assigned_odata' => auth()->user()->odata
        ]);

        activity()
            ->performedOn($crm)
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => ['status' => $status]])
            ->event('process')
            ->log('processed crm');
        return $crm;
    } 


    public function process_followup($odata, $status, $remaks, $ket_remarks)
    {
        $crm = Crm::where('odata', $odata)->firstOrFail();
        $crm->update([
            'status' => $status,
            'remaks' => $remaks,
            'ket_remaks' => $ket_remarks
        ]);

        CrmHistory::create([
            'odata' => (string) Str::uuid(),
            'leads_id' => $crm->id,
            'leads_odata' => $crm->odata,
            'remarks' => $remaks . ' - ' . $ket_remarks . ' by ' . auth()->user()->name,
            'assigned_id' => auth()->id(),
            'assigned_odata' => auth()->user()->odata
        ]);

        if ($status === 'CLOSING') {

            $cek=User::where('email', $crm->email)->first();
            if ($cek) {
                User::where('id', $cek->id)->update([
                    'name' => $crm->nama,
                    'email' => $crm->email,
                    'phone' => $crm->notelp,
                    'birth_date' => $crm->tgl_lahir,
                ]);
            }else {
                $user = User::create([
                    'odata' => (string) Str::uuid(),
                    'name' => $crm->nama,
                    'email' => $crm->email,
                    'password' => bcrypt('password'),
                    'phone' => $crm->notelp,
                    'birth_date' => $crm->tgl_lahir,
                    'gender' => $crm->jenis_kelamin,
                    'status_users' => 0,
                    'change_password' => 1
                ]);

                User::where('id', $user->id)->update([
                    'referral_code' =>  strtoupper(substr(Str::slug($user->name, ''), 0, 4)). str_pad($user->id, 4, '0', STR_PAD_LEFT) . strtoupper(Str::random(2))
                ]);

                // assign role
                $user->assignRole('users');
            }
        }
    

        activity()
            ->performedOn($crm)
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => ['status' => $status, 'remaks' => $remaks, 'ket_remarks' => $ket_remarks]])
            ->event('process_followup')
            ->log('processed followup crm');

        return $crm;
    }

    public function get_source()
    {
        return CrmSource::select('source')->where('status', 0)->get();
    }

    public function get_remark()
    {
        return CrmRemarks::select('remark')->where('status', 0)->get();
    }

    public function get_history($leads_odata)
    {
        $crm = Crm::where('odata', $leads_odata)->firstOrFail();
        return $crm->history()->orderBy('created_at', 'desc')->get();
    }

    public function list_source($search = null, $pagging = 10)
    {
        return CrmSource::when($search, function ($query) use ($search) {
            $query->where('source', 'like', "%$search%");
        })->orderBy('created_at', 'desc')->paginate($pagging);
    }

    public function store_source($source, $status)
    {
        return CrmSource::create([
            'odata' => (string) Str::uuid(),
            'source' => $source,
            'status' => $status
        ]);
    }

    public function update_source($odata, $source, $status)
    {
        $crmSource = CrmSource::where('odata', $odata)->firstOrFail();
        $crmSource->update([
            'source' => $source,
            'status' => $status
        ]);
        return $crmSource;
    }

    public function delete_source($odata)
    {
        $crmSource = CrmSource::where('odata', $odata)->firstOrFail();
        $crmSource->delete();
        return $crmSource;
    }

    public function list_parameter()
    {
        return Parameter::all();
    }

    public function update_parameter($rate_komisi, $bonus_repeat, $point, $unit_aktif, $target_occupancy)
    {
        $parameter = Parameter::where('id', 1)->firstOrFail();
        $parameter->update([
            'rate_komisi' => $rate_komisi,
            'bonus_repeat' => $bonus_repeat,
            'point' => $point,
            'unit_aktif' => $unit_aktif,
            'target_occupancy' => $target_occupancy
        ]);
        return $parameter;
    }

}

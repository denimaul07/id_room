<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Jobs\SendWACampaignContact;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Support\Facades\Cache;

class CampaignService
{
    public function list($search = null, $paginate = 10, $type = 'all')
    {
        $query = Campaign::with('contacts')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('title', 'like', "%$search%");
        }

        if ($type) {
            $query->where('type_coupon', $type);
        }

        return $query->paginate($paginate);
    }

    public function create(array $data)
    {
        
        if (isset($data['images'])) {
            $imagePath = $data['images']->store('campaigns', 'public');
        }

        $campaign = Campaign::create([
            'odata' => (string) Str::uuid(),
            'name' => $data['name'],
            'template_name' => $data['template_name'],
            'scheduled_at' => $data['schedule_time'],
            'images' => $imagePath ?? null,
            'status' => $data['status']
        ]);

        activity()
            ->performedOn($campaign)
            ->causedBy(Auth::user())
            ->event('create')
            ->log('created campaign');

        return $campaign;
    }

    public function update($odata, array $data)
    {
        $campaign = Campaign::where('odata', $odata)->first();
        if (!$campaign) {
            throw new HttpResponseException(response()->json(['error' => 'Campaign not found'], 404));
        }

        if (isset($data['images'])) {
            // Only store if it's an UploadedFile instance
            if ($data['images'] instanceof UploadedFile) {
                $imagePath = $data['images']->store('campaigns', 'public');
                $data['images'] = $imagePath;
            }
            // else, assume it's already a string path and do nothing
        }

        $campaign->update([
            'name' => $data['name'],
            'template_name' => $data['template_name'],
            'scheduled_at' => $data['schedule_time'],
            'images' => $data['images'] ?? null,
            'status' => $data['status']
        ]);

        activity()
            ->performedOn($campaign)
            ->causedBy(Auth::user())
            ->event('update')
            ->log('updated campaign');
        return $campaign;
    }

    public function delete($odata)
    {
    
        $campaign = Campaign::where('odata', $odata)->first();
        if (!$campaign) {
            throw new HttpResponseException(response()->json(['error' => 'Campaign not found'], 404));
        }

         // Hapus semua kontak terkait sebelum menghapus campaign
        CampaignContact::where('campaign_odata', $odata)->delete();

        $contacts = CampaignContact::where('campaign_odata', $odata)->delete();
        $campaign->delete();
        activity()
            ->performedOn($campaign)
            ->causedBy(Auth::user())
            ->event('delete')
            ->log('deleted campaign');
        return true;
    }

    public function exportTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('Contact List Template');

        $worksheet->setCellValue('A1', 'Name');
        $worksheet->setCellValue('B1', 'Phone');

        return $this->downloadSpreadsheet($spreadsheet, 'Contact_List_Template.xlsx');
    }

    private function downloadSpreadsheet(Spreadsheet $spreadsheet, string $filename)
    {
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function uploadContacts($campaignOdata, $contacts)
    {
        $campaign = Campaign::where('odata', $campaignOdata)->first();
        if (!$campaign) {
            throw new HttpResponseException(response()->json(['error' => 'Campaign not found'], 404));
        }

        // Ambil semua nomor yang sudah ada di campaign
        $existingPhones = CampaignContact::where('campaign_id', $campaign->id)
            ->pluck('phone')
            ->toArray();

        foreach ($contacts as $contact) {
            if (in_array($contact['phone'], $existingPhones)) {
                // Skip jika nomor sudah ada
                continue;
            }
            CampaignContact::create([
                'odata' => (string) Str::uuid(),
                'campaign_id' => $campaign->id,
                'campaign_odata' => $campaign->odata,
                'name' => $contact['name'],
                'phone' => $contact['phone']
            ]);
        }

        activity()
            ->performedOn($campaign)
            ->causedBy(Auth::user())
            ->event('upload_contacts')
            ->log('uploaded contacts to campaign');
    }

    public function listMembers($search = null, $paginate = 10)
    {
        $contacts = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'users');
        });

        if ($search) {
            $contacts->where('name', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
        }

        return $contacts->select('odata', 'name', 'phone', 'status_users')->paginate($paginate);
    }

    public function addMember($campaignOdata, $memberId)
    {
        $campaign = Campaign::where('odata', $campaignOdata)->first();
        if (!$campaign) {
            throw new HttpResponseException(response()->json(['error' => 'Campaign not found'], 404));
        }

        $user = User::where('odata', $memberId)->first();
        if (!$user) {
            throw new HttpResponseException(response()->json(['error' => 'User not found'], 404));
        }

        // Cek jika nomor sudah ada di campaign
        $exists = CampaignContact::where('campaign_id', $campaign->id)
            ->where('phone', $user->phone)
            ->exists();
        if ($exists) {
            // Skip simpan jika sudah ada
            return $campaign;
        }

        CampaignContact::create([
            'odata' => (string) Str::uuid(),
            'campaign_id' => $campaign->id,
            'campaign_odata' => $campaign->odata,
            'name' => $user->name,
            'phone' => $user->phone
        ]);

        activity()
            ->performedOn($campaign)
            ->causedBy(Auth::user())
            ->event('add_member')
            ->log('added member to campaign');

        return $campaign;
    }

    public function getContacts($campaignOdata, $search = null)
    {
        $campaign = CampaignContact::where('campaign_odata', $campaignOdata);

        if ($search) {
            $campaign->where('name', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
        }

        return $campaign->get();
    }

    public function addMembersBulk($campaignOdata, $memberIds)
    {
        $campaign = Campaign::where('odata', $campaignOdata)->first();
        if (!$campaign) {
            throw new HttpResponseException(response()->json(['error' => 'Campaign not found'], 404));
        }

        $users = User::whereIn('odata', $memberIds)->get();

        // Ambil semua nomor yang sudah ada di campaign
        $existingPhones = CampaignContact::where('campaign_id', $campaign->id)
            ->pluck('phone')
            ->toArray();

        foreach ($users as $user) {
            if (in_array($user->phone, $existingPhones)) {
                // Skip jika nomor sudah ada
                continue;
            }
            CampaignContact::create([
                'odata' => (string) Str::uuid(),
                'campaign_id' => $campaign->id,
                'campaign_odata' => $campaign->odata,
                'name' => $user->name,
                'phone' => $user->phone
            ]);
        }

        activity()
            ->performedOn($campaign)
            ->causedBy(Auth::user())
            ->event('add_members_bulk')
            ->log('added multiple members to campaign');

        return $campaign;
    }

    public function deleteContact($contactOdata)
    {
        $contact = CampaignContact::where('odata', $contactOdata)->first();
        if (!$contact) {
            throw new HttpResponseException(response()->json(['error' => 'Contact not found'], 404));
        }

        $campaign = Campaign::where('id', $contact->campaign_id)->first();

        $contact->delete();

        activity()
            ->performedOn($campaign)
            ->causedBy(Auth::user())
            ->event('delete_contact')
            ->log('deleted contact from campaign');

        return $campaign;
    }

    public function sendWA($campaignOdata, array $extraParams = [])
    {
        $campaign = Campaign::with('contacts')
            ->where('odata', $campaignOdata)
            ->firstOrFail();

        // Hanya kontak yang belum sent
        $pending = $campaign->contacts->whereNotIn('status', ['sent']);

        if ($pending->isEmpty()) {
            return response()->json(['message' => 'Semua kontak sudah terkirim.']);
        }

        // ── Resolve template detail dari Meta (sekali) ────────────────────────
        $wa        = new WhatsAppService();
        $tplDetail = $wa->getTemplateDetail($campaign->template_name);

        $language           = $tplDetail['language'] ?? 'id';
        $templateComponents = $tplDetail['components'] ?? [];

        // ── Upload media sekali (jika template punya HEADER media) ───────────
        $uploadedMediaId = null;
        $hasMediaHeader  = collect($templateComponents)->contains(function ($c) {
            return strtoupper($c['type'] ?? '') === 'HEADER'
                && in_array(strtoupper($c['format'] ?? ''), ['IMAGE', 'VIDEO', 'DOCUMENT']);
        });

        if ($hasMediaHeader && $campaign->images) {
            $uploadedMediaId = $wa->uploadMedia($campaign->images);
        }

        // ── Buat batch key & inisialisasi cache progress ──────────────────────
        $batchKey     = (string) Str::uuid();
        $progressData = [];

        foreach ($pending as $contact) {
            $progressData[$contact->odata] = [
                'name'          => $contact->name,
                'phone'         => $contact->phone,
                'status'        => 'queued',
                'error_message' => null,
                'updated_at'    => now()->toIso8601String(),
            ];
            $contact->update(['status' => 'queued']);
        }

        Cache::put("campaign_progress:{$batchKey}", $progressData, now()->addHours(2));

        // ── Dispatch jobs dengan delay antar contact ──────────────────────────
        $delaySeconds = 0;
        foreach ($pending as $contact) {
            SendWACampaignContact::dispatch(
                $contact->odata,
                $campaign->odata,
                $campaign->template_name,
                $language,
                $templateComponents,
                $uploadedMediaId,
                $extraParams,   // <-- dari parameter, bukan $request
                $batchKey,
            )->delay(now()->addSeconds($delaySeconds));

            $delaySeconds += 2;
        }

        $campaign->update(['status' => 'completed']);

        return response()->json([
            'message'   => "Berhasil mengantri {$pending->count()} pesan WA.",
            'batch_key' => $batchKey,
            'total'     => $pending->count(),
        ]);
    }

    public function sendWAProgress($batchKey)
    {
        if (!$batchKey) {
            return response()->json(['error' => 'batch_key required'], 422);
        }

        $data = Cache::get("campaign_progress:{$batchKey}", []);

        $total   = count($data);
        $sent    = collect($data)->where('status', 'sent')->count();
        $failed  = collect($data)->where('status', 'failed')->count();
        $sending = collect($data)->where('status', 'sending')->count();
        $queued  = collect($data)->where('status', 'queued')->count();
        $done    = $sent + $failed;

        return response()->json([
            'contacts'  => $data,
            'summary'   => compact('total', 'sent', 'failed', 'sending', 'queued', 'done'),
            'completed' => ($total > 0 && $done === $total),
        ]);
    }
}

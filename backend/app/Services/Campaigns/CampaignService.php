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

        foreach ($contacts as $contact) {
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

        return $contacts->select('name', 'phone', 'status_users')->paginate($paginate);
    }
}

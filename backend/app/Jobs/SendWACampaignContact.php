<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendWACampaignContact implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 10;

    public function __construct(
        protected string  $contactOdata,
        protected string  $campaignOdata,
        protected string  $templateName,
        protected string  $language,
        protected array   $templateComponents,  // di-resolve sekali sebelum dispatch
        protected ?string $uploadedMediaId,     // di-upload sekali sebelum dispatch
        protected array   $extraParams  = [],
        protected string  $jobBatchKey  = '',
    ) {}

    public function handle(): void
    {
        $contact = CampaignContact::where('odata', $this->contactOdata)->first();
        if (!$contact) return;

        $contact->update(['status' => 'sending', 'error_message' => null]);
        $this->updateProgress($contact->odata, 'sending', null);

        try {
            $wa = new WhatsAppService();

            // params: {{1}}=name, {{2}}=phone, {{3+}}=extraParams
            $params = array_merge(
                [$contact->name, $contact->phone],
                $this->extraParams
            );

            $wa->sendTemplate(
                toPhone:            $contact->phone,
                templateName:       $this->templateName,
                params:             $params,
                templateComponents: $this->templateComponents,
                uploadedMediaId:    $this->uploadedMediaId,
                language:           $this->language,
            );

            $contact->update(['status' => 'sent', 'error_message' => null]);
            $this->updateProgress($contact->odata, 'sent', null);

        } catch (\Throwable $e) {
            Log::error('SendWACampaignContact failed', [
                'contact' => $this->contactOdata,
                'error'   => $e->getMessage(),
            ]);

            $contact->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            $this->updateProgress($contact->odata, 'failed', $e->getMessage());
        }

        $this->checkCampaignCompletion();
    }

    public function failed(\Throwable $e): void
    {
        $contact = CampaignContact::where('odata', $this->contactOdata)->first();
        if ($contact) {
            $contact->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            $this->updateProgress($contact->odata, 'failed', $e->getMessage());
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function updateProgress(string $contactOdata, string $status, ?string $error): void
    {
        if (!$this->jobBatchKey) return;

        $key  = "campaign_progress:{$this->jobBatchKey}";
        $data = Cache::get($key, []);

        if (isset($data[$contactOdata])) {
            $data[$contactOdata]['status']        = $status;
            $data[$contactOdata]['error_message'] = $error;
            $data[$contactOdata]['updated_at']    = now()->toIso8601String();
        }

        Cache::put($key, $data, now()->addHours(2));
    }

    private function checkCampaignCompletion(): void
    {
        $total = CampaignContact::where('campaign_odata', $this->campaignOdata)->count();
        $done  = CampaignContact::where('campaign_odata', $this->campaignOdata)
                    ->whereIn('status', ['sent', 'failed'])
                    ->count();

        if ($total > 0 && $total === $done) {
            Campaign::where('odata', $this->campaignOdata)->update(['status' => 'completed']);
        }
    }
}

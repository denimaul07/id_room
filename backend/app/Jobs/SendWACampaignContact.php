<?php

namespace App\Jobs;

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

    public function __construct(
        protected string  $contactOdata,
        protected string  $campaignOdata,
        protected string  $templateName,
        protected string  $language,
        protected array   $templateComponents,
        protected ?string $uploadedMediaId,
        protected array   $extraParams  = [],
        protected string  $jobBatchKey  = '',
        protected string  $mediaLink    = '',
        protected ?string $couponCode   = null,  // ✅
    ) {}

    public function handle(): void
    {
        $contact = CampaignContact::where('odata', $this->contactOdata)->first();

        if (!$contact) {
            $this->updateProgress($this->contactOdata, 'failed', 'Contact not found');
            return;
        }

        $contact->update(['status' => 'sending']);
        $this->updateProgress($this->contactOdata, 'sending');

        try {
            $wa = new WhatsAppService();

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
                mediaLink:          $this->mediaLink,
                couponCode:         $this->couponCode,  // ✅
            );

            $contact->update(['status' => 'sent', 'error_message' => null]);
            $this->updateProgress($this->contactOdata, 'sent');

        } catch (\Throwable $e) {
            Log::error('SendWACampaignContact failed', [
                'contact' => $this->contactOdata,
                'error'   => $e->getMessage(),
            ]);

            $contact->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            $this->updateProgress($this->contactOdata, 'failed', $e->getMessage());
        }
    }

    private function updateProgress(string $odata, string $status, ?string $errorMessage = null): void
    {
        $data = Cache::get("campaign_progress:{$this->jobBatchKey}", []);

        if (isset($data[$odata])) {
            $data[$odata]['status']        = $status;
            $data[$odata]['error_message'] = $errorMessage;
            $data[$odata]['updated_at']    = now()->toIso8601String();
        }

        Cache::put("campaign_progress:{$this->jobBatchKey}", $data, now()->addHours(2));
    }
}
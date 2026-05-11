<?php

namespace App\Services\WhatsApp;

use App\Models\WaSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected WaSetting $settings;

    public function __construct()
    {
        $settings = WaSetting::first();

        if (!$settings || !$settings->phone_number_id || !$settings->access_token) {
            throw new \RuntimeException('WA settings not configured. Please set Phone Number ID and Access Token.');
        }

        $this->settings = $settings;
    }

    // ─── Fetch template components + language from Meta ──────────────────────

    public function getTemplateDetail(string $templateName): array
    {
        try {
            $res = Http::get(
                "https://graph.facebook.com/v19.0/{$this->settings->waba_id}/message_templates",
                [
                    'name'         => $templateName,
                    'access_token' => $this->settings->access_token,
                ]
            );

            $data = $res->json()['data'] ?? [];
            if (!empty($data[0])) {
                return $data[0]; // ['language' => ..., 'components' => [...]]
            }
        } catch (\Throwable $e) {
            Log::warning('WhatsAppService: getTemplateDetail failed', ['error' => $e->getMessage()]);
        }

        return [];
    }

    // ─── Upload media ke Meta sekali, return media_id ─────────────────────────

    public function uploadMedia(string $localStoragePath): ?string
    {
        $localPath = storage_path('app/public/' . $localStoragePath);

        if (!file_exists($localPath)) {
            Log::warning('WhatsAppService: media file not found', ['path' => $localPath]);
            return null;
        }

        $mimeType  = mime_content_type($localPath) ?: 'image/jpeg';

        $res = Http::withToken($this->settings->access_token)
            ->attach('file', file_get_contents($localPath), basename($localPath), ['Content-Type' => $mimeType])
            ->post("https://graph.facebook.com/v19.0/{$this->settings->phone_number_id}/media", [
                'messaging_product' => 'whatsapp',
                'type'              => $mimeType,
            ]);

        if ($res->successful()) {
            return $res->json()['id'] ?? null;
        }

        Log::warning('WhatsAppService: uploadMedia failed', ['response' => $res->json()]);
        return null;
    }

    // ─── Kirim satu pesan template ke satu nomor ─────────────────────────────
    // $params : ['name_value', 'phone_value', 'extra1', 'extra2', ...]
    // $templateComponents : array dari Meta API (sudah di-fetch sebelumnya)
    // $uploadedMediaId    : sudah di-upload sekali sebelum loop contacts

    public function sendTemplate(
        string  $toPhone,
        string  $templateName,
        array   $params             = [],
        array   $templateComponents = [],
        ?string $uploadedMediaId    = null,
        string  $language           = 'id'
    ): array {
        $countVars = function (string $text): int {
            preg_match_all('/\{\{\d+\}\}/', $text, $m);
            return count($m[0]);
        };

        $msgComponents = [];

        foreach ($templateComponents as $comp) {
            $type = strtoupper($comp['type'] ?? '');

            if ($type === 'HEADER') {
                $format = strtoupper($comp['format'] ?? '');

                if ($format === 'TEXT' && !empty($comp['text'])) {
                    $varCount = $countVars($comp['text']);
                    if ($varCount > 0) {
                        $msgComponents[] = [
                            'type'       => 'header',
                            'parameters' => $this->buildTextParams($varCount, $params),
                        ];
                    }
                } elseif (in_array($format, ['IMAGE', 'VIDEO', 'DOCUMENT']) && $uploadedMediaId) {
                    $mediaType       = strtolower($format);
                    $msgComponents[] = [
                        'type'       => 'header',
                        'parameters' => [[
                            'type'   => $mediaType,
                            $mediaType => [
                                'id' => (string) $uploadedMediaId,
                            ],
                        ]],
                    ];
                }
    
            } elseif ($type === 'BODY') {
                $varCount = $countVars($comp['text'] ?? '');
                if ($varCount > 0) {
                    $msgComponents[] = [
                        'type'       => 'body',
                        'parameters' => $this->buildTextParams($varCount, $params),
                    ];
                }
            }
            // FOOTER & BUTTONS (QUICK_REPLY) tidak butuh parameters
        }

        $template = [
            'name'     => $templateName,
            'language' => ['code' => $language],
        ];
        if (!empty($msgComponents)) {
            $template['components'] = $msgComponents;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'                => $toPhone,
            'type'              => 'template',
            'template'          => $template,
        ];

        $response = Http::withToken($this->settings->access_token)
            ->post("https://graph.facebook.com/v19.0/{$this->settings->phone_number_id}/messages", $payload);

        if (!$response->successful()) {
            $body = $response->json();
            $msg  = $body['error']['message'] ?? json_encode($body);
            throw new \RuntimeException($msg);
        }

        return $response->json();
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Build text parameter array untuk Meta template.
     * $params index: 0 = {{1}}, 1 = {{2}}, dst
     */
    private function buildTextParams(int $varCount, array $params): array
    {
        $result = [];
        for ($i = 0; $i < $varCount; $i++) {
            $result[] = [
                'type' => 'text',
                'text' => $params[$i] ?? '',
            ];
        }
        return $result;
    }
}

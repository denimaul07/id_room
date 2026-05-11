<?php

namespace App\Http\Controllers;

use App\Models\LogsError;
use App\Models\WaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WhatsappController extends Controller
{
    // ─── SETTINGS ────────────────────────────────────────────────────────────

    public function getSettings()
    {
        $settings = WaSetting::first();
        return response()->json(['data' => $settings]);
    }

    public function updateSettings(Request $request)
    {
        $data = [
            'phone_number_id' => $request->phone_number_id,
            'waba_id'         => $request->waba_id,
            'access_token'    => $request->access_token,
        ];

        if ($request->hasFile('media_image')) {
            $path = $request->file('media_image')->store('wa_media', 'public');
            $data['media_url'] = config('app.url') . '/storage/' . $path;
        }

        if ($request->hasFile('media_video')) {
            $path = $request->file('media_video')->store('wa_media', 'public');
            $data['media_video_url'] = config('app.url') . '/storage/' . $path;
        }

        if ($request->hasFile('media_document')) {
            $path = $request->file('media_document')->store('wa_media', 'public');
            $data['media_document_url'] = config('app.url') . '/storage/' . $path;
        }

        $settings = WaSetting::first();
        if (!$settings) {
            $settings = WaSetting::create($data);
        } else {
            $settings->update($data);
        }

        return response()->json(['message' => 'WA settings updated successfully', 'data' => $settings]);
    }

    // ─── TEMPLATES ───────────────────────────────────────────────────────────

    public function getTemplates(Request $request)
    {
        $settings = WaSetting::first();
        if (!$settings || !$settings->waba_id || !$settings->access_token) {
            return response()->json(['error' => 'WA settings not configured'], 422);
        }

        $params = [
            'access_token' => $settings->access_token,
            'limit'        => 100,
        ];
        if ($request->status) {
            $params['status'] = $request->status;
        }

        $response = Http::get(
            "https://graph.facebook.com/v19.0/{$settings->waba_id}/message_templates",
            $params
        );

        return response()->json($response->json(), $response->status());
    }

    public function createTemplate(Request $request)
    {
        $settings = WaSetting::first();
        if (!$settings || !$settings->waba_id || !$settings->access_token) {
            return response()->json(['message' => 'WA settings not configured'], 422);
        }

        $payload = [
            'name'       => $request->name,
            'category'   => $request->category,
            'language'   => $request->language,
            'components' => $request->components,
        ];

        $response = Http::withToken($settings->access_token)
            ->post(
                "https://graph.facebook.com/v19.0/{$settings->waba_id}/message_templates",
                $payload
            );

        $body = $response->json();

        if (!$response->successful()) {
            $metaError    = $body['error'] ?? [];

            LogsError::create([
                'odata'       => Str::uuid(),
                'message'     => '[WhatsApp] createTemplate failed: ' . json_encode($body),
                'url'         => request()->fullUrl(),
                'method'      => 'POST',
                'status_code' => $response->status(),
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->header('User-Agent'),
                'user'        => auth()->check() ? auth()->user()->name : 'System',
            ]);

            $userMsg      = $metaError['error_user_msg']   ?? $metaError['message']      ?? 'Gagal membuat template.';
            $userTitle    = $metaError['error_user_title'] ?? $metaError['type']         ?? 'Meta API Error';
            $errorSubcode = $metaError['error_subcode']    ?? $metaError['code']         ?? null;

            return response()->json([
                'message'   => $userMsg . ($errorSubcode ? " (code: {$errorSubcode})" : ''),
                'title'     => $userTitle,
                'meta'      => $metaError,
            ], $response->status());
        }

        return response()->json($body, $response->status());
    }

    public function deleteTemplate(Request $request)
    {
        $settings = WaSetting::first();
        if (!$settings || !$settings->waba_id || !$settings->access_token) {
            return response()->json(['message' => 'WA settings not configured'], 422);
        }

        $response = Http::withToken($settings->access_token)
            ->delete("https://graph.facebook.com/v19.0/{$settings->waba_id}/message_templates", [
                'name' => $request->name,
            ]);

        $body = $response->json();

        if (!$response->successful()) {
            $metaError = $body['error'] ?? [];

            LogsError::create([
                'odata'       => Str::uuid(),
                'message'     => '[WhatsApp] deleteTemplate failed: ' . json_encode($body),
                'url'         => request()->fullUrl(),
                'method'      => 'DELETE',
                'status_code' => $response->status(),
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->header('User-Agent'),
                'user'        => auth()->check() ? auth()->user()->name : 'System',
            ]);

            $userMsg   = $metaError['error_user_msg']   ?? $metaError['message']  ?? 'Gagal menghapus template.';
            $userTitle = $metaError['error_user_title'] ?? $metaError['type']     ?? 'Meta API Error';
            $errorSubcode = $metaError['error_subcode'] ?? $metaError['code']     ?? null;

            return response()->json([
                'message' => $userMsg . ($errorSubcode ? " (code: {$errorSubcode})" : ''),
                'title'   => $userTitle,
                'meta'    => $metaError,
            ], $response->status());
        }

        return response()->json($body, $response->status());
    }

    // ─── SEND CAMPAIGN ───────────────────────────────────────────────────────

    public function sendCampaign(Request $request)
    {
        $settings = WaSetting::first();
        if (!$settings || !$settings->phone_number_id || !$settings->access_token) {
            return response()->json(['error' => 'WA settings not configured. Please set Phone Number ID and Access Token.'], 422);
        }

        $campaign = \App\Models\Campaign::with('contacts')->where('odata', $request->odata)->first();
        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        if (!$campaign->template_name) {
            return response()->json(['error' => 'Campaign does not have a template set.'], 422);
        }

        // Try to resolve the template language + components from Meta API
        $language         = $request->language ?? 'id';
        $templateComponents = [];
        try {
            $tplRes = Http::get(
                "https://graph.facebook.com/v19.0/{$settings->waba_id}/message_templates",
                ['name' => $campaign->template_name, 'access_token' => $settings->access_token]
            );
            $tplData = $tplRes->json()['data'] ?? [];
            if (!empty($tplData[0])) {
                $tpl = $tplData[0];
                if (!empty($tpl['language'])) {
                    $language = $tpl['language'];
                }
                $templateComponents = $tpl['components'] ?? [];
            }
        } catch (\Exception $e) {
            // fallback
        }

        // Helper: count {{n}} variables in a text
        $countVars = function (string $text): int {
            preg_match_all('/\{\{\d+\}\}/', $text, $m);
            return count($m[0]);
        };

        // Extra params from frontend for {{3}}, {{4}}, ...
        $extraParams = $request->extra_params ?? [];

        // Upload media to Meta once (before contacts loop) — avoids per-contact uploads
        // and bypasses the localhost-URL issue (Meta cannot reach local server URLs)
        $uploadedMediaId = null;
        $hasMediaHeader  = collect($templateComponents)->contains(function ($c) {
            return strtoupper($c['type'] ?? '') === 'HEADER'
                && in_array(strtoupper($c['format'] ?? ''), ['IMAGE', 'VIDEO', 'DOCUMENT']);
        });

        if ($hasMediaHeader && $campaign->images) {
            $localPath = storage_path('app/public/' . $campaign->images);
            if (file_exists($localPath)) {
                $mimeType  = mime_content_type($localPath) ?: 'image/jpeg';
                $uploadRes = Http::withToken($settings->access_token)
                    ->attach('file', file_get_contents($localPath), basename($localPath), ['Content-Type' => $mimeType])
                    ->post("https://graph.facebook.com/v19.0/{$settings->phone_number_id}/media", [
                        'messaging_product' => 'whatsapp',
                        'type'              => $mimeType,
                    ]);
                if ($uploadRes->successful()) {
                    $uploadedMediaId = $uploadRes->json()['id'] ?? null;
                }
            }
        }

        $sent    = 0;
        $failed  = 0;
        $skipped = 0;

        foreach ($campaign->contacts as $contact) {
            if ($contact->status === 'sent') {
                $skipped++;
                continue;
            }

            try {
                // Build components array with parameter values
                $msgComponents = [];

                foreach ($templateComponents as $comp) {
                    $type = strtoupper($comp['type'] ?? '');

                    if ($type === 'HEADER') {
                        $format = strtoupper($comp['format'] ?? '');
                        if ($format === 'TEXT' && !empty($comp['text'])) {
                            $varCount = $countVars($comp['text']);
                            if ($varCount > 0) {
                                $params = [];
                                for ($i = 1; $i <= $varCount; $i++) {
                                    if ($i === 1)      $val = $contact->name ?? '';
                                    elseif ($i === 2)  $val = $contact->phone ?? '';
                                    else               $val = $extraParams[$i - 3] ?? '';
                                    $params[] = ['type' => 'text', 'text' => $val];
                                }
                                $msgComponents[] = ['type' => 'header', 'parameters' => $params];
                            }
                        } elseif (in_array($format, ['IMAGE', 'VIDEO', 'DOCUMENT'])) {
                            if ($uploadedMediaId) {
                                // Use pre-uploaded media ID (avoids Meta trying to fetch localhost URL)
                                $mediaType  = strtolower($format);
                                $mediaParam = ['type' => $mediaType, $mediaType => ['id' => $uploadedMediaId]];
                                $msgComponents[] = ['type' => 'header', 'parameters' => [$mediaParam]];
                            }
                        }
                    } elseif ($type === 'BODY') {
                        $bodyText = $comp['text'] ?? '';
                        $varCount = $countVars($bodyText);
                        if ($varCount > 0) {
                            $params = [];
                            for ($i = 1; $i <= $varCount; $i++) {
                                if ($i === 1)      $val = $contact->name ?? '';
                                elseif ($i === 2)  $val = $contact->phone ?? '';
                                else               $val = $extraParams[$i - 3] ?? '';
                                $params[] = ['type' => 'text', 'text' => $val];
                            }
                            $msgComponents[] = ['type' => 'body', 'parameters' => $params];
                        }
                    }
                    // FOOTER & BUTTONS with QUICK_REPLY don't need parameters
                }

                $template = [
                    'name'     => $campaign->template_name,
                    'language' => ['code' => $language],
                ];
                if (!empty($msgComponents)) {
                    $template['components'] = $msgComponents;
                }

                $payload = [
                    'messaging_product' => 'whatsapp',
                    'to'                => $contact->phone,
                    'type'              => 'template',
                    'template'          => $template,
                ];

                $response = Http::withToken($settings->access_token)
                    ->post("https://graph.facebook.com/v19.0/{$settings->phone_number_id}/messages", $payload);

                if ($response->successful()) {
                    $contact->status        = 'sent';
                    $contact->error_message = null;
                    $contact->save();
                    $sent++;
                } else {
                    $errorBody = $response->json();
                    $errorMsg  = $errorBody['error']['message'] ?? json_encode($errorBody);
                    $contact->status        = 'failed';
                    $contact->error_message = $errorMsg;
                    $contact->save();
                    $failed++;
                }
            } catch (\Exception $e) {
                $contact->status        = 'failed';
                $contact->error_message = $e->getMessage();
                $contact->save();
                $failed++;
            }
        }

        $campaign->status = 'sent';
        $campaign->save();

        return response()->json([
            'message' => "Campaign dikirim: {$sent} berhasil, {$failed} gagal, {$skipped} sudah terkirim",
            'sent'    => $sent,
            'failed'  => $failed,
            'skipped' => $skipped,
        ]);
    }
}

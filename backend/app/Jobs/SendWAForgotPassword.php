<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWAForgotPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $email,
        protected string $resetUrl,
    ) {}

    public function handle(): void
    {
        $user = User::where('email', $this->email)->first();
        if (!$user || !$user->phone) return;

        try {
            $wa = new WhatsAppService();

            $templateName = 'lupa_password';

            // Template button di Meta: https://idroom.id/forgot_password?{{1}}
            // {{1}} = hanya query string: "token=TOKEN&email=EMAIL"
            $parsed    = parse_url($this->resetUrl);
            $urlSuffix = '?' . ($parsed['query'] ?? ''); // "token=...&email=..."

            // Fetch language dari Meta (opsional, default 'id')
            $tplDetail = $wa->getTemplateDetail($templateName);
            $language  = $tplDetail['language'] ?? 'id';
            if (is_array($language)) $language = $language['code'] ?? 'id';

            Log::info('SendWAForgotPassword: template detail', [
                'user'       => $user->odata,
                'components' => $tplDetail['components'] ?? [],
                'language'   => $language,
                'urlSuffix'  => $urlSuffix,
            ]);

            // Gunakan rawComponents agar URL suffix selalu terkirim
            // (tidak bergantung apakah getTemplateDetail berhasil atau tidak)
            $wa->sendTemplate(
                toPhone:       $user->phone,
                templateName:  $templateName,
                language:      $language,
                rawComponents: [
                    [
                        'type'       => 'body',
                        'parameters' => [['type' => 'text', 'text' => $user->name]],
                    ],
                    [
                        'type'       => 'button',
                        'sub_type'   => 'url',
                        'index'      => '0',
                        'parameters' => [['type' => 'text', 'text' => $urlSuffix]],
                    ],
                ],
            );

            Log::info('SendWAForgotPassword: berhasil', [
                'user'     => $user->odata,
                'phone'    => $user->phone,
                'resetUrl' => $this->resetUrl,
            ]);

        } catch (\Throwable $e) {
            Log::error('SendWAForgotPassword: gagal', [
                'user'  => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

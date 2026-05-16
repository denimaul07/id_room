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
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SendWAVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $userOdata
    ) {}

    public function handle(): void
    {
        $user = User::where('odata', $this->userOdata)->first();
        if (!$user) return;

        // Generate signed URL verifikasi email (sama seperti Laravel default)
        // Force root URL agar tidak pakai default APP_URL yang salah
        $appUrl = config('app.url');
        URL::forceRootUrl($appUrl);
        $parsedScheme = parse_url($appUrl, PHP_URL_SCHEME);
        if ($parsedScheme) {
            URL::forceScheme($parsedScheme);
        }

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id'   => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        try {
            $wa = new WhatsAppService();

            $templateName = 'email_verifikasi';

            // Fetch struktur template dari Meta
            $tplDetail          = $wa->getTemplateDetail($templateName);
            $language           = $tplDetail['language'] ?? 'id';
            if (is_array($language)) $language = $language['code'] ?? 'id';
            $templateComponents = $tplDetail['components'] ?? [];

            Log::info('SendWAVerification: template detail', [
                'user'       => $user->odata,
                'components' => $templateComponents,
                'language'   => $language,
            ]);

            // Template button di Meta: https://api.idroom.id/api/auth/verify-email/{{1}}
            // {{1}} hanya suffix: "82/hash?expires=...&signature=..."
            $verifyBasePath = 'https://api.idroom.id/api/auth/verify-email/';
            $urlSuffix      = Str::after($verifyUrl, $verifyBasePath);

            $wa->sendTemplate(
                toPhone:            $user->phone,
                templateName:       $templateName,
                params:             [$user->name],
                templateComponents: $templateComponents,
                language:           $language,
                urlParams:          [$urlSuffix],
            );

            Log::info('SendWAVerification: berhasil', [
                'user'      => $user->odata,
                'phone'     => $user->phone,
                'verifyUrl' => $verifyUrl,
            ]);

        } catch (\Throwable $e) {
            Log::error('SendWAVerification: gagal', [
                'user'  => $user->odata,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
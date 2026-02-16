<x-mail::message>
    {{-- Header --}}
    <div style="text-align:center;background:#0f172a;padding:24px 0;border-radius:12px 12px 0 0;">
        <img src="{{ asset('logo/logo.webp') }}" alt="ID Room" style="height:42px;">
    </div>

    {{-- Body --}}
    <div style="padding:32px 24px;text-align:center;">
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;margin-bottom:12px;">
            Verifikasi Email Anda
        </h2>

        <p style="font-size:15px;color:#334155;line-height:1.6;margin-bottom:24px;">
            Terima kasih telah mendaftar di <b>ID Room</b>.<br>
            Silakan klik tombol di bawah ini untuk memverifikasi email Anda dan mengaktifkan akun.
        </p>

        {{-- Button --}}
        @isset($actionText)
            <x-mail::button :url="$actionUrl" color="primary" style="font-size:16px;padding:14px 28px;border-radius:8px;">
                {{ $actionText }}
            </x-mail::button>
        @endisset

        {{-- Fallback link --}}
        <div style="margin-top:32px;font-size:13px;color:#475569;text-align:left;">
            Jika tombol di atas tidak bisa diklik, salin dan tempel link berikut ke browser Anda:
            <div style="margin-top:8px;word-break:break-all;color:#2563eb;">
                {{ $displayableActionUrl }}
            </div>
        </div>

        {{-- Footer --}}
        <div style="margin-top:40px;font-size:14px;color:#334155;">
            Salam hangat,<br>
            <b>Tim ID Room</b>
        </div>
    </div>
</x-mail::message>

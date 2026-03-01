<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
</head>

<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 0;">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:#0f172a;padding:24px;text-align:center;">
                            <img src="{{ asset('storage/logo/logo.webp') }}"  alt="ID Room">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;text-align:center;">
                            <h2 style="margin:0 0 12px;color:#0f172a;">
                                Verifikasi Email Anda
                            </h2>

                            <p style="font-size:15px;color:#334155;line-height:1.6;">
                                Terima kasih telah mendaftar di <b>ID Room</b>.<br>
                                Klik tombol di bawah untuk memverifikasi email Anda.
                            </p>

                            <a href="{{ $actionUrl }}"
                                style="display:inline-block;margin-top:24px;
               background:#2563eb;color:#fff;
               text-decoration:none;padding:14px 28px;
               border-radius:8px;font-weight:600;">
                                {{ $actionText }}
                            </a>

                            <p style="margin-top:32px;font-size:13px;color:#475569;text-align:left;">
                                Jika tombol tidak bisa diklik, salin link berikut:
                            </p>

                            <p style="font-size:12px;color:#2563eb;word-break:break-all;text-align:left;">
                                {{ $displayableActionUrl }}
                            </p>

                            <p style="margin-top:40px;font-size:14px;color:#334155;">
                                Salam hangat,<br>
                                <b>Tim ID Room</b>
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>

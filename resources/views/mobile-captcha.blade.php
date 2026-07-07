@php
    $siteKey = env('RECAPTCHA_SITE_KEY');
    $testKey = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    $enabled = $siteKey && $siteKey !== $testKey;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Verifikasi Keamanan</title>
    @if ($enabled)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #F7F2F0;
        }
        .wrap {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            text-align: center;
        }
        .title {
            font-size: 16px;
            font-weight: 700;
            color: #522E2E;
            margin-bottom: 6px;
        }
        .subtitle {
            font-size: 12.5px;
            color: #7a6a6a;
            margin-bottom: 22px;
            line-height: 1.4;
        }
        .g-recaptcha { display: inline-block; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="title">Verifikasi Keamanan</div>
        <div class="subtitle">Centang kotak di bawah untuk memastikan Anda bukan robot.</div>

        @if ($enabled)
            <div class="g-recaptcha"
                 data-sitekey="{{ $siteKey }}"
                 data-callback="onCaptchaSuccess"
                 data-expired-callback="onCaptchaExpired"
                 data-error-callback="onCaptchaError"></div>
        @endif

        <script>
            function sendToApp(msg) {
                try {
                    if (window.CaptchaChannel && window.CaptchaChannel.postMessage) {
                        window.CaptchaChannel.postMessage(msg);
                    }
                } catch (e) {}
            }
            function onCaptchaSuccess(token) { sendToApp(token); }
            function onCaptchaExpired() { sendToApp('EXPIRED'); }
            function onCaptchaError() { sendToApp('ERROR'); }

            @unless ($enabled)
                // reCAPTCHA belum dikonfigurasi di server → lewati agar login tetap jalan.
                sendToApp('DISABLED');
            @endunless
        </script>
    </div>
</body>
</html>

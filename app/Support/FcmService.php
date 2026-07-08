<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Pengiriman push notification via Firebase Cloud Messaging HTTP v1 API
 * (menggantikan API legacy yang dimatikan Google 20 Juni 2024).
 *
 * Kredensial diambil dari env FIREBASE_CREDENTIALS berisi service account JSON
 * (boleh base64 maupun JSON mentah). Bila kosong, pengiriman dilewati (simulasi).
 */
class FcmService
{
    public static function send(?string $fcmToken, string $title, string $body, array $data = []): void
    {
        if (!$fcmToken) return;

        $creds = self::credentials();
        if (!$creds) {
            Log::info('FCM v1 (simulasi — FIREBASE_CREDENTIALS belum diset)', compact('title', 'body'));
            return;
        }

        $accessToken = self::accessToken($creds);
        if (!$accessToken) {
            Log::warning('FCM v1: gagal memperoleh access token');
            return;
        }

        // FCM v1 mewajibkan seluruh nilai pada data berupa string
        $stringData = [];
        foreach ($data as $k => $v) {
            $stringData[$k] = (string) $v;
        }

        try {
            $resp = Http::withToken($accessToken)->post(
                "https://fcm.googleapis.com/v1/projects/{$creds['project_id']}/messages:send",
                [
                    'message' => [
                        'token'        => $fcmToken,
                        'notification' => ['title' => $title, 'body' => $body],
                        'data'         => $stringData,
                        'android'      => [
                            'priority'     => 'high',
                            'notification' => ['channel_id' => 'barantin_channel'],
                        ],
                    ],
                ]
            );

            if ($resp->failed()) {
                Log::warning('FCM v1 gagal: ' . $resp->status() . ' ' . $resp->body());
            }
        } catch (\Throwable $e) {
            Log::error('FCM v1 error: ' . $e->getMessage());
        }
    }

    /** Kredensial service account dari env (base64 JSON atau JSON mentah). */
    private static function credentials(): ?array
    {
        $raw = env('FIREBASE_CREDENTIALS');
        if (!$raw) return null;

        $json = str_starts_with(trim($raw), '{') ? $raw : base64_decode($raw, true);
        if (!$json) return null;

        $data = json_decode($json, true);
        if (!is_array($data)
            || empty($data['client_email'])
            || empty($data['private_key'])
            || empty($data['project_id'])) {
            return null;
        }
        return $data;
    }

    /** OAuth2 access token, di-cache ~55 menit. */
    private static function accessToken(array $creds): ?string
    {
        return Cache::remember('fcm_v1_access_token', 3300, function () use ($creds) {
            $now = time();
            $header = self::b64json(['alg' => 'RS256', 'typ' => 'JWT']);
            $claim  = self::b64json([
                'iss'   => $creds['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
            ]);

            $signingInput = "{$header}.{$claim}";
            $signature = '';
            if (!openssl_sign($signingInput, $signature, $creds['private_key'], OPENSSL_ALGO_SHA256)) {
                return null;
            }
            $jwt = "{$signingInput}." . self::b64url($signature);

            $resp = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            return $resp->json('access_token');
        });
    }

    private static function b64json(array $data): string
    {
        return self::b64url(json_encode($data));
    }

    private static function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

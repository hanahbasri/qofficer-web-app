<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PasswordPolicyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * FR-P01, FR-K01: Login dengan NIP + password
     * Response berisi token + data user + role
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'nip'      => 'required|string',
            'password' => 'required|string',
        ]);

        // Verifikasi reCAPTCHA (skip jika key belum diset / test key) — sama seperti web
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        if ($secretKey && $secretKey !== '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe') {
            $token = $request->input('recaptcha_token');
            if (!$token) {
                throw ValidationException::withMessages([
                    'nip' => ['Verifikasi keamanan (captcha) belum diselesaikan.'],
                ]);
            }
            $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);
            if (!($verify->json('success') ?? false)) {
                throw ValidationException::withMessages([
                    'nip' => ['Verifikasi keamanan (captcha) gagal. Coba lagi.'],
                ]);
            }
        }

        $user = User::with('role', 'upt')
            ->where('nip', $request->nip)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nip' => ['NIP atau password salah.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Akun tidak aktif. Hubungi Super Admin.'], 403);
        }

        // Hapus token lama (single session per device)
        $user->tokens()->delete();

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->buildUserPayload($user),
            'password_policy' => PasswordPolicyService::buildStatus($user),
        ]);
    }

    /**
     * FR-P03: Logout — hapus token aktif
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    /**
     * Perbarui FCM token perangkat
     */
    public function updateFcmToken(Request $request): JsonResponse
    {
        $request->validate(['fcm_token' => 'required|string']);

        $request->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'FCM token diperbarui.']);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('role', 'upt');

        return response()->json([
            'user' => $this->buildUserPayload($user),
            'password_policy' => PasswordPolicyService::buildStatus($user),
        ]);
    }

    /**
     * Ganti password user yang sedang login.
     */
    public function gantiPassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check((string) $request->input('current_password'), (string) $user->password)) {
            return response()->json(['message' => 'Password saat ini tidak sesuai.'], 422);
        }

        if (Hash::check((string) $request->input('password'), (string) $user->password)) {
            return response()->json(['message' => 'Password baru harus berbeda dari password saat ini.'], 422);
        }

        $user->update(PasswordPolicyService::managedPasswordData(
            (string) $request->input('password'),
            false
        ));
        $user->loadMissing('role', 'upt');

        return response()->json([
            'message' => 'Password berhasil diubah. Masa berlaku password diperpanjang 30 hari ke depan.',
            'user' => $this->buildUserPayload($user),
            'password_policy' => PasswordPolicyService::buildStatus($user),
        ]);
    }

    /**
     * Upload foto profil (FR-P30)
     */
    public function uploadFotoProfil(Request $request): JsonResponse
    {
        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);

        $user = $request->user();
        $path = $request->file('foto')->store("profil/{$user->id}", 'public');
        $user->update(['foto_profil' => $path]);

        return response()->json([
            'message'     => 'Foto profil diperbarui.',
            'foto_profil' => $path,
        ]);
    }

    private function buildUserPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'nip' => $user->nip,
            'nama' => $user->nama,
            'email' => $user->email,
            'golongan' => $user->golongan,
            'pangkat' => $user->pangkat,
            'foto_profil' => $user->foto_profil,
            'must_change_password' => (bool) $user->must_change_password,
            'password_expires_at' => $user->password_expires_at?->toISOString(),
            'password_expired' => $user->isPasswordExpired(),
            'upt' => $user->upt ? [
                'kode' => $user->upt->kode,
                'nama' => $user->upt->nama,
                'wilayah' => $user->upt->wilayah,
            ] : null,
            'role' => $user->role ? [
                'name' => $user->role->name,
                'display_name' => $user->role->display_name,
            ] : null,
        ];
    }
}

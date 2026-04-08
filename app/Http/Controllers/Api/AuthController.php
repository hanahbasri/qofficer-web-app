<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'user'  => [
                'id'          => $user->id,
                'nip'         => $user->nip,
                'nama'        => $user->nama,
                'email'       => $user->email,
                'golongan'    => $user->golongan,
                'pangkat'     => $user->pangkat,
                'foto_profil' => $user->foto_profil,
                'upt'         => $user->upt ? [
                    'kode'    => $user->upt->kode,
                    'nama'    => $user->upt->nama,
                    'wilayah' => $user->upt->wilayah,
                ] : null,
                'role' => $user->role ? [
                    'name'         => $user->role->name,
                    'display_name' => $user->role->display_name,
                ] : null,
            ],
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
}

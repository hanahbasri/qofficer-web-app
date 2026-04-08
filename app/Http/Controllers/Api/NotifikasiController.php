<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    /**
     * FR-P28: Riwayat notifikasi user yang login
     */
    public function index(Request $request): JsonResponse
    {
        $notif = Notifikasi::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(30);

        return response()->json($notif);
    }

    /**
     * FR-P28: Tandai notifikasi sudah dibaca
     */
    public function baca(Request $request, int $id): JsonResponse
    {
        Notifikasi::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->update(['sudah_dibaca' => true]);

        return response()->json(['message' => 'Notifikasi ditandai dibaca.']);
    }

    /**
     * FR-P28: Hapus satu notifikasi
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        Notifikasi::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['message' => 'Notifikasi dihapus.']);
    }

    /**
     * FR-P28: Hapus semua notifikasi user
     */
    public function destroyAll(Request $request): JsonResponse
    {
        Notifikasi::where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Semua notifikasi dihapus.']);
    }

    /**
     * FR-K13: Kirim FCM push notification ke petugas yang ditugaskan
     * Dipanggil setelah ST berhasil dibuat
     */
    public function kirimFcm(Request $request): JsonResponse
    {
        $request->validate([
            'st_id'       => 'required|string|exists:surat_tugas,id',
            'petugas_ids' => 'required|array',
            'title'       => 'required|string',
            'body'        => 'required|string',
        ]);

        $petugas = User::whereIn('id', $request->petugas_ids)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->all();

        if (empty($petugas)) {
            return response()->json(['message' => 'Tidak ada perangkat terdaftar untuk menerima notifikasi.']);
        }

        $hasil = $this->sendFcm($petugas, $request->title, $request->body, [
            'type'  => 'st_baru',
            'st_id' => $request->st_id,
        ]);

        // Simpan ke tabel notifikasi
        foreach ($request->petugas_ids as $petugasId) {
            Notifikasi::create([
                'user_id'      => $petugasId,
                'judul'        => $request->title,
                'pesan'        => $request->body,
                'type'         => 'st_baru',
                'referensi_id' => $request->st_id,
            ]);
        }

        return response()->json(['message' => 'Notifikasi dikirim.', 'fcm_result' => $hasil]);
    }

    // ── Internal helper ───────────────────────────────────────────
    /**
     * Kirim FCM via HTTP v1 API.
     * Untuk skripsi/simulasi: jika FIREBASE_SERVER_KEY tidak di-set, log saja.
     */
    private function sendFcm(array $tokens, string $title, string $body, array $data = []): array
    {
        $serverKey = config('services.firebase.server_key');

        if (!$serverKey) {
            Log::info('FCM (simulasi) → ', compact('tokens', 'title', 'body', 'data'));
            return ['simulated' => true, 'tokens' => count($tokens)];
        }

        $results = [];
        foreach ($tokens as $token) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "key={$serverKey}",
                    'Content-Type'  => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to'           => $token,
                    'notification' => ['title' => $title, 'body' => $body],
                    'data'         => $data,
                ]);

                $results[] = $response->json();
            } catch (\Throwable $e) {
                Log::error('FCM send error: ' . $e->getMessage());
                $results[] = ['error' => $e->getMessage()];
            }
        }

        return $results;
    }
}

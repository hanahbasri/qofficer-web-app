<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HasilPemeriksaan;
use App\Models\RekomendasiKarantina;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BestTrustController extends Controller
{
    /**
     * FR-W12–W14: Trigger simulasi BEST-TRUST setelah rekomendasi disimpan.
     * Endpoint ini dipanggil dari web Koordinator maupun bisa dipanggil manual.
     */
    public function trigger(Request $request, int $rekomendasiId): JsonResponse
    {
        $rekomendasi = RekomendasiKarantina::with('hasilPemeriksaan.suratTugas')
            ->findOrFail($rekomendasiId);

        $hasil = $rekomendasi->hasilPemeriksaan;
        $st    = $hasil->suratTugas;

        // FR-W13: Payload ke BEST-TRUST
        $payload = [
            'ptk_id'          => $st?->ptk_id,
            'id_surat_tugas'  => $hasil->id_surat_tugas,
            'id_pemeriksaan'  => $hasil->id,
            'tgl_periksa'     => $hasil->tgl_periksa?->toDateTimeString(),
            'target'          => $hasil->target,
            'metode'          => $hasil->metode,
            'temuan'          => $hasil->temuan,
            'komoditas'       => $hasil->komoditas,
            'tindakan'        => $rekomendasi->tindakan,
            'catatan'         => $rekomendasi->catatan,
        ];

        $bestTrustUrl = config('services.best_trust.url');

        if (!$bestTrustUrl) {
            // Mode simulasi: kembalikan response dummy
            $dummyResponse = [
                'status'     => 'success',
                'message'    => 'Data pemeriksaan diterima oleh BEST-TRUST (simulasi).',
                'reference'  => 'BT-' . strtoupper(substr($hasil->id, 0, 8)),
                'timestamp'  => now()->toIso8601String(),
            ];

            $rekomendasi->update([
                'best_trust_status'   => 'success',
                'best_trust_response' => json_encode($dummyResponse),
            ]);

            return response()->json([
                'message'   => 'Simulasi BEST-TRUST berhasil.',
                'response'  => $dummyResponse,
                'simulated' => true,
            ]);
        }

        // Kirim ke endpoint BEST-TRUST nyata
        try {
            $response = Http::timeout(10)->post($bestTrustUrl, $payload);

            $status = $response->successful() ? 'success' : 'failed';

            $rekomendasi->update([
                'best_trust_status'   => $status,
                'best_trust_response' => $response->body(),
            ]);

            return response()->json([
                'message'  => $status === 'success' ? 'Data berhasil dikirim ke BEST-TRUST.' : 'BEST-TRUST mengembalikan error.',
                'response' => $response->json(),
                'status'   => $status,
            ], $response->successful() ? 200 : 502);
        } catch (\Throwable $e) {
            $rekomendasi->update([
                'best_trust_status'   => 'failed',
                'best_trust_response' => $e->getMessage(),
            ]);

            Log::error('BEST-TRUST error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Gagal menghubungi BEST-TRUST. Data tersimpan untuk retry.',
                'error'   => $e->getMessage(),
            ], 502);
        }
    }

    /**
     * Endpoint simulasi BEST-TRUST (menerima data dari sistem lain)
     * Digunakan saat testing tanpa server BEST-TRUST nyata.
     */
    public function simulate(Request $request): JsonResponse
    {
        Log::info('BEST-TRUST simulate received:', $request->all());

        return response()->json([
            'status'    => 'success',
            'message'   => 'Data pemeriksaan diterima (simulasi BEST-TRUST).',
            'reference' => 'BT-' . strtoupper(\Str::random(8)),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

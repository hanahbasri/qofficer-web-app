<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterTarget;
use App\Models\MasterTemuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MasterDataController extends Controller
{
    /**
     * FR-P14, FR-P25: Data master Target per jenis karantina.
     * Ambil nilai unik dari field 'uraian' di response ESPS targetUji,
     * fallback ke DB lokal.
     */
    public function target(Request $request): JsonResponse
    {
        $jenis = $request->query('jenis') ?? $request->query('kar'); // H | T | I

        $espsItems = $this->fetchEspsRecords($jenis);
        if ($espsItems !== null) {
            $data = collect($espsItems)
                ->pluck('uraian')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->map(fn($v) => ['uraian' => $v])
                ->all();
            return response()->json(['data' => $data]);
        }

        // Fallback ke database lokal
        Log::warning('ESPS targetUji tidak tersedia, menggunakan data lokal (target).');
        $query = MasterTarget::aktif();
        if ($jenis) {
            $query->jenis($jenis);
        }
        $data = $query->orderBy('nama')->get()->map(fn($row) => [
            'uraian' => $row->nama,
        ])->values();
        return response()->json(['data' => $data]);
    }

    /**
     * FR-P14, FR-P25: Data master Temuan per jenis karantina.
     * Ambil nilai unik dari field 'uraian' di response ESPS targetUji,
     * fallback ke DB lokal.
     */
    public function temuan(Request $request): JsonResponse
    {
        $jenis = $request->query('jenis') ?? $request->query('kar');

        $espsItems = $this->fetchEspsRecords($jenis);
        if ($espsItems !== null) {
            $data = collect($espsItems)
                ->pluck('uraian')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->map(fn($v) => ['uraian' => $v])
                ->all();
            return response()->json(['data' => $data]);
        }

        // Fallback ke database lokal
        Log::warning('ESPS targetUji tidak tersedia, menggunakan data lokal (temuan).');
        $query = MasterTemuan::aktif();
        if ($jenis) {
            $query->jenis($jenis);
        }
        $data = $query->orderBy('nama')->get()->map(fn($row) => [
            'uraian' => $row->nama,
        ])->values();
        return response()->json(['data' => $data]);
    }

    /**
     * Fetch list records dari ESPS API endpoint targetUji.
     * Mengembalikan array asli records, atau null jika gagal.
     */
    private function fetchEspsRecords(?string $kar): ?array
    {
        $baseUrl = config('services.esps.base_url');
        $token   = config('services.esps.auth_token');

        if (empty($baseUrl) || empty($token)) {
            return null;
        }

        try {
            $params = $kar ? ['kar' => $kar] : [];
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $token,
                'Accept'        => 'application/json',
            ])->timeout(10)->get("{$baseUrl}/targetUji", $params);

            if (!$response->successful()) {
                Log::warning("ESPS targetUji gagal: HTTP {$response->status()}");
                return null;
            }

            $body = $response->json();

            if (isset($body['data']) && is_array($body['data'])) {
                return $body['data'];
            }
            if (is_array($body) && !empty($body) && !isset($body['data'])) {
                return $body;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('ESPS targetUji exception: ' . $e->getMessage());
            return null;
        }
    }

    // ── Admin CRUD (opsional untuk Super Admin web) ───────────────
    public function storeTarget(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nama'            => 'required|string',
            'jenis_karantina' => 'required|in:H,T,I',
        ]);

        $target = MasterTarget::create($data);

        return response()->json(['data' => $target], 201);
    }

    public function storeTemuan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nama'            => 'required|string',
            'jenis_karantina' => 'required|in:H,T,I',
        ]);

        $temuan = MasterTemuan::create($data);

        return response()->json(['data' => $temuan], 201);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterTarget;
use App\Models\MasterTemuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * FR-P14, FR-P25: Data master Target per jenis karantina
     * Digunakan sebagai dropdown form K-3.7b + di-cache offline
     */
    public function target(Request $request): JsonResponse
    {
        $jenis = $request->query('jenis'); // H | T | I

        $query = MasterTarget::aktif();

        if ($jenis) {
            $query->jenis($jenis);
        }

        return response()->json(['data' => $query->orderBy('nama')->get()]);
    }

    /**
     * FR-P14, FR-P25: Data master Temuan per jenis karantina
     */
    public function temuan(Request $request): JsonResponse
    {
        $jenis = $request->query('jenis');

        $query = MasterTemuan::aktif();

        if ($jenis) {
            $query->jenis($jenis);
        }

        return response()->json(['data' => $query->orderBy('nama')->get()]);
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

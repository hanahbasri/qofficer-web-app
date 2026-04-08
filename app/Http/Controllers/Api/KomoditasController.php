<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Komoditas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KomoditasController extends Controller
{
    /**
     * Daftar komoditas master berdasarkan jenis karantina.
     * GET /komoditas?jenis=H|I|T
     *
     * Mapping jenis → kategori_id:
     *   H (Hewan)    → 1
     *   I (Ikan)     → 2
     *   T (Tumbuhan) → 3
     */
    public function index(Request $request): JsonResponse
    {
        $jenis = strtoupper($request->query('jenis', 'H'));

        $kategoriMap = ['H' => 1, 'I' => 2, 'T' => 3];
        $kategoriId  = $kategoriMap[$jenis] ?? 1;

        $komoditas = Komoditas::where('kategori_id', $kategoriId)
            ->select('id', 'kategori_id', 'nama', 'kode_hs')
            ->orderBy('nama')
            ->get();

        return response()->json(['data' => $komoditas]);
    }
}

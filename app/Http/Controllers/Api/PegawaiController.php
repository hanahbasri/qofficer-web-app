<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Daftar semua pegawai aktif dalam UPT (untuk dropdown penandatangan).
     * GET /pegawai?upt={kode_upt}
     */
    public function index(Request $request): JsonResponse
    {
        $uptId = $request->query('upt', $request->user()->upt_id);

        $pegawai = User::where('upt_id', $uptId)
            ->where('is_active', true)
            ->select('id', 'nip', 'nama', 'golongan', 'pangkat')
            ->orderBy('nama')
            ->get();

        return response()->json(['data' => $pegawai]);
    }
}

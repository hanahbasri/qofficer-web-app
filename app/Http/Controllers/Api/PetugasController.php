<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    /**
     * FR-K09: Daftar petugas aktif dalam satu UPT
     * Digunakan Koordinator untuk multi-select saat assign ST
     */
    public function index(Request $request): JsonResponse
    {
        $uptId = $request->query('upt', $request->user()->upt_id);

        $petugas = User::with('role')
            ->where('upt_id', $uptId)
            ->where('is_active', true)
            ->whereHas('role', fn($q) => $q->where('name', 'petugas-lapangan'))
            ->select('id', 'nip', 'nama', 'golongan', 'pangkat', 'foto_profil', 'upt_id')
            ->get();

        return response()->json(['data' => $petugas]);
    }

    /**
     * Detail satu petugas (FR-K11)
     */
    public function show(int $id): JsonResponse
    {
        $petugas = User::with('upt')
            ->where('id', $id)
            ->whereHas('role', fn($q) => $q->where('name', 'petugas-lapangan'))
            ->select('id', 'nip', 'nama', 'golongan', 'pangkat', 'foto_profil', 'upt_id')
            ->firstOrFail();

        return response()->json(['data' => $petugas]);
    }
}

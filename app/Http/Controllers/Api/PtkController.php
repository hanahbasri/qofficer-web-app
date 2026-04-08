<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PtkController extends Controller
{
    /**
     * FR-K03: Daftar Permohonan Tindakan Karantina (PTK) yang menunggu penugasan.
     * Dalam produksi, data ini berasal dari sistem iQFAST/BEST-TRUST.
     * Saat ini dikembalikan sebagai simulasi.
     *
     * GET /api/ptk?upt={kode_upt}&status=menunggu
     */
    public function index(Request $request): JsonResponse
    {
        $uptId = $request->query('upt', $request->user()->upt_id);

        // Simulasi data PTK dari sistem iQFAST
        // Dalam produksi: ganti dengan Http::get(config('services.iqfast.url'), [...])
        $simulasiData = $this->getSimulasiPtk($uptId);

        return response()->json(['data' => $simulasiData, 'simulated' => true]);
    }

    /**
     * Detail satu PTK
     * GET /api/ptk/{ptk_id}
     */
    public function show(Request $request, string $ptkId): JsonResponse
    {
        $simulasiData = collect($this->getSimulasiPtk($request->user()->upt_id))
            ->firstWhere('ptk_id', $ptkId);

        if (!$simulasiData) {
            return response()->json(['message' => 'PTK tidak ditemukan.'], 404);
        }

        return response()->json(['data' => $simulasiData, 'simulated' => true]);
    }

    private function getSimulasiPtk(string $uptId): array
    {
        $tahun = date('Y');
        $bulan = date('m');

        return [
            [
                'ptk_id'          => "PTK-{$uptId}-{$tahun}{$bulan}-001",
                'no_ptk'          => "PTK/{$uptId}/{$tahun}/{$bulan}/0001",
                'tanggal_ptk'     => date('Y-m-d'),
                'jenis_karantina' => 'H',
                'nama_pemohon'    => 'PT Maju Bersama',
                'komoditas'       => 'Sapi Impor',
                'asal_negara'     => 'Australia',
                'tujuan'          => 'Jakarta',
                'jumlah'          => '50 ekor',
                'dokumen_karantina' => 'HC-2024-001',
                'status'          => 'menunggu',
            ],
            [
                'ptk_id'          => "PTK-{$uptId}-{$tahun}{$bulan}-002",
                'no_ptk'          => "PTK/{$uptId}/{$tahun}/{$bulan}/0002",
                'tanggal_ptk'     => date('Y-m-d', strtotime('-1 day')),
                'jenis_karantina' => 'T',
                'nama_pemohon'    => 'CV Agro Sejahtera',
                'komoditas'       => 'Bibit Mangga',
                'asal_negara'     => 'Thailand',
                'tujuan'          => 'Pontianak',
                'jumlah'          => '200 batang',
                'dokumen_karantina' => 'PC-2024-002',
                'status'          => 'menunggu',
            ],
            [
                'ptk_id'          => "PTK-{$uptId}-{$tahun}{$bulan}-003",
                'no_ptk'          => "PTK/{$uptId}/{$tahun}/{$bulan}/0003",
                'tanggal_ptk'     => date('Y-m-d', strtotime('-2 days')),
                'jenis_karantina' => 'I',
                'nama_pemohon'    => 'UD Bahari Nusantara',
                'komoditas'       => 'Udang Vaname',
                'asal_negara'     => 'Vietnam',
                'tujuan'          => 'Surabaya',
                'jumlah'          => '5000 kg',
                'dokumen_karantina' => 'FC-2024-003',
                'status'          => 'menunggu',
            ],
        ];
    }
}

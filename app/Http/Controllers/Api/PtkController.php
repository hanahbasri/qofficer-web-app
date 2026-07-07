<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuratTugas;
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

        // PTK yang sudah dibuatkan ST
        $ptkIdsSudahSt = SuratTugas::whereNotNull('ptk_id')
            ->pluck('ptk_id')
            ->flip()
            ->all();

        // Untuk list, kirim ringkasan (tanpa detail komoditas_list agar response ringan)
        $ringkasan = array_map(function ($ptk) use ($ptkIdsSudahSt) {
            return [
                'ptk_id'           => $ptk['ptk_id'],
                'no_ptk'           => $ptk['no_ptk'],
                'tanggal_ptk'      => $ptk['tanggal_ptk'],
                'jenis_karantina'  => $ptk['jenis_karantina'],
                'nama_pemohon'     => $ptk['nama_pemohon'],
                'komoditas'        => $ptk['komoditas_ringkasan'],
                'asal_negara'      => $ptk['asal_negara'],
                'tujuan'           => $ptk['tujuan'],
                'pelabuhan_muat'   => $ptk['pelabuhan_muat'],
                'pelabuhan_bongkar'=> $ptk['pelabuhan_bongkar'],
                'jumlah_komoditas' => count($ptk['komoditas_list']),
                'dokumen_karantina'=> $ptk['dokumen_karantina'],
                'status'           => $ptk['status'],
                'has_surat_tugas'  => isset($ptkIdsSudahSt[$ptk['ptk_id']]),
            ];
        }, $simulasiData);

        return response()->json(['data' => $ringkasan, 'simulated' => true]);
    }

    /**
     * Detail satu PTK — termasuk komoditas_list lengkap
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
        // PTK simulasi hanya tersedia untuk BBKHIT DKI Jakarta (kode UPT 31xx).
        // Koordinator UPT lain tidak menerima PTK ini.
        if (!str_starts_with($uptId, '31')) {
            return [];
        }

        $today = now();

        // Simulasi PTK Domestik Keluar (Dokel) — fokus BBKHIT DKI Jakarta.
        // DKI sebagai titik muat (Pelabuhan Tanjung Priok): asal Jabodetabek/Jabar,
        // tujuan kota-kota luar Jawa. Nama & data bersifat FIKTIF (dummy).
        $defs = [
            ['id' => '31000DKH260705A1MKS', 'jk' => 'H', 'pemohon' => 'PT Nusantara Dairy Prima', 'asal' => 'Kota Bekasi', 'tujuan' => 'Kota Makassar', 'dok' => 'KH-3100-001', 'day' => 0, 'kom' => [
                ['nama' => 'Es Krim Aneka Rasa', 'latin' => '-', 'vol' => 500, 'sat' => 'karton'],
            ]],
            ['id' => '31000DKH260705A2PNK', 'jk' => 'H', 'pemohon' => 'PT Boga Unggas Sejahtera', 'asal' => 'Jakarta Utara', 'tujuan' => 'Kota Pontianak', 'dok' => 'KH-3100-002', 'day' => 0, 'kom' => [
                ['nama' => 'Daging Ayam Beku', 'latin' => 'Gallus gallus domesticus', 'vol' => 3000, 'sat' => 'kg'],
            ]],
            ['id' => '31000DKT260704A3MDC', 'jk' => 'T', 'pemohon' => 'PT Segar Tani Makmur', 'asal' => 'Kota Bandung', 'tujuan' => 'Kota Manado', 'dok' => 'KT-3100-003', 'day' => 1, 'kom' => [
                ['nama' => 'Kentang Iris Beku', 'latin' => 'Solanum tuberosum', 'vol' => 2000, 'sat' => 'kg'],
            ]],
            ['id' => '31000DKT260704A4PRE', 'jk' => 'T', 'pemohon' => 'PT Buah Nusantara Jaya', 'asal' => 'Kota Bogor', 'tujuan' => 'Kota Pare-Pare', 'dok' => 'KT-3100-004', 'day' => 1, 'kom' => [
                ['nama' => 'Pir Segar',  'latin' => 'Pyrus communis',  'vol' => 1000, 'sat' => 'kg'],
                ['nama' => 'Apel Fuji',  'latin' => 'Malus domestica', 'vol' => 1200, 'sat' => 'kg'],
            ]],
            ['id' => '31000DKT260703A5MMJ', 'jk' => 'T', 'pemohon' => 'CV Sumber Buah Lestari', 'asal' => 'Kota Bekasi', 'tujuan' => 'Kabupaten Mamuju', 'dok' => 'KT-3100-005', 'day' => 2, 'kom' => [
                ['nama' => 'Jeruk',     'latin' => 'Citrus sinensis',      'vol' => 1500, 'sat' => 'kg'],
                ['nama' => 'Kelengkeng', 'latin' => 'Dimocarpus longan',   'vol' => 700,  'sat' => 'kg'],
            ]],
            ['id' => '31000DKT260703A6KDI', 'jk' => 'T', 'pemohon' => 'PT Tropika Fruit Indonesia', 'asal' => 'Kota Tangerang', 'tujuan' => 'Kota Kendari', 'dok' => 'KT-3100-006', 'day' => 2, 'kom' => [
                ['nama' => 'Belimbing (Star Fruit)', 'latin' => 'Averrhoa carambola', 'vol' => 600, 'sat' => 'kg'],
            ]],
            ['id' => '31000DKT260702A7BPN', 'jk' => 'T', 'pemohon' => 'PT Rimba Kayu Lestari', 'asal' => 'Kabupaten Bogor', 'tujuan' => 'Kota Balikpapan', 'dok' => 'KT-3100-007', 'day' => 3, 'kom' => [
                ['nama' => 'Kayu Gergajian', 'latin' => 'Tectona grandis', 'vol' => 30, 'sat' => 'm3'],
            ]],
            ['id' => '31000DKH260702A8PLU', 'jk' => 'H', 'pemohon' => 'PT Aneka Frozen Food', 'asal' => 'Jakarta Timur', 'tujuan' => 'Kota Palu', 'dok' => 'KH-3100-008', 'day' => 3, 'kom' => [
                ['nama' => 'Nugget Ayam Beku', 'latin' => '-', 'vol' => 1500, 'sat' => 'kg'],
                ['nama' => 'Sosis Sapi Beku',  'latin' => '-', 'vol' => 800,  'sat' => 'kg'],
            ]],
            ['id' => '31000DKI260701A9SRG', 'jk' => 'I', 'pemohon' => 'PT Mina Bahari Segar', 'asal' => 'Jakarta Utara', 'tujuan' => 'Kota Sorong', 'dok' => 'KI-3100-009', 'day' => 4, 'kom' => [
                ['nama' => 'Ikan Kembung Beku', 'latin' => 'Rastrelliger kanagurta', 'vol' => 2500, 'sat' => 'kg'],
            ]],
            ['id' => '31000DKI260701B1AMB', 'jk' => 'I', 'pemohon' => 'CV Laut Nusantara Jaya', 'asal' => 'Kota Bekasi', 'tujuan' => 'Kota Ambon', 'dok' => 'KI-3100-010', 'day' => 4, 'kom' => [
                ['nama' => 'Udang Vaname Beku', 'latin' => 'Litopenaeus vannamei', 'vol' => 1200, 'sat' => 'kg'],
            ]],
            ['id' => '31000DKH260630B2BJM', 'jk' => 'H', 'pemohon' => 'PT Prima Telur Nusantara', 'asal' => 'Kabupaten Bogor', 'tujuan' => 'Kota Banjarmasin', 'dok' => 'KH-3100-011', 'day' => 5, 'kom' => [
                ['nama' => 'Telur Ayam Konsumsi', 'latin' => 'Gallus gallus domesticus', 'vol' => 5000, 'sat' => 'kg'],
            ]],
            ['id' => '31000DKT260630B3KPG', 'jk' => 'T', 'pemohon' => 'PT Hasil Bumi Sentosa', 'asal' => 'Kota Bandung', 'tujuan' => 'Kota Kupang', 'dok' => 'KT-3100-012', 'day' => 5, 'kom' => [
                ['nama' => 'Wortel Beku',  'latin' => 'Daucus carota',        'vol' => 1000, 'sat' => 'kg'],
                ['nama' => 'Brokoli Beku', 'latin' => 'Brassica oleracea',    'vol' => 600,  'sat' => 'kg'],
            ]],
        ];

        return array_map(function ($d, $idx) use ($today, $uptId) {
            $seq = '2607' . str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT);
            $komoditas = $this->buildKomoditasItems($d['kom'], $d['jk'], $uptId, $seq);
            $jumlah = count($d['kom']);
            $ringkasan = $jumlah === 1
                ? $d['kom'][0]['nama']
                : $d['kom'][0]['nama'] . ' +' . ($jumlah - 1) . ' lainnya';

            // Pelabuhan bongkar per kota tujuan (pelabuhan muat = satpel DKI: Tanjung Priok)
            $bongkarMap = [
                'Kota Makassar'    => 'Pelabuhan Makassar',
                'Kota Pontianak'   => 'Pelabuhan Dwikora Pontianak',
                'Kota Manado'      => 'Pelabuhan Bitung',
                'Kota Pare-Pare'   => 'Pelabuhan Nusantara Pare-Pare',
                'Kabupaten Mamuju' => 'Pelabuhan Belang-Belang Mamuju',
                'Kota Kendari'     => 'Pelabuhan Kendari',
                'Kota Balikpapan'  => 'Pelabuhan Semayang Balikpapan',
                'Kota Palu'        => 'Pelabuhan Pantoloan Palu',
                'Kota Sorong'      => 'Pelabuhan Sorong',
                'Kota Ambon'       => 'Pelabuhan Yos Sudarso Ambon',
                'Kota Banjarmasin' => 'Pelabuhan Trisakti Banjarmasin',
                'Kota Kupang'      => 'Pelabuhan Tenau Kupang',
            ];

            return [
                'ptk_id'             => $d['id'],
                'no_ptk'             => $d['id'],
                'tanggal_ptk'        => $today->copy()->subDays($d['day'])->toDateString(),
                'jenis_karantina'    => $d['jk'],
                'nama_pemohon'       => $d['pemohon'],
                'komoditas_ringkasan'=> $ringkasan,
                'asal_negara'        => $d['asal'],
                'tujuan'             => $d['tujuan'],
                'pelabuhan_muat'     => 'Pelabuhan Tanjung Priok',
                'pelabuhan_bongkar'  => $bongkarMap[$d['tujuan']] ?? ('Pelabuhan ' . $d['tujuan']),
                'dokumen_karantina'  => $d['dok'],
                'status'             => 'menunggu',
                'komoditas_list'     => $komoditas,
            ];
        }, $defs, array_keys($defs));
    }

    private function buildKomoditasItems(array $items, string $jenis, string $uptId, string $seq): array
    {
        return array_values(array_map(function ($item, $idx) use ($jenis, $uptId, $seq) {
            $no = str_pad($idx + 1, 3, '0', STR_PAD_LEFT);
            return [
                'komoditas_id'    => "KOM-{$uptId}-{$seq}-{$no}",
                'nama_komoditas'  => $item['nama'],
                'nama_latin'      => $item['latin'],
                'volume'          => $item['vol'],
                'satuan'          => $item['sat'],
                'jenis_karantina' => $jenis,
            ];
        }, $items, array_keys($items)));
    }
}

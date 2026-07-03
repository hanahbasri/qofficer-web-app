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
        $today = now();

        return [
            [
                'ptk_id'             => '15012EXT260211134302UISE2S',
                'no_ptk'             => '15012EXT260211134302UISE2S',
                'tanggal_ptk'        => $today->toDateString(),
                'jenis_karantina'    => 'H',
                'nama_pemohon'       => 'PT Maju Bersama',
                'komoditas_ringkasan'=> 'Sapi Impor (15 jenis)',
                'asal_negara'        => 'Australia',
                'tujuan'             => 'Jakarta',
                'dokumen_karantina'  => 'HC-2024-001',
                'status'             => 'menunggu',
                'komoditas_list'     => $this->dummyKomoditasHewan($uptId, '260211001'),
            ],
            [
                'ptk_id'             => '15012EXT2602091226270KGI7S',
                'no_ptk'             => '15012EXT2602091226270KGI7S',
                'tanggal_ptk'        => $today->copy()->subDay()->toDateString(),
                'jenis_karantina'    => 'T',
                'nama_pemohon'       => 'CV Agro Sejahtera',
                'komoditas_ringkasan'=> 'Bibit/Benih Tanaman (18 jenis)',
                'asal_negara'        => 'Thailand',
                'tujuan'             => 'Pontianak',
                'dokumen_karantina'  => 'PC-2024-002',
                'status'             => 'menunggu',
                'komoditas_list'     => $this->dummyKomoditasTumbuhan($uptId, '260209002'),
            ],
            [
                'ptk_id'             => '32000IMP260207101530A9BC2D',
                'no_ptk'             => '32000IMP260207101530A9BC2D',
                'tanggal_ptk'        => $today->copy()->subDays(2)->toDateString(),
                'jenis_karantina'    => 'I',
                'nama_pemohon'       => 'PT Bahari Nusantara',
                'komoditas_ringkasan'=> 'Udang & Ikan Impor (12 jenis)',
                'asal_negara'        => 'Vietnam',
                'tujuan'             => 'Surabaya',
                'dokumen_karantina'  => 'FC-2024-003',
                'status'             => 'menunggu',
                'komoditas_list'     => $this->dummyKomoditasIkan($uptId, '260207003'),
            ],
        ];
    }

    /** Dummy komoditas Hewan */
    private function dummyKomoditasHewan(string $uptId, string $seq): array
    {
        $items = [
            ['nama' => 'Sapi Limousin',      'latin' => 'Bos taurus (Limousin)',      'vol' => 50,   'sat' => 'ekor'],
            ['nama' => 'Sapi Simental',      'latin' => 'Bos taurus (Simmental)',     'vol' => 30,   'sat' => 'ekor'],
            ['nama' => 'Sapi Brahman',       'latin' => 'Bos indicus (Brahman)',      'vol' => 20,   'sat' => 'ekor'],
            ['nama' => 'Sapi Angus',         'latin' => 'Bos taurus (Angus)',         'vol' => 25,   'sat' => 'ekor'],
            ['nama' => 'Sapi Hereford',      'latin' => 'Bos taurus (Hereford)',      'vol' => 15,   'sat' => 'ekor'],
            ['nama' => 'Domba Merino',       'latin' => 'Ovis aries (Merino)',        'vol' => 100,  'sat' => 'ekor'],
            ['nama' => 'Kambing Boer',       'latin' => 'Capra aegagrus (Boer)',      'vol' => 80,   'sat' => 'ekor'],
            ['nama' => 'Babi Duroc',         'latin' => 'Sus scrofa domesticus',      'vol' => 200,  'sat' => 'ekor'],
            ['nama' => 'Kuda Thoroughbred',  'latin' => 'Equus ferus caballus',       'vol' => 5,    'sat' => 'ekor'],
            ['nama' => 'Ayam Broiler DOC',   'latin' => 'Gallus gallus domesticus',   'vol' => 5000, 'sat' => 'ekor'],
            ['nama' => 'Ayam Petelur DOC',   'latin' => 'Gallus gallus domesticus',   'vol' => 3000, 'sat' => 'ekor'],
            ['nama' => 'Bebek Peking',       'latin' => 'Anas platyrhynchos',         'vol' => 500,  'sat' => 'ekor'],
            ['nama' => 'Semen Beku Sapi',    'latin' => 'Bos taurus (semen)',         'vol' => 1000, 'sat' => 'dosis'],
            ['nama' => 'Embrio Sapi',        'latin' => 'Bos taurus (embrio)',        'vol' => 50,   'sat' => 'embrio'],
            ['nama' => 'Daging Sapi Beku',   'latin' => 'Bos taurus (carcass)',       'vol' => 5000, 'sat' => 'kg'],
        ];
        return $this->buildKomoditasItems($items, 'H', $uptId, $seq);
    }

    /** Dummy komoditas Tumbuhan */
    private function dummyKomoditasTumbuhan(string $uptId, string $seq): array
    {
        $items = [
            ['nama' => 'Bibit Mangga Harum Manis', 'latin' => 'Mangifera indica',         'vol' => 500,  'sat' => 'batang'],
            ['nama' => 'Bibit Durian Monthong',    'latin' => 'Durio zibethinus',          'vol' => 300,  'sat' => 'batang'],
            ['nama' => 'Benih Padi Hibrida',       'latin' => 'Oryza sativa',              'vol' => 1000, 'sat' => 'kg'],
            ['nama' => 'Benih Jagung Hibrida',     'latin' => 'Zea mays',                  'vol' => 500,  'sat' => 'kg'],
            ['nama' => 'Benih Kedelai',            'latin' => 'Glycine max',               'vol' => 2000, 'sat' => 'kg'],
            ['nama' => 'Bibit Alpukat Hass',       'latin' => 'Persea americana',          'vol' => 200,  'sat' => 'batang'],
            ['nama' => 'Bibit Stroberi',           'latin' => 'Fragaria x ananassa',       'vol' => 1000, 'sat' => 'pcs'],
            ['nama' => 'Benih Tomat Cherry',       'latin' => 'Solanum lycopersicum',      'vol' => 50,   'sat' => 'kg'],
            ['nama' => 'Benih Paprika',            'latin' => 'Capsicum annuum',           'vol' => 20,   'sat' => 'kg'],
            ['nama' => 'Bibit Kelapa Sawit',       'latin' => 'Elaeis guineensis',         'vol' => 5000, 'sat' => 'batang'],
            ['nama' => 'Bibit Karet',              'latin' => 'Hevea brasiliensis',        'vol' => 2000, 'sat' => 'batang'],
            ['nama' => 'Bibit Anggur Muscat',      'latin' => 'Vitis vinifera',            'vol' => 100,  'sat' => 'batang'],
            ['nama' => 'Bunga Krisan',             'latin' => 'Chrysanthemum morifolium',  'vol' => 10000,'sat' => 'pot'],
            ['nama' => 'Tanaman Hias Anthurium',   'latin' => 'Anthurium andraeanum',      'vol' => 500,  'sat' => 'pot'],
            ['nama' => 'Benih Kentang',            'latin' => 'Solanum tuberosum',         'vol' => 3000, 'sat' => 'kg'],
            ['nama' => 'Bibit Kopi Arabika',       'latin' => 'Coffea arabica',            'vol' => 1000, 'sat' => 'batang'],
            ['nama' => 'Bibit Teh',                'latin' => 'Camellia sinensis',         'vol' => 800,  'sat' => 'batang'],
            ['nama' => 'Kayu Jati',                'latin' => 'Tectona grandis',           'vol' => 10,   'sat' => 'm3'],
        ];
        return $this->buildKomoditasItems($items, 'T', $uptId, $seq);
    }

    /** Dummy komoditas Ikan */
    private function dummyKomoditasIkan(string $uptId, string $seq): array
    {
        $items = [
            ['nama' => 'Udang Vaname',         'latin' => 'Litopenaeus vannamei',     'vol' => 5000,  'sat' => 'kg'],
            ['nama' => 'Udang Windu',          'latin' => 'Penaeus monodon',          'vol' => 2000,  'sat' => 'kg'],
            ['nama' => 'Ikan Salmon Atlantik', 'latin' => 'Salmo salar',              'vol' => 3000,  'sat' => 'kg'],
            ['nama' => 'Ikan Trout Pelangi',   'latin' => 'Oncorhynchus mykiss',      'vol' => 1500,  'sat' => 'kg'],
            ['nama' => 'Ikan Nila Gift',       'latin' => 'Oreochromis niloticus',    'vol' => 10000, 'sat' => 'ekor'],
            ['nama' => 'Ikan Lele Sangkuriang','latin' => 'Clarias gariepinus',       'vol' => 5000,  'sat' => 'ekor'],
            ['nama' => 'Ikan Koi',             'latin' => 'Cyprinus carpio (koi)',    'vol' => 200,   'sat' => 'ekor'],
            ['nama' => 'Lobster Air Tawar',    'latin' => 'Cherax quadricarinatus',   'vol' => 500,   'sat' => 'ekor'],
            ['nama' => 'Ikan Arwana Silver',   'latin' => 'Osteoglossum bicirrhosum', 'vol' => 50,    'sat' => 'ekor'],
            ['nama' => 'Kepiting Bakau',       'latin' => 'Scylla serrata',           'vol' => 1000,  'sat' => 'kg'],
            ['nama' => 'Kerang Mutiara',       'latin' => 'Pinctada maxima',          'vol' => 500,   'sat' => 'pcs'],
            ['nama' => 'Benih Ikan Mas',       'latin' => 'Cyprinus carpio',          'vol' => 50000, 'sat' => 'ekor'],
        ];
        return $this->buildKomoditasItems($items, 'I', $uptId, $seq);
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

<?php

namespace Database\Seeders;

use App\Models\HasilPemeriksaan;
use App\Models\SuratTugas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Dummy data untuk BBKHIT DKI Jakarta (kode UPT: 3100–3103).
 * Digunakan untuk keperluan pengujian dashboard & unduh laporan pimpinan.
 *
 * Jalankan: php artisan db:seed --class=DummyBBKHITDKISeeder
 */
class DummyBBKHITDKISeeder extends Seeder
{
    // ── Koordinat area Jakarta ─────────────────────────────────────────
    private const LOC = [
        '3101' => ['lat' => '-6.1088', 'long' => '106.8803', 'nama' => 'Pelabuhan Laut Tanjung Priok'],
        '3102' => ['lat' => '-6.1219', 'long' => '106.8103', 'nama' => 'Pelabuhan Sunda Kelapa'],
        '3103' => ['lat' => '-6.2662', 'long' => '106.8901', 'nama' => 'Bandara Halim Perdana Kusuma'],
        '3100' => ['lat' => '-6.2088', 'long' => '106.8456', 'nama' => 'BBKHIT DKI Jakarta – Kantor Induk'],
    ];

    public function run(): void
    {
        // ── Idempotency check ─────────────────────────────────────────
        if (SuratTugas::where('upt_id', '3101')->count() >= 5) {
            $this->command->warn('⚠  Data dummy BBKHIT DKI Jakarta sudah ada. Seeder dilewati.');
            return;
        }

        // ── Ambil user DKI Jakarta ────────────────────────────────────
        $koordinator = User::where('email', 'koordinator.dki@qofficer.barantin.go.id')->first();
        $pimpinan    = User::where('email', 'pimpinan@qofficer.barantin.go.id')->first();

        if (! $koordinator || ! $pimpinan) {
            $this->command->error('❌ User koordinator/pimpinan DKI tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $petugasList = User::whereIn('email', [
            'petugas1.dki@qofficer.barantin.go.id',
            'petugas2.dki@qofficer.barantin.go.id',
            'petugas3.dki@qofficer.barantin.go.id',
            'petugas4.dki@qofficer.barantin.go.id',
            'petugas5.dki@qofficer.barantin.go.id',
            'petugas6.dki@qofficer.barantin.go.id',
            'petugas7.dki@qofficer.barantin.go.id',
        ])->get();

        if ($petugasList->count() < 2) {
            $this->command->error('❌ Petugas DKI tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        DB::transaction(function () use ($koordinator, $pimpinan, $petugasList) {
            $this->seedSuratTugas($koordinator, $pimpinan, $petugasList);
        });

        $this->command->info('✅ Data dummy BBKHIT DKI Jakarta berhasil dibuat.');
    }

    // ─────────────────────────────────────────────────────────────────────
    private function seedSuratTugas($koordinator, $pimpinan, $petugasList): void
    {
        // ── Definisi surat tugas ──────────────────────────────────────
        // Tanggal relatif terhadap April 7, 2026 (hari ini saat seeder dibuat)
        $stDefs = [
            // ── Maret 2026 (selesai) ─────────────────────────────────
            [
                'no_st'   => 'B-001/BBKHIT.DKI/KH.100/03/2026',
                'tanggal' => '2026-03-07',
                'upt_id'  => '3101',
                'jk'      => 'H',
                'perihal' => 'Pemeriksaan Lalulitas Hewan di Pelabuhan Tanjung Priok',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Sapi', 'latin' => 'Bos taurus', 'vol' => 25.000, 'satuan' => 'Ekor']],
                'petugas' => [0, 1],
            ],
            [
                'no_st'   => 'B-002/BBKHIT.DKI/KT.200/03/2026',
                'tanggal' => '2026-03-10',
                'upt_id'  => '3101',
                'jk'      => 'T',
                'perihal' => 'Pemeriksaan Lalulitas Tumbuhan di Pelabuhan Tanjung Priok',
                'status'  => 'selesai',
                'komoditas' => [
                    ['nama' => 'Buah Segar', 'latin' => null, 'vol' => 5000.000, 'satuan' => 'Kg'],
                    ['nama' => 'Sayuran Segar', 'latin' => null, 'vol' => 3200.000, 'satuan' => 'Kg'],
                ],
                'petugas' => [2, 3],
            ],
            [
                'no_st'   => 'B-003/BBKHIT.DKI/KI.300/03/2026',
                'tanggal' => '2026-03-12',
                'upt_id'  => '3101',
                'jk'      => 'I',
                'perihal' => 'Pemeriksaan Lalulitas Ikan di Pelabuhan Tanjung Priok',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Ikan Hias', 'latin' => null, 'vol' => 1500.000, 'satuan' => 'Ekor']],
                'petugas' => [4, 5],
            ],
            [
                'no_st'   => 'B-004/BBKHIT.DKI/KH.100/03/2026',
                'tanggal' => '2026-03-15',
                'upt_id'  => '3102',
                'jk'      => 'H',
                'perihal' => 'Pemeriksaan Lalulitas Hewan di Pelabuhan Sunda Kelapa',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Kambing', 'latin' => 'Capra aegagrus hircus', 'vol' => 40.000, 'satuan' => 'Ekor']],
                'petugas' => [0, 2],
            ],
            [
                'no_st'   => 'B-005/BBKHIT.DKI/KT.200/03/2026',
                'tanggal' => '2026-03-18',
                'upt_id'  => '3102',
                'jk'      => 'T',
                'perihal' => 'Pemeriksaan Lalulitas Tumbuhan di Pelabuhan Sunda Kelapa',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Biji Kopi', 'latin' => 'Coffea arabica', 'vol' => 2000.000, 'satuan' => 'Kg']],
                'petugas' => [1, 3],
            ],
            [
                'no_st'   => 'B-006/BBKHIT.DKI/KI.300/03/2026',
                'tanggal' => '2026-03-20',
                'upt_id'  => '3103',
                'jk'      => 'I',
                'perihal' => 'Pemeriksaan Lalulitas Ikan di Bandara Halim Perdana Kusuma',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Udang', 'latin' => 'Penaeus vannamei', 'vol' => 800.000, 'satuan' => 'Kg']],
                'petugas' => [4, 6],
            ],
            [
                'no_st'   => 'B-007/BBKHIT.DKI/KH.100/03/2026',
                'tanggal' => '2026-03-22',
                'upt_id'  => '3103',
                'jk'      => 'H',
                'perihal' => 'Pemeriksaan Lalulitas Hewan di Bandara Halim Perdana Kusuma',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Anjing', 'latin' => 'Canis lupus familiaris', 'vol' => 5.000, 'satuan' => 'Ekor']],
                'petugas' => [0, 5],
            ],
            [
                'no_st'   => 'B-008/BBKHIT.DKI/KT.200/03/2026',
                'tanggal' => '2026-03-25',
                'upt_id'  => '3100',
                'jk'      => 'T',
                'perihal' => 'Pemeriksaan Lalulitas Tumbuhan di Kantor Induk BBKHIT DKI Jakarta',
                'status'  => 'selesai',
                'komoditas' => [
                    ['nama' => 'Tanaman Hias', 'latin' => null, 'vol' => 350.000, 'satuan' => 'Pot'],
                    ['nama' => 'Benih Padi', 'latin' => 'Oryza sativa', 'vol' => 500.000, 'satuan' => 'Kg'],
                ],
                'petugas' => [2, 4],
            ],
            [
                'no_st'   => 'B-009/BBKHIT.DKI/KH.100/03/2026',
                'tanggal' => '2026-03-28',
                'upt_id'  => '3101',
                'jk'      => 'H',
                'perihal' => 'Pemeriksaan Lalulitas Produk Hewan di Pelabuhan Tanjung Priok',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Produk Hewan', 'latin' => null, 'vol' => 4500.000, 'satuan' => 'Kg']],
                'petugas' => [1, 6],
            ],
            [
                'no_st'   => 'B-010/BBKHIT.DKI/KI.300/03/2026',
                'tanggal' => '2026-03-30',
                'upt_id'  => '3101',
                'jk'      => 'I',
                'perihal' => 'Pemeriksaan Lalulitas Produk Perikanan di Pelabuhan Tanjung Priok',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Ikan Konsumsi', 'latin' => null, 'vol' => 6000.000, 'satuan' => 'Kg']],
                'petugas' => [3, 5],
            ],
            // ── April 2026 – minggu 1 (selesai) ─────────────────────
            [
                'no_st'   => 'B-011/BBKHIT.DKI/KT.200/04/2026',
                'tanggal' => '2026-04-01',
                'upt_id'  => '3101',
                'jk'      => 'T',
                'perihal' => 'Pemeriksaan Lalulitas Tumbuhan di Pelabuhan Tanjung Priok',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Kayu Olahan', 'latin' => null, 'vol' => 12000.000, 'satuan' => 'M3']],
                'petugas' => [0, 3],
            ],
            [
                'no_st'   => 'B-012/BBKHIT.DKI/KH.100/04/2026',
                'tanggal' => '2026-04-02',
                'upt_id'  => '3102',
                'jk'      => 'H',
                'perihal' => 'Pemeriksaan Lalulitas Hewan di Pelabuhan Sunda Kelapa',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Babi', 'latin' => 'Sus scrofa', 'vol' => 18.000, 'satuan' => 'Ekor']],
                'petugas' => [1, 4],
            ],
            [
                'no_st'   => 'B-013/BBKHIT.DKI/KI.300/04/2026',
                'tanggal' => '2026-04-03',
                'upt_id'  => '3100',
                'jk'      => 'I',
                'perihal' => 'Pemeriksaan Lalulitas Ikan di Kantor Induk BBKHIT DKI Jakarta',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Kepiting', 'latin' => 'Scylla serrata', 'vol' => 300.000, 'satuan' => 'Kg']],
                'petugas' => [2, 6],
            ],
            [
                'no_st'   => 'B-014/BBKHIT.DKI/KT.200/04/2026',
                'tanggal' => '2026-04-05',
                'upt_id'  => '3103',
                'jk'      => 'T',
                'perihal' => 'Pemeriksaan Lalulitas Tumbuhan di Bandara Halim Perdana Kusuma',
                'status'  => 'selesai',
                'komoditas' => [['nama' => 'Biji Kakao', 'latin' => 'Theobroma cacao', 'vol' => 1800.000, 'satuan' => 'Kg']],
                'petugas' => [0, 5],
            ],
            // ── April 7, 2026 – hari ini (aktif) ────────────────────
            [
                'no_st'   => 'B-015/BBKHIT.DKI/KH.100/04/2026',
                'tanggal' => '2026-04-07',
                'upt_id'  => '3101',
                'jk'      => 'H',
                'perihal' => 'Pemeriksaan Lalulitas Hewan di Pelabuhan Tanjung Priok',
                'status'  => 'aktif',
                'komoditas' => [['nama' => 'Kuda', 'latin' => 'Equus caballus', 'vol' => 3.000, 'satuan' => 'Ekor']],
                'petugas' => [1, 3],
            ],
        ];

        foreach ($stDefs as $def) {
            $stId = (string) Str::uuid();

            // ── surat_tugas ───────────────────────────────────────────
            DB::table('surat_tugas')->insert([
                'id'                 => $stId,
                'ptk_id'             => null,
                'no_st'              => $def['no_st'],
                'tanggal'            => $def['tanggal'],
                'perihal'            => $def['perihal'],
                'dasar_hukum'        => 'Undang-Undang Nomor 21 Tahun 2019 tentang Karantina Hewan, Ikan, dan Tumbuhan; Peraturan Pemerintah Nomor 29 Tahun 2023 tentang Penyelenggaraan Karantina.',
                'nama_penandatangan' => 'Dr. Budi Santoso, M.Si',
                'nip_penandatangan'  => '197500000002',
                'status'             => $def['status'],
                'jenis_karantina'    => $def['jk'],
                'koordinator_id'     => $koordinator->id,
                'upt_id'             => $def['upt_id'],
                'link'               => null,
                'created_at'         => $def['tanggal'] . ' 08:00:00',
                'updated_at'         => $def['tanggal'] . ' 08:00:00',
            ]);

            // ── surat_tugas_petugas ───────────────────────────────────
            foreach ($def['petugas'] as $idx) {
                if (! isset($petugasList[$idx])) {
                    continue;
                }
                $petugas     = $petugasList[$idx];
                $diterimaTgl = date('Y-m-d H:i:s', strtotime($def['tanggal'] . ' +2 hours'));
                DB::table('surat_tugas_petugas')->insert([
                    'surat_tugas_id'    => $stId,
                    'petugas_id'        => $petugas->id,
                    'status_penerimaan' => 'diterima',
                    'diterima_at'       => $diterimaTgl,
                    'created_at'        => $diterimaTgl,
                    'updated_at'        => $diterimaTgl,
                ]);
            }

            // ── lokasi_st ─────────────────────────────────────────────
            $loc = self::LOC[$def['upt_id']];
            DB::table('lokasi_st')->insert([
                'surat_tugas_id' => $stId,
                'nama_lokasi'    => $loc['nama'],
                'lat'            => $loc['lat'],
                'long'           => $loc['long'],
                'detail_lokasi'  => 'Pemeriksaan fisik di lokasi ' . $loc['nama'] . ', DKI Jakarta.',
                'created_at'     => $def['tanggal'] . ' 08:00:00',
                'updated_at'     => $def['tanggal'] . ' 08:00:00',
            ]);

            // ── komoditas_st ──────────────────────────────────────────
            foreach ($def['komoditas'] as $kom) {
                DB::table('komoditas_st')->insert([
                    'surat_tugas_id'  => $stId,
                    'nama_komoditas'  => $kom['nama'],
                    'nama_latin'      => $kom['latin'],
                    'volume'          => $kom['vol'],
                    'satuan'          => $kom['satuan'],
                    'jenis_karantina' => $def['jk'],
                    'created_at'      => $def['tanggal'] . ' 08:00:00',
                    'updated_at'      => $def['tanggal'] . ' 08:00:00',
                ]);
            }

            // ── hasil_pemeriksaan & rekomendasi ───────────────────────
            $this->insertHasil($stId, $def, $koordinator, $petugasList);
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    private function insertHasil(string $stId, array $def, $koordinator, $petugasList): void
    {
        // ST aktif hari ini: hanya 2 hasil, belum ada rekomendasi
        if ($def['status'] === 'aktif') {
            $this->buatHasil($stId, $def, $petugasList[0], null, '09:30:00', false, null);
            $this->buatHasil($stId, $def, $petugasList[1], null, '11:15:00', false, null);
            return;
        }

        // Pemetaan skenario pemeriksaan per jenis karantina
        $skenario = $this->skenario($def['jk'], $def['no_st']);

        $jamMulai = 9;
        foreach ($skenario as $s) {
            $jam        = sprintf('%02d:%02d:00', $jamMulai, rand(0, 50));
            $tglPeriksa = $def['tanggal'];
            $petugasObj = $petugasList[$s['petugas_idx'] % $petugasList->count()];

            $hasilId = $this->buatHasil($stId, $def, $petugasObj, $s, $jam, true, $koordinator);
            $jamMulai += rand(1, 2);
        }
    }

    private function buatHasil(
        string $stId,
        array $def,
        $petugas,
        ?array $s,
        string $jam,
        bool $withRekomendasi,
        $koordinator
    ): string {
        $loc      = self::LOC[$def['upt_id']];
        $hasilId  = (string) Str::uuid();
        $tglPeriksa = $def['tanggal'] . ' ' . $jam;
        $komoditas  = $def['komoditas'][0]['nama']; // nama komoditas utama

        DB::table('hasil_pemeriksaan')->insert([
            'id'             => $hasilId,
            'id_surat_tugas' => $stId,
            'id_petugas'     => $petugas->id,
            'lat'            => (string)((float)$loc['lat']  + (rand(-5, 5) / 1000)),
            'long'           => (string)((float)$loc['long'] + (rand(-5, 5) / 1000)),
            'target'         => $s['target'] ?? null,
            'metode'         => $s['metode'] ?? null,
            'temuan'         => $s['temuan'] ?? null,
            'catatan'        => $s['catatan'] ?? null,
            'komoditas'      => $komoditas,
            'status_review'  => $withRekomendasi ? 'sudah_direview' : 'belum_direview',
            'tgl_periksa'    => $tglPeriksa,
            'created_at'     => $tglPeriksa,
            'updated_at'     => $tglPeriksa,
        ]);

        if ($withRekomendasi && $s !== null && $koordinator !== null) {
            $rekTgl = date('Y-m-d H:i:s', strtotime($tglPeriksa . ' +1 hour'));
            DB::table('rekomendasi_karantina')->insert([
                'id_hasil_pemeriksaan' => $hasilId,
                'koordinator_id'       => $koordinator->id,
                'tindakan'             => $s['tindakan'],
                'catatan'              => $s['catatan_rek'] ?? 'Pemeriksaan telah dilaksanakan sesuai prosedur.',
                'best_trust_status'    => 'success',
                'best_trust_response'  => null,
                'created_at'           => $rekTgl,
                'updated_at'           => $rekTgl,
            ]);
        }

        return $hasilId;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Skenario pemeriksaan realistis per jenis karantina.
    // no_st dipakai sebagai variasi agar skenario berbeda antar ST.
    // ─────────────────────────────────────────────────────────────────────
    private function skenario(string $jk, string $noSt): array
    {
        $hash = crc32($noSt); // angka unik deterministik per no_st

        // ── Hewan ──────────────────────────────────────────────────────
        if ($jk === 'H') {
            $pilihan = [
                // pelepasan bersih
                [
                    ['target' => 'Pemeriksaan fisik dan kesehatan hewan', 'metode' => 'Pemeriksaan klinis dan visual', 'temuan' => 'Negatif (Tidak Ditemukan Penyakit)', 'catatan' => 'Kondisi hewan baik, dokumen lengkap.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Hewan memenuhi syarat karantina. Direkomendasikan pelepasan.', 'petugas_idx' => 0],
                    ['target' => 'Pemeriksaan dokumen kesehatan', 'metode' => 'Verifikasi dokumen', 'temuan' => 'Negatif (Tidak Ditemukan Penyakit)', 'catatan' => 'Sertifikat kesehatan hewan valid.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Dokumen lengkap dan valid. Direkomendasikan pelepasan.', 'petugas_idx' => 1],
                ],
                // ada penyakit — penolakan
                [
                    ['target' => 'Pemeriksaan fisik dan kesehatan hewan', 'metode' => 'Pemeriksaan klinis dan uji laboratorium', 'temuan' => 'Positif PMK (Penyakit Mulut dan Kuku)', 'catatan' => 'Ditemukan lesi pada kaki hewan, terduga PMK.', 'tindakan' => 'penolakan', 'catatan_rek' => 'Ditemukan indikasi PMK. Hewan ditolak masuk dan dikembalikan ke daerah asal.', 'petugas_idx' => 2],
                    ['target' => 'Pemeriksaan fisik dan kesehatan hewan', 'metode' => 'Pemeriksaan organoleptik', 'temuan' => 'Negatif (Tidak Ditemukan Penyakit)', 'catatan' => 'Hewan lain dalam kondisi sehat.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Hewan yang sehat diperbolehkan masuk setelah isolasi.', 'petugas_idx' => 3],
                ],
                // perlakuan karantina
                [
                    ['target' => 'Pemeriksaan fisik dan kesehatan hewan', 'metode' => 'Pemeriksaan klinis', 'temuan' => 'Positif Brucellosis', 'catatan' => 'Hasil uji darah menunjukkan reaksi positif Brucella.', 'tindakan' => 'perlakuan', 'catatan_rek' => 'Hewan perlu perlakuan karantina. Diisolasi untuk pengobatan dan pemantauan.', 'petugas_idx' => 0],
                ],
                // dokumen tidak lengkap — pemusnahan terhadap produk
                [
                    ['target' => 'Verifikasi dokumen dan pemeriksaan fisik', 'metode' => 'Pemeriksaan dokumen dan visual', 'temuan' => 'Dokumen Tidak Lengkap', 'catatan' => 'Tidak dilengkapi sertifikat veteriner asal daerah.', 'tindakan' => 'penolakan', 'catatan_rek' => 'Dokumen tidak lengkap. Kiriman dikembalikan ke pengirim.', 'petugas_idx' => 1],
                    ['target' => 'Pemeriksaan fisik hewan', 'metode' => 'Pemeriksaan organoleptik', 'temuan' => 'Negatif (Tidak Ditemukan Penyakit)', 'catatan' => 'Hewan dalam kondisi baik meski dokumen kurang.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Dokumen dilengkapi di tempat. Direkomendasikan pelepasan.', 'petugas_idx' => 2],
                ],
            ];
            return $pilihan[abs($hash) % count($pilihan)];
        }

        // ── Tumbuhan ───────────────────────────────────────────────────
        if ($jk === 'T') {
            $pilihan = [
                [
                    ['target' => 'Deteksi Organisme Pengganggu Tumbuhan Karantina (OPTK)', 'metode' => 'Pemeriksaan visual dan uji laboratorium', 'temuan' => 'Negatif (Tidak Ditemukan OPTK)', 'catatan' => 'Sampel bebas OPTK. Dokumen fitosanitari valid.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Tumbuhan bebas OPTK. Direkomendasikan pelepasan.', 'petugas_idx' => 0],
                    ['target' => 'Verifikasi dokumen phytosanitary', 'metode' => 'Verifikasi dokumen', 'temuan' => 'Negatif (Tidak Ditemukan OPTK)', 'catatan' => 'Dokumen phytosanitary certificate sesuai.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Dokumen lengkap. Direkomendasikan pelepasan.', 'petugas_idx' => 1],
                ],
                [
                    ['target' => 'Deteksi OPTK pada buah/sayur', 'metode' => 'Pemeriksaan visual dan intersepsi', 'temuan' => 'Positif Hama Lalat Buah', 'catatan' => 'Ditemukan larva Bactrocera sp. pada sampel buah.', 'tindakan' => 'perlakuan', 'catatan_rek' => 'Kiriman mendapat perlakuan fumigasi sebelum pelepasan.', 'petugas_idx' => 2],
                    ['target' => 'Verifikasi identitas dan volume', 'metode' => 'Pemeriksaan fisik dan timbang', 'temuan' => 'Negatif (Tidak Ditemukan OPTK)', 'catatan' => 'Volume sesuai manifes. Kondisi baik.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Komoditas memenuhi syarat. Direkomendasikan pelepasan.', 'petugas_idx' => 3],
                ],
                [
                    ['target' => 'Deteksi jamur dan patogen tanaman', 'metode' => 'Uji laboratorium mikologi', 'temuan' => 'Positif Jamur Patogen', 'catatan' => 'Terdeteksi Fusarium sp. pada sampel benih.', 'tindakan' => 'pemusnahan', 'catatan_rek' => 'Benih terinfeksi jamur patogen. Tidak dapat diperlakukan. Direkomendasikan pemusnahan.', 'petugas_idx' => 0],
                ],
                [
                    ['target' => 'Pemeriksaan benih dan label SNI', 'metode' => 'Verifikasi dokumen dan visual', 'temuan' => 'Benih Tanpa Label SNI', 'catatan' => 'Benih tidak memiliki label standar nasional Indonesia.', 'tindakan' => 'penolakan', 'catatan_rek' => 'Benih tidak memenuhi standar. Dikembalikan ke pengirim.', 'petugas_idx' => 1],
                    ['target' => 'Deteksi OPTK pada benih', 'metode' => 'Uji laboratorium', 'temuan' => 'Negatif (Tidak Ditemukan OPTK)', 'catatan' => 'Tidak ditemukan OPTK meski label tidak sesuai.', 'tindakan' => 'penolakan', 'catatan_rek' => 'Penolakan karena ketidaksesuaian dokumen/label.', 'petugas_idx' => 2],
                ],
            ];
            return $pilihan[abs($hash) % count($pilihan)];
        }

        // ── Ikan ───────────────────────────────────────────────────────
        $pilihan = [
            [
                ['target' => 'Deteksi Hama Penyakit Ikan Karantina (HPIK)', 'metode' => 'Pemeriksaan klinis dan uji PCR', 'temuan' => 'Negatif (Tidak Ditemukan HPIK)', 'catatan' => 'Ikan sehat, kondisi baik, tidak ditemukan HPIK.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Ikan bebas HPIK. Direkomendasikan pelepasan.', 'petugas_idx' => 0],
            ],
            [
                ['target' => 'Deteksi HPIK pada udang', 'metode' => 'Uji PCR dan histopatologi', 'temuan' => 'Positif White Spot Syndrome Virus', 'catatan' => 'Hasil PCR konfirmasi WSSV positif pada 3 sampel.', 'tindakan' => 'pemusnahan', 'catatan_rek' => 'Udang terinfeksi WSSV. Direkomendasikan pemusnahan segera.', 'petugas_idx' => 1],
                ['target' => 'Pemeriksaan dokumentasi dan kemasan', 'metode' => 'Verifikasi dokumen', 'temuan' => 'Negatif (Tidak Ditemukan HPIK)', 'catatan' => 'Dokumen SPF (Specific Pathogen Free) valid.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Batch lain bebas HPIK. Direkomendasikan pelepasan.', 'petugas_idx' => 2],
            ],
            [
                ['target' => 'Deteksi KHV pada ikan koi', 'metode' => 'Uji PCR Koi Herpesvirus', 'temuan' => 'Positif Koi Herpes Virus', 'catatan' => 'KHV terdeteksi pada 2 dari 10 sampel ikan koi.', 'tindakan' => 'penolakan', 'catatan_rek' => 'Ikan terinfeksi KHV. Seluruh kiriman ditolak.', 'petugas_idx' => 3],
            ],
            [
                ['target' => 'Pemeriksaan fisik dan dokumen ikan konsumsi', 'metode' => 'Pemeriksaan organoleptik', 'temuan' => 'Negatif (Tidak Ditemukan HPIK)', 'catatan' => 'Ikan segar dalam kondisi layak konsumsi.', 'tindakan' => 'pelepasan', 'catatan_rek' => 'Produk memenuhi syarat karantina.', 'petugas_idx' => 0],
                ['target' => 'Verifikasi sertifikat kesehatan ikan', 'metode' => 'Verifikasi dokumen', 'temuan' => 'Dokumen Tidak Lengkap', 'catatan' => 'Sertifikat health certificate tidak terlampir.', 'tindakan' => 'perlakuan', 'catatan_rek' => 'Penahanan sementara hingga dokumen dilengkapi.', 'petugas_idx' => 1],
            ],
        ];
        return $pilihan[abs($hash) % count($pilihan)];
    }
}

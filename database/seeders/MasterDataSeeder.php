<?php

namespace Database\Seeders;

use App\Models\MasterTarget;
use App\Models\MasterTemuan;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Master Target ─────────────────────────────────────────────
        $targets = [
            // Hewan (H)
            ['nama' => 'Sapi',             'jenis_karantina' => 'H'],
            ['nama' => 'Babi',             'jenis_karantina' => 'H'],
            ['nama' => 'Ayam',             'jenis_karantina' => 'H'],
            ['nama' => 'Kambing',          'jenis_karantina' => 'H'],
            ['nama' => 'Anjing',           'jenis_karantina' => 'H'],
            ['nama' => 'Kucing',           'jenis_karantina' => 'H'],
            ['nama' => 'Kuda',             'jenis_karantina' => 'H'],
            ['nama' => 'Produk Hewan',     'jenis_karantina' => 'H'],
            // Tumbuhan (T)
            ['nama' => 'Benih Padi',       'jenis_karantina' => 'T'],
            ['nama' => 'Benih Jagung',     'jenis_karantina' => 'T'],
            ['nama' => 'Buah Segar',       'jenis_karantina' => 'T'],
            ['nama' => 'Sayuran Segar',    'jenis_karantina' => 'T'],
            ['nama' => 'Kayu Olahan',      'jenis_karantina' => 'T'],
            ['nama' => 'Tanaman Hias',     'jenis_karantina' => 'T'],
            ['nama' => 'Biji Kopi',        'jenis_karantina' => 'T'],
            ['nama' => 'Biji Kakao',       'jenis_karantina' => 'T'],
            // Ikan (I)
            ['nama' => 'Ikan Hias',        'jenis_karantina' => 'I'],
            ['nama' => 'Ikan Konsumsi',    'jenis_karantina' => 'I'],
            ['nama' => 'Udang',            'jenis_karantina' => 'I'],
            ['nama' => 'Kepiting',         'jenis_karantina' => 'I'],
            ['nama' => 'Kerang',           'jenis_karantina' => 'I'],
            ['nama' => 'Produk Perikanan', 'jenis_karantina' => 'I'],
        ];

        foreach ($targets as $t) {
            MasterTarget::firstOrCreate($t, array_merge($t, ['is_active' => true]));
        }

        // ── Master Temuan ─────────────────────────────────────────────
        $temuan = [
            // Hewan (H)
            ['nama' => 'Negatif (Tidak Ditemukan Penyakit)', 'jenis_karantina' => 'H'],
            ['nama' => 'Positif PMK (Penyakit Mulut dan Kuku)', 'jenis_karantina' => 'H'],
            ['nama' => 'Positif Brucellosis',                'jenis_karantina' => 'H'],
            ['nama' => 'Positif Rabies',                     'jenis_karantina' => 'H'],
            ['nama' => 'Positif AI (Avian Influenza)',        'jenis_karantina' => 'H'],
            ['nama' => 'Dokumen Tidak Lengkap',              'jenis_karantina' => 'H'],
            // Tumbuhan (T)
            ['nama' => 'Negatif (Tidak Ditemukan OPTK)',     'jenis_karantina' => 'T'],
            ['nama' => 'Positif Hama Lalat Buah',            'jenis_karantina' => 'T'],
            ['nama' => 'Positif Jamur Patogen',              'jenis_karantina' => 'T'],
            ['nama' => 'Positif Kutu Putih',                 'jenis_karantina' => 'T'],
            ['nama' => 'Benih Tanpa Label SNI',              'jenis_karantina' => 'T'],
            ['nama' => 'Dokumen Tidak Lengkap',              'jenis_karantina' => 'T'],
            // Ikan (I)
            ['nama' => 'Negatif (Tidak Ditemukan HPIK)',     'jenis_karantina' => 'I'],
            ['nama' => 'Positif White Spot Syndrome Virus',  'jenis_karantina' => 'I'],
            ['nama' => 'Positif Koi Herpes Virus',           'jenis_karantina' => 'I'],
            ['nama' => 'Positif Viral Nervous Necrosis',     'jenis_karantina' => 'I'],
            ['nama' => 'Dokumen Tidak Lengkap',              'jenis_karantina' => 'I'],
        ];

        foreach ($temuan as $t) {
            MasterTemuan::firstOrCreate($t, array_merge($t, ['is_active' => true]));
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomoditasSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ═══════════════════ HEWAN (kategori_id = 1) ═══════════════════
        $hewan = [
            'Sapi', 'Kambing', 'Domba', 'Ayam', 'Itik', 'Burung', 'Babi',
            'Kelinci', 'Telur', 'Daging Sapi', 'Daging Ayam', 'Daging Beku',
            'Susu', 'Produk Susu', 'Kulit Hewan', 'Pakan Hewan', 'Bibit Ternak',
            'Hewan Hidup', 'Produk Hewan Olahan', 'Karkas Hewan',
        ];
        foreach ($hewan as $nama) {
            DB::table('komoditas')->insertOrIgnore([
                'kategori_id' => 1, 'nama' => $nama,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // ═══════════════════ IKAN (kategori_id = 2) ════════════════════
        $ikan = [
            'Ikan Hidup', 'Ikan Segar', 'Ikan Beku', 'Ikan Asin', 'Udang',
            'Kepiting', 'Lobster', 'Kerang', 'Cumi-cumi', 'Gurita',
            'Benih Ikan', 'Pakan Ikan', 'Produk Olahan Ikan', 'Fillet Ikan',
            'Ikan Kaleng', 'Ikan Hias', 'Rumput Laut', 'Teripang',
            'Telur Ikan', 'Produk Perikanan Lainnya',
        ];
        foreach ($ikan as $nama) {
            DB::table('komoditas')->insertOrIgnore([
                'kategori_id' => 2, 'nama' => $nama,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // ═══════════════════ TUMBUHAN (kategori_id = 3) ════════════════
        $tumbuhan = [
            'Buah', 'Sayur', 'Bibit Tanaman', 'Tanaman Hias', 'Biji-bijian',
            'Padi', 'Jagung', 'Kedelai', 'Kacang-kacangan', 'Rempah-rempah',
            'Kayu', 'Produk Olahan Tumbuhan', 'Daun', 'Akar', 'Umbi-umbian',
            'Tanaman Perkebunan', 'Tanaman Pangan', 'Pupuk Organik',
            'Media Tanam', 'Produk Hortikultura',
        ];
        foreach ($tumbuhan as $nama) {
            DB::table('komoditas')->insertOrIgnore([
                'kategori_id' => 3, 'nama' => $nama,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
}

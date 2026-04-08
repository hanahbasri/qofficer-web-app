<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomoditasKategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('komoditas_kategori')->insertOrIgnore([
            ['id' => 1, 'nama' => 'Hewan',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Ikan',     'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'Tumbuhan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

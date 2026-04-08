<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'petugas-lapangan', 'display_name' => 'Petugas Lapangan'],
            ['name' => 'koordinator-upt',  'display_name' => 'Koordinator UPT'],
            ['name' => 'pimpinan',         'display_name' => 'Pimpinan'],
            ['name' => 'super-admin',      'display_name' => 'Super Admin'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}

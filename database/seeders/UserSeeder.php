<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('id', 'name');

        $users = [
            // Super Admin
            [
                'nip'      => '199000000001',
                'nama'     => 'Super Admin Sistem',
                'email'    => 'admin@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => null,
                'role_id'  => $roles['super-admin'],
                'is_active'=> true,
            ],
            // ── PIMPINAN KANTOR PUSAT (NASIONAL) ──
            // Kepala Badan
            [
                'nip'      => '196500000081',
                'nama'     => 'Dr. H. Susilo Bambang Yudhoyono, M.Sc.',
                'email'    => 'kepala.badan@karantinaindonesia.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/e',
                'pangkat'  => 'Pembina Utama',
                'is_active'=> true,
            ],
            // Sekretariat Utama
            [
                'nip'      => '196800000082',
                'nama'     => 'Dr. Ir. Bambang Setiawan, M.M.',
                'email'    => 'sekretariat.utama@karantinaindonesia.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/c',
                'pangkat'  => 'Pembina Tingkat I',
                'is_active'=> true,
            ],
            // Deputi Karantina Hewan
            [
                'nip'      => '196200000083',
                'nama'     => 'Prof. Dr. Slamet Wijaya Kusuma, S.V., M.V.Sc.',
                'email'    => 'deputi.hewan@karantinaindonesia.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/c',
                'pangkat'  => 'Pembina Tingkat I',
                'is_active'=> true,
            ],
            // Deputi Karantina Ikan
            [
                'nip'      => '196405000084',
                'nama'     => 'Dr. Ir. Rahma Handayani, M.P.',
                'email'    => 'deputi.ikan@karantinaindonesia.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/c',
                'pangkat'  => 'Pembina Tingkat I',
                'is_active'=> true,
            ],
            // Deputi Karantina Tumbuhan
            [
                'nip'      => '196610000085',
                'nama'     => 'Dr. Ir. Eka Wardhani, M.Si.',
                'email'    => 'deputi.tumbuhan@karantinaindonesia.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/c',
                'pangkat'  => 'Pembina Tingkat I',
                'is_active'=> true,
            ],
            // ── PIMPINAN UPT (BALAI) ──
            // Pimpinan DKI
            [
                'nip'      => '197500000002',
                'nama'     => 'Dr. Budi Santoso, M.Si',
                'email'    => 'pimpinan@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['pimpinan'],
                'is_active'=> true,
            ],
            // Koordinator UPT DKI
            [
                'nip'      => '198000000003',
                'nama'     => 'Sari Dewi, S.P.',
                'email'    => 'koordinator.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['koordinator-upt'],
                'golongan' => 'III/c',
                'pangkat'  => 'Penata',
                'is_active'=> true,
            ],
            // Petugas Lapangan 1 — DKI
            [
                'nip'      => '199500000004',
                'nama'     => 'Ahmad Fauzi, A.Md.',
                'email'    => 'petugas1.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
            // Petugas Lapangan 2 — DKI
            [
                'nip'      => '199600000005',
                'nama'     => 'Rina Marlina, A.Md.',
                'email'    => 'petugas2.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/c',
                'pangkat'  => 'Pengatur',
                'is_active'=> true,
            ],
            // Koordinator UPT Surabaya
            [
                'nip'      => '198200000006',
                'nama'     => 'Hendra Wijaya, S.P.',
                'email'    => 'koordinator.sby@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3500',
                'role_id'  => $roles['koordinator-upt'],
                'golongan' => 'III/b',
                'pangkat'  => 'Penata Muda Tk.I',
                'is_active'=> true,
            ],
            // Petugas Lapangan — Surabaya
            [
                'nip'      => '199800000007',
                'nama'     => 'Dian Pratiwi, A.Md.',
                'email'    => 'petugas1.sby@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3500',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/b',
                'pangkat'  => 'Pengatur Muda Tk.I',
                'is_active'=> true,
            ],
            // ── PETUGAS LAPANGAN BBKHIT DKI JAKARTA (Tambahan) ──
            // Petugas 3 — DKI
            [
                'nip'      => '199700000010',
                'nama'     => 'Bambang Sutrisno, A.Md.',
                'email'    => 'petugas3.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
            // Petugas 4 — DKI
            [
                'nip'      => '199800000011',
                'nama'     => 'Sri Handayani, S.P.',
                'email'    => 'petugas4.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/c',
                'pangkat'  => 'Pengatur',
                'is_active'=> true,
            ],
            // Petugas 5 — DKI
            [
                'nip'      => '199900000012',
                'nama'     => 'Dwi Hermawan, A.Md.',
                'email'    => 'petugas5.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/b',
                'pangkat'  => 'Pengatur Muda Tk.I',
                'is_active'=> true,
            ],
            // Petugas 6 — DKI
            [
                'nip'      => '200000000013',
                'nama'     => 'Nurul Azizah, A.Md.',
                'email'    => 'petugas6.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
            // Petugas 7 — DKI
            [
                'nip'      => '200100000014',
                'nama'     => 'Yudi Prasetyo, A.Md.',
                'email'    => 'petugas7.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/c',
                'pangkat'  => 'Pengatur',
                'is_active'=> true,
            ],
            // Petugas 8 — DKI
            [
                'nip'      => '200200000015',
                'nama'     => 'Linda Suryatni, S.P.',
                'email'    => 'petugas8.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/b',
                'pangkat'  => 'Pengatur Muda Tk.I',
                'is_active'=> true,
            ],
            // Petugas 9 — DKI
            [
                'nip'      => '200300000016',
                'nama'     => 'Rexon Adrianto, A.Md.',
                'email'    => 'petugas9.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
            // Petugas 10 — DKI
            [
                'nip'      => '200400000017',
                'nama'     => 'Siti Nursyamsi, A.Md.',
                'email'    => 'petugas10.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/c',
                'pangkat'  => 'Pengatur',
                'is_active'=> true,
            ],
            // Petugas 11 — DKI
            [
                'nip'      => '200500000018',
                'nama'     => 'Supratno Wijaya, S.P.',
                'email'    => 'petugas11.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/b',
                'pangkat'  => 'Pengatur Muda Tk.I',
                'is_active'=> true,
            ],
            // Petugas 12 — DKI
            [
                'nip'      => '200600000019',
                'nama'     => 'Riza Puspitasari, A.Md.',
                'email'    => 'petugas12.dki@qofficer.barantin.go.id',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['nip' => $userData['nip']], $userData);
        }
    }
}

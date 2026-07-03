<?php

namespace Database\Seeders;

use App\Models\SystemLog;
use App\Models\Upt;
use App\Models\User;
use Illuminate\Database\Seeder;

class SystemLogSeeder extends Seeder
{
    public function run(): void
    {
        if (SystemLog::count() > 0) {
            $this->command?->warn('Log sistem sudah berisi data. Seeder dilewati.');
            return;
        }

        $admin = User::whereHas('role', fn($q) => $q->where('name', 'super-admin'))->first();

        if (! $admin) {
            $this->command?->error('Super admin tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $koordinator = User::whereHas('role', fn($q) => $q->where('name', 'koordinator-upt'))->first();
        $petugas = User::whereHas('role', fn($q) => $q->where('name', 'petugas-lapangan'))->first();
        $pimpinan = User::whereHas('role', fn($q) => $q->where('name', 'pimpinan'))->first();
        $uptDki = Upt::find('3100') ?? Upt::first();
        $uptBali = Upt::find('5100') ?? Upt::orderBy('kode')->skip(1)->first() ?? $uptDki;

        $logs = [
            [
                'module' => 'autentikasi',
                'action' => 'login',
                'subject_type' => 'User',
                'subject_id' => (string) $admin->id,
                'description' => 'Login ke dashboard super admin.',
                'properties' => ['role' => $admin->role?->display_name ?? 'Super Admin'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDays(4)->setTime(8, 15),
                'updated_at' => now()->subDays(4)->setTime(8, 15),
            ],
            [
                'module' => 'pengguna',
                'action' => 'tambah',
                'subject_type' => 'User',
                'subject_id' => (string) ($petugas?->id ?? $admin->id),
                'description' => 'Menambahkan pengguna ' . ($petugas?->nama ?? 'Petugas Lapangan') . '.',
                'properties' => [
                    'nip' => $petugas?->nip ?? '199503152018041004',
                    'email' => $petugas?->email ?? 'ahmad.fauzi@karantinaindonesia.go.id',
                    'role' => $petugas?->role?->display_name ?? 'Petugas Lapangan',
                    'upt' => $petugas?->upt?->nama ?? ($uptDki?->nama ?? 'BBKHIT DKI Jakarta'),
                    'status' => 'Aktif',
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDays(4)->setTime(8, 33),
                'updated_at' => now()->subDays(4)->setTime(8, 33),
            ],
            [
                'module' => 'role',
                'action' => 'atur-role',
                'subject_type' => 'User',
                'subject_id' => (string) ($koordinator?->id ?? $admin->id),
                'description' => 'Mengubah role pengguna ' . ($koordinator?->nama ?? 'Koordinator UPT') . '.',
                'properties' => [
                    'role_lama' => 'Petugas Lapangan',
                    'role_baru' => $koordinator?->role?->display_name ?? 'Koordinator UPT',
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDays(4)->setTime(10, 15),
                'updated_at' => now()->subDays(4)->setTime(10, 15),
            ],
            [
                'module' => 'pengguna',
                'action' => 'ubah',
                'subject_type' => 'User',
                'subject_id' => (string) ($pimpinan?->id ?? $admin->id),
                'description' => 'Memperbarui data pengguna ' . ($pimpinan?->nama ?? 'Pimpinan UPT') . '.',
                'properties' => [
                    'nama_lama' => $pimpinan?->nama ?? 'Pimpinan UPT',
                    'nama_baru' => $pimpinan?->nama ?? 'Pimpinan UPT',
                    'email_lama' => $pimpinan?->email ?? 'budi.santoso@karantinaindonesia.go.id',
                    'email_baru' => $pimpinan?->email ?? 'budi.santoso@karantinaindonesia.go.id',
                    'role_lama' => $pimpinan?->role?->display_name ?? 'Pimpinan',
                    'role_baru' => $pimpinan?->role?->display_name ?? 'Pimpinan',
                    'upt_lama' => $pimpinan?->upt?->nama ?? ($uptDki?->nama ?? 'BBKHIT DKI Jakarta'),
                    'upt_baru' => $pimpinan?->upt?->nama ?? ($uptDki?->nama ?? 'BBKHIT DKI Jakarta'),
                    'password_diubah' => 'Tidak',
                ],
                'ip_address' => '10.8.0.12',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDays(3)->setTime(8, 50),
                'updated_at' => now()->subDays(3)->setTime(8, 50),
            ],
            [
                'module' => 'pengguna',
                'action' => 'nonaktifkan',
                'subject_type' => 'User',
                'subject_id' => (string) ($petugas?->id ?? $admin->id),
                'description' => 'Dinonaktifkan akun ' . ($petugas?->nama ?? 'Petugas Lapangan') . '.',
                'properties' => [
                    'nip' => $petugas?->nip ?? '199503152018041004',
                    'status_lama' => 'Aktif',
                    'status_baru' => 'Nonaktif',
                ],
                'ip_address' => '10.8.0.12',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDays(3)->setTime(10, 5),
                'updated_at' => now()->subDays(3)->setTime(10, 5),
            ],
            [
                'module' => 'pengguna',
                'action' => 'aktifkan',
                'subject_type' => 'User',
                'subject_id' => (string) ($petugas?->id ?? $admin->id),
                'description' => 'Diaktifkan akun ' . ($petugas?->nama ?? 'Petugas Lapangan') . '.',
                'properties' => [
                    'nip' => $petugas?->nip ?? '199503152018041004',
                    'status_lama' => 'Nonaktif',
                    'status_baru' => 'Aktif',
                ],
                'ip_address' => '10.8.0.12',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDays(3)->setTime(12, 0),
                'updated_at' => now()->subDays(3)->setTime(12, 0),
            ],
            [
                'module' => 'upt',
                'action' => 'tambah',
                'subject_type' => 'Upt',
                'subject_id' => (string) ($uptBali?->kode ?? '5100'),
                'description' => 'Menambahkan UPT ' . ($uptBali?->nama ?? 'BBKHIT Bali') . '.',
                'properties' => [
                    'kode' => $uptBali?->kode ?? '5100',
                    'alias' => $uptBali?->alias ?? 'BBKHIT Bali',
                    'wilayah' => $uptBali?->wilayah ?? 'Provinsi Bali',
                ],
                'ip_address' => '10.8.0.15',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDays(2)->setTime(8, 25),
                'updated_at' => now()->subDays(2)->setTime(8, 25),
            ],
            [
                'module' => 'upt',
                'action' => 'ubah',
                'subject_type' => 'Upt',
                'subject_id' => (string) ($uptDki?->kode ?? '3100'),
                'description' => 'Memperbarui data UPT ' . ($uptDki?->nama ?? 'BBKHIT DKI Jakarta') . '.',
                'properties' => [
                    'nama_lama' => $uptDki?->nama ?? 'BBKHIT DKI Jakarta',
                    'nama_baru' => $uptDki?->nama ?? 'BBKHIT DKI Jakarta',
                    'alias_lama' => $uptDki?->alias ?? 'BBKHIT DKI Jakarta',
                    'alias_baru' => $uptDki?->alias ?? 'BBKHIT DKI Jakarta',
                    'wilayah_lama' => $uptDki?->wilayah ?? 'Provinsi DKI Jakarta',
                    'wilayah_baru' => $uptDki?->wilayah ?? 'Provinsi DKI Jakarta',
                ],
                'ip_address' => '10.8.0.15',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDays(2)->setTime(10, 0),
                'updated_at' => now()->subDays(2)->setTime(10, 0),
            ],
            [
                'module' => 'profil',
                'action' => 'ubah',
                'subject_type' => 'User',
                'subject_id' => (string) $admin->id,
                'description' => 'Memperbarui profil super admin.',
                'properties' => [
                    'nama_lama' => $admin->nama,
                    'nama_baru' => $admin->nama,
                    'email_lama' => $admin->email,
                    'email_baru' => $admin->email,
                    'golongan_lama' => $admin->golongan ?? '-',
                    'golongan_baru' => $admin->golongan ?? '-',
                    'pangkat_lama' => $admin->pangkat ?? '-',
                    'pangkat_baru' => $admin->pangkat ?? '-',
                    'password_diubah' => 'Ya',
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDay()->setTime(9, 10),
                'updated_at' => now()->subDay()->setTime(9, 10),
            ],
            [
                'module' => 'autentikasi',
                'action' => 'logout',
                'subject_type' => 'User',
                'subject_id' => (string) $admin->id,
                'description' => 'Logout dari dashboard super admin.',
                'properties' => ['role' => $admin->role?->display_name ?? 'Super Admin'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/135.0 Q-Officer',
                'created_at' => now()->subDay()->setTime(17, 15),
                'updated_at' => now()->subDay()->setTime(17, 15),
            ],
        ];

        foreach ($logs as $log) {
            SystemLog::create(array_merge($log, [
                'user_id' => $admin->id,
            ]));
        }

        $this->command?->info('Contoh log sistem berhasil dibuat.');
    }
}

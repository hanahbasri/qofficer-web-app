<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Upt;
use App\Models\User;
use App\Support\PasswordPolicyService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /** Pool nama untuk generator staf UPT. */
    private const FIRST_M = [
        'Agus', 'Budi', 'Andi', 'Rizki', 'Fajar', 'Hendra', 'Dwi', 'Eko', 'Bayu', 'Yoga',
        'Irfan', 'Wahyu', 'Teguh', 'Rahmat', 'Dedi', 'Arif', 'Gunawan', 'Hadi', 'Joko', 'Slamet',
        'Bagus', 'Faisal', 'Rudi', 'Taufik', 'Yusuf',
    ];
    private const FIRST_F = [
        'Siti', 'Dewi', 'Rina', 'Ayu', 'Putri', 'Nur', 'Indah', 'Sri', 'Fitri', 'Wulan',
        'Ratna', 'Lestari', 'Maya', 'Dian', 'Anisa', 'Rahma', 'Yuni', 'Citra', 'Novi', 'Intan',
        'Mega', 'Sari', 'Vina', 'Wati', 'Tari',
    ];
    private const LAST = [
        'Santoso', 'Wijaya', 'Pratama', 'Nugroho', 'Saputra', 'Hidayat', 'Kurniawan', 'Halim',
        'Setiawan', 'Permana', 'Utomo', 'Firmansyah', 'Ramadhan', 'Maulana', 'Anggraini', 'Puspita',
        'Handayani', 'Wibowo', 'Purnama', 'Cahyani', 'Kusuma', 'Mahendra', 'Syahputra', 'Oktaviani',
    ];

    public function run(): void
    {
        $roles = Role::pluck('id', 'name');
        $usedEmails = [];

        // ── User eksplisit (dipertahankan agar DummyBBKHITDKISeeder tetap valid) ──
        $users = [
            // Super Admin
            [
                'nip'      => '199001152015041001',
                'nama'     => 'Super Admin Sistem',
                'password' => Hash::make('password'),
                'upt_id'   => null,
                'role_id'  => $roles['super-admin'],
                'is_active'=> true,
            ],
            // ── PIMPINAN KANTOR PUSAT (NASIONAL) ──
            [
                'nip'      => '196507201990031081',
                'nama'     => 'Dr. H. Susilo Bambang Yudhoyono, M.Sc.',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/e',
                'pangkat'  => 'Pembina Utama',
                'is_active'=> true,
            ],
            [
                'nip'      => '196805101993031082',
                'nama'     => 'Dr. Ir. Bambang Setiawan, M.M.',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/c',
                'pangkat'  => 'Pembina Tingkat I',
                'is_active'=> true,
            ],
            [
                'nip'      => '196203251988021083',
                'nama'     => 'Prof. Dr. Slamet Wijaya Kusuma, S.V., M.V.Sc.',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/c',
                'pangkat'  => 'Pembina Tingkat I',
                'is_active'=> true,
            ],
            [
                'nip'      => '196405121990032084',
                'nama'     => 'Dr. Ir. Rahma Handayani, M.P.',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/c',
                'pangkat'  => 'Pembina Tingkat I',
                'is_active'=> true,
            ],
            [
                'nip'      => '196610081992032085',
                'nama'     => 'Dr. Ir. Eka Wardhani, M.Si.',
                'password' => Hash::make('password'),
                'upt_id'   => '1000',
                'role_id'  => $roles['pimpinan'],
                'golongan' => 'IV/c',
                'pangkat'  => 'Pembina Tingkat I',
                'is_active'=> true,
            ],
            // ── BBKHIT DKI JAKARTA (3100) — staf eksplisit ──
            [
                'nip'      => '197506152000041002',
                'nama'     => 'Dr. Budi Santoso, M.Si',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['pimpinan'],
                'is_active'=> true,
            ],
            [
                'nip'      => '198008222005042003',
                'nama'     => 'Sari Dewi, S.P.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['koordinator-upt'],
                'golongan' => 'III/c',
                'pangkat'  => 'Penata',
                'is_active'=> true,
            ],
            [
                'nip'      => '199503152018041004',
                'nama'     => 'Ahmad Fauzi, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
            [
                'nip'      => '199609052019042005',
                'nama'     => 'Rina Marlina, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/c',
                'pangkat'  => 'Pengatur',
                'is_active'=> true,
            ],
            [
                'nip'      => '199607102019041010',
                'nama'     => 'Bambang Sutrisno, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
            [
                'nip'      => '199704252019042011',
                'nama'     => 'Sri Handayani, S.P.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/c',
                'pangkat'  => 'Pengatur',
                'is_active'=> true,
            ],
            [
                'nip'      => '199812032020041012',
                'nama'     => 'Dwi Hermawan, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/b',
                'pangkat'  => 'Pengatur Muda Tk.I',
                'is_active'=> true,
            ],
            [
                'nip'      => '199806142021042013',
                'nama'     => 'Nurul Azizah, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
            [
                'nip'      => '199901282022041014',
                'nama'     => 'Yudi Prasetyo, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/c',
                'pangkat'  => 'Pengatur',
                'is_active'=> true,
            ],
            [
                'nip'      => '199908092022042015',
                'nama'     => 'Linda Suryatni, S.P.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/b',
                'pangkat'  => 'Pengatur Muda Tk.I',
                'is_active'=> true,
            ],
            [
                'nip'      => '200003172023041016',
                'nama'     => 'Rexon Adrianto, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
            [
                'nip'      => '200005222023042017',
                'nama'     => 'Siti Nursyamsi, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/c',
                'pangkat'  => 'Pengatur',
                'is_active'=> true,
            ],
            [
                'nip'      => '200102112024041018',
                'nama'     => 'Supratno Wijaya, S.P.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/b',
                'pangkat'  => 'Pengatur Muda Tk.I',
                'is_active'=> true,
            ],
            [
                'nip'      => '200109062024042019',
                'nama'     => 'Riza Puspitasari, A.Md.',
                'password' => Hash::make('password'),
                'upt_id'   => '3100',
                'role_id'  => $roles['petugas-lapangan'],
                'golongan' => 'II/d',
                'pangkat'  => 'Pengatur Tk.I',
                'is_active'=> true,
            ],
        ];

        foreach ($users as $userData) {
            $this->persistUser($userData, $usedEmails);
        }

        // ── Generator: setiap UPT balai induk (kode berakhiran '00') diberi
        //    1 pimpinan + 1 koordinator + 3 petugas lapangan.
        //    Dikecualikan: '1000' (Kantor Pusat) dan '3100' (sudah eksplisit di atas).
        $indukUpt = Upt::where('kode', 'like', '%00')
            ->whereNotIn('kode', ['1000', '3100'])
            ->orderBy('kode')
            ->pluck('kode');

        $seq = 100;     // nomor urut global NIP generated (hindari bentrok 001–085)
        $nameIdx = 0;   // penunjuk pool nama

        foreach ($indukUpt as $kode) {
            $this->persistUser($this->makeStaff($kode, 'pimpinan', $roles, $seq, $nameIdx), $usedEmails);
            $this->persistUser($this->makeStaff($kode, 'koordinator-upt', $roles, $seq, $nameIdx), $usedEmails);
            for ($i = 0; $i < 3; $i++) {
                $this->persistUser($this->makeStaff($kode, 'petugas-lapangan', $roles, $seq, $nameIdx), $usedEmails);
            }
        }
    }

    /** Simpan satu user (lengkapi email + kebijakan password). */
    private function persistUser(array $userData, array &$usedEmails): void
    {
        $userData['email'] = $this->generateEmailFromName($userData['nama'], $usedEmails);
        $userData['password'] = $userData['password'] ?? Hash::make('password');
        $userData['must_change_password'] = $userData['must_change_password'] ?? false;
        $userData['password_changed_at'] = $userData['password_changed_at'] ?? now();
        $userData['password_expires_at'] = $userData['password_expires_at']
            ?? now()->addDays(PasswordPolicyService::EXPIRY_DAYS);

        User::updateOrCreate(['nip' => $userData['nip']], $userData);
    }

    /** Bangun data staf UPT hasil generate (nama, NIP, golongan sesuai role). */
    private function makeStaff(string $uptKode, string $roleName, $roles, int &$seq, int &$nameIdx): array
    {
        $isMale = ($nameIdx % 2 === 0);
        $first  = $isMale
            ? self::FIRST_M[intdiv($nameIdx, 2) % count(self::FIRST_M)]
            : self::FIRST_F[intdiv($nameIdx, 2) % count(self::FIRST_F)];
        $last   = self::LAST[($nameIdx * 3) % count(self::LAST)];
        $gender = $isMale ? '1' : '2';
        $nameIdx++;

        if ($roleName === 'pimpinan') {
            $birthYear = 1971 + ($seq % 6);
            $cpnsYear  = $birthYear + 26;
            $golongan  = 'IV/b';
            $pangkat   = 'Pembina Tingkat I';
            $gelar     = 'M.Si';
        } elseif ($roleName === 'koordinator-upt') {
            $birthYear = 1983 + ($seq % 5);
            $cpnsYear  = $birthYear + 25;
            $golongan  = 'III/c';
            $pangkat   = 'Penata';
            $gelar     = 'S.P.';
        } else {
            $birthYear = 1995 + ($seq % 5);
            $cpnsYear  = $birthYear + 23;
            $golongan  = 'II/c';
            $pangkat   = 'Pengatur';
            $gelar     = 'A.Md.';
        }

        return [
            'nip'      => $this->makeNip($birthYear, $cpnsYear, $gender, $seq),
            'nama'     => "{$first} {$last}, {$gelar}",
            'password' => Hash::make('password'),
            'upt_id'   => $uptKode,
            'role_id'  => $roles[$roleName],
            'golongan' => $golongan,
            'pangkat'  => $pangkat,
            'is_active'=> true,
        ];
    }

    /**
     * Bangun NIP 18 digit valid: YYYYMMDD(lahir) + YYYYMM(CPNS) + G(1/2) + NNN(urut).
     * $seq dipakai sebagai nomor urut global sekaligus variasi tanggal lahir.
     */
    private function makeNip(int $birthYear, int $cpnsYear, string $gender, int &$seq): string
    {
        $month = str_pad((string) (($seq % 12) + 1), 2, '0', STR_PAD_LEFT);
        $day   = str_pad((string) (($seq % 28) + 1), 2, '0', STR_PAD_LEFT);
        $urut  = str_pad((string) $seq, 3, '0', STR_PAD_LEFT);
        $seq++;

        return sprintf('%04d%s%s%04d04%s%s', $birthYear, $month, $day, $cpnsYear, $gender, $urut);
    }

    private function generateEmailFromName(string $nama, array &$usedEmails): string
    {
        $baseName = trim(Str::before($nama, ','));
        $baseName = preg_replace('/^(?:(?:prof|drh|dr|ir|h|hj)\.?\s+)+/i', '', $baseName) ?? $baseName;
        $baseName = Str::of($baseName)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '.')
            ->trim('.')
            ->value();

        $localPart = $baseName !== '' ? $baseName : 'user';
        $email = $localPart . '@karantinaindonesia.go.id';
        $suffix = 2;

        while (in_array($email, $usedEmails, true)) {
            $email = $localPart . $suffix . '@karantinaindonesia.go.id';
            $suffix++;
        }

        $usedEmails[] = $email;

        return $email;
    }
}

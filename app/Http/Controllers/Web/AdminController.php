<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SystemLog;
use App\Models\Upt;
use App\Models\User;
use App\Support\PasswordPolicyService;
use App\Support\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    private const EMAIL_DOMAIN = 'karantinaindonesia.go.id';

    private const SYSTEM_LOG_MODULES = [
        'autentikasi',
        'pengguna',
        'role',
        'upt',
        'profil',
    ];

    private const SYSTEM_LOG_ACTIONS = [
        'login',
        'logout',
        'tambah',
        'ubah',
        'aktifkan',
        'nonaktifkan',
        'atur-role',
    ];

    // ══════════════════════════════════════════════════════════════
    // MANAJEMEN PENGGUNA (FR-W21–W23)
    // ══════════════════════════════════════════════════════════════

    /**
     * FR-W21: Daftar pengguna
     */
    public function indexPengguna(Request $request): View
    {
        $query = User::with('role', 'upt')->orderBy('nama');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%"));
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        if ($request->filled('upt')) {
            $query->where('upt_id', $request->upt);
        }

        $users = $query->paginate(25)->withQueryString();
        $roles = Role::all();

        // Dropdown: hanya UPT induk (tanpa satpel)
        $uptList = Upt::where(fn($q) => $q->where('nama_satpel', 'UPT Induk')->orWhere('kode', '1000'))
                        ->orderBy('nama')->get();

        return view('admin.pengguna', compact('users', 'roles', 'uptList'));
    }

    /**
     * FR-W22: Form tambah pengguna (GET) + simpan (POST)
     */
    public function createPengguna(): View
    {
        $roles      = Role::all();
        $uptList    = Upt::where(fn($q) => $q->where('nama_satpel', 'UPT Induk')->orWhere('kode', '1000'))
                        ->orderBy('nama')->get();

        return view('admin.pengguna-form', compact('roles', 'uptList'));
    }

    public function storePengguna(Request $request): RedirectResponse
    {
        $data = $this->validatePenggunaPayload($request);
        $data['is_active'] = true;
        $data = array_merge(
            $data,
            PasswordPolicyService::managedPasswordData((string) $request->input('password'))
        );

        $user = User::create([
            'nip'       => $data['nip'],
            'nama'      => $data['nama'],
            'email'     => $data['email'],
            'password'  => $data['password'],
            'upt_id'    => $data['upt_id'],
            'role_id'   => $data['role_id'],
            'golongan'  => $data['golongan'],
            'pangkat'   => $data['pangkat'],
            'is_active' => $data['is_active'],
        ]);

        $user->loadMissing('role', 'upt');

        SystemLogService::record(
            $request,
            'pengguna',
            'tambah',
            "Menambahkan pengguna {$user->nama}.",
            $user,
            [
                'nip' => $user->nip,
                'email' => $user->email,
                'role' => $user->role?->display_name,
                'upt' => $user->upt?->nama ?? 'Kantor Pusat',
                'status' => $user->is_active ? 'Aktif' : 'Nonaktif',
                'wajib_ganti_password' => $user->must_change_password ? 'Ya' : 'Tidak',
                'password_berlaku_sampai' => $user->password_expires_at?->format('d M Y H:i'),
            ]
        );

        return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * FR-W23: Edit data pengguna
     */
    public function editPengguna(int $id): View
    {
        $user       = User::findOrFail($id);
        $roles      = Role::all();
        $uptList    = Upt::where(fn($q) => $q->where('nama_satpel', 'UPT Induk')->orWhere('kode', '1000'))
                        ->orderBy('nama')->get();

        return view('admin.pengguna-form', compact('user', 'roles', 'uptList'));
    }

    public function updatePengguna(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->loadMissing('role', 'upt');
        $before = [
            'nama' => $user->nama,
            'email' => $user->email,
            'role' => $user->role?->display_name,
            'upt' => $user->upt?->nama ?? 'Kantor Pusat',
        ];

        $data = $this->validatePenggunaPayload($request, $user);
        $roleBaru = Role::findOrFail($data['role_id']);

        if ($response = $this->guardSuperAdminAccountChange($user, $roleBaru)) {
            return $response;
        }

        unset($data['password']);

        if ($request->filled('password')) {
            $data = array_merge(
                $data,
                PasswordPolicyService::managedPasswordData((string) $request->input('password'))
            );
        }

        $user->update($data);
        $user->load('role', 'upt');

        SystemLogService::record(
            $request,
            'pengguna',
            'ubah',
            "Memperbarui data pengguna {$user->nama}.",
            $user,
            [
                'nama_lama' => $before['nama'],
                'nama_baru' => $user->nama,
                'email_lama' => $before['email'],
                'email_baru' => $user->email,
                'role_lama' => $before['role'],
                'role_baru' => $user->role?->display_name,
                'upt_lama' => $before['upt'],
                'upt_baru' => $user->upt?->nama ?? 'Kantor Pusat',
                'password_diubah' => $request->filled('password') ? 'Ya' : 'Tidak',
                'wajib_ganti_password' => $request->filled('password') && $user->must_change_password ? 'Ya' : 'Tidak',
            ]
        );

        return redirect()->route('admin.pengguna')->with('success', 'Data pengguna diperbarui.');
    }

    /**
     * FR-W23: Nonaktifkan / aktifkan akun (soft delete via is_active)
     */
    public function toggleAktif(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($response = $this->guardSuperAdminStatusToggle($user)) {
            return $response;
        }

        $statusLama = $user->is_active ? 'Aktif' : 'Nonaktif';
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $statusBaru = $user->is_active ? 'Aktif' : 'Nonaktif';

        SystemLogService::record(
            request(),
            'pengguna',
            $user->is_active ? 'aktifkan' : 'nonaktifkan',
            ucfirst($status) . " akun {$user->nama}.",
            $user,
            [
                'nip' => $user->nip,
                'status_lama' => $statusLama,
                'status_baru' => $statusBaru,
            ]
        );

        return back()->with('success', "Akun {$user->nama} berhasil {$status}.");
    }

    /**
     * Bulk reset password: reset password semua user (non-super-admin) ke NIP masing-masing.
     * Opsional filter berdasarkan role_id dan/atau upt_id.
     */
    public function bulkResetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'filter_role' => 'nullable|exists:roles,id',
            'filter_upt'  => 'nullable|exists:upt,kode',
        ]);

        $adminId = auth()->id();

        $query = User::with('role')
            ->where('id', '!=', $adminId)
            ->whereHas('role', fn($q) => $q->where('name', '!=', 'super-admin'));

        if ($request->filled('filter_role')) {
            $query->where('role_id', $request->filter_role);
        }

        if ($request->filled('filter_upt')) {
            $query->where('upt_id', $request->filter_upt);
        }

        $users = $query->get();
        $count = $users->count();

        if ($count === 0) {
            return back()->with('warning', 'Tidak ada pengguna yang memenuhi kriteria reset.');
        }

        $results = [];
        foreach ($users as $user) {
            // Generate password unik: 4 huruf kapital + 4 angka, misal KRTX4829
            $newPassword = strtoupper(Str::random(4)) . rand(1000, 9999);
            $user->update(
                PasswordPolicyService::managedPasswordData($newPassword)
            );
            $results[] = [
                'nama'     => $user->nama,
                'nip'      => $user->nip,
                'role'     => $user->role?->display_name ?? '-',
                'password' => $newPassword,
            ];
        }

        SystemLogService::record(
            $request,
            'pengguna',
            'ubah',
            "Reset password massal untuk {$count} pengguna.",
            null,
            [
                'jumlah_pengguna' => $count,
                'filter_role'     => $request->filled('filter_role') ? Role::find($request->filter_role)?->display_name : 'Semua',
                'filter_upt'      => $request->filled('filter_upt') ? (Upt::find($request->filter_upt)?->nama ?? $request->filter_upt) : 'Semua',
                'password_baru'   => 'Generated unik per pengguna',
                'wajib_ganti'     => 'Ya',
            ]
        );

        // Simpan hasil di session (server-side only) — tidak di-embed ke HTML
        session()->put('bulk_reset_results', $results);
        session()->put('bulk_reset_count', $count);

        return back()->with('success', "Password {$count} pengguna berhasil direset. Unduh daftar password sebelum meninggalkan halaman ini.");
    }

    /**
     * Stream CSV hasil bulk reset password dari session.
     * Password tidak pernah di-render ke HTML — hanya dikirim via response ini.
     */
    public function bulkResetDownload(): \Symfony\Component\HttpFoundation\StreamedResponse|RedirectResponse
    {
        $results = session('bulk_reset_results');

        if (empty($results)) {
            return redirect()->route('admin.pengguna')
                ->with('warning', 'Data reset sudah kedaluwarsa atau sudah diunduh. Silakan lakukan reset ulang jika diperlukan.');
        }

        $filename = 'reset-password-' . now()->format('Ymd-His') . '.csv';

        // Hapus dari session sebelum stream dimulai (session di-commit sebelum output)
        session()->forget(['bulk_reset_results', 'bulk_reset_count']);

        return response()->streamDownload(function () use ($results) {
            $out = fopen('php://output', 'w');
            // BOM agar Excel tidak mojibake
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['No', 'Nama', 'NIP', 'Role', 'Password Baru']);
            foreach ($results as $i => $r) {
                fputcsv($out, [$i + 1, $r['nama'], $r['nip'], $r['role'], $r['password']]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    // PROFIL ADMIN
    // ══════════════════════════════════════════════════════════════

    public function profil(): View
    {
        return view('admin.profil', ['user' => auth()->user()]);
    }

    public function updateProfil(Request $request): RedirectResponse
    {
        $user = auth()->user();
        /** @var User $user */
        $before = [
            'nama' => $user->nama,
            'email' => $user->email,
            'golongan' => $user->golongan,
            'pangkat' => $user->pangkat,
        ];

        $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $email = strtolower((string) $value);

                    if (!str_ends_with($email, '@' . self::EMAIL_DOMAIN)) {
                        $fail('Email harus menggunakan domain @' . self::EMAIL_DOMAIN . '.');
                    }
                },
            ],
            'golongan'=> 'nullable|string|max:10',
            'pangkat' => 'nullable|string|max:100',
            'password'=> 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only('nama', 'email', 'golongan', 'pangkat');

        if ($request->filled('password')) {
            $data = array_merge(
                $data,
                PasswordPolicyService::managedPasswordData((string) $request->input('password'), false)
            );
        }

        $user->update($data);

        SystemLogService::record(
            $request,
            'profil',
            'ubah',
            'Memperbarui profil super admin.',
            $user,
            [
                'nama_lama' => $before['nama'],
                'nama_baru' => $user->nama,
                'email_lama' => $before['email'],
                'email_baru' => $user->email,
                'golongan_lama' => $before['golongan'] ?? '-',
                'golongan_baru' => $user->golongan ?? '-',
                'pangkat_lama' => $before['pangkat'] ?? '-',
                'pangkat_baru' => $user->pangkat ?? '-',
                'password_diubah' => $request->filled('password') ? 'Ya' : 'Tidak',
                'password_berlaku_sampai' => $request->filled('password')
                    ? $user->password_expires_at?->format('d M Y H:i')
                    : '-',
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ══════════════════════════════════════════════════════════════
    // MANAJEMEN ROLE (FR-W24)
    // ══════════════════════════════════════════════════════════════

    public function indexRole(): View
    {
        $roles = Role::withCount('users')->get();

        return view('admin.role', compact('roles'));
    }

    public function updateRole(Request $request, int $userId): RedirectResponse
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);

        $user = User::with('role')->findOrFail($userId);
        $roleLama = $user->role?->display_name ?? '-';
        $roleBaru = Role::findOrFail($request->role_id);

        if ($response = $this->guardSuperAdminAccountChange($user, $roleBaru)) {
            return $response;
        }

        $user->update(['role_id' => $request->role_id]);
        $user->load('role');

        SystemLogService::record(
            $request,
            'role',
            'atur-role',
            "Mengubah role pengguna {$user->nama}.",
            $user,
            [
                'role_lama' => $roleLama,
                'role_baru' => $roleBaru->display_name,
            ]
        );

        return back()->with('success', 'Role pengguna diperbarui.');
    }

    public function indexSystemLog(Request $request): View
    {
        $query = SystemLog::with(['user.role'])->latest('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%")
                    ->orWhere('subject_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('nip', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(20)->appends(request()->query());
        $modules = collect(self::SYSTEM_LOG_MODULES)
            ->merge(SystemLog::query()->select('module')->distinct()->orderBy('module')->pluck('module'))
            ->filter()
            ->unique()
            ->values();
        $actions = collect(self::SYSTEM_LOG_ACTIONS)
            ->merge(SystemLog::query()->select('action')->distinct()->orderBy('action')->pluck('action'))
            ->filter()
            ->unique()
            ->values();
        $summary = [
            'total' => SystemLog::count(),
            'today' => SystemLog::whereDate('created_at', today())->count(),
            'month' => SystemLog::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'lastActivity' => SystemLog::latest('created_at')->value('created_at'),
        ];

        return view('admin.log-sistem', compact('logs', 'modules', 'actions', 'summary'));
    }

    // ══════════════════════════════════════════════════════════════
    // MANAJEMEN UPT (FR-W25)
    // ══════════════════════════════════════════════════════════════

    public function indexUpt(): View
    {
        $uptList = Upt::withCount('users')->orderBy('nama')->get();

        return view('admin.upt', compact('uptList'));
    }

    public function storeUpt(Request $request): RedirectResponse
    {
        $request->validate([
            'kode'    => 'required|string|max:20|unique:upt,kode',
            'nama'    => 'required|string|max:255',
            'alias'   => 'nullable|string|max:60',
            'wilayah' => 'nullable|string|max:255',
        ]);

        $upt = Upt::create($request->only('kode', 'nama', 'alias', 'wilayah'));

        SystemLogService::record(
            $request,
            'upt',
            'tambah',
            "Menambahkan UPT {$upt->nama}.",
            $upt,
            [
                'kode' => $upt->kode,
                'alias' => $upt->alias ?? '-',
                'wilayah' => $upt->wilayah ?? '-',
            ]
        );

        return redirect()->route('admin.upt')->with('success', 'UPT berhasil ditambahkan.');
    }

    public function updateUpt(Request $request, string $kode): RedirectResponse
    {
        $upt = Upt::findOrFail($kode);
        $before = [
            'nama' => $upt->nama,
            'alias' => $upt->alias,
            'wilayah' => $upt->wilayah,
        ];

        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'alias'   => 'nullable|string|max:60',
            'wilayah' => 'nullable|string|max:255',
        ]);

        $upt->update($validated);

        SystemLogService::record(
            $request,
            'upt',
            'ubah',
            "Memperbarui data UPT {$upt->nama}.",
            $upt,
            [
                'nama_lama' => $before['nama'],
                'nama_baru' => $upt->nama,
                'alias_lama' => $before['alias'] ?? '-',
                'alias_baru' => $upt->alias ?? '-',
                'wilayah_lama' => $before['wilayah'] ?? '-',
                'wilayah_baru' => $upt->wilayah ?? '-',
            ]
        );

        return back()->with('success', 'UPT berhasil diperbarui.');
    }

    private function validatePenggunaPayload(Request $request, ?User $user = null): array
    {
        $userId = $user?->id;

        return $request->validate([
            'nip' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'nip')->ignore($userId),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $email = strtolower((string) $value);

                    if (!str_ends_with($email, '@' . self::EMAIL_DOMAIN)) {
                        $fail('Email harus menggunakan domain @' . self::EMAIL_DOMAIN . '.');
                    }
                },
            ],
            'role_id' => ['required', 'exists:roles,id'],
            'upt_id' => [
                'nullable',
                'string',
                'exists:upt,kode',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    $roleName = Role::query()
                        ->whereKey($request->input('role_id'))
                        ->value('name');

                    if ($roleName && !in_array($roleName, ['super-admin', 'pimpinan'], true) && blank($value)) {
                        $fail('UPT wajib dipilih untuk role ini.');
                    }
                },
            ],
            'golongan' => ['nullable', 'string', 'max:10'],
            'pangkat' => ['nullable', 'string', 'max:100'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    private function guardSuperAdminStatusToggle(User $user): ?RedirectResponse
    {
        $authUser = auth()->user();

        if (!$user->is_active) {
            return null;
        }

        if ($authUser && $authUser->id === $user->id) {
            return back()->with('error', 'Akun super admin yang sedang digunakan tidak dapat dinonaktifkan.');
        }

        if ($user->hasRole('super-admin') && $this->activeSuperAdminCount() <= 1) {
            return back()->with('error', 'Minimal harus ada satu super admin aktif di sistem.');
        }

        return null;
    }

    private function guardSuperAdminAccountChange(User $user, Role $newRole): ?RedirectResponse
    {
        $authUser = auth()->user();
        $isCurrentSuperAdmin = $user->hasRole('super-admin');
        $isStillSuperAdmin = $newRole->name === 'super-admin';

        if (!$isCurrentSuperAdmin || $isStillSuperAdmin) {
            return null;
        }

        if ($authUser && $authUser->id === $user->id) {
            return back()->with('error', 'Akun super admin yang sedang digunakan tidak dapat diturunkan rolenya.');
        }

        if ($user->is_active && $this->activeSuperAdminCount() <= 1) {
            return back()->with('error', 'Minimal harus ada satu super admin aktif di sistem.');
        }

        return null;
    }

    private function activeSuperAdminCount(): int
    {
        return User::query()
            ->where('is_active', true)
            ->whereHas('role', fn($query) => $query->where('name', 'super-admin'))
            ->count();
    }
}

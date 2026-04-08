<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Upt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
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
        $request->validate([
            'nip'      => 'required|string|max:30|unique:users,nip',
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'upt_id'   => 'nullable|string|exists:upt,kode',
            'role_id'  => 'required|exists:roles,id',
            'golongan' => 'nullable|string|max:10',
            'pangkat'  => 'nullable|string|max:100',
        ]);

        User::create([
            'nip'      => $request->nip,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'upt_id'   => $request->upt_id,
            'role_id'  => $request->role_id,
            'golongan' => $request->golongan,
            'pangkat'  => $request->pangkat,
            'is_active'=> true,
        ]);

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

        $request->validate([
            'nip'     => 'required|string|max:30|unique:users,nip,' . $id,
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'upt_id'  => 'nullable|string|exists:upt,kode',
            'role_id' => 'required|exists:roles,id',
            'golongan'=> 'nullable|string|max:10',
            'pangkat' => 'nullable|string|max:100',
        ]);

        $data = $request->only('nip', 'nama', 'email', 'upt_id', 'role_id', 'golongan', 'pangkat');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.pengguna')->with('success', 'Data pengguna diperbarui.');
    }

    /**
     * FR-W23: Nonaktifkan / aktifkan akun (soft delete via is_active)
     */
    public function toggleAktif(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->nama} berhasil {$status}.");
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

        $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'golongan'=> 'nullable|string|max:10',
            'pangkat' => 'nullable|string|max:100',
            'password'=> 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only('nama', 'email', 'golongan', 'pangkat');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

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

        User::findOrFail($userId)->update(['role_id' => $request->role_id]);

        return back()->with('success', 'Role pengguna diperbarui.');
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

        Upt::create($request->only('kode', 'nama', 'alias', 'wilayah'));

        return redirect()->route('admin.upt')->with('success', 'UPT berhasil ditambahkan.');
    }

    public function updateUpt(Request $request, string $kode): RedirectResponse
    {
        $upt = Upt::findOrFail($kode);

        $request->validate([
            'nama'    => 'required|string|max:255',
            'alias'   => 'nullable|string|max:60',
            'wilayah' => 'nullable|string|max:255',
        ]);

        $upt->update($request->only('nama', 'alias', 'wilayah'));

        return back()->with('success', 'UPT berhasil diperbarui.');
    }
}

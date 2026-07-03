@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('admin.pengguna') }}" data-label="Pengguna"
            class="nav-link {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Pengguna</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.role') }}" data-label="Role"
            class="nav-link {{ request()->routeIs('admin.role') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i><span>Role</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.upt') }}" data-label="UPT"
            class="nav-link {{ request()->routeIs('admin.upt') ? 'active' : '' }}">
            <i class="bi bi-building"></i><span>UPT</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.log-sistem') }}" data-label="Log Sistem"
            class="nav-link {{ request()->routeIs('admin.log-sistem') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i><span>Log Sistem</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.profil') }}" data-label="Profil"
            class="nav-link {{ request()->routeIs('admin.profil') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i><span>Profil Saya</span>
        </a>
    </li>
@endsection

@section('content')

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="page-heading mb-0">Manajemen Pengguna</h1>
            <p class="text-muted mb-0" style="font-size:.78rem;margin-top:.2rem">Kelola akun seluruh pengguna sistem</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-warning btn-sm" id="btnBulkReset"
                title="Reset password semua pengguna ke NIP masing-masing">
                <i class="bi bi-key me-1"></i>Reset Password Massal
            </button>
            <a href="{{ route('admin.pengguna.tambah') }}" class="btn btn-brand btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah Pengguna
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card p-3 mb-3">
        <form method="GET" id="userFilterForm" class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" id="userSearchInput" class="form-control form-control-sm"
                    placeholder="Nama atau username..." value="{{ request('search') }}">
            </div>
            <div class="col-sm-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select form-select-sm user-auto-filter">
                    <option value="">Semua Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label">UPT</label>
                <select name="upt" id="uptSelect" class="form-select form-select-sm user-auto-filter">
                    <option value="">Semua UPT</option>
                    @foreach ($uptList as $upt)
                        <option value="{{ $upt->kode }}" {{ request('upt') == $upt->kode ? 'selected' : '' }}>
                            {{ $upt->short_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <div class="text-muted mb-2" style="font-size:.73rem;line-height:1.45">
                    Filter otomatis saat mengetik atau memilih opsi.
                </div>
                <a href="{{ route('admin.pengguna') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pengguna</th>
                        <th>Username</th>
                        <th>UPT</th>
                        <th>Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $user->nama }}</div>
                                <div style="font-size:.73rem;color:var(--text-muted);overflow-wrap:anywhere">
                                    {{ $user->email ?? $user->nip . '@karantinaindonesia.go.id' }}
                                </div>
                            </td>
                            <td>
                                <span class="font-monospace" style="font-size:.8rem;color:var(--primary);font-weight:600">
                                    {{ $user->nip }}
                                </span>
                            </td>
                            <td style="font-size:.82rem">
                                <div style="white-space:normal;overflow-wrap:anywhere;line-height:1.4">
                                    {{ $user->upt?->nama ?? '-' }}
                                </div>
                                @if ($user->upt?->nama_satpel && $user->upt->nama_satpel !== 'UPT Induk')
                                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:.2rem">
                                        {{ $user->upt->nama_satpel }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-role">{{ $user->role?->display_name ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                @if ($user->is_active)
                                    <span class="badge badge-status-aktif">Aktif</span>
                                @else
                                    <span class="badge badge-rekomendasi-penolakan">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <a href="{{ route('admin.pengguna.edit', $user->id) }}"
                                        class="btn btn-sm btn-outline-brand" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.pengguna.toggle-aktif', $user->id) }}"
                                        class="d-inline toggle-form">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="_nama" value="{{ $user->nama }}">
                                        <input type="hidden" name="_action" value="{{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}">
                                        <button type="button"
                                            class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} btn-toggle-confirm"
                                            title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi bi-toggle-{{ $user->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-people"
                                    style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                                <span class="text-muted">Tidak ada pengguna.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="px-3 py-2 border-top" style="background:#f8fafc">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Reset Password Massal --}}
    <div id="bulkResetModal" style="
        display:none;position:fixed;inset:0;z-index:9999;
        background:rgba(0,0,0,.35);backdrop-filter:blur(2px);
        align-items:center;justify-content:center">
        <div style="
            background:#fff;border-radius:1.2rem;padding:2rem 2rem 1.5rem;
            max-width:440px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2);">
            <div style="width:52px;height:52px;border-radius:50%;background:#fff8e1;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.4rem">
                <i class="bi bi-key-fill" style="color:#f59e0b"></i>
            </div>
            <div style="font-size:1rem;font-weight:800;color:var(--text);text-align:center;margin-bottom:.4rem">Reset Password Massal</div>
            <div style="font-size:.83rem;color:var(--text-muted);text-align:center;line-height:1.6;margin-bottom:1.5rem">
                Password setiap pengguna akan direset ke <strong>password unik yang di-generate otomatis</strong>.
                Setelah reset, daftar password baru akan ditampilkan untuk didistribusikan ke pengguna.
                Mereka wajib mengganti password saat login berikutnya.
            </div>
            <form method="POST" action="{{ route('admin.pengguna.bulk-reset-password') }}" id="bulkResetForm">
                @csrf
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label" style="font-size:.78rem;font-weight:600">Filter Role</label>
                        <select name="filter_role" class="form-select form-select-sm">
                            <option value="">Semua Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label" style="font-size:.78rem;font-weight:600">Filter UPT</label>
                        <select name="filter_upt" class="form-select form-select-sm">
                            <option value="">Semua UPT</option>
                            @foreach ($uptList as $upt)
                                <option value="{{ $upt->kode }}">{{ $upt->short_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="alert alert-warning py-2 px-3" style="font-size:.78rem;border-radius:.6rem">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Aksi ini tidak dapat dibatalkan. Super admin tidak termasuk.
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" id="bulkResetCancel" class="btn btn-ghost flex-fill" style="font-size:.85rem">Batal</button>
                    <button type="submit" class="btn flex-fill" style="font-size:.85rem;font-weight:700;background:#d97706;color:#fff;border:none">
                        <i class="bi bi-key me-1"></i>Ya, Reset Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hasil Reset Password Massal --}}
    @if(session()->has('bulk_reset_results'))
    <div id="bulkResultModal" style="
        display:flex;position:fixed;inset:0;z-index:10000;
        background:rgba(0,0,0,.45);backdrop-filter:blur(2px);
        align-items:center;justify-content:center">
        <div style="
            background:#fff;border-radius:1.2rem;padding:2rem 2rem 1.5rem;
            max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);">
            <div style="width:52px;height:52px;border-radius:50%;background:#e6f9f0;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.4rem">
                <i class="bi bi-check-lg" style="color:#0f6b3a"></i>
            </div>
            <div style="font-size:1rem;font-weight:800;color:var(--text);text-align:center;margin-bottom:.4rem">
                Reset Password Berhasil
            </div>
            <div style="font-size:.83rem;color:var(--text-muted);text-align:center;line-height:1.6;margin-bottom:1.5rem">
                <strong>{{ session('bulk_reset_count') }} pengguna</strong> berhasil direset.<br>
                Unduh file CSV untuk melihat daftar password baru.<br>
                <span class="text-danger" style="font-size:.75rem">File hanya bisa diunduh sekali.</span>
            </div>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" onclick="document.getElementById('bulkResultModal').style.display='none'"
                    class="btn btn-ghost btn-sm" style="font-size:.8rem">Tutup</button>
                <a href="{{ route('admin.pengguna.bulk-reset-download') }}"
                    class="btn btn-brand btn-sm" style="font-size:.8rem;font-weight:700">
                    <i class="bi bi-download me-1"></i>Unduh CSV
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Toast konfirmasi toggle --}}
    <div id="toggleToast" style="
        display:none;position:fixed;inset:0;z-index:9999;
        background:rgba(0,0,0,.35);backdrop-filter:blur(2px);
        align-items:center;justify-content:center">
        <div id="toastBox" style="
            background:#fff;border-radius:1.2rem;padding:2rem 2rem 1.5rem;
            max-width:360px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2);
            transform:scale(.85);opacity:0;transition:all .25s cubic-bezier(.34,1.56,.64,1)">
            <div id="toastIconWrap" style="width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.4rem"></div>
            <div id="toastTitle" style="font-size:1rem;font-weight:800;color:var(--text);text-align:center;margin-bottom:.4rem"></div>
            <div id="toastBody" style="font-size:.83rem;color:var(--text-muted);text-align:center;line-height:1.6;margin-bottom:1.5rem"></div>
            <div class="d-flex gap-2">
                <button id="toastCancel" class="btn btn-ghost flex-fill" style="font-size:.85rem">Batal</button>
                <button id="toastConfirm" class="btn flex-fill" style="font-size:.85rem;font-weight:700"></button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
(function() {
    let pendingForm = null;

    function showToast(form) {
        const nama    = form.querySelector('[name="_nama"]').value;
        const action  = form.querySelector('[name="_action"]').value;
        const isNon   = action === 'nonaktifkan';

        const iconWrap  = document.getElementById('toastIconWrap');
        const title     = document.getElementById('toastTitle');
        const body      = document.getElementById('toastBody');
        const confirmBtn= document.getElementById('toastConfirm');

        iconWrap.style.background = isNon ? '#fde8e8' : '#e6f9f0';
        iconWrap.innerHTML = `<i class="bi ${isNon ? 'bi-toggle-off' : 'bi-toggle-on'}" style="color:${isNon ? '#b91c1c' : '#0f6b3a'}"></i>`;
        title.textContent  = isNon ? 'Nonaktifkan akun?' : 'Aktifkan akun?';
        body.innerHTML     = `Akun <strong>${nama}</strong> akan ${isNon ? 'dinonaktifkan dan tidak bisa login' : 'diaktifkan kembali'}.`;
        confirmBtn.textContent = isNon ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan';
        confirmBtn.style.background = isNon ? '#b91c1c' : '#0f6b3a';
        confirmBtn.style.color = '#fff';
        confirmBtn.style.border = 'none';

        const overlay = document.getElementById('toggleToast');
        const box     = document.getElementById('toastBox');
        overlay.style.display = 'flex';
        requestAnimationFrame(() => {
            box.style.transform = 'scale(1)';
            box.style.opacity   = '1';
        });
        pendingForm = form;
    }

    function hideToast() {
        const overlay = document.getElementById('toggleToast');
        const box     = document.getElementById('toastBox');
        box.style.transform = 'scale(.85)';
        box.style.opacity   = '0';
        setTimeout(() => { overlay.style.display = 'none'; }, 220);
        pendingForm = null;
    }

    document.querySelectorAll('.btn-toggle-confirm').forEach(function(btn) {
        btn.addEventListener('click', function() {
            showToast(this.closest('.toggle-form'));
        });
    });

    document.getElementById('toastCancel').addEventListener('click', hideToast);
    document.getElementById('toggleToast').addEventListener('click', function(e) {
        if (e.target === this) hideToast();
    });
    document.getElementById('toastConfirm').addEventListener('click', function() {
        if (pendingForm) pendingForm.submit();
    });
})();

(function() {
    const modal = document.getElementById('bulkResetModal');
    document.getElementById('btnBulkReset').addEventListener('click', function() {
        modal.style.display = 'flex';
    });
    document.getElementById('bulkResetCancel').addEventListener('click', function() {
        modal.style.display = 'none';
    });
    modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.style.display = 'none';
    });
})();

(function() {
    const form = document.getElementById('userFilterForm');
    const searchInput = document.getElementById('userSearchInput');
    const selects = document.querySelectorAll('.user-auto-filter');

    if (!form || !searchInput) return;

    let searchTimer = null;

    function submitFilters() {
        form.requestSubmit();
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(submitFilters, 350);
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimer);
            submitFilters();
        }
    });

    selects.forEach(function(select) {
        select.addEventListener('change', submitFilters);
    });
})();
</script>
@endpush

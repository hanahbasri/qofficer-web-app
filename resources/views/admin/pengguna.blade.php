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
        <a href="{{ route('admin.pengguna.tambah') }}" class="btn btn-brand btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Pengguna
        </a>
    </div>

    {{-- Filter --}}
    <div class="card p-3 mb-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Nama atau username..." value="{{ request('search') }}">
            </div>
            <div class="col-sm-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select form-select-sm">
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
                <select name="upt" id="uptSelect" class="form-select form-select-sm">
                    <option value="">Semua UPT</option>
                    @foreach ($uptList as $upt)
                        <option value="{{ $upt->kode }}" {{ request('upt') == $upt->kode ? 'selected' : '' }}>
                            {{ $upt->short_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button class="btn btn-brand btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
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
                                <div style="font-size:.73rem;color:var(--text-muted)">
                                    {{ $user->email ?? $user->nip . '@karantina.go.id' }}
                                </div>
                            </td>
                            <td>
                                <span class="font-monospace" style="font-size:.8rem;color:var(--primary);font-weight:600">
                                    {{ $user->nip }}
                                </span>
                            </td>
                            <td style="font-size:.82rem;min-width:250px">
                                <div style="white-space:normal;overflow:visible;line-height:1.4">
                                    {{ $user->upt?->nama ?? '—' }}
                                </div>
                                @if ($user->upt?->nama_satpel && $user->upt->nama_satpel !== 'UPT Induk')
                                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:.2rem">
                                        {{ $user->upt->nama_satpel }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-role">{{ $user->role?->display_name ?? '—' }}</span>
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
</script>
@endpush

@extends('layouts.app')

@section('title', 'Petugas UPT')
@section('page-title', 'Manajemen Petugas')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('koordinator.dashboard') }}" data-label="Dashboard"
            class="nav-link {{ request()->routeIs('koordinator.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.hasil-periksa') }}" data-label="Hasil Pemeriksaan"
            class="nav-link {{ request()->routeIs('koordinator.hasil-periksa*') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-check"></i><span>Hasil Pemeriksaan</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.petugas') }}" data-label="Petugas"
            class="nav-link {{ request()->routeIs('koordinator.petugas*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Petugas</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.profil') }}" data-label="Profil"
            class="nav-link {{ request()->routeIs('koordinator.profil') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i><span>Profil Saya</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.keamanan') }}" data-label="Keamanan Akun"
            class="nav-link {{ request()->routeIs('koordinator.keamanan') ? 'active' : '' }}">
            <i class="bi bi-key-fill"></i><span>Keamanan Akun</span>
        </a>
    </li>
@endsection

@push('styles')
<style>
    .modal-content { border: none; border-radius: 1rem; overflow: hidden; }
    .modal-header { background: var(--primary); color: #fff; border: none; padding: 1rem 1.25rem; }
    .modal-header .btn-close { filter: brightness(0) invert(1); opacity: .7; }
    .modal-body { padding: 1.5rem 1.25rem; }
    .modal-footer { border-top: 1px solid var(--border); padding: .9rem 1.25rem; background: #f8fafc; }
    .petugas-search {
        display: flex;
        align-items: center;
        gap: .55rem;
        max-width: 360px;
        background: #fff;
        border: 1.5px solid #dee2e6;
        border-radius: .75rem;
        padding: .15rem .8rem;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .petugas-search:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 .18rem rgba(19, 49, 57, .12);
    }
    .petugas-search-icon {
        color: #9ca3af;
        font-size: .9rem;
        line-height: 1;
        flex-shrink: 0;
    }
    .petugas-search-input {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: .42rem 0 !important;
        font-size: .84rem !important;
    }
    .petugas-search-input:focus {
        border: none !important;
        box-shadow: none !important;
    }
    .modal-action-btn {
        width: 138px;
        height: 38px;
        padding: 0 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        line-height: 1;
    }
    .modal-action-btn .bi {
        margin-right: 0 !important;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    Petugas Lapangan UPT
    <small>{{ Auth::user()->upt?->nama ?? 'UPT tidak diketahui' }} - hanya tampil petugas dalam UPT Anda</small>
</div>

<div class="mb-3">
    <div class="petugas-search">
        <i class="bi bi-search petugas-search-icon"></i>
        <input type="text" id="petugasSearch" class="form-control petugas-search-input" autocomplete="off"
            placeholder="Cari nama, NIP, atau email petugas...">
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Nama Petugas</th>
                    <th>NIP</th>
                    <th>Golongan / Pangkat</th>
                    <th>Status</th>
                    <th class="text-end">Reset Password</th>
                </tr>
            </thead>
            <tbody>
                @forelse($petugas as $p)
                <tr class="petugas-row">
                    <td>
                        <div class="fw-semibold petugas-nama" style="font-size:.88rem">{{ $p->nama }}</div>
                        <div class="text-muted petugas-email" style="font-size:.73rem;overflow-wrap:anywhere">{{ $p->email ?? '-' }}</div>
                    </td>
                    <td class="petugas-nip" style="font-size:.85rem;font-weight:500">{{ $p->nip }}</td>
                    <td style="font-size:.82rem">
                        {{ $p->golongan ?? '-' }}
                        @if($p->pangkat)
                            <span class="text-muted">/ {{ $p->pangkat }}</span>
                        @endif
                    </td>
                    <td>
                        @if($p->is_active)
                            <span class="badge badge-status-aktif">Aktif</span>
                        @else
                            <span class="badge badge-rekomendasi-penolakan">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-ghost btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalReset"
                            data-id="{{ $p->id }}"
                            data-nama="{{ $p->nama }}">
                            <i class="bi bi-key me-1"></i>Reset Password
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-people" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                        <span class="text-muted">Belum ada petugas lapangan di UPT ini.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div id="petugasNoResult" class="text-center py-5 d-none">
            <i class="bi bi-search" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
            <span class="text-muted">Tidak ada petugas yang cocok dengan pencarian.</span>
        </div>
    </div>
</div>

{{-- Info box --}}
<div class="mt-3 p-3 rounded d-flex gap-3 align-items-start"
    style="background:#fff9e6;border:1px solid rgba(254,197,89,.4)">
    <i class="bi bi-info-circle-fill mt-1" style="color:#c88a00;flex-shrink:0"></i>
    <div style="font-size:.83rem;color:#5a4a00;line-height:1.6">
        <strong>Kebijakan Reset Password:</strong>
        Koordinator UPT dapat mereset password Petugas Lapangan dalam UPT yang sama.
        Password hasil reset wajib diganti oleh petugas saat login dan berlaku selama 30 hari.
    </div>
</div>

{{-- Modal Reset Password --}}
<div class="modal fade" id="modalReset" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-key me-2"></i>Reset Password Petugas
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formReset" action="">
                @csrf
                <div class="modal-body">
                    <p class="mb-3" style="font-size:.85rem;color:#374151">
                        Reset password untuk: <strong id="namaTarget">-</strong>
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Minimal 8 karakter" required minlength="8">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password baru" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer justify-content-end gap-2">
                    <button type="button" class="btn btn-sm modal-action-btn" style="background:#f0f3f4;color:#4a6068;border:none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand btn-sm modal-action-btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('modalReset').addEventListener('show.bs.modal', function (e) {
    const btn     = e.relatedTarget;
    const id      = btn.dataset.id;
    const nama    = btn.dataset.nama;
    const baseUrl = '{{ route("koordinator.petugas") }}';

    document.getElementById('namaTarget').textContent = nama;
    document.getElementById('formReset').action = baseUrl + '/' + id + '/reset-password';
});

document.getElementById('petugasSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.petugas-row');
    let visibleCount = 0;

    rows.forEach(function(row) {
        const nama = row.querySelector('.petugas-nama')?.textContent.toLowerCase() ?? '';
        const nip = row.querySelector('.petugas-nip')?.textContent.toLowerCase() ?? '';
        const email = row.querySelector('.petugas-email')?.textContent.toLowerCase() ?? '';
        const match = !q || nama.includes(q) || nip.includes(q) || email.includes(q);

        row.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });

    document.getElementById('petugasNoResult').classList.toggle('d-none', visibleCount > 0);
});
</script>
@endpush
@endsection

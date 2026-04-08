@extends('layouts.app')

@section('title', 'Petugas UPT')
@section('page-title', 'Manajemen Petugas')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('koordinator.dashboard') }}" data-label="Dashboard" class="nav-link">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.hasil-periksa') }}" data-label="Hasil Pemeriksaan" class="nav-link">
            <i class="bi bi-clipboard2-check"></i><span>Hasil Pemeriksaan</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.petugas') }}" data-label="Petugas" class="nav-link active">
            <i class="bi bi-people-fill"></i><span>Petugas</span>
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
</style>
@endpush

@section('content')
<div class="page-heading">
    Petugas Lapangan UPT
    <small>{{ Auth::user()->upt?->nama }} — hanya tampil petugas dalam UPT Anda</small>
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
                <tr>
                    <td>
                        <div class="fw-semibold" style="font-size:.88rem">{{ $p->nama }}</div>
                        <div class="text-muted" style="font-size:.73rem">{{ $p->email ?? '—' }}</div>
                    </td>
                    <td style="font-size:.85rem;font-weight:500">{{ $p->nip }}</td>
                    <td style="font-size:.82rem">
                        {{ $p->golongan ?? '—' }}
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
    </div>
</div>

{{-- Info box --}}
<div class="mt-3 p-3 rounded d-flex gap-3 align-items-start"
    style="background:#fff9e6;border:1px solid rgba(254,197,89,.4)">
    <i class="bi bi-info-circle-fill mt-1" style="color:#c88a00;flex-shrink:0"></i>
    <div style="font-size:.83rem;color:#5a4a00;line-height:1.6">
        <strong>Kebijakan Reset Password:</strong>
        Koordinator UPT dapat mereset password Petugas Lapangan dalam UPT yang sama.
        Jika Anda (Koordinator) lupa password, silakan hubungi Super Admin.
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
                        Reset password untuk: <strong id="namaTarget">—</strong>
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
                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand btn-sm">
                        <i class="bi bi-check2 me-1"></i>Simpan Password
                    </button>
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
    const baseUrl = '{{ url("koordinator/petugas") }}';

    document.getElementById('namaTarget').textContent = nama;
    document.getElementById('formReset').action = baseUrl + '/' + id + '/reset-password';
});
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')

@section('sidebar-menu')
    @if($role === 'koordinator')
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
            <a href="{{ route('koordinator.ganti-password') }}" data-label="Ganti Password"
                class="nav-link active">
                <i class="bi bi-key-fill"></i><span>Ganti Password</span>
            </a>
        </li>
    @else
        <li class="nav-item">
            <a href="{{ route('pimpinan.dashboard') }}" data-label="Dashboard" class="nav-link">
                <i class="bi bi-grid-fill"></i><span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pimpinan.monitoring') }}" data-label="Monitoring" class="nav-link">
                <i class="bi bi-binoculars-fill"></i><span>Monitoring</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pimpinan.surat-tugas') }}" data-label="Surat Tugas" class="nav-link">
                <i class="bi bi-file-earmark-text-fill"></i><span>Surat Tugas</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pimpinan.ekspor') }}" data-label="Ekspor Data" class="nav-link">
                <i class="bi bi-download"></i><span>Ekspor Data</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pimpinan.ganti-password') }}" data-label="Ganti Password"
                class="nav-link active">
                <i class="bi bi-key-fill"></i><span>Ganti Password</span>
            </a>
        </li>
    @endif
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm" style="border-radius:.85rem;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div style="width:2.4rem;height:2.4rem;border-radius:.6rem;background:var(--primary);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-key-fill text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-700">Ganti Password</h5>
                        <div class="text-muted" style="font-size:.82rem;">Ubah password akun Anda</div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3" style="border-radius:.6rem;font-size:.88rem;">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    </div>
                @endif

                <form method="POST"
                    action="{{ $role === 'koordinator' ? route('koordinator.ganti-password.post') : route('pimpinan.ganti-password.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:.88rem;">Password Lama</label>
                        <div class="input-group">
                            <input type="password" name="password_lama" id="passwordLama"
                                class="form-control @error('password_lama') is-invalid @enderror"
                                placeholder="Masukkan password lama" autocomplete="current-password">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="toggleVisibility('passwordLama', this)" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password_lama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:.88rem;">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_baru" id="passwordBaru"
                                class="form-control @error('password_baru') is-invalid @enderror"
                                placeholder="Minimal 8 karakter" autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="toggleVisibility('passwordBaru', this)" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:.88rem;">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_baru_confirmation" id="passwordKonfirmasi"
                                class="form-control"
                                placeholder="Ulangi password baru" autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="toggleVisibility('passwordKonfirmasi', this)" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ $role === 'koordinator' ? route('koordinator.dashboard') : route('pimpinan.dashboard') }}"
                            class="btn btn-ghost btn-sm">Batal</a>
                        <button type="submit" class="btn btn-brand btn-sm">
                            <i class="bi bi-check2 me-1"></i>Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endpush

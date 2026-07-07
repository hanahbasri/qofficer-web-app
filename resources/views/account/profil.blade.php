@extends('layouts.app')

@php($isKoordinator = $user->hasRole('koordinator-upt'))

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('sidebar-menu')
    @if ($isKoordinator)
        <li class="nav-item"><a href="{{ route('koordinator.dashboard') }}" data-label="Dashboard" class="nav-link {{ request()->routeIs('koordinator.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-fill"></i><span>Dashboard</span></a></li>
        <li class="nav-item"><a href="{{ route('koordinator.hasil-periksa') }}" data-label="Hasil Pemeriksaan" class="nav-link {{ request()->routeIs('koordinator.hasil-periksa*') ? 'active' : '' }}"><i class="bi bi-clipboard2-check"></i><span>Hasil Pemeriksaan</span></a></li>
        <li class="nav-item"><a href="{{ route('koordinator.petugas') }}" data-label="Petugas" class="nav-link {{ request()->routeIs('koordinator.petugas*') ? 'active' : '' }}"><i class="bi bi-people-fill"></i><span>Petugas</span></a></li>
        <li class="nav-item"><a href="{{ route('koordinator.profil') }}" data-label="Profil" class="nav-link {{ request()->routeIs('koordinator.profil') ? 'active' : '' }}"><i class="bi bi-person-circle"></i><span>Profil Saya</span></a></li>
        <li class="nav-item"><a href="{{ route('koordinator.keamanan') }}" data-label="Keamanan Akun" class="nav-link {{ request()->routeIs('koordinator.keamanan') ? 'active' : '' }}"><i class="bi bi-key-fill"></i><span>Keamanan Akun</span></a></li>
    @else
        <li class="nav-item"><a href="{{ route('pimpinan.dashboard') }}" data-label="Dashboard" class="nav-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-fill"></i><span>Dashboard</span></a></li>
        <li class="nav-item"><a href="{{ route('pimpinan.monitoring') }}" data-label="Monitoring" class="nav-link {{ request()->routeIs('pimpinan.monitoring*') ? 'active' : '' }}"><i class="bi bi-binoculars-fill"></i><span>Monitoring</span></a></li>
        <li class="nav-item"><a href="{{ route('pimpinan.surat-tugas') }}" data-label="Surat Tugas" class="nav-link {{ request()->routeIs('pimpinan.surat-tugas') ? 'active' : '' }}"><i class="bi bi-file-earmark-text-fill"></i><span>Surat Tugas</span></a></li>
        <li class="nav-item"><a href="{{ route('pimpinan.ekspor') }}" data-label="Unduh Laporan" class="nav-link {{ request()->routeIs('pimpinan.ekspor*') ? 'active' : '' }}"><i class="bi bi-download"></i><span>Unduh Laporan</span></a></li>
        <li class="nav-item"><a href="{{ route('pimpinan.profil') }}" data-label="Profil" class="nav-link {{ request()->routeIs('pimpinan.profil') ? 'active' : '' }}"><i class="bi bi-person-circle"></i><span>Profil Saya</span></a></li>
        <li class="nav-item"><a href="{{ route('pimpinan.keamanan') }}" data-label="Keamanan Akun" class="nav-link {{ request()->routeIs('pimpinan.keamanan') ? 'active' : '' }}"><i class="bi bi-key-fill"></i><span>Keamanan Akun</span></a></li>
    @endif
@endsection

@push('styles')
<style>
    .profile-avatar{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a6e7c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;box-shadow:0 4px 14px rgba(19,49,57,.25);flex-shrink:0}
    .profile-meta{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.75rem}
    .profile-meta-item{padding:.9rem 1rem;border-radius:.75rem;background:#f8fafc;border:1px solid #e5edf3}
    .profile-meta-label{font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:.25rem}
    .profile-meta-value{font-size:.86rem;font-weight:700;color:var(--text)}
</style>
@endpush

@section('content')
    <div class="card p-4 mb-4" style="background:linear-gradient(120deg,var(--primary) 0%,#2a6e7c 100%);color:#fff;border:none">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="profile-avatar">{{ strtoupper(substr($user->nama, 0, 1)) }}</div>
            <div style="min-width:0">
                <div style="font-size:1.15rem;font-weight:800;line-height:1.2">{{ $user->nama }}</div>
                <div style="font-size:.82rem;opacity:.75;margin-top:.3rem">{{ $user->nip }}</div>
                <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                    <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.75rem"><i class="bi bi-shield-check me-1"></i>{{ $user->role?->display_name ?? '-' }}</span>
                    <span class="badge" style="background:rgba(255,255,255,.15);color:#fff;font-size:.75rem"><i class="bi bi-building me-1"></i>{{ $user->upt?->short_name ?? 'Kantor Pusat' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-3" style="font-size:.85rem">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <ul class="mb-0 mt-1 ps-3">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-8">
            <form method="POST" action="{{ route($updateRoute) }}" autocomplete="off">
                @csrf @method('PUT')
                <div class="card p-4">
                    <div class="section-divider"><div class="bar"></div><h6>Data Diri</h6></div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">NIP / Username</label><input type="text" class="form-control" value="{{ $user->nip }}" disabled style="background:#f8fafc;color:var(--text-muted)"></div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" required autocomplete="off">
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="off">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3"><label class="form-label">Golongan</label><input type="text" name="golongan" class="form-control" value="{{ old('golongan', $user->golongan) }}" maxlength="10" placeholder="III/a"></div>
                        <div class="col-md-3"><label class="form-label">Pangkat</label><input type="text" name="pangkat" class="form-control" value="{{ old('pangkat', $user->pangkat) }}" placeholder="Penata"></div>
                    </div>
                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                        <a href="{{ route($dashboardRoute) }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-brand"><i class="bi bi-check2 me-1"></i>Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 h-100">
                <div class="section-divider"><div class="bar"></div><h6>Informasi Akun</h6></div>
                <div class="profile-meta mb-3">
                    <div class="profile-meta-item"><div class="profile-meta-label">Role</div><div class="profile-meta-value">{{ $user->role?->display_name ?? '-' }}</div></div>
                    <div class="profile-meta-item"><div class="profile-meta-label">UPT</div><div class="profile-meta-value">{{ $user->upt?->nama ?? 'Kantor Pusat' }}</div></div>
                    <div class="profile-meta-item"><div class="profile-meta-label">Bergabung Sejak</div><div class="profile-meta-value">{{ $user->created_at?->format('d M Y') ?? '-' }}</div></div>
                    <div class="profile-meta-item"><div class="profile-meta-label">Terakhir Diperbarui</div><div class="profile-meta-value">{{ $user->updated_at?->diffForHumans() ?? '-' }}</div></div>
                </div>
                <a href="{{ route($securityRoute) }}" class="btn btn-outline-brand w-100"><i class="bi bi-key-fill me-1"></i>Kelola Keamanan Akun</a>
            </div>
        </div>
    </div>
@endsection

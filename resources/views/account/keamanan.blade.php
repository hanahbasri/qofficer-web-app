@extends('layouts.app')

@php
    $isKoordinator = $user->hasRole('koordinator-upt');
    $updateRoute = $isKoordinator ? route('koordinator.keamanan.update') : route('pimpinan.keamanan.update');
@endphp

@section('title', 'Keamanan Akun')
@section('page-title', 'Keamanan Akun')
@section('suppress-warning-alert', '1')
@section('suppress-success-alert', '1')

@section('sidebar-menu')
    @if ($isKoordinator)
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
    @else
        <li class="nav-item">
            <a href="{{ route('pimpinan.dashboard') }}" data-label="Dashboard"
                class="nav-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i><span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pimpinan.ekspor') }}" data-label="Unduh Laporan"
                class="nav-link {{ request()->routeIs('pimpinan.ekspor*') ? 'active' : '' }}">
                <i class="bi bi-download"></i><span>Unduh Laporan</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pimpinan.profil') }}" data-label="Profil"
                class="nav-link {{ request()->routeIs('pimpinan.profil') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i><span>Profil Saya</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pimpinan.keamanan') }}" data-label="Keamanan Akun"
                class="nav-link {{ request()->routeIs('pimpinan.keamanan') ? 'active' : '' }}">
                <i class="bi bi-key-fill"></i><span>Keamanan Akun</span>
            </a>
        </li>
    @endif
@endsection

@push('styles')
    <style>
        .security-hero {
            border: none;
            background: linear-gradient(135deg, var(--primary) 0%, #2a6e7c 100%);
            color: #fff;
            overflow: hidden;
        }

        .security-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: .75rem;
        }

        .security-meta-item {
            padding: .9rem 1rem;
            border-radius: .85rem;
            background: #f8fafc;
            border: 1px solid #e5edf3;
        }

        .security-meta-label {
            font-size: .72rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .3rem;
        }

        .security-meta-value {
            font-size: .9rem;
            font-weight: 700;
            color: #111827;
        }

        .pw-field-wrap {
            position: relative;
        }

        .pw-field-wrap .pw-eye {
            position: absolute;
            right: .8rem;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #9ca3af;
            font-size: 1rem;
            padding: 0;
            z-index: 5;
        }

        .pw-field-wrap .pw-eye:hover {
            color: var(--primary);
        }

        .pw-field-wrap .form-control {
            padding-right: 2.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h1 class="page-heading mb-0">Keamanan Akun</h1>
            <p class="text-muted mb-0" style="font-size:.78rem;margin-top:.2rem">
                Ubah password akun untuk akses {{ $isKoordinator ? 'web dan mobile koordinator' : 'web pimpinan' }}.
            </p>
        </div>
    </div>

    <div class="card security-hero p-4 mb-4">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <div style="font-size:1.15rem;font-weight:800;line-height:1.2">{{ $user->nama }}</div>
                <div style="font-size:.82rem;opacity:.75;margin-top:.25rem">{{ $user->nip }} &middot; {{ $user->role?->display_name ?? '-' }}</div>
            </div>
            <span class="badge" style="background:rgba(255,255,255,.18);color:#fff;font-size:.78rem;padding:.5rem .8rem">
                Berlaku 30 hari sejak password diubah
            </span>
        </div>
    </div>

    @if ($passwordMessage)
        <div class="alert alert-warning rounded-3 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $passwordMessage }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success rounded-3 mb-3" style="font-size:.85rem" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-3" style="font-size:.85rem">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card p-4 h-100 d-flex flex-column">
                <div class="section-divider">
                    <div class="bar"></div>
                    <h6>Ubah Password</h6>
                </div>

                <form method="POST" action="{{ $updateRoute }}" autocomplete="off" class="flex-grow-1 d-flex flex-column">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                            <div class="pw-field-wrap">
                                <input type="password" name="current_password" id="currentPassword"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    required minlength="8" autocomplete="current-password"
                                    placeholder="Masukkan password yang sedang digunakan">
                                <button type="button" class="pw-eye" data-target="currentPassword" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                            <div class="pw-field-wrap">
                                <input type="password" name="password" id="newPassword"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required minlength="8" autocomplete="new-password"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" class="pw-eye" data-target="newPassword" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <div class="pw-field-wrap">
                                <input type="password" name="password_confirmation" id="confirmPassword"
                                    class="form-control" required minlength="8" autocomplete="new-password"
                                    placeholder="Ulangi password baru">
                                <button type="button" class="pw-eye" data-target="confirmPassword" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-auto pt-4">
                        <a href="{{ route($dashboardRoute) }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-brand">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 h-100">
                <div class="section-divider">
                    <div class="bar"></div>
                    <h6>Status Password</h6>
                </div>
                <div class="security-meta">
                    <div class="security-meta-item">
                        <div class="security-meta-label">Status</div>
                        <div class="security-meta-value">
                            @if ($user->must_change_password)
                                Wajib diganti
                            @elseif ($user->isPasswordExpired())
                                Kedaluwarsa
                            @else
                                Aktif
                            @endif
                        </div>
                    </div>
                    <div class="security-meta-item">
                        <div class="security-meta-label">Terakhir Diubah</div>
                        <div class="security-meta-value">
                            {{ $user->password_changed_at?->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>
                    <div class="security-meta-item">
                        <div class="security-meta-label">Berlaku Sampai</div>
                        <div class="security-meta-value">
                            {{ $user->password_expires_at?->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="alert alert-light border mt-3 mb-0" style="font-size:.8rem;color:#4b5563">
                    <i class="bi bi-info-circle me-2"></i>
                    Jika password direset oleh atasan atau admin, password baru wajib segera diganti dan masa berlakunya
                    akan dihitung ulang selama 30 hari.
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.pw-eye').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });
    </script>
@endpush

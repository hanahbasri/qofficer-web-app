@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

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

@push('styles')
<style>
    .profil-avatar {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), #2a6e7c);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: #fff; font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(19,49,57,.25);
    }
    .pw-field-wrap { position: relative; }
    .pw-field-wrap .pw-eye {
        position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: #9ca3af; cursor: pointer;
        padding: 0; font-size: .95rem; z-index: 5;
    }
    .pw-field-wrap .pw-eye:hover { color: var(--primary); }
    .pw-field-wrap .form-control { padding-right: 2.4rem; }
</style>
@endpush

@section('content')

    {{-- Header hero card --}}
    <div class="card p-4 mb-4" style="background:linear-gradient(120deg,var(--primary) 0%,#2a6e7c 100%);color:#fff;border:none">
        <div class="d-flex align-items-center gap-3">
            <div class="profil-avatar">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
            <div>
                <div style="font-size:1.1rem;font-weight:800;line-height:1.2">{{ $user->nama }}</div>
                <div style="font-size:.8rem;opacity:.75;margin-top:.3rem">{{ $user->nip }}</div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.75rem">
                        <i class="bi bi-shield-check me-1"></i>{{ $user->role?->display_name ?? '-' }}
                    </span>
                    <span class="badge" style="background:rgba(255,255,255,.15);color:#fff;font-size:.75rem">
                        <i class="bi bi-building me-1"></i>{{ $user->upt?->short_name ?? 'Kantor Pusat' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-3" style="font-size:.85rem">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profil.update') }}" autocomplete="off">
        @csrf @method('PUT')
        <input type="text" style="display:none" name="fakeuser">
        <input type="password" style="display:none" name="fakepass">

        <div class="row g-3">
            <div class="col-lg-8">

                <div class="card p-4 mb-3">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>Data Diri</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">NIP / Username</label>
                            <input type="text" class="form-control" value="{{ $user->nip }}" disabled
                                style="background:#f8fafc;color:var(--text-muted)">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $user->nama) }}" required autocomplete="off">
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required autocomplete="off">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Golongan</label>
                            <input type="text" name="golongan" class="form-control"
                                value="{{ old('golongan', $user->golongan) }}" maxlength="10" placeholder="III/a">
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Pangkat</label>
                            <input type="text" name="pangkat" class="form-control"
                                value="{{ old('pangkat', $user->pangkat) }}" placeholder="Penata">
                        </div>
                    </div>
                </div>

                <div class="card p-4">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>Ubah Password
                            <span class="fw-normal text-muted" style="font-size:.78rem">- kosongkan jika tidak diubah</span>
                        </h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Password Baru</label>
                            <div class="pw-field-wrap">
                                <input type="password" name="password" id="pw1"
                                    class="form-control @error('password') is-invalid @enderror"
                                    minlength="8" placeholder="Minimal 8 karakter" autocomplete="new-password">
                                <button type="button" class="pw-eye" data-target="pw1" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="pw-field-wrap">
                                <input type="password" name="password_confirmation" id="pw2" class="form-control"
                                    minlength="8" placeholder="Ulangi password baru" autocomplete="new-password">
                                <button type="button" class="pw-eye" data-target="pw2" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4" style="position:sticky;top:72px">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>Informasi Akun</h6>
                    </div>
                    <div class="d-flex flex-column gap-3 mb-4">
                        <div>
                            <div class="info-label mb-1">Status</div>
                            <span class="badge badge-status-aktif">Aktif</span>
                        </div>
                        <div>
                            <div class="info-label mb-1">Bergabung sejak</div>
                            <span style="font-size:.83rem;color:var(--text)">{{ $user->created_at?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div>
                            <div class="info-label mb-1">Terakhir diperbarui</div>
                            <span style="font-size:.83rem;color:var(--text)">{{ $user->updated_at?->diffForHumans() ?? '-' }}</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-brand w-100">
                        <i class="bi bi-check2 me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
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

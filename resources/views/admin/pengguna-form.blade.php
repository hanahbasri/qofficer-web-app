@extends('layouts.app')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('page-title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

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
        .pw-field-wrap {
            position: relative;
        }

        .pw-field-wrap .pw-eye {
            position: absolute;
            right: .75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            font-size: .95rem;
            line-height: 1;
            z-index: 5;
        }

        .pw-field-wrap .pw-eye:hover {
            color: var(--primary);
        }

        .pw-field-wrap .form-control {
            padding-right: 2.4rem;
        }

    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.pengguna') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-heading mb-0">{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h1>
            <p class="text-muted mb-0" style="font-size:.76rem;margin-top:.15rem">
                {{ isset($user) ? 'Ubah data akun: ' . $user->nama : 'Buat akun pengguna sistem baru' }}
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-3" style="font-size:.85rem">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST"
        action="{{ isset($user) ? route('admin.pengguna.update', $user->id) : route('admin.pengguna.store') }}"
        autocomplete="off">
        @csrf
        @if (isset($user))
            @method('PUT')
        @endif

        {{-- Dummy hidden fields to prevent browser autofill --}}
        <input type="text" style="display:none" name="fakeuser">
        <input type="password" style="display:none" name="fakepass">

        <div class="row g-3">
            {{-- Left column --}}
            <div class="col-lg-8">
                <div class="card p-4 mb-3">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>Identitas Pengguna</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">NIP / Username <span class="text-danger">*</span></label>
                            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                value="{{ old('nip', $user->nip ?? '') }}" required maxlength="30"
                                placeholder="1970123456789001" autocomplete="off">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $user->nama ?? '') }}" required placeholder="Dr. Budi Santoso, S.P."
                                autocomplete="off">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email ?? '') }}" required
                                placeholder="nama@karantinaindonesia.go.id" autocomplete="off">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Golongan</label>
                            <input type="text" name="golongan" class="form-control"
                                value="{{ old('golongan', $user->golongan ?? '') }}" maxlength="10" placeholder="III/a"
                                autocomplete="off">
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Pangkat</label>
                            <input type="text" name="pangkat" class="form-control"
                                value="{{ old('pangkat', $user->pangkat ?? '') }}" placeholder="Penata" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="card p-4 mb-3">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>Hak Akses &amp; Unit Kerja</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                <option value="">- Pilih Role -</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">UPT</label>
                            <select name="upt_id" id="uptSelectForm" class="form-select @error('upt_id') is-invalid @enderror">
                                <option value="">- Pilih UPT -</option>
                                @foreach ($uptList as $upt)
                                    <option value="{{ $upt->kode }}"
                                        {{ old('upt_id', $user->upt_id ?? '') == $upt->kode ? 'selected' : '' }}>
                                        {{ $upt->short_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('upt_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text" style="font-size:.75rem">Opsional untuk Super Admin dan Pimpinan nasional.</div>
                        </div>
                    </div>
                </div>

                <div class="card p-4">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>
                            Password
                            @if (isset($user))
                                <span class="fw-normal text-muted" style="font-size:.8rem">- kosongkan jika tidak
                                    diubah</span>
                            @endif
                        </h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Password {{ !isset($user) ? '*' : '' }}</label>
                            <div class="pw-field-wrap">
                                <input type="password" name="password" id="pw1"
                                    class="form-control @error('password') is-invalid @enderror"
                                    {{ !isset($user) ? 'required' : '' }} minlength="8" placeholder="Minimal 8 karakter"
                                    autocomplete="new-password">
                                <button type="button" class="pw-eye" data-target="pw1" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="pw-field-wrap">
                                <input type="password" name="password_confirmation" id="pw2" class="form-control"
                                    minlength="8" placeholder="Ulangi password" autocomplete="new-password">
                                <button type="button" class="pw-eye" data-target="pw2" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-light border mt-3 mb-0" style="font-size:.78rem;color:#4b5563">
                        <i class="bi bi-info-circle me-2"></i>
                        Jika password diisi atau direset oleh admin, pengguna wajib mengganti password saat login dan
                        masa berlakunya dihitung selama 30 hari.
                    </div>
                </div>
            </div>

            {{-- Right column --}}
            <div class="col-lg-4">
                <div class="card p-4" style="position:sticky;top:72px">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>Simpan</h6>
                    </div>
                    <p class="text-muted mb-3" style="font-size:.82rem;line-height:1.6">
                        Pastikan data yang diisi sudah benar sebelum menyimpan.
                    </p>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-brand">
                            <i class="bi bi-check2 me-1"></i>
                            {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                        </button>
                        <a href="{{ route('admin.pengguna') }}" class="btn btn-ghost">Batal</a>
                    </div>

                    @if (isset($user))
                        <div class="mt-3 pt-3 border-top">
                            <div class="info-label mb-1">Status Akun</div>
                            @if ($user->is_active)
                                <span class="badge badge-status-aktif">Aktif</span>
                            @else
                                <span class="badge badge-rekomendasi-penolakan">Nonaktif</span>
                            @endif
                            <div class="text-muted mt-2" style="font-size:.73rem">
                                Dibuat: {{ $user->created_at?->format('d M Y') }}
                            </div>
                        </div>
                    @endif
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

@extends('layouts.app')

@section('title', 'Manajemen Role')
@section('page-title', 'Manajemen Role')

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
        .role-card {
            background: #fff;
            border: 1px solid var(--border);
            border-left: 3px solid var(--primary);
            border-radius: var(--radius);
            padding: 1rem 1.1rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .role-card-head {
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .role-card-icon {
            width: 40px;
            height: 40px;
            border-radius: .5rem;
            background: var(--bg);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .role-card-body {
            min-width: 0;
        }

        .role-card-name {
            font-size: .9rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }

        .role-card-slug {
            font-size: .7rem;
            color: var(--text-muted);
            font-family: monospace;
            margin-top: .12rem;
            white-space: nowrap;
        }

        .role-card-foot {
            margin-top: .9rem;
            padding-top: .7rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .role-card-foot .lbl {
            font-size: .72rem;
            color: var(--text-muted);
        }

        .role-card-foot .val {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="page-heading mb-0">Manajemen Role</h1>
            <p class="text-muted mb-0" style="font-size:.78rem;margin-top:.2rem">Peran dan hak akses dalam sistem</p>
        </div>
    </div>

    {{-- Role cards --}}
    @php
        $roleIcons = [
            'super-admin' => ['icon' => 'bi-shield-fill-check', 'bg' => 'rgba(239,68,68,.1)', 'color' => '#b91c1c'],
            'koordinator-upt' => ['icon' => 'bi-diagram-3-fill', 'bg' => 'rgba(59,130,246,.1)', 'color' => '#1d4ed8'],
            'petugas-lapangan' => [
                'icon' => 'bi-person-badge-fill',
                'bg' => 'rgba(16,185,129,.1)',
                'color' => '#065f46',
            ],
            'pimpinan' => ['icon' => 'bi-star-fill', 'bg' => 'rgba(245,158,11,.1)', 'color' => '#92400e'],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @forelse($roles as $role)
            @php $ri = $roleIcons[$role->name] ?? ['icon' => 'bi-person', 'bg' => 'rgba(19,49,57,.08)', 'color' => 'var(--primary)']; @endphp
            <div class="col-sm-6 col-lg-3">
                <div class="role-card">
                    <div class="role-card-head">
                        <div class="role-card-icon">
                            <i class="bi {{ $ri['icon'] }}"></i>
                        </div>
                        <div class="role-card-body">
                            <div class="role-card-name">{{ $role->display_name }}</div>
                            <div class="role-card-slug">{{ $role->name }}</div>
                        </div>
                    </div>
                    <div class="role-card-foot">
                        <span class="lbl">Total pengguna</span>
                        <span class="val">{{ $role->users_count }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">Belum ada data role.</div>
        @endforelse
    </div>

    <div class="d-flex align-items-start gap-2 p-3 rounded-3"
        style="background:#fff9e6;border:1px solid rgba(254,197,89,.4);font-size:.83rem;color:#5a4a00">
        <i class="bi bi-info-circle-fill flex-shrink-0" style="color:#c88a00;font-size:1rem;line-height:1"></i>
        <span>Role telah ditetapkan dalam sistem dan tidak dapat ditambah atau dihapus. Untuk mengubah role pengguna,
            gunakan menu
            <a href="{{ route('admin.pengguna') }}" class="fw-semibold" style="color:#5a4a00">Pengguna</a>.</span>
    </div>
@endsection

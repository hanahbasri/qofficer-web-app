@extends('layouts.app')

@section('title', 'Log Sistem')
@section('page-title', 'Log Sistem')

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
        .log-summary-card {
            background: #fff;
            border: 1px solid var(--border);
            border-left: 3px solid var(--primary);
            border-radius: var(--radius);
            padding: 1rem 1.1rem;
            height: 100%;
            display: flex;
            align-items: center;
            gap: .85rem;
        }

        .log-card-icon {
            width: 40px;
            height: 40px;
            border-radius: .5rem;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            color: var(--primary);
            flex-shrink: 0;
        }

        .log-card-body {
            flex: 1;
            min-width: 0;
        }

        .log-summary-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .05em;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .log-summary-value {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1.1;
            margin-top: .2rem;
        }

        .log-summary-value-sm {
            font-size: .88rem;
            font-weight: 600;
        }

        .log-chip {
            display: inline-flex;
            align-items: center;
            padding: .25rem .55rem;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 700;
            line-height: 1;
        }

        .log-chip-meta {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        /* panel detail aktivitas — rapi, key-value */
        .log-meta {
            display: grid;
            gap: .18rem;
            background: #f8fafc;
            border: 1px solid #eef0f3;
            border-radius: .5rem;
            padding: .5rem .65rem;
            max-width: 420px;
        }

        .log-meta-row {
            display: flex;
            gap: .5rem;
            font-size: .72rem;
            line-height: 1.45;
        }

        .log-meta-key {
            color: var(--text-muted);
            font-weight: 600;
            min-width: 96px;
            flex-shrink: 0;
        }

        .log-meta-val {
            color: var(--text);
            overflow-wrap: anywhere;
        }
    </style>
@endpush

@section('content')
    @php
        $moduleColors = [
            'autentikasi' => ['bg' => 'rgba(59,130,246,.1)', 'color' => '#1d4ed8'],
            'pengguna' => ['bg' => 'rgba(16,185,129,.12)', 'color' => '#047857'],
            'role' => ['bg' => 'rgba(249,115,22,.12)', 'color' => '#c2410c'],
            'upt' => ['bg' => 'rgba(168,85,247,.12)', 'color' => '#7c3aed'],
            'profil' => ['bg' => 'rgba(236,72,153,.12)', 'color' => '#be185d'],
        ];
        $actionColors = [
            'login' => ['bg' => 'rgba(16,185,129,.12)', 'color' => '#047857'],
            'logout' => ['bg' => 'rgba(107,114,128,.12)', 'color' => '#4b5563'],
            'tambah' => ['bg' => 'rgba(59,130,246,.12)', 'color' => '#1d4ed8'],
            'ubah' => ['bg' => 'rgba(245,158,11,.14)', 'color' => '#b45309'],
            'aktifkan' => ['bg' => 'rgba(5,150,105,.12)', 'color' => '#047857'],
            'nonaktifkan' => ['bg' => 'rgba(239,68,68,.12)', 'color' => '#b91c1c'],
            'atur-role' => ['bg' => 'rgba(124,58,237,.12)', 'color' => '#6d28d9'],
        ];
    @endphp

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="page-heading mb-0">Log Sistem</h1>
            <p class="text-muted mb-0" style="font-size:.78rem;margin-top:.2rem">
                Riwayat aktivitas penting yang dilakukan oleh super admin.
            </p>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="log-summary-card">
                <div class="log-card-icon">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div class="log-card-body">
                    <div class="log-summary-label">Total Log</div>
                    <div class="log-summary-value">{{ number_format($summary['total']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="log-summary-card">
                <div class="log-card-icon">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="log-card-body">
                    <div class="log-summary-label">Hari Ini</div>
                    <div class="log-summary-value">{{ number_format($summary['today']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="log-summary-card">
                <div class="log-card-icon">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div class="log-card-body">
                    <div class="log-summary-label">Bulan Ini</div>
                    <div class="log-summary-value">{{ number_format($summary['month']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="log-summary-card">
                <div class="log-card-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="log-card-body">
                    <div class="log-summary-label">Aktivitas Terakhir</div>
                    <div class="log-summary-value log-summary-value-sm">
                        @if($summary['lastActivity'])
                            {{ $summary['lastActivity']->format('d/m/Y H:i') }}
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3 mb-3">
        <form method="GET" id="logFilterForm" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Cari</label>
                <input type="text" name="search" id="logSearchInput" class="form-control form-control-sm"
                    placeholder="Cari deskripsi, admin, atau subjek..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Modul</label>
                <select name="module" class="form-select form-select-sm log-auto-filter">
                    <option value="">Semua Modul</option>
                    @foreach ($modules as $module)
                        <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $module)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Aksi</label>
                <select name="action" class="form-select form-select-sm log-auto-filter">
                    <option value="">Semua Aksi</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="text-muted mb-2" style="font-size:.73rem;line-height:1.45">
                    Filter otomatis saat mengetik atau memilih opsi.
                </div>
                <a href="{{ route('admin.log-sistem') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="min-width:135px">Waktu</th>
                        <th style="min-width:180px">Admin</th>
                        <th style="min-width:110px">Modul</th>
                        <th style="min-width:110px">Aksi</th>
                        <th style="min-width:320px">Aktivitas</th>
                        <th style="min-width:160px">Akses</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php
                            $moduleStyle = $moduleColors[$log->module] ?? ['bg' => 'rgba(19,49,57,.1)', 'color' => 'var(--primary)'];
                            $actionStyle = $actionColors[$log->action] ?? ['bg' => 'rgba(19,49,57,.1)', 'color' => 'var(--primary)'];
                            $properties = collect($log->properties ?? [])->filter(fn($value) => filled($value));
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:.82rem">{{ $log->created_at?->format('d M Y') }}</div>
                                <div style="font-size:.73rem;color:var(--text-muted)">{{ $log->created_at?->format('H:i:s') }}</div>
                            </td>
                            <td>
                                @if ($log->user)
                                    <div class="fw-semibold" style="font-size:.84rem">{{ $log->user->nama }}</div>
                                    <div style="font-size:.72rem;color:var(--text-muted)">
                                        {{ $log->user->nip }} &middot; {{ $log->user->role?->display_name ?? 'Super Admin' }}
                                    </div>
                                @else
                                    <div class="fw-semibold" style="font-size:.84rem">Sistem</div>
                                    <div style="font-size:.72rem;color:var(--text-muted)">Tanpa pengguna</div>
                                @endif
                            </td>
                            <td>
                                <span class="log-chip" style="background:{{ $moduleStyle['bg'] }};color:{{ $moduleStyle['color'] }}">
                                    {{ ucfirst(str_replace('-', ' ', $log->module)) }}
                                </span>
                            </td>
                            <td>
                                <span class="log-chip" style="background:{{ $actionStyle['bg'] }};color:{{ $actionStyle['color'] }}">
                                    {{ ucfirst(str_replace('-', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:.84rem;line-height:1.5">{{ $log->description }}</div>
                                @if ($log->subject_type || $log->subject_id)
                                    <div style="font-size:.72rem;color:var(--text-muted);margin-top:.2rem">
                                        Subjek: {{ $log->subject_type ?? 'Data' }}{{ $log->subject_id ? ' #' . $log->subject_id : '' }}
                                    </div>
                                @endif
                                @if ($properties->isNotEmpty())
                                    <div class="log-meta mt-2">
                                        @foreach ($properties as $key => $value)
                                            <div class="log-meta-row">
                                                <span class="log-meta-key">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                                <span class="log-meta-val">{{ is_scalar($value) ? $value : json_encode($value) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:.78rem">{{ $log->ip_address ?? '-' }}</div>
                                <div style="font-size:.7rem;color:var(--text-muted);margin-top:.2rem">
                                    {{ \Illuminate\Support\Str::limit($log->user_agent ?? '-', 55) }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-journal-text"
                                    style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                                <span class="text-muted">Belum ada log sistem yang tercatat.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="px-3 py-2 border-top" style="background:#f8fafc">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const form = document.getElementById('logFilterForm');
            const searchInput = document.getElementById('logSearchInput');
            const selects = document.querySelectorAll('.log-auto-filter');

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

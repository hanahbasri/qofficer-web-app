<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Q-Officer System') — Barantin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Prevent browser UA / Bootstrap italic from affecting Bootstrap Icons */
        .bi {
            font-style: normal !important;
        }

        :root {
            --primary: #133139;
            --primary-dark: #0c2228;
            --primary-mid: #1e4a56;
            --gold: #FEC559;
            --maroon: #522E2E;
            --bg: #f4f6f8;
            --surface: #ffffff;
            --border: #e2e8ed;
            --text: #111827;
            --text-muted: #6b7280;
            --sidebar-w: 252px;
            --sidebar-w-sm: 64px;
            --topbar-h: 60px;
            --radius: .65rem;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: .9rem;
            margin: 0;
        }

        /* ─── Sidebar ──────────────────────────────────────────── */
        #sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--primary);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 300;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width .22s ease;
        }

        #sidebar::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .15);
            border-radius: 4px;
        }

        /* ── Collapsed state (desktop) ── */
        body.sidebar-collapsed #sidebar {
            width: var(--sidebar-w-sm);
        }

        body.sidebar-collapsed #topbar {
            left: var(--sidebar-w-sm);
        }

        body.sidebar-collapsed #main-content {
            margin-left: var(--sidebar-w-sm);
        }

        body.sidebar-collapsed .sidebar-logo-text,
        body.sidebar-collapsed .sidebar-section,
        body.sidebar-collapsed .sidebar-user-info,
        body.sidebar-collapsed .sidebar-nav .nav-link span {
            display: none;
        }

        body.sidebar-collapsed .sidebar-logo {
            justify-content: center;
            padding: 1rem .5rem;
        }

        body.sidebar-collapsed .sidebar-nav {
            padding: 0 .4rem;
        }

        body.sidebar-collapsed .sidebar-nav .nav-link {
            justify-content: center;
            padding: .6rem;
            position: relative;
        }

        body.sidebar-collapsed .sidebar-nav .nav-link i {
            width: auto;
            font-size: 1.1rem;
        }

        body.sidebar-collapsed .sidebar-footer {
            padding: .6rem .4rem;
        }

        body.sidebar-collapsed .sidebar-user {
            justify-content: center;
        }

        body.sidebar-collapsed .sidebar-avatar {
            margin: 0 auto;
        }

        body.sidebar-collapsed .btn-logout {
            padding: .5rem;
            justify-content: center;
        }

        body.sidebar-collapsed .btn-logout span {
            display: none;
        }

        /* tooltip on collapsed */
        body.sidebar-collapsed .sidebar-nav .nav-link::after {
            content: attr(data-label);
            position: absolute;
            left: calc(var(--sidebar-w-sm) - .4rem);
            background: var(--primary-dark);
            color: #fff;
            font-size: .75rem;
            font-weight: 600;
            white-space: nowrap;
            padding: .3rem .75rem;
            border-radius: .4rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity .15s;
            z-index: 400;
        }

        body.sidebar-collapsed .sidebar-nav .nav-link:hover::after {
            opacity: 1;
        }

        .sidebar-logo {
            padding: 1rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .7rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            flex-shrink: 0;
            min-height: var(--topbar-h);
        }

        .sidebar-logo-icon {
            width: 34px;
            height: 34px;
            background: var(--gold);
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            color: var(--primary);
            flex-shrink: 0;
        }

        .sidebar-logo-text strong {
            display: block;
            color: #fff;
            font-size: .88rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .sidebar-logo-text span {
            display: block;
            color: rgba(255, 255, 255, .45);
            font-size: .66rem;
        }

        .sidebar-section {
            padding: .85rem 1.1rem .2rem;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .3);
            text-transform: uppercase;
            white-space: nowrap;
        }

        .sidebar-nav {
            padding: 0 .55rem;
            flex: 1;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            color: rgba(255, 255, 255, .65);
            padding: .5rem .8rem;
            margin-bottom: .1rem;
            border-radius: .45rem;
            font-size: .84rem;
            font-weight: 500;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }

        .sidebar-nav .nav-link i {
            font-size: .95rem;
            width: 1rem;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .9);
        }

        .sidebar-nav .nav-link.active {
            background: var(--gold);
            color: var(--primary);
            font-weight: 700;
        }

        .sidebar-footer {
            padding: .9rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, .08);
            flex-shrink: 0;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: .65rem;
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;
            background: rgba(255, 255, 255, .12);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            color: rgba(255, 255, 255, .8);
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-size: .8rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            max-width: 165px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .sidebar-user-upt {
            font-size: .68rem;
            color: rgba(255, 255, 255, .4);
            word-break: break-word;
            overflow-wrap: break-word;
            max-width: 165px;
            line-height: 1.3;
            margin-top: .2rem;
        }

        .btn-logout {
            width: 100%;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .1);
            color: rgba(255, 255, 255, .7);
            border-radius: 999px;
            padding: .4rem .9rem;
            font-size: .78rem;
            font-weight: 600;
            transition: background .15s, color .15s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, .14);
            color: #fff;
        }

        /* ─── Topbar ────────────────────────────────────────────── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            z-index: 200;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: .75rem;
            transition: left .22s ease;
        }

        .topbar-toggle {
            background: none;
            border: 1.5px solid var(--border);
            color: var(--text-muted);
            font-size: 1rem;
            cursor: pointer;
            padding: .28rem .5rem;
            border-radius: .45rem;
            line-height: 1;
            transition: background .15s, color .15s;
            flex-shrink: 0;
        }

        .topbar-toggle:hover {
            background: var(--bg);
            color: var(--primary);
        }

        .topbar-title {
            flex: 1;
            font-size: .96rem;
            font-weight: 800;
            color: var(--text);
        }

        .topbar-role {
            font-size: .72rem;
            font-weight: 700;
            background: rgba(19, 49, 57, .1);
            color: var(--primary);
            border: 1px solid rgba(19, 49, 57, .15);
            border-radius: 999px;
            padding: .25rem .75rem;
        }

        .topbar-upt {
            font-size: .76rem;
            color: var(--text-muted);
            max-width: 300px;
            word-break: break-word;
            overflow-wrap: break-word;
            line-height: 1.4;
        }

        /* ─── Main ──────────────────────────────────────────────── */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 1.75rem;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ─── Cards ─────────────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
        }

        .card-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            font-weight: 700;
            font-size: .88rem;
            padding: .9rem 1.2rem;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        /* ─── Stat cards ────────────────────────────────────────── */
        .stat-card {
            transition: transform .15s, box-shadow .15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 49, 57, .1);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: .55rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1;
            color: var(--text);
        }

        .stat-label {
            font-size: .74rem;
            color: var(--text-muted);
            margin-top: .2rem;
            font-weight: 500;
        }

        /* ─── Tables ────────────────────────────────────────────── */
        .table> :not(caption)>*>* {
            padding: .7rem 1rem;
        }

        .table thead th {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--text-muted);
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table tbody tr {
            border-bottom: 1px solid #f0f4f7;
            transition: background .1s;
        }

        .table tbody tr:hover {
            background: #f8fbfc;
        }

        .table tbody tr:last-child {
            border-bottom: none;
        }

        /* ─── Buttons (all pill-shaped) ─────────────────────────── */
        .btn {
            border-radius: 999px !important;
            font-weight: 600;
            font-size: .82rem;
            letter-spacing: .01em;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-brand {
            background: var(--primary);
            border: none;
            color: #fff;
            padding: .45rem 1.15rem;
        }

        .btn-brand:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .btn-gold {
            background: var(--gold);
            border: none;
            color: var(--primary);
            padding: .45rem 1.15rem;
            font-weight: 700;
        }

        .btn-gold:hover {
            background: #f5b840;
            color: var(--primary);
        }

        .btn-outline-brand {
            border: 1.5px solid var(--primary);
            color: var(--primary);
            background: transparent;
            padding: .4rem 1rem;
        }

        .btn-outline-brand:hover {
            background: var(--primary);
            color: #fff;
        }

        .btn-ghost {
            border: 1.5px solid var(--border);
            color: var(--text-muted);
            background: transparent;
            padding: .4rem 1rem;
        }

        .btn-ghost:hover {
            background: var(--bg);
            color: var(--text);
        }

        .btn-outline-danger {
            border: 1.5px solid #fca5a5;
            color: #b91c1c;
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #f87171;
        }

        .btn-outline-success {
            border: 1.5px solid #6ee7b7;
            color: #0f6b3a;
            background: transparent;
        }

        .btn-outline-success:hover {
            background: #e6f9f0;
            color: #0f6b3a;
            border-color: #34d399;
        }

        /* Override Bootstrap defaults to be pill */
        .btn-outline-secondary {
            border-radius: 999px !important;
        }

        .btn-outline-primary {
            border-radius: 999px !important;
        }

        .btn-outline-danger {
            border-radius: 999px !important;
        }

        .btn-outline-success {
            border-radius: 999px !important;
        }

        .btn-sm {
            padding: .35rem .85rem !important;
            font-size: .78rem !important;
        }

        /* ─── Badges ─────────────────────────────────────────────── */
        .badge {
            font-weight: 600;
            font-size: .71rem;
            padding: .3em .7em;
            border-radius: .35rem;
            letter-spacing: .02em;
        }

        .badge-status-tertunda {
            background: #fff3e0;
            color: #c84b00;
        }

        .badge-status-aktif {
            background: #e6f9f0;
            color: #0f6b3a;
        }

        .badge-status-dikirim {
            background: #e8f0fe;
            color: #1a56a8;
        }

        .badge-status-selesai {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .badge-rekomendasi-pelepasan {
            background: #e6f9f0;
            color: #0f6b3a;
        }

        .badge-rekomendasi-penolakan {
            background: #fde8e8;
            color: #b91c1c;
        }

        .badge-rekomendasi-perlakuan {
            background: #fff3e0;
            color: #c84b00;
        }

        .badge-rekomendasi-pemusnahan {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .badge-role {
            background: rgba(254, 197, 89, .2);
            color: #7a5200;
            border: 1px solid rgba(254, 197, 89, .4);
        }

        /* ─── Hide browser native password reveal button ────────── */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
        }

        /* ─── Forms ──────────────────────────────────────────────── */
        .form-control,
        .form-select {
            border: 1.5px solid #d1dbe2;
            border-radius: .55rem !important;
            padding: .6rem .9rem;
            font-size: .875rem;
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .18rem rgba(19, 49, 57, .15);
            background: #fff;
        }

        .form-label {
            font-weight: 600;
            font-size: .78rem;
            color: #374151;
            margin-bottom: .35rem;
        }

        .input-group-text {
            background: #f4f6f8;
            border: 1.5px solid #d1dbe2;
            font-size: .875rem;
            color: var(--text-muted);
        }

        /* ─── Alerts ─────────────────────────────────────────────── */
        .alert {
            border: none;
            border-radius: .55rem;
            font-size: .85rem;
            padding: .7rem 1rem .7rem 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .alert .btn-close {
            margin-left: auto;
            flex-shrink: 0;
            padding: 0;
            width: 1.2rem;
            height: 1.2rem;
            opacity: .5;
        }

        .alert-success {
            background: #e6f9f0;
            color: #0f6b3a;
        }

        .alert-danger {
            background: #fde8e8;
            color: #b91c1c;
        }

        .alert-warning {
            background: #fff9e6;
            color: #7a5200;
        }

        /* ─── Custom select arrow ────────────────────────────────── */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23133139' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right .75rem center;
            background-size: 14px 10px;
            padding-right: 2.4rem;
        }

        /* ─── Section divider ────────────────────────────────────── */
        .section-divider {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-bottom: 1rem;
        }

        .section-divider .bar {
            width: 4px;
            height: 18px;
            background: var(--gold);
            border-radius: 2px;
        }

        .section-divider h6 {
            margin: 0;
            font-weight: 800;
            font-size: .9rem;
            color: var(--text);
        }

        /* ─── Info block ─────────────────────────────────────────── */
        .info-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: .2rem;
        }

        .info-value {
            font-size: .88rem;
            font-weight: 500;
            color: var(--text);
        }

        /* ─── Sidebar overlay (mobile) ───────────────────────────── */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            z-index: 299;
        }

        #sidebar-overlay.show {
            display: block;
        }

        /* ─── Page heading ───────────────────────────────────────── */
        .page-heading {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 1.35rem;
            line-height: 1.2;
        }

        .page-heading small {
            display: block;
            font-size: .76rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-top: .18rem;
        }

        /* ─── Mobile ─────────────────────────────────────────────── */
        @media (max-width: 991px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-w) !important;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #topbar {
                left: 0 !important;
            }

            #main-content {
                margin-left: 0 !important;
                padding: 1.25rem;
            }

            .topbar-upt {
                display: none;
            }
        }

        @media (max-width: 576px) {
            #main-content {
                padding: 1rem;
            }
        }
    </style>

    <!-- Select2 CSS for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            border: 1.5px solid #e5e7eb;
            border-radius: .7rem;
            padding: 0;
            height: auto;
            background: #f9fafb;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: .65rem .9rem;
            line-height: 1;
            color: #111827;
            font-size: .9rem;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default .select2-selection--single:hover {
            border-color: #133139;
            box-shadow: 0 0 0 .17rem rgba(19, 49, 57, .14);
            background: #ffffff;
        }

        .select2-dropdown {
            border: 1.5px solid #e5e7eb;
            border-radius: .7rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
        }

        .select2-results__group {
            color: #374151;
            font-weight: 600;
            font-size: .85rem;
            padding: .65rem .85rem;
        }

        .select2-results__option {
            padding: .65rem .85rem;
            font-size: .9rem;
        }

        .select2-results__option--highlighted {
            background: #133139;
        }

        .select2-search__field {
            border: none;
            padding: .65rem .9rem;
            font-size: .9rem;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- Sidebar --}}
    <nav id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <i class="bi bi-shield-fill-check"></i>
            </div>
            <div class="sidebar-logo-text">
                <strong>Q-Officer</strong>
                <span>@php
                    echo match (Auth::user()?->getRoleName()) {
                        'koordinator-upt' => 'Koordinator UPT',
                        'pimpinan' => 'Pimpinan',
                        'super-admin' => 'Admin Sistem',
                        default => 'Q-Officer System',
                    };
                @endphp</span>
            </div>
        </div>

        <div class="sidebar-section">Menu</div>
        <div class="sidebar-nav">
            <ul class="nav flex-column">
                @yield('sidebar-menu')
            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="sidebar-user-info" style="min-width:0">
                    <div class="sidebar-user-name">{{ Auth::user()->nama ?? '-' }}</div>
                    <div class="sidebar-user-upt">{{ Auth::user()->upt?->nama ?? 'Kantor Pusat' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-left"></i><span>Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    {{-- Topbar --}}
    <header id="topbar">
        <button class="topbar-toggle" onclick="toggleSidebar()" type="button" title="Toggle sidebar">
            <i class="bi bi-layout-sidebar" id="sidebarToggleIcon"></i>
        </button>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-2">
            <span class="topbar-upt d-none d-lg-block">{{ Auth::user()->upt?->nama ?? 'Kantor Pusat' }}</span>
            <span class="topbar-role">{{ Auth::user()->role?->display_name ?? '' }}</span>
        </div>
    </header>

    {{-- Content --}}
    <main id="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5s
        document.querySelectorAll('.alert.alert-dismissible').forEach(function(el) {
            setTimeout(function() {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            }, 5000);
        });

        function toggleSidebar() {
            if (window.innerWidth <= 991) {
                // mobile: slide in/out
                document.getElementById('sidebar').classList.toggle('show');
                document.getElementById('sidebar-overlay').classList.toggle('show');
            } else {
                // desktop: collapse to icon-only
                document.body.classList.toggle('sidebar-collapsed');
                const collapsed = document.body.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
            }
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebar-overlay').classList.remove('show');
        }
        // restore state on load
        (function() {
            if (window.innerWidth > 991 && localStorage.getItem('sidebarCollapsed') === '1') {
                document.body.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>

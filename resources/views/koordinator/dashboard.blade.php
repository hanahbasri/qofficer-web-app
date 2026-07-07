@extends('layouts.app')

@section('title', 'Dashboard Koordinator')
@section('page-title', 'Dashboard')

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
    /* ── Hero welcome banner ─────────────────────────────────── */
    .dash-hero {
        background: linear-gradient(135deg, #133139 0%, #1e4a56 60%, #276475 100%);
        border-radius: 1rem;
        padding: 1.75rem 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .dash-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    .dash-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; right: 80px;
        width: 160px; height: 160px;
        background: rgba(254,197,89,.08);
        border-radius: 50%;
    }
    .dash-hero-greeting {
        font-size: .8rem;
        opacity: .75;
        letter-spacing: .05em;
        text-transform: uppercase;
        font-weight: 600;
    }
    .dash-hero-name {
        font-size: 1.55rem;
        font-weight: 800;
        line-height: 1.2;
        margin: .3rem 0 .4rem;
    }
    .dash-hero-upt {
        font-size: .82rem;
        opacity: .7;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .dash-hero-date {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: .5rem;
        padding: .5rem .9rem;
        font-size: .78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        margin-top: 1rem;
        backdrop-filter: blur(4px);
    }
    .dash-hero-badge {
        position: absolute;
        top: 1.4rem; right: 1.8rem;
        background: rgba(254,197,89,.18);
        border: 1px solid rgba(254,197,89,.35);
        color: #FEC559;
        border-radius: 2rem;
        padding: .35rem .9rem;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .04em;
    }

    /* ── Stat cards v2 ───────────────────────────────────────── */
    .stat2 {
        background: #fff;
        border-radius: .85rem;
        padding: 1.25rem 1.35rem;
        border: 1px solid #e8edf2;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: box-shadow .15s, transform .15s;
        text-decoration: none;
        color: inherit;
    }
    a.stat2:hover {
        box-shadow: 0 6px 22px rgba(19,49,57,.1);
        transform: translateY(-2px);
        color: inherit;
    }
    .stat2-icon {
        width: 52px; height: 52px;
        border-radius: .7rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .stat2-val {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        color: var(--text);
    }
    .stat2-lbl {
        font-size: .72rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: .22rem;
        letter-spacing: .02em;
    }
    .stat2-trend {
        font-size: .72rem;
        font-weight: 600;
        margin-top: .3rem;
    }

    /* ── Section header ──────────────────────────────────────── */
    .sec-head {
        display: flex;
        align-items: center;
        gap: .6rem;
        margin-bottom: 1rem;
    }
    .sec-head-bar {
        width: 4px; height: 20px;
        background: var(--gold);
        border-radius: 3px;
        flex-shrink: 0;
    }
    .sec-head-title {
        font-size: .92rem;
        font-weight: 800;
        color: var(--text);
        margin: 0;
    }

    /* ── Quick action tiles ──────────────────────────────────── */
    .qa-tile {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: .85rem;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        color: var(--text);
        transition: box-shadow .15s, transform .15s;
    }

    .qa-grid > [class*='col-'] {
        display: flex;
    }

    .qa-grid .qa-tile {
        width: 100%;
        min-height: 94px;
    }
    .qa-tile:hover {
        box-shadow: 0 4px 18px rgba(19,49,57,.1);
        transform: translateY(-2px);
        color: var(--text);
    }
    .qa-tile-icon {
        width: 44px; height: 44px;
        border-radius: .6rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .qa-tile-title {
        font-size: .87rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .qa-tile-sub {
        font-size: .74rem;
        color: var(--text-muted);
        margin-top: .15rem;
    }

    /* ── Info card ───────────────────────────────────────────── */
    .info-card {
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: .85rem;
        padding: 1.25rem;
        height: 100%;
    }

    .dashboard-equal > [class*='col-'] {
        display: flex;
    }

    .dashboard-panel {
        width: 100%;
        height: 100%;
    }

    /* ── Review table ────────────────────────────────────────── */
    .review-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--text-muted);
    }
    .review-empty i {
        display: block;
        font-size: 2.2rem;
        margin-bottom: .6rem;
        color: #2e7d32;
        opacity: .5;
    }
</style>
@endpush

@section('content')

{{-- ── Hero Banner ─────────────────────────────────────────────────────── --}}
@php
    $h = now()->hour;
    $greeting = $h < 11 ? 'Selamat Pagi' : ($h < 15 ? 'Selamat Siang' : ($h < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $tgl = \Carbon\Carbon::now()->translatedFormat('l, j F Y');
@endphp
<div class="dash-hero">
    <div class="dash-hero-greeting">{{ $greeting }},</div>
    <div class="dash-hero-name">{{ Auth::user()->nama ?? Auth::user()->name }}</div>
    <div class="dash-hero-upt">
        <i class="bi bi-building"></i>
        {{ Auth::user()->upt?->nama ?? 'UPT tidak diketahui' }}
    </div>
    <div class="dash-hero-date">
        <i class="bi bi-calendar3"></i> {{ $tgl }}
    </div>
</div>

{{-- ── Stat Cards ───────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat2">
            <div class="stat2-icon" style="background:#fff3e0">
                <i class="bi bi-file-earmark-text" style="color:#e65100"></i>
            </div>
            <div>
                <div class="stat2-val">{{ $stAktif }}</div>
                <div class="stat2-lbl">ST Aktif</div>
                <div class="stat2-trend" style="color:#e65100">
                    <i class="bi bi-activity"></i> Sedang berjalan
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat2">
            <div class="stat2-icon" style="background:#e8f5e9">
                <i class="bi bi-check-circle-fill" style="color:#2e7d32"></i>
            </div>
            <div>
                <div class="stat2-val">{{ $stSelesaiHari }}</div>
                <div class="stat2-lbl">Selesai Hari Ini</div>
                <div class="stat2-trend" style="color:#2e7d32">
                    <i class="bi bi-calendar-check"></i> Hari ini
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <a href="{{ route('koordinator.hasil-periksa') }}?status=belum_direview" class="stat2">
            <div class="stat2-icon" style="background:#fff8e1">
                <i class="bi bi-hourglass-split" style="color:#f57f17"></i>
            </div>
            <div>
                <div class="stat2-val">{{ $menungguReview }}</div>
                <div class="stat2-lbl">Menunggu Review</div>
                <div class="stat2-trend" style="color:#f57f17">
                    @if($menungguReview > 0)
                        <i class="bi bi-arrow-right-circle"></i> Klik untuk review
                    @else
                        <i class="bi bi-check2-all"></i> Semua selesai
                    @endif
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat2">
            <div class="stat2-icon" style="background:#ede7f6">
                <i class="bi bi-people-fill" style="color:#512da8"></i>
            </div>
            <div>
                <div class="stat2-val">{{ $petugasAktif }}</div>
                <div class="stat2-lbl">Petugas Aktif</div>
                <div class="stat2-trend" style="color:#512da8">
                    <i class="bi bi-person-check"></i> Di lapangan
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Quick Actions + Info ─────────────────────────────────────────────── --}}
<div class="row g-3 mb-4 dashboard-equal">
    <div class="col-lg-7">
        <div class="dashboard-panel" style="background:#fff;border-radius:.85rem;border:1px solid #e8edf2;padding:1.25rem;">
            <div class="sec-head">
                <div class="sec-head-bar"></div>
                <h6 class="sec-head-title">Aksi Cepat</h6>
            </div>
            <div class="row g-2 qa-grid">
                <div class="col-md-6">
                    <a href="{{ route('koordinator.hasil-periksa') }}?status=belum_direview" class="qa-tile">
                        <div class="qa-tile-icon" style="background:#fff3cd">
                            <i class="bi bi-clipboard2-pulse" style="color:#d97706"></i>
                        </div>
                        <div>
                            <div class="qa-tile-title">
                                Review Pemeriksaan
                                @if($menungguReview > 0)
                                    <span class="badge ms-1" style="background:#d97706;color:#fff;font-size:.65rem">{{ $menungguReview }}</span>
                                @endif
                            </div>
                            <div class="qa-tile-sub">Tinjau laporan belum direview</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#ccc;font-size:.8rem"></i>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('koordinator.hasil-periksa') }}" class="qa-tile">
                        <div class="qa-tile-icon" style="background:#e0f2fe">
                            <i class="bi bi-list-check" style="color:#0369a1"></i>
                        </div>
                        <div>
                            <div class="qa-tile-title">Semua Hasil Pemeriksaan</div>
                            <div class="qa-tile-sub">Lihat seluruh laporan masuk</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#ccc;font-size:.8rem"></i>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('koordinator.petugas') }}" class="qa-tile">
                        <div class="qa-tile-icon" style="background:#ede7f6">
                            <i class="bi bi-people" style="color:#512da8"></i>
                        </div>
                        <div>
                            <div class="qa-tile-title">Daftar Petugas</div>
                            <div class="qa-tile-sub">Kelola petugas lapangan</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#ccc;font-size:.8rem"></i>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('koordinator.keamanan') }}" class="qa-tile">
                        <div class="qa-tile-icon" style="background:#e8f5e9">
                            <i class="bi bi-shield-lock" style="color:#2e7d32"></i>
                        </div>
                        <div>
                            <div class="qa-tile-title">Keamanan Akun</div>
                            <div class="qa-tile-sub">Ubah password akun Anda</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto" style="color:#ccc;font-size:.8rem"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="info-card dashboard-panel d-flex flex-column justify-content-between">
            <div>
                <div class="sec-head mb-2">
                    <div class="sec-head-bar" style="background:var(--primary-mid)"></div>
                    <h6 class="sec-head-title" style="color:var(--primary)">Buat Surat Tugas</h6>
                </div>
                <p class="text-muted mb-3" style="font-size:.82rem;line-height:1.65">
                    Pembuatan dan pengiriman <strong>Surat Tugas</strong> ke petugas lapangan dilakukan
                    melalui aplikasi mobile <strong>Q-Officer Koordinator</strong>.
                </p>
                <div style="background:rgba(19,49,57,.06);border-radius:.6rem;padding:.85rem 1rem;">
                    <div class="d-flex gap-2 align-items-start mb-2">
                        <i class="bi bi-1-circle-fill" style="color:var(--primary-mid);font-size:1rem;flex-shrink:0;margin-top:1px"></i>
                        <span style="font-size:.79rem">Login ke aplikasi mobile sebagai Koordinator</span>
                    </div>
                    <div class="d-flex gap-2 align-items-start mb-2">
                        <i class="bi bi-2-circle-fill" style="color:var(--primary-mid);font-size:1rem;flex-shrink:0;margin-top:1px"></i>
                        <span style="font-size:.79rem">Buka menu <strong>Buat Surat Tugas</strong></span>
                    </div>
                    <div class="d-flex gap-2 align-items-start">
                        <i class="bi bi-3-circle-fill" style="color:var(--primary-mid);font-size:1rem;flex-shrink:0;margin-top:1px"></i>
                        <span style="font-size:.79rem">Pilih petugas, komoditas, & lokasi penugasan</span>
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex align-items-center gap-2">
                <div style="width:8px;height:8px;background:#2e7d32;border-radius:50%;animation:pulse-dot 1.5s infinite"></div>
                <span style="font-size:.75rem;color:#2e7d32;font-weight:600">Aplikasi tersedia di Android</span>
            </div>
        </div>
    </div>
</div>

{{-- Penugasan Sedang Berjalan (informasi ringkas; tracking detail di mobile) --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="info-card">
            <div class="sec-head mb-3">
                <div class="sec-head-bar" style="background:#2e7d32"></div>
                <h6 class="sec-head-title" style="color:var(--primary)">Penugasan Sedang Berjalan</h6>
                <span class="badge badge-status-aktif ms-auto">{{ $penugasanAktif->count() }} aktif</span>
            </div>
            @forelse($penugasanAktif as $st)
                <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(46,125,50,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-geo-alt-fill" style="color:#2e7d32"></i>
                    </div>
                    <div class="flex-grow-1" style="min-width:0">
                        <div class="fw-semibold" style="font-size:.85rem;color:var(--primary)">{{ $st->no_st }}</div>
                        <div class="text-muted text-truncate" style="font-size:.76rem">{{ $st->perihal }}</div>
                    </div>
                    <div class="text-end" style="flex-shrink:0">
                        <div style="font-size:.78rem">{{ $st->petugas->count() }} petugas</div>
                        <div class="text-muted" style="font-size:.72rem">{{ \Carbon\Carbon::parse($st->tanggal)->format('d M Y') }}</div>
                    </div>
                </div>
            @empty
                <div class="text-muted text-center py-3" style="font-size:.82rem">
                    <i class="bi bi-inbox"></i> Tidak ada penugasan yang sedang berjalan.
                </div>
            @endforelse
            <div class="text-muted mt-2" style="font-size:.72rem">
                <i class="bi bi-info-circle"></i> Progres detail tiap petugas dapat dipantau di aplikasi mobile.
            </div>
        </div>
    </div>
</div>


@push('scripts')
<style>
    @keyframes pulse-dot {
        0%,100%{opacity:1;transform:scale(1)}
        50%{opacity:.5;transform:scale(1.4)}
    }
</style>
@endpush

@endsection

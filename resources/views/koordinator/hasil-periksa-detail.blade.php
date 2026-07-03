@extends('layouts.app')
@section('title', 'Detail Periksa Lapangan')
@section('page-title', 'Detail Periksa Lapangan')
@section('suppress-success-alert', '1')

@if (session()->has('success'))
    <div class="success-notification" id="successNotification" role="alert">
        <div class="success-notification-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="success-notification-content">
            <div class="success-notification-title">Berhasil!</div>
            <div class="success-notification-message">{{ session('success') }}</div>
        </div>
        <button type="button" class="success-notification-close" id="closeSuccessNotif">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('koordinator.dashboard') }}" class="nav-link {{ request()->routeIs('koordinator.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.hasil-periksa') }}" class="nav-link {{ request()->routeIs('koordinator.hasil-periksa*') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-check"></i><span>Hasil Pemeriksaan</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.petugas') }}" class="nav-link {{ request()->routeIs('koordinator.petugas*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Petugas</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.keamanan') }}" class="nav-link {{ request()->routeIs('koordinator.keamanan') ? 'active' : '' }}">
            <i class="bi bi-key-fill"></i><span>Keamanan Akun</span>
        </a>
    </li>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
    <style>
        .detail-hero {
            background:linear-gradient(135deg, #12323b 0%, #1a4855 100%);
            border-radius:1.1rem;
            padding:.85rem 1rem;
            color:#fff;
            overflow:hidden;
            position:relative;
            box-shadow:0 12px 28px rgba(8, 27, 33, .12);
        }
        .hero-kicker {
            display:inline-flex;
            align-items:center;
            gap:.45rem;
            padding:.28rem .6rem;
            border-radius:999px;
            background:rgba(255,255,255,.12);
            font-size:.68rem;
            font-weight:700;
            letter-spacing:.08em;
            text-transform:uppercase;
            margin-bottom:.45rem;
            backdrop-filter:blur(8px);
        }
        .hero-title {
            font-size:1.2rem;
            font-weight:800;
            line-height:1.2;
            margin-bottom:.3rem;
            max-width:620px;
        }
        .hero-meta {
            display:flex;
            flex-wrap:wrap;
            gap:.4rem;
        }
        .hero-chip {
            display:inline-flex;
            align-items:center;
            gap:.38rem;
            padding:.32rem .58rem;
            border-radius:999px;
            background:rgba(255,255,255,.12);
            border:1px solid rgba(255,255,255,.16);
            font-size:.72rem;
            font-weight:600;
        }
        .hero-side {
            background:rgba(255,255,255,.78);
            color:#10242a;
            border-radius:.85rem;
            padding:.75rem;
            backdrop-filter:blur(10px);
            border:1px solid rgba(19,49,57,.08);
            min-height:100%;
        }
        .hero-side-label {
            font-size:.67rem;
            font-weight:700;
            color:var(--text-muted);
            letter-spacing:.08em;
            text-transform:uppercase;
            margin-bottom:.15rem;
        }
        .hero-side-value {
            font-size:.88rem;
            font-weight:800;
            color:var(--primary-dark);
        }
        .surface-card {
            background:linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
            border:1px solid rgba(19,49,57,.08);
            border-radius:1rem;
            padding:.9rem;
            box-shadow:0 8px 24px rgba(15, 33, 39, .05);
        }
        .surface-card + .surface-card {
            margin-top:.65rem;
        }
        .surface-card.soft {
            background:linear-gradient(180deg, #f8fbfc 0%, #ffffff 100%);
        }
        .info-label { font-size:.68rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.08em; margin-bottom:.2rem; }
        .info-value  { font-size:.9rem; color:#13282e; font-weight:600; line-height:1.45; }
        .section-head { display:flex; align-items:center; justify-content:space-between; gap:.75rem; margin-bottom:.6rem; }
        .section-head-main { display:flex; align-items:center; gap:.6rem; min-width:0; }
        .section-head .bar { width:3px; height:15px; background:var(--primary); border-radius:2px; flex-shrink:0; }
        .section-head h6 { margin:0; font-weight:800; font-size:.9rem; color:var(--primary-dark); }
        .section-subtitle { font-size:.72rem; color:var(--text-muted); }
        .section-pill {
            display:inline-flex;
            align-items:center;
            gap:.3rem;
            padding:.28rem .55rem;
            border-radius:999px;
            background:#eef5f7;
            color:var(--primary);
            font-size:.68rem;
            font-weight:700;
        }
        .info-grid {
            display:grid;
            grid-template-columns:repeat(2, minmax(0, 1fr));
            gap:.5rem;
        }
        .info-panel {
            border:1px solid var(--border);
            border-radius:.75rem;
            padding:.6rem .7rem;
            background:#fff;
            min-height:100%;
        }
        .info-panel.highlight {
            background:linear-gradient(135deg, rgba(19,49,57,.06), rgba(254,197,89,.1));
        }
        .section-divider {
            border:none;
            border-top:1px solid rgba(19,49,57,.09);
            margin:.7rem 0;
        }
        .location-grid,
        .team-grid,
        .gallery-grid {
            display:grid;
            gap:.55rem;
        }
        .location-grid {
            grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
        }
        .location-card {
            border:1px solid rgba(19,49,57,.09);
            border-radius:.75rem;
            padding:.65rem .7rem;
            background:linear-gradient(180deg, #ffffff 0%, #f7fbfb 100%);
            min-height:100%;
        }
        .location-index {
            width:24px;
            height:24px;
            border-radius:50%;
            background:var(--primary);
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:.7rem;
            font-weight:800;
            margin-bottom:.45rem;
        }
        .location-title {
            font-size:.86rem;
            font-weight:800;
            color:var(--primary-dark);
            margin-bottom:.15rem;
        }
        .location-desc {
            font-size:.76rem;
            color:var(--text-muted);
            line-height:1.5;
        }
        .location-coord {
            margin-top:.35rem;
            display:inline-flex;
            align-items:center;
            gap:.3rem;
            padding:.22rem .42rem;
            border-radius:.55rem;
            background:#eef5f7;
            color:var(--primary);
            font-size:.7rem;
            font-weight:700;
        }
        .ptg-card {
            border:1px solid rgba(19,49,57,.09);
            border-radius:.75rem;
            padding:.55rem .7rem;
            display:flex;
            align-items:center;
            gap:.65rem;
            background:linear-gradient(180deg, #ffffff 0%, #f9fbfc 100%);
            min-height:100%;
        }
        .ptg-avatar {
            width:34px;
            height:34px;
            border-radius:12px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:.82rem;
            font-weight:800;
            flex-shrink:0;
        }
        .ptg-avatar.selesai { background:var(--primary); color:#fff; box-shadow:0 8px 18px rgba(19,49,57,.18); }
        .ptg-avatar.pending { background:#eef5f7; color:var(--text-muted); }
        .badge-selesai { background:#dff0d8; color:#3c763d; font-size:.68rem; padding:.28em .65em; border-radius:999px; font-weight:700; }
        .badge-pending { background:#e8f0f2; color:var(--text-muted); font-size:.68rem; padding:.28em .65em; border-radius:999px; font-weight:700; }
        .insight-stack {
            display:grid;
            gap:.5rem;
        }
        .insight-card {
            border:1px solid rgba(19,49,57,.08);
            border-radius:.75rem;
            padding:.6rem .7rem;
            background:#fff;
        }
        .insight-title {
            font-size:.7rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.07em;
            color:var(--text-muted);
            margin-bottom:.2rem;
        }
        .insight-value {
            font-size:.88rem;
            font-weight:800;
            color:var(--primary-dark);
            line-height:1.4;
        }
        .gallery-grid {
            grid-template-columns:repeat(auto-fill, 120px);
        }
        .foto-frame {
            position:relative;
            border-radius:.75rem;
            overflow:hidden;
            background:#edf4f6;
            border:1px solid rgba(19,49,57,.08);
        }
        .foto-thumb { height:100px; width:100%; object-fit:cover; cursor:pointer; transition:.22s ease; display:block; }
        .foto-frame:hover .foto-thumb { transform:scale(1.04); }
        .foto-meta {
            position:absolute;
            left:0;
            right:0;
            bottom:0;
            padding:.6rem .7rem;
            background:linear-gradient(to top, rgba(10,18,23,.78), rgba(10,18,23,0));
            color:#fff;
            font-size:.68rem;
            font-weight:600;
        }
        .history-card {
            padding:0;
            overflow:hidden;
        }
        .history-head {
            padding:.6rem .9rem;
            border-bottom:1px solid var(--border);
            background:linear-gradient(180deg, #f8fbfc 0%, #f2f7f8 100%);
        }
        .riwayat-table th { font-size:.68rem; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:.07em; border-bottom:1px solid var(--border) !important; background:#fbfcfd; }
        .riwayat-table td { font-size:.8rem; vertical-align:middle; border-color:var(--border); padding-top:.6rem; padding-bottom:.6rem; }
        .riwayat-table tr.active-row td { background:#eef7f5; }
        .riwayat-table tr:hover td { background:#f7fafb; }
        .history-date { font-size:.84rem; font-weight:700; color:var(--primary-dark); }
        .history-sub { font-size:.72rem; color:var(--text-muted); margin-top:.12rem; }
        .target-cell, .note-cell {
            max-width:180px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }
        .map-card {
            overflow:hidden;
        }
        .map-shell {
            position:relative;
            border-radius:1rem;
            overflow:hidden;
            border:1px solid rgba(19,49,57,.08);
            background:linear-gradient(180deg, #edf4f6 0%, #f7fbfc 100%);
        }
        #trackingMap { height:400px; width:100%; z-index:0; }
        .map-status {
            display:flex;
            align-items:center;
            gap:.55rem;
            padding:.85rem 1rem;
            border-radius:.8rem;
            background:#f5fafb;
            color:var(--text-muted);
            font-size:.82rem;
            border:1px solid rgba(19,49,57,.08);
        }
        .map-status.error {
            background:#fff4f4;
            color:#8a4242;
            border-color:#f1d1d1;
        }
        .map-footer { display:flex; align-items:flex-start; justify-content:space-between; margin-top:.9rem; flex-wrap:wrap; gap:.85rem; }
        .gps-count-badge { background:var(--primary); color:#fff; font-size:.72rem; font-weight:700; padding:.35em .78em; border-radius:999px; }
        .map-legend {
            display:flex;
            flex-wrap:wrap;
            gap:.5rem;
        }
        .legend-chip {
            display:inline-flex;
            align-items:center;
            gap:.38rem;
            padding:.38rem .62rem;
            border-radius:999px;
            background:#eef5f7;
            color:var(--primary-dark);
            font-size:.74rem;
            font-weight:700;
        }
        .legend-dot {
            width:10px;
            height:10px;
            border-radius:50%;
            display:inline-block;
        }
        .log-list { position:relative; padding-left:.15rem; }
        .log-list::before { content:''; position:absolute; left:21px; top:26px; bottom:26px; width:2px; background:rgba(19,49,57,.08); border-radius:2px; }
        .log-card {
            border:1px solid rgba(19,49,57,.09);
            border-radius:1rem;
            padding:1rem 1.1rem;
            margin-bottom:.8rem;
            background:linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
            display:flex;
            gap:.95rem;
            align-items:flex-start;
            position:relative;
        }
        .log-card:last-child { margin-bottom:0; }
        .log-icon {
            width:42px;
            height:42px;
            border-radius:16px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:.88rem;
            flex-shrink:0;
            position:relative;
            z-index:1;
            box-shadow:0 10px 22px rgba(15, 33, 39, .08);
        }
        .log-icon.terima   { background:#e8f4f8; color:#31708f; }
        .log-icon.berangkat{ background:#d9edf7; color:#1f6a96; }
        .log-icon.periksa  { background:#eef5f7; color:var(--primary); }
        .log-icon.periksa.active { background:var(--primary); color:#fff; }
        .log-icon.selesai  { background:#dff0d8; color:#3c763d; }
        .log-type { font-size:.68rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
        .log-desc { font-size:.84rem; color:#33434a; margin:.15rem 0; font-weight:600; line-height:1.55; }
        .log-gps  { font-size:.73rem; color:var(--text-muted); display:flex; align-items:center; gap:.3rem; margin-top:.35rem; }
        .log-time { font-size:.74rem; color:var(--text-muted); margin-left:auto; white-space:nowrap; padding-top:.15rem; line-height:1.45; text-align:right; }
        .rekom-card .form-label {
            font-size:.76rem;
            font-weight:700;
            color:var(--text-muted);
            text-transform:uppercase;
            letter-spacing:.06em;
        }

        /* ── Success Notification ── */
        .success-notification {
            position:fixed; top:20px; right:20px;
            background:linear-gradient(135deg,#d4edda,#c3e6cb);
            border:1px solid #b1dfbb; border-radius:.75rem;
            padding:1.25rem 1.5rem;
            box-shadow:0 10px 30px rgba(0,0,0,.15);
            display:flex; align-items:flex-start; gap:1rem;
            z-index:9999; min-width:360px;
            animation:slideInRight .4s cubic-bezier(.34,1.56,.64,1);
        }
        .success-notification.hide { animation:slideOutRight .4s cubic-bezier(.34,1.56,.64,1) forwards; }
        .success-notification-icon { flex-shrink:0; width:40px; height:40px; background:#28a745; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.25rem; }
        .success-notification-content { flex:1; }
        .success-notification-title { font-weight:600; color:#155724; margin-bottom:.25rem; }
        .success-notification-message { font-size:.85rem; color:#155724; opacity:.9; }
        .success-notification-close { flex-shrink:0; background:none; border:none; cursor:pointer; color:#155724; font-size:1.25rem; padding:0; line-height:1; opacity:.6; transition:.2s; }
        .success-notification-close:hover { opacity:1; }
        @keyframes slideInRight { from { transform:translateX(400px); opacity:0; } to { transform:translateX(0); opacity:1; } }
        @keyframes slideOutRight { from { transform:translateX(0); opacity:1; } to { transform:translateX(400px); opacity:0; } }
        @media (max-width:767.98px) {
            .detail-hero { padding:.7rem .8rem; }
            .hero-title { font-size:1.05rem; }
            .info-grid { grid-template-columns:1fr; }
            .section-head { flex-direction:column; align-items:flex-start; }
            .map-footer { flex-direction:column; }
            .log-card { flex-direction:column; }
            .log-time { margin-left:0; text-align:left; }
            #trackingMap { height:300px; }
        }
        @media (max-width:576px) {
            .success-notification { min-width:auto; left:10px; right:10px; }
            .gallery-grid { grid-template-columns:repeat(2, minmax(0, 1fr)); }
        }
    </style>
@endpush

@section('content')
@php
    $jenis = ['H'=>'Hewan','T'=>'Tumbuhan','I'=>'Ikan'];
    $lokasiList = $st->lokasi;
    $lokasi = $lokasiList->first();
    $lokasiUtama = $lokasiList->first(function ($item) {
        return filled($item->lat) && filled($item->long);
    }) ?? $lokasi;
    $lokasiMapPoints = $lokasiList->map(function ($loc) {
        return [
            'name' => $loc->nama_lokasi,
            'detail' => $loc->detail_lokasi,
            'lat' => $loc->lat,
            'lng' => $loc->long,
        ];
    })->values();
    $aktifIndex = $st->hasilPemeriksaan->search(fn ($item) => $item->id === $hasil->id);
@endphp

{{-- Page header --}}
<div class="mb-3">
    <a href="{{ route('koordinator.hasil-periksa') }}" class="btn btn-ghost btn-sm mb-2"><i class="bi bi-arrow-left"></i></a>
    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
        <div>
            <div style="font-size:1.05rem;font-weight:800;color:var(--primary-dark);line-height:1.3">
                {{ $st->perihal ?: 'Pemeriksaan lapangan tanpa perihal' }}
            </div>
            <div class="d-flex flex-wrap gap-3 mt-1">
                <span class="text-muted" style="font-size:.78rem"><i class="bi bi-file-text me-1"></i>{{ $st->no_st ?? '-' }}</span>
                <span class="text-muted" style="font-size:.78rem"><i class="bi bi-tags me-1"></i>{{ $jenis[$st->jenis_karantina] ?? '-' }}</span>
                <span class="text-muted" style="font-size:.78rem"><i class="bi bi-calendar me-1"></i>{{ $st->created_at?->format('d M Y') ?? '-' }}</span>
            </div>
        </div>
        @if ($hasil->status_review === 'sudah_direview')
            <span class="badge badge-status-aktif"><i class="bi bi-check-circle me-1"></i>Sudah Direview</span>
        @else
            <span class="badge badge-status-tertunda"><i class="bi bi-clock me-1"></i>Menunggu Review</span>
        @endif
    </div>
</div>

{{-- Single flat info card --}}
<div class="surface-card mb-3">
    <div class="row g-0">
        {{-- Kolom kiri: detail laporan --}}
        <div class="col-md-4 pe-md-4" style="border-right:1px solid var(--border)">
            <div class="info-label mb-1">Petugas Lapor</div>
            <div class="info-value mb-3">{{ $hasil->petugas?->nama ?? '-' }}</div>
            <div class="info-label mb-1">Waktu Periksa</div>
            <div class="info-value">{{ $hasil->tgl_periksa?->format('d M Y, H:i') ?? '-' }} WIB</div>
        </div>
        {{-- Kolom tengah: lokasi penugasan --}}
        <div class="col-md-4 px-md-4" style="border-right:1px solid var(--border)">
            <div class="info-label mb-2">Lokasi Penugasan ({{ $lokasiList->count() }})</div>
            @forelse ($lokasiList as $li => $loc)
                <div class="{{ $li > 0 ? 'mt-3 pt-3' : '' }}" style="{{ $li > 0 ? 'border-top:1px solid var(--border)' : '' }}">
                    <div style="font-size:.82rem;font-weight:700;color:var(--text)">{{ $li + 1 }}. {{ $loc->nama_lokasi }}</div>
                    @if ($loc->detail_lokasi)
                        <div class="text-muted" style="font-size:.75rem;line-height:1.5;margin-top:.15rem">{{ $loc->detail_lokasi }}</div>
                    @endif
                    @if ($loc->lat && $loc->long)
                        <div class="text-muted" style="font-size:.72rem;margin-top:.2rem"><i class="bi bi-crosshair2 me-1"></i>{{ $loc->lat }}, {{ $loc->long }}</div>
                    @endif
                </div>
            @empty
                <div class="text-muted" style="font-size:.78rem">Belum ada lokasi.</div>
            @endforelse
        </div>
        {{-- Kolom kanan: petugas --}}
        <div class="col-md-4 ps-md-4">
            <div class="info-label mb-2">Petugas Pemeriksa ({{ $st->petugas->count() }})</div>
            @forelse ($st->petugas as $ptg)
                @php $selesai = $petugasSelesaiIds->contains($ptg->id); @endphp
                <div class="d-flex align-items-center justify-content-between {{ !$loop->first ? 'mt-3 pt-3' : '' }}" style="{{ !$loop->first ? 'border-top:1px solid var(--border)' : '' }}">
                    <div>
                        <div style="font-size:.82rem;font-weight:700;color:var(--text)">
                            {{ $ptg->nama }}
                            @if ($ptg->id === $hasil->id_petugas)
                                <span class="badge ms-1" style="background:var(--primary);color:#fff;font-size:.58rem;padding:.15em .4em">Pembuat</span>
                            @endif
                        </div>
                        <div class="text-muted" style="font-size:.72rem">{{ $ptg->nip }}</div>
                    </div>
                    @if ($selesai)
                        <span class="badge-selesai" style="font-size:.65rem">Selesai</span>
                    @else
                        <span class="badge-pending" style="font-size:.65rem">Pending</span>
                    @endif
                </div>
            @empty
                <div class="text-muted" style="font-size:.78rem">Belum ada petugas.</div>
            @endforelse
        </div>
    </div>
    @if ($hasil->dokumentasi->count() > 0)
        <hr style="border-color:var(--border);margin:.9rem 0">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span style="font-size:.82rem;font-weight:700;color:var(--primary-dark)">Dokumentasi Lapangan</span>
            <span class="section-pill"><i class="bi bi-images"></i>{{ $hasil->dokumentasi->count() }} foto</span>
        </div>
        <div class="gallery-grid">
            @foreach ($hasil->dokumentasi as $foto)
                <div class="foto-frame">
                    @if ($foto->foto_path)
                        <img src="{{ asset('storage/'.$foto->foto_path) }}" class="foto-thumb"
                            data-bs-toggle="modal" data-bs-target="#fotoModal"
                            data-src="{{ asset('storage/'.$foto->foto_path) }}">
                    @elseif($foto->foto_display || $foto->foto_server)
                        <img src="data:image/jpeg;base64,{{ $foto->foto_display ?? $foto->foto_server }}"
                            class="foto-thumb" data-bs-toggle="modal" data-bs-target="#fotoModal"
                            data-src="data:image/jpeg;base64,{{ $foto->foto_display ?? $foto->foto_server }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="height:130px;background:#eef5f7">
                            <i class="bi bi-image text-muted" style="opacity:.4"></i>
                        </div>
                    @endif
                    <div class="foto-meta">
                        <i class="bi bi-clock-history me-1"></i>{{ $foto->created_at?->format('d M Y, H:i') ?? 'Dokumentasi' }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="surface-card map-card mb-4">
    <div class="section-head">
        <div class="section-head-main">
            <div class="bar"></div>
            <div>
                <h6>Tracking Rute Lapangan</h6>
                <div class="section-subtitle">Peta menampilkan seluruh titik GPS yang valid dan semua marker tujuan lokasi.</div>
            </div>
        </div>
        @if ($gpsPoints->count() > 0)
            <span class="gps-count-badge"><i class="bi bi-geo me-1"></i>{{ $gpsPoints->count() }} titik GPS</span>
        @else
            <span class="gps-count-badge" style="background:var(--text-muted)"><i class="bi bi-geo-slash me-1"></i>Tanpa GPS</span>
        @endif
    </div>
    <div class="map-shell">
        <div id="trackingMap"></div>
    </div>
    <div id="trackingMapStatus" class="map-status mt-3 d-none"></div>
    <div class="map-footer">
        <div>
            <div class="map-legend mb-2">
                <span class="legend-chip"><span class="legend-dot" style="background:#2a6978"></span>Titik GPS</span>
                <span class="legend-chip"><span class="legend-dot" style="background:#133139"></span>Laporan aktif</span>
                <span class="legend-chip"><span class="legend-dot" style="background:#27ae60"></span>Tujuan lokasi</span>
            </div>
            <div class="section-subtitle">
                @if ($gpsPoints->count() > 0)
                    Rute diambil dari histori hasil pemeriksaan yang memiliki koordinat valid.
                @elseif ($lokasiUtama)
                    Data GPS belum tersedia, jadi peta difokuskan ke lokasi tujuan surat tugas.
                @else
                    Belum ada GPS maupun lokasi berkoordinat, peta menggunakan tampilan Indonesia sebagai fallback.
                @endif
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if ($lokasiUtama && $lokasiUtama->lat && $lokasiUtama->long)
                <a href="https://www.google.com/maps/search/?api=1&query={{ $lokasiUtama->lat }},{{ $lokasiUtama->long }}"
                    target="_blank" rel="noopener noreferrer"
                    class="btn btn-sm btn-ghost" style="font-size:.78rem;font-weight:700;color:var(--primary)">
                    <i class="bi bi-pin-map me-1"></i>Buka Lokasi Tujuan
                </a>
            @endif
        </div>
    </div>
</div>

<div class="surface-card mb-4">
    <div class="section-head">
        <div class="section-head-main">
            <div class="bar"></div>
            <div>
                <h6>Tracking Petugas</h6>
                <div class="section-subtitle">Aktivitas disusun sebagai timeline agar progres lebih mudah diikuti.</div>
            </div>
        </div>
        <span class="section-pill"><i class="bi bi-activity"></i>{{ $logEvents->count() }} aktivitas</span>
    </div>
    @if ($logEvents->count() > 0)
        <div class="log-list">
            @foreach ($logEvents as $ev)
                @php
                    $iconMap = [
                        'terima'    => ['class'=>'terima',    'icon'=>'bi-inbox-fill',        'color'=>'#31708f',  'label'=>'TERIMA TUGAS'],
                        'berangkat' => ['class'=>'berangkat', 'icon'=>'bi-send-fill',         'color'=>'#1f6a96',  'label'=>'BERANGKAT'],
                        'periksa'   => ['class'=>'periksa',   'icon'=>'bi-clipboard2-check',  'color'=>'var(--primary)',  'label'=>'PERIKSA'],
                        'selesai'   => ['class'=>'selesai',   'icon'=>'bi-check2-all',        'color'=>'#3c763d',  'label'=>'SELESAI'],
                    ];
                    $ic = $iconMap[$ev['type']] ?? $iconMap['periksa'];
                    $isActive = ($ev['type'] === 'periksa') && (($ev['hp_id'] ?? null) === $hasil->id);
                @endphp
                <div class="log-card">
                    <div class="log-icon {{ $ic['class'] }} {{ $isActive ? 'active' : '' }}">
                        <i class="bi {{ $ic['icon'] }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="log-type" style="color:{{ $isActive ? 'var(--primary)' : $ic['color'] }}">{{ $ic['label'] }}</div>
                        <div class="log-desc">
                            @if ($ev['type'] === 'terima')
                                {{ $ev['nama'] }} telah menerima surat tugas.
                            @elseif ($ev['type'] === 'berangkat')
                                {{ $ev['nama'] }} berangkat ke lokasi pemeriksaan.
                            @elseif ($ev['type'] === 'periksa')
                                Hasil pemeriksaan disimpan{{ $ev['nama'] ? ' oleh '.$ev['nama'] : '' }}.
                                @if ($isActive)
                                    <span class="badge ms-1" style="background:var(--primary);color:#fff;font-size:.6rem;padding:.18em .45em">Laporan aktif</span>
                                @endif
                            @elseif ($ev['type'] === 'selesai')
                                Surat tugas telah ditandai selesai.
                            @endif
                        </div>
                        @if ($ev['lat'] && $ev['long'])
                            <div class="log-gps">
                                <i class="bi bi-geo-alt-fill" style="color:#28a745"></i>
                                GPS: {{ $ev['lat'] }}, {{ $ev['long'] }}
                            </div>
                        @endif
                    </div>
                    <div class="log-time">{{ $ev['at']?->format('d M Y') }}<br>{{ $ev['at']?->format('H:i') }} WIB</div>
                </div>
            @endforeach
        </div>
    @else
        <div class="map-status"><i class="bi bi-info-circle"></i>Belum ada aktivitas petugas yang tercatat.</div>
    @endif
</div>

<div class="surface-card rekom-card mb-3">
    <div class="section-head">
        <div class="section-head-main">
            <div class="bar"></div>
            <h6>Rekomendasi Tindakan</h6>
        </div>
        @if ($hasil->rekomendasi)
            @php $bts = $hasil->rekomendasi->best_trust_status ?? 'success'; @endphp
            <span class="badge {{ $bts==='success'?'badge-status-aktif':($bts==='failed'?'badge-rekomendasi-penolakan':'badge-status-dikirim') }}" style="font-size:.72rem">
                <i class="bi bi-broadcast me-1"></i>BEST-TRUST: {{ $bts==='success' ? 'Terkirim' : strtoupper($bts) }}
            </span>
        @endif
    </div>

    @if ($hasil->rekomendasi)
        @php
            $tk = $hasil->rekomendasi->tindakan;
            $bm = ['pelepasan'=>'badge-rekomendasi-pelepasan','penolakan'=>'badge-rekomendasi-penolakan','perlakuan'=>'badge-rekomendasi-perlakuan','pemusnahan'=>'badge-rekomendasi-pemusnahan'];
        @endphp
        <div class="row g-3 align-items-start">
            <div class="col-auto">
                <div class="info-label mb-1">Tindakan</div>
                <span class="badge {{ $bm[$tk] ?? '' }}" style="font-size:.88rem;padding:.45em 1em">{{ ucfirst($tk) }}</span>
            </div>
            <div class="col">
                <div class="info-label mb-1">Catatan</div>
                <div style="font-size:.84rem;line-height:1.65;color:#33434a">{{ $hasil->rekomendasi->catatan }}</div>
            </div>
            <div class="col-md-3 text-md-end">
                <div class="info-label mb-1">Ditetapkan oleh</div>
                <div style="font-size:.84rem;font-weight:700;color:var(--primary-dark)">{{ $hasil->rekomendasi->koordinator?->nama }}</div>
                <div class="text-muted" style="font-size:.73rem">{{ $hasil->rekomendasi->created_at?->format('d M Y, H:i') }}</div>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('koordinator.rekomendasi.simpan') }}" class="row g-3 align-items-end">
            @csrf
            <input type="hidden" name="id_hasil_pemeriksaan" value="{{ $hasil->id }}">
            <div class="col-md-3">
                <label class="form-label">Tindakan <span class="text-danger">*</span></label>
                <select name="tindakan" class="form-select form-select-sm @error('tindakan') is-invalid @enderror" required>
                    <option value="">— Pilih —</option>
                    <option value="pelepasan"  {{ old('tindakan')=='pelepasan'  ? 'selected':'' }}>Pelepasan</option>
                    <option value="penolakan"  {{ old('tindakan')=='penolakan'  ? 'selected':'' }}>Penolakan</option>
                    <option value="perlakuan"  {{ old('tindakan')=='perlakuan'  ? 'selected':'' }}>Perlakuan</option>
                    <option value="pemusnahan" {{ old('tindakan')=='pemusnahan' ? 'selected':'' }}>Pemusnahan</option>
                </select>
                @error('tindakan')<div class="invalid-feedback small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-7">
                <label class="form-label">Catatan <span class="text-danger">*</span></label>
                <textarea name="catatan" rows="2" class="form-control form-control-sm @error('catatan') is-invalid @enderror"
                    placeholder="Jelaskan alasan rekomendasi..." required minlength="10">{{ old('catatan') }}</textarea>
                @error('catatan')<div class="invalid-feedback small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-brand btn-sm w-100">
                    <i class="bi bi-check2-circle me-1"></i>Simpan
                </button>
            </div>
        </form>
    @endif
</div>

{{-- Foto Lightbox --}}
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background:#1a1a1a;border:none">
            <div class="modal-header" style="background:#1a1a1a;border-bottom:1px solid #333">
                <h6 class="modal-title text-white">Foto Dokumentasi</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalFotoSrc" src="" class="img-fluid rounded" style="max-height:75vh;object-fit:contain">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
<script>
    // Success notification
    (function(){
        const n = document.getElementById('successNotification');
        if(!n) return;
        const dismiss = ()=>{ n.classList.add('hide'); setTimeout(()=>n.style.display='none',400); };
        document.getElementById('closeSuccessNotif')?.addEventListener('click', dismiss);
        setTimeout(dismiss, 6000);
    })();

    // Foto lightbox
    document.querySelectorAll('[data-bs-target="#fotoModal"]').forEach(el=>{
        el.addEventListener('click',()=>{ document.getElementById('modalFotoSrc').src = el.dataset.src; });
    });

    // Leaflet tracking map with stronger fallbacks and marker sanitization
    (function(){
        const mapEl = document.getElementById('trackingMap');
        const statusEl = document.getElementById('trackingMapStatus');
        if (!mapEl) return;

        const rawPoints = @json($gpsPoints);
        const rawDestinations = @json($lokasiMapPoints);

        const showStatus = (message, isError = false) => {
            if (!statusEl) return;
            statusEl.classList.remove('d-none', 'error');
            if (isError) {
                statusEl.classList.add('error');
            }
            statusEl.innerHTML = `<i class="bi ${isError ? 'bi-exclamation-triangle' : 'bi-info-circle'}"></i><span>${message}</span>`;
        };

        const toLatLng = (lat, lng) => {
            const parsedLat = Number.parseFloat(lat);
            const parsedLng = Number.parseFloat(lng);
            if (!Number.isFinite(parsedLat) || !Number.isFinite(parsedLng)) {
                return null;
            }
            return [parsedLat, parsedLng];
        };

        const points = rawPoints
            .map((point) => {
                const coords = toLatLng(point.lat, point.lng);
                return coords ? { ...point, coords } : null;
            })
            .filter(Boolean);

        const destinations = rawDestinations
            .map((destination) => {
                const coords = toLatLng(destination.lat, destination.lng);
                return coords ? { ...destination, coords } : null;
            })
            .filter(Boolean);

        if (typeof L === 'undefined') {
            showStatus('Leaflet gagal dimuat, jadi peta tidak bisa ditampilkan.', true);
            return;
        }

        let map;

        try {
            map = L.map(mapEl, {
                scrollWheelZoom: false,
                zoomControl: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>',
            })
            .on('tileerror', function () {
                showStatus('Tile peta gagal dimuat. Periksa koneksi internet atau akses ke OpenStreetMap.', true);
            })
            .addTo(map);

            const createPointIcon = (isActive, label) => {
                const size = isActive ? 30 : 22;
                const fontSize = isActive ? 10 : 8;
                return L.divIcon({
                    className: '',
                    html: `<div style="width:${size}px;height:${size}px;border-radius:50%;background:${isActive ? '#133139' : '#2a6978'};border:${isActive ? 3 : 2}px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,.28);display:flex;align-items:center;justify-content:center;color:#fff;font-size:${fontSize}px;font-weight:800">${label}</div>`,
                    iconSize: [size, size],
                    iconAnchor: [size / 2, size / 2],
                    popupAnchor: [0, -15],
                });
            };

            const destinationIcon = L.divIcon({
                className: '',
                html: '<div style="width:34px;height:34px;border-radius:50% 50% 50% 0;background:#27ae60;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 10px rgba(0,0,0,.28)"></div>',
                iconSize: [34, 34],
                iconAnchor: [17, 34],
                popupAnchor: [0, -34],
            });

            const allBoundsPoints = [];

            if (points.length > 1) {
                L.polyline(points.map((point) => point.coords), {
                    color: '#1e4a56',
                    weight: 4,
                    opacity: .85,
                    dashArray: '8,5',
                    lineCap: 'round',
                }).addTo(map);
            }

            points.forEach((point, index) => {
                allBoundsPoints.push(point.coords);
                L.marker(point.coords, {
                    icon: createPointIcon(Boolean(point.active), index + 1),
                })
                .addTo(map)
                .bindPopup(`<b>${point.label || 'Petugas'}</b><br>${point.time || '-'}<br><small>${point.coords[0]}, ${point.coords[1]}</small>`);
            });

            destinations.forEach((destination, index) => {
                allBoundsPoints.push(destination.coords);
                const popupParts = [`<b>Tujuan ${index + 1}: ${destination.name || '-'}</b>`];
                if (destination.detail) {
                    popupParts.push(`<small>${destination.detail}</small>`);
                }
                L.marker(destination.coords, { icon: destinationIcon })
                    .addTo(map)
                    .bindPopup(popupParts.join('<br>'));
            });

            if (allBoundsPoints.length > 1) {
                map.fitBounds(L.latLngBounds(allBoundsPoints).pad(0.16));
            } else if (allBoundsPoints.length === 1) {
                map.setView(allBoundsPoints[0], points.length ? 15 : 14);
            } else {
                map.setView([-2.5, 118.0], 5);
                showStatus('Koordinat GPS atau lokasi valid belum tersedia. Peta memakai fallback Indonesia.', false);
            }

            const refreshMap = () => map.invalidateSize();
            requestAnimationFrame(refreshMap);
            setTimeout(refreshMap, 180);
            setTimeout(refreshMap, 420);
            window.addEventListener('resize', refreshMap);
        } catch (error) {
            console.error('trackingMap init failed', error);
            showStatus('Peta gagal diinisialisasi. Periksa data koordinat atau console browser untuk detail error.', true);
        }
    })();
</script>
@endpush
@endsection

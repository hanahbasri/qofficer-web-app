@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('pimpinan.dashboard') }}" data-label="Dashboard"
            class="nav-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('pimpinan.ekspor') }}" data-label="Unduh Laporan" class="nav-link">
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
@endsection

@push('styles')
    <style>
        .dash-hero {
            background: linear-gradient(135deg, #133139 0%, #1e4a56 60%, #276475 100%);
            border-radius: 1rem;
            padding: 1.75rem 2rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .dash-hero::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, .06);
            border-radius: 50%;
        }
        .dash-hero::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: 80px;
            width: 160px;
            height: 160px;
            background: rgba(254, 197, 89, .08);
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
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .2);
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
            top: 1.4rem;
            right: 1.8rem;
            background: rgba(254, 197, 89, .18);
            border: 1px solid rgba(254, 197, 89, .35);
            color: #FEC559;
            border-radius: 2rem;
            padding: .35rem .9rem;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .04em;
        }

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
            height: 100%;
        }
        .stat2:hover {
            box-shadow: 0 6px 22px rgba(19, 49, 57, .1);
            transform: translateY(-2px);
            color: inherit;
        }
        .stat2-icon {
            width: 52px;
            height: 52px;
            border-radius: .7rem;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .dash-panel {
            border-radius: .85rem;
            border: 1px solid #e8edf2;
            background: #fff;
            overflow: hidden;
            box-shadow: none;
        }

        .chart-panel {
            border-color: #dfe8ef;
            box-shadow: 0 2px 10px rgba(15, 23, 42, .04);
        }

        .chart-panel .dash-panel-header {
            background: linear-gradient(180deg, #fbfdff 0%, #f8fbfe 100%);
            border-bottom: 1px solid #eef3f7;
            padding-top: .9rem;
            padding-bottom: .62rem;
        }

        .chart-panel .dash-panel-title {
            color: #23354a;
            letter-spacing: .055em;
        }

        .chart-accent-primary { color: #4285F4 !important; }
        .chart-accent-danger { color: #EA4335 !important; }
        .chart-accent-violet { color: #7E57C2 !important; }
        .dash-panel-header {
            padding: .85rem 1.1rem .5rem;
            display: flex; align-items: flex-start; justify-content: space-between;
        }
        .dash-panel-title {
            font-size: .75rem; font-weight: 700; color: #374151;
            text-transform: uppercase; letter-spacing: .06em;
        }
        .dash-panel-subtitle { font-size: .67rem; color: #9ca3af; margin-top: .1rem; }

        .upt-table th {
            font-size: .68rem; text-transform: uppercase; letter-spacing: .05em;
            color: #9ca3af; font-weight: 700; padding: .5rem .75rem;
            border-bottom: 1px solid #f3f4f6; white-space: nowrap;
        }
        .upt-table td {
            font-size: .8rem; padding: .45rem .75rem;
            vertical-align: middle; border-bottom: 1px solid #f9fafb;
        }
        .upt-table tbody tr:last-child td { border-bottom: none; }
        .upt-table tbody tr:hover { background: #fafafa; }

        .upt-scroll { max-height: 310px; overflow-y: auto; overflow-x: auto; }
        .upt-scroll::-webkit-scrollbar { width: 4px; height: 4px; }
        .upt-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 99px; }

        .badge-pill {
            padding: .18rem .6rem; border-radius: 99px;
            font-size: .72rem; font-weight: 700;
        }
        .progress-thin { height: 5px; border-radius: 99px; background: #f3f4f6; min-width: 60px; }
        .progress-thin .bar { height: 100%; border-radius: 99px; }

        .filter-input {
            border: 1px solid #e5e7eb; border-radius: .5rem;
            padding: .3rem .75rem; font-size: .78rem; width: 180px;
            outline: none; transition: border-color .15s;
        }
        .filter-input:focus { border-color: #133139; }

        .periode-pill {
            display: flex;
            gap: .2rem;
            background: #f3f4f6;
            border-radius: 99px;
            padding: .2rem;
            border: 1px solid #d8e2ea;
        }
        .periode-pill a {
            padding: .25rem .8rem; border-radius: 99px; font-size: .72rem;
            font-weight: 600; color: #6b7280; text-decoration: none; transition: all .15s; white-space: nowrap;
        }
        .periode-pill a.active {
            background: #fff;
            color: #133139;
            box-shadow: 0 1px 4px rgba(0,0,0,.12);
            border: 1px solid #b8c9d6;
        }

        .page-block {
            margin-bottom: 1.25rem;
        }

        .empty-chart {
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            color: #9ca3af;
            gap: .35rem;
        }

        .empty-chart i {
            font-size: 1.8rem;
            opacity: .35;
        }

        .chart-canvas-wrap {
            position: relative;
            width: 100%;
            height: 250px;
        }

        .chart-canvas-wrap.tall {
            height: 300px;
        }

        .chart-canvas-wrap > canvas {
            width: 100% !important;
            height: 100% !important;
            display: block;
        }

        @media (max-width: 767.98px) {
            .dash-hero {
                padding: 1.25rem 1.2rem;
            }
            .dash-hero-badge {
                position: static;
                display: inline-block;
                margin-bottom: .7rem;
            }
            .periode-pill {
                margin-top: 0;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')

    @php
        $h = now()->hour;
        $greeting = $h < 11 ? 'Selamat Pagi' : ($h < 15 ? 'Selamat Siang' : ($h < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        $scopeTitle = $isNasional ? 'Seluruh UPT Barantin' : ($scopeUptName ?? (Auth::user()->upt?->nama ?? 'UPT'));
    @endphp

    <div class="dash-hero page-block">
        <div class="dash-hero-greeting">{{ $greeting }},</div>
        <div class="dash-hero-name">{{ Auth::user()->nama ?? Auth::user()->name }}</div>
        <div class="dash-hero-upt">
            <i class="bi bi-building"></i>
            {{ $scopeTitle }}
        </div>
        <div class="dash-hero-date">
            <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <div class="page-block" style="display:flex;justify-content:flex-start;">
        <div class="periode-pill" style="width:max-content;">
            <a href="?periode=hari_ini" class="{{ $periode == 'hari_ini' ? 'active' : '' }}">Hari Ini</a>
            <a href="?periode=7_hari"   class="{{ $periode == '7_hari'   ? 'active' : '' }}">7 Hari</a>
            <a href="?periode=1_bulan"  class="{{ $periode == '1_bulan'  ? 'active' : '' }}">1 Bulan</a>
        </div>
    </div>

    {{-- Ringkasan KPI --}}
    <div class="row g-3 page-block">
        <div class="col-6 col-xl-3">
            <div class="stat2">
                <div class="stat2-icon" style="background:#e8f0fe">
                    <i class="bi bi-clipboard2-pulse" style="color:#1d4ed8"></i>
                </div>
                <div>
                    <div class="stat2-val">{{ $totalPemeriksaan }}</div>
                    <div class="stat2-lbl">Total Pemeriksaan</div>
                    <div class="stat2-trend" style="color:#1d4ed8">
                        <i class="bi bi-graph-up"></i> Periode terpilih
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat2">
                <div class="stat2-icon" style="background:#e8f5e9">
                    <i class="bi bi-check2-circle" style="color:#16a34a"></i>
                </div>
                <div>
                    <div class="stat2-val">{{ $totalPelepasan }}</div>
                    <div class="stat2-lbl">Komoditas Dilepaskan</div>
                    <div class="stat2-trend" style="color:#16a34a">
                        <i class="bi bi-shield-check"></i> Aman dilepas
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat2">
                <div class="stat2-icon" style="background:#fff1f2">
                    <i class="bi bi-exclamation-triangle" style="color:#b91c1c"></i>
                </div>
                <div>
                    <div class="stat2-val">{{ $totalPerluTindakan }}</div>
                    <div class="stat2-lbl">Perlu Penanganan</div>
                    <div class="stat2-trend" style="color:#b91c1c">
                        <i class="bi bi-arrow-repeat"></i> Tindak lanjut
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat2">
                <div class="stat2-icon" style="background:#f0fdfa">
                    <i class="bi bi-building" style="color:#0f766e"></i>
                </div>
                <div>
                    <div class="stat2-val">{{ $totalUpt }}</div>
                    <div class="stat2-lbl">{{ $isNasional ? 'Total UPT' : 'UPT Dipantau' }}</div>
                    <div class="stat2-trend" style="color:#0f766e">
                        <i class="bi bi-diagram-3"></i> {{ $isNasional ? 'UPT aktif' : 'Ruang lingkup akun' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart + Table --}}
    <div class="row g-2">

        {{-- Bar Chart per UPT --}}
        <div class="col-12">
            <div class="dash-panel chart-panel h-100">
                <div class="dash-panel-header">
                    <div>
                        <div class="dash-panel-title">
                            <i class="bi bi-bar-chart-fill me-1 chart-accent-primary"></i>
                            {{ $isNasional ? 'Hasil Pemeriksaan per UPT' : 'Hasil Pemeriksaan ' . $scopeTitle }}
                        </div>
                        <div class="dash-panel-subtitle">
                            {{ $isNasional ? 'Dilepaskan vs. perlu penanganan antar UPT — periode ini' : 'Dilepaskan vs. perlu penanganan untuk ' . $scopeTitle . ' — periode ini' }}
                        </div>
                    </div>
                </div>
                <div style="padding:.5rem 1rem 1rem;min-height:350px">
                    @if($totalPemeriksaan > 0)
                        <div class="chart-canvas-wrap tall">
                            <canvas id="chartUpt"></canvas>
                        </div>
                    @else
                        <div class="empty-chart">
                            <i class="bi bi-bar-chart"></i>
                            <div style="font-weight:600">Belum ada data pemeriksaan pada periode ini.</div>
                            <div style="font-size:.8rem">Coba ubah periode untuk melihat riwayat data.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Chart Tindakan + Jenis Karantina (side by side) --}}
        <div class="col-12 col-xl-6">
            <div class="dash-panel chart-panel h-100">
                <div class="dash-panel-header">
                    <div>
                        <div class="dash-panel-title">
                            <i class="bi bi-exclamation-triangle me-1 chart-accent-danger"></i>
                            {{ $isNasional ? 'Tindakan per UPT' : 'Ringkasan Tindakan ' . $scopeTitle }}
                        </div>
                        <div class="dash-panel-subtitle">
                            {{ $isNasional ? 'Pelepasan, penolakan, perlakuan, pemusnahan' : 'Komposisi pelepasan, penolakan, perlakuan, dan pemusnahan' }}
                        </div>
                    </div>
                </div>
                <div style="padding:.5rem 1rem 1rem">
                    <div class="chart-canvas-wrap">
                        <canvas id="chartTindakanUpt"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="dash-panel chart-panel h-100">
                <div class="dash-panel-header">
                    <div>
                        <div class="dash-panel-title">
                            <i class="bi bi-diagram-3 me-1 chart-accent-violet"></i>
                            Distribusi Jenis Karantina
                        </div>
                        <div class="dash-panel-subtitle">
                            {{ $isNasional ? 'Total pemeriksaan hewan, ikan, tumbuhan' : 'Komposisi jenis karantina di ' . $scopeTitle }}
                        </div>
                    </div>
                </div>
                <div style="padding:.5rem 1rem 1rem">
                    <div class="chart-canvas-wrap">
                        <canvas id="chartJenis"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel per UPT (nasional saja) --}}
        @if($isNasional)
        <div class="col-12">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <div>
                        <div class="dash-panel-title">
                            <i class="bi bi-building me-1" style="color:#a855f7"></i>
                            Ringkasan Pemeriksaan per UPT
                        </div>
                        <div class="dash-panel-subtitle">
                            Jumlah pemeriksaan &amp; hasil tindakan masing-masing UPT
                        </div>
                    </div>
                    <input type="text" id="filterUpt" class="filter-input" placeholder="Cari UPT...">
                </div>
                @php $maxPeriksa = $rekapUpt->max('total_periksa_count') ?: 1; @endphp
                <div class="upt-scroll">
                    <table class="table upt-table mb-0" style="table-layout:auto;min-width:560px">
                        <thead style="position:sticky;top:0;background:#fff;z-index:1">
                            <tr>
                                <th style="min-width:200px;width:38%">UPT</th>
                                <th class="text-center" style="min-width:100px;width:16%">Total Periksa</th>
                                <th class="text-center" style="min-width:90px;width:14%">Dilepaskan</th>
                                <th class="text-center" style="min-width:100px;width:14%">Perlu Tindakan</th>
                                <th style="min-width:120px;width:18%">Proporsi</th>
                            </tr>
                        </thead>
                        <tbody id="uptTbody">
                            @forelse($rekapUpt as $upt)
                                <tr>
                                    <td>
                                        <div class="fw-semibold" style="color:#111827;font-size:.82rem;white-space:normal">
                                            {{ $upt->display_name }}
                                        </div>
                                        @if($upt->display_name !== $upt->nama_lengkap)
                                            <div style="font-size:.67rem;color:#9ca3af;margin-top:.1rem;white-space:normal">
                                                {{ \Str::limit($upt->nama_lengkap, 60) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-pill" style="background:#eff6ff;color:#1d4ed8">
                                            {{ $upt->total_periksa_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-pill" style="background:#dcfce7;color:#15803d">
                                            {{ $upt->pelepasan_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-pill" style="background:#fee2e2;color:#991b1b">
                                            {{ $upt->perlu_tindakan_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress-thin">
                                            <div class="bar"
                                                style="width:{{ $maxPeriksa > 0 ? round(($upt->total_periksa_count / $maxPeriksa) * 100) : 0 }}%;background:linear-gradient(90deg,#3b82f6,#22c55e)">
                                            </div>
                                        </div>
                                        <div style="font-size:.63rem;color:#9ca3af;margin-top:.15rem">
                                            {{ $upt->total_periksa_count }} pemeriksaan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-building" style="font-size:1.8rem;opacity:.15;display:block;margin-bottom:.4rem"></i>
                                        <span class="text-muted" style="font-size:.8rem">Belum ada data pemeriksaan pada periode ini.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="noResult" class="text-center py-3" style="display:none;font-size:.8rem;color:#9ca3af">
                    <i class="bi bi-search me-1"></i>UPT tidak ditemukan.
                </div>
            </div>
        </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const CHART_DPR = Math.max(window.devicePixelRatio || 1, 2);
        const isNasional = @json($isNasional);
        const CHART_COLORS = {
            blue: '#4285F4',
            blueSoft: 'rgba(66,133,244,0.82)',
            green: '#34A853',
            greenSoft: 'rgba(52,168,83,0.82)',
            red: '#EA4335',
            redSoft: 'rgba(234,67,53,0.82)',
            orange: '#F9AB00',
            orangeSoft: 'rgba(249,171,0,0.82)',
            purple: '#7E57C2',
            purpleSoft: 'rgba(126,87,194,0.82)',
            grid: '#E8EEF6',
            axis: '#5F6B7A',
            text: '#2F3A4A',
        };

        // Chart per UPT: dilepaskan vs perlu tindakan
        const chartUptEl = document.getElementById('chartUpt');
        if (chartUptEl) {
        new Chart(chartUptEl, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Dilepaskan',
                        data: @json($chartPelepasan),
                        backgroundColor: CHART_COLORS.greenSoft,
                        borderColor: CHART_COLORS.green,
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8,
                    },
                    {
                        label: 'Perlu Penanganan',
                        data: @json($chartPerluTindakan),
                        backgroundColor: CHART_COLORS.redSoft,
                        borderColor: CHART_COLORS.red,
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8,
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                devicePixelRatio: CHART_DPR,
                indexAxis: isNasional ? 'x' : 'y',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 10, weight: 600 }, boxWidth: 10, padding: 12, usePointStyle: true }
                    },
                    tooltip: {
                        mode: 'index', intersect: false,
                        backgroundColor: 'rgba(0,0,0,0.8)', padding: 10,
                        titleFont: { size: 11, weight: 600 }, bodyFont: { size: 10 },
                    }
                },
                scales: {
                    x: {
                        grid: { display: !isNasional, color: CHART_COLORS.grid },
                        ticks: {
                            font: { size: 8, weight: 500 },
                            color: CHART_COLORS.text,
                            maxRotation: isNasional ? 90 : 0,
                            minRotation: isNasional ? 45 : 0,
                        }
                    },
                    y: {
                        grid: { color: CHART_COLORS.grid, display: isNasional },
                        ticks: { font: { size: 10 }, stepSize: 1, color: CHART_COLORS.axis },
                        beginAtZero: true
                    },
                }
            }
        });
        }

        // ── Chart Tindakan ──────────────────
        const tindakanUptData = @json($tindakanPerUpt);
        if (tindakanUptData.length > 0) {
            if (isNasional) {
                const uptNames = tindakanUptData.map(d => d.nama.length > 18 ? d.nama.substring(0, 18) + '...' : d.nama);
                const pelepasan = tindakanUptData.map(d => d.pelepasan);
                const penolakan = tindakanUptData.map(d => d.penolakan);
                const perlakuan = tindakanUptData.map(d => d.perlakuan);
                const pemusnahan = tindakanUptData.map(d => d.pemusnahan);

                new Chart(document.getElementById('chartTindakanUpt'), {
                    type: 'bar',
                    data: {
                        labels: uptNames,
                        datasets: [
                            {
                                label: 'Dilepaskan',
                                data: pelepasan,
                                backgroundColor: CHART_COLORS.greenSoft,
                                borderColor: CHART_COLORS.green,
                                borderWidth: 1,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'Penolakan',
                                data: penolakan,
                                backgroundColor: CHART_COLORS.redSoft,
                                borderColor: CHART_COLORS.red,
                                borderWidth: 1,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'Perlakuan',
                                data: perlakuan,
                                backgroundColor: CHART_COLORS.orangeSoft,
                                borderColor: CHART_COLORS.orange,
                                borderWidth: 1,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'Pemusnahan',
                                data: pemusnahan,
                                backgroundColor: CHART_COLORS.purpleSoft,
                                borderColor: CHART_COLORS.purple,
                                borderWidth: 1,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        devicePixelRatio: CHART_DPR,
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { size: 10, weight: 600 }, boxWidth: 10, padding: 12, usePointStyle: true } },
                            tooltip: { mode: 'index', intersect: false, backgroundColor: 'rgba(0,0,0,0.8)', padding: 10 },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 9, weight: 500 }, rotation: 45, minRotation: 0, color: CHART_COLORS.text }
                            },
                            y: {
                                grid: { color: CHART_COLORS.grid },
                                ticks: { font: { size: 9 }, stepSize: 1, color: CHART_COLORS.axis },
                                beginAtZero: true,
                                stacked: false,
                            },
                        },
                    }
                });
            } else {
                const d = tindakanUptData[0];
                const tindakanLabels = ['Pelepasan', 'Penolakan', 'Perlakuan', 'Pemusnahan'];
                const tindakanJumlah = [d.pelepasan, d.penolakan, d.perlakuan, d.pemusnahan];

                new Chart(document.getElementById('chartTindakanUpt'), {
                    type: 'bar',
                    data: {
                        labels: tindakanLabels,
                        datasets: [{
                            label: 'Jumlah Tindakan',
                            data: tindakanJumlah,
                            backgroundColor: [CHART_COLORS.greenSoft, CHART_COLORS.redSoft, CHART_COLORS.orangeSoft, CHART_COLORS.purpleSoft],
                            borderColor: [CHART_COLORS.green, CHART_COLORS.red, CHART_COLORS.orange, CHART_COLORS.purple],
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        devicePixelRatio: CHART_DPR,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.82)',
                                padding: 10,
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: CHART_COLORS.grid },
                                ticks: { font: { size: 10 }, stepSize: 1, color: CHART_COLORS.axis },
                                beginAtZero: true,
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { size: 10, weight: 600 }, color: CHART_COLORS.text },
                            },
                        },
                    }
                });
            }
        }

        // ── Chart Jenis Karantina ──────────────────
        const jenisData = @json($jenisKarantina);
        if (jenisData.length > 0) {
            const jenisLabels = jenisData.map(d => d.jenis);
            const jenisJumlah = jenisData.map(d => d.jumlah);
            const jenisColors = {
                'Hewan': CHART_COLORS.orange,
                'Ikan': CHART_COLORS.blue,
                'Tumbuhan': CHART_COLORS.green,
            };
            const jenisBorderMap = {
                'Hewan': CHART_COLORS.orange,
                'Ikan': CHART_COLORS.blue,
                'Tumbuhan': CHART_COLORS.green,
            };
            const jenisBgColors = jenisLabels.map(j => jenisColors[j] || CHART_COLORS.purple);
            const jenisBorderColors = jenisLabels.map(j => jenisBorderMap[j] || CHART_COLORS.purple);

            new Chart(document.getElementById('chartJenis'), {
                type: 'bar',
                data: {
                    labels: jenisLabels,
                    datasets: [{
                        label: 'Total Pemeriksaan',
                        data: jenisJumlah,
                        backgroundColor: jenisBgColors.map(c => c + 'CC'),
                        borderColor: jenisBorderColors,
                        borderWidth: 1.5,
                        borderRadius: 8,
                        borderSkipped: false,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    devicePixelRatio: CHART_DPR,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 10, weight: 600 }, boxWidth: 10, padding: 12, usePointStyle: true } },
                        tooltip: { backgroundColor: 'rgba(0,0,0,0.8)', padding: 10, titleFont: { size: 11 }, bodyFont: { size: 10 } },
                    },
                    indexAxis: isNasional ? 'x' : 'y',
                    scales: {
                        x: {
                            grid: { color: CHART_COLORS.grid, display: !isNasional },
                            ticks: { font: { size: 10, weight: isNasional ? 600 : 500 }, stepSize: 1, color: CHART_COLORS.axis },
                            beginAtZero: true,
                        },
                        y: {
                            grid: { color: CHART_COLORS.grid, display: isNasional },
                            ticks: { font: { size: 10, weight: 600 }, color: CHART_COLORS.text },
                        },
                    },
                }
            });
        }

        const filterUptInput = document.getElementById('filterUpt');
        if (filterUptInput) {
            filterUptInput.addEventListener('input', function () {
                const q = this.value.toLowerCase();
                const rows = document.querySelectorAll('#uptTbody tr');
                let visible = 0;
                rows.forEach(row => {
                    const show = row.querySelector('td')?.textContent.toLowerCase().includes(q) ?? true;
                    row.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                document.getElementById('noResult').style.display = visible === 0 && q ? 'block' : 'none';
            });
        }
    </script>
@endpush

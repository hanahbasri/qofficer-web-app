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
@endsection

@push('styles')
    <style>
        .kpi-card {
            border: none;
            border-radius: .85rem;
            padding: 1rem 1.2rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,.13);
            transition: transform .18s, box-shadow .18s;
        }
        .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.18); }
        .kpi-card .kpi-icon {
            position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
            font-size: 3.2rem; opacity: .15;
        }
        .kpi-card .kpi-label {
            font-size: .68rem; font-weight: 700; letter-spacing: .07em;
            text-transform: uppercase; opacity: .82; margin-bottom: .2rem;
        }
        .kpi-card .kpi-value { font-size: 2rem; font-weight: 800; line-height: 1.1; }
        .kpi-card .kpi-sub   { font-size: .67rem; opacity: .72; margin-top: .15rem; }

        .kpi-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .kpi-green  { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .kpi-red    { background: linear-gradient(135deg, #ef4444, #b91c1c); }
        .kpi-teal   { background: linear-gradient(135deg, #14b8a6, #0f766e); }

        .dash-panel {
            border: none; border-radius: .85rem;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            background: #fff; overflow: hidden;
        }
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
        .filter-input:focus { border-color: #3b82f6; }

        .periode-pill { display: flex; gap: .2rem; background: #f3f4f6; border-radius: 99px; padding: .2rem; }
        .periode-pill a {
            padding: .25rem .8rem; border-radius: 99px; font-size: .72rem;
            font-weight: 600; color: #6b7280; text-decoration: none; transition: all .15s; white-space: nowrap;
        }
        .periode-pill a.active { background: #fff; color: #133139; box-shadow: 0 1px 4px rgba(0,0,0,.12); }
    </style>
@endpush

@section('content')

    {{-- Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <div style="font-size:1.1rem;font-weight:800;color:#111827;line-height:1.2">Rekap Kegiatan Pemeriksaan Kesehatan Karantina</div>
            <div style="font-size:.75rem;color:#9ca3af">{{ now()->translatedFormat('d F Y') }} &mdash; Seluruh UPT Barantin</div>
        </div>
        <div class="periode-pill">
            <a href="?periode=hari_ini" class="{{ $periode == 'hari_ini' ? 'active' : '' }}">Hari Ini</a>
            <a href="?periode=7_hari"   class="{{ $periode == '7_hari'   ? 'active' : '' }}">7 Hari</a>
            <a href="?periode=1_bulan"  class="{{ $periode == '1_bulan'  ? 'active' : '' }}">1 Bulan</a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-xl-3">
            <div class="kpi-card kpi-blue">
                <i class="bi bi-clipboard2-pulse kpi-icon"></i>
                <div class="kpi-label">Total Pemeriksaan</div>
                <div class="kpi-value">{{ $totalPemeriksaan }}</div>
                <div class="kpi-sub">Hasil periksa diterima periode ini</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card kpi-green">
                <i class="bi bi-check2-circle kpi-icon"></i>
                <div class="kpi-label">Komoditas Dilepaskan</div>
                <div class="kpi-value">{{ $totalPelepasan }}</div>
                <div class="kpi-sub">Dinyatakan aman &amp; dilepas</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card kpi-red">
                <i class="bi bi-exclamation-triangle kpi-icon"></i>
                <div class="kpi-label">Perlu Penanganan</div>
                <div class="kpi-value">{{ $totalPerluTindakan }}</div>
                <div class="kpi-sub">Ditolak / perlakuan / pemusnahan</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="kpi-card kpi-teal">
                <i class="bi bi-building kpi-icon"></i>
                <div class="kpi-label">Total UPT</div>
                <div class="kpi-value">{{ $totalUpt }}</div>
                <div class="kpi-sub">Unit Pelaksana Teknis aktif</div>
            </div>
        </div>
    </div>

    {{-- Chart + Table --}}
    <div class="row g-2">

        {{-- Bar Chart per UPT --}}
        <div class="col-12">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <div>
                        <div class="dash-panel-title">
                            <i class="bi bi-bar-chart-fill me-1" style="color:#3b82f6"></i>
                            Hasil Pemeriksaan per UPT
                        </div>
                        <div class="dash-panel-subtitle">Dilepaskan vs. perlu penanganan — periode ini</div>
                    </div>
                </div>
                <div style="padding:.5rem 1rem 1rem;min-height:350px">
                    <canvas id="chartUpt" style="max-height:300px"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart Komoditas Tindakan + Jenis Karantina (side by side) --}}
        @if ($isNasional)
            <div class="col-12 col-xl-6">
                <div class="dash-panel h-100">
                    <div class="dash-panel-header">
                        <div>
                            <div class="dash-panel-title">
                                <i class="bi bi-exclamation-triangle me-1" style="color:#ef4444"></i>
                                Tindakan per UPT
                            </div>
                            <div class="dash-panel-subtitle">Pelepasan, penolakan, perlakuan, pemusnahan</div>
                        </div>
                    </div>
                    <div style="padding:.5rem 1rem 1rem">
                        <canvas id="chartTindakanUpt" style="max-height:250px"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="dash-panel h-100">
                    <div class="dash-panel-header">
                        <div>
                            <div class="dash-panel-title">
                                <i class="bi bi-diagram-3 me-1" style="color:#a855f7"></i>
                                Distribusi Jenis Karantina
                            </div>
                            <div class="dash-panel-subtitle">Total pemeriksaan hewan, ikan, tumbuhan</div>
                        </div>
                    </div>
                    <div style="padding:.5rem 1rem 1rem">
                        <canvas id="chartJenis" style="max-height:250px"></canvas>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tabel per UPT --}}
        <div class="col-12">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <div>
                        <div class="dash-panel-title">
                            <i class="bi bi-building me-1" style="color:#a855f7"></i>
                            Ringkasan Pemeriksaan per UPT
                        </div>
                        <div class="dash-panel-subtitle">Jumlah pemeriksaan &amp; hasil tindakan masing-masing UPT</div>
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

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        // Chart per UPT: dilepaskan vs perlu tindakan
        new Chart(document.getElementById('chartUpt'), {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Dilepaskan',
                        data: @json($chartPelepasan),
                        backgroundColor: 'rgba(34,197,94,0.85)',
                        borderColor: '#16a34a',
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8,
                    },
                    {
                        label: 'Perlu Penanganan',
                        data: @json($chartPerluTindakan),
                        backgroundColor: 'rgba(239,68,68,0.8)',
                        borderColor: '#b91c1c',
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
                maintainAspectRatio: true,
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
                        grid: { display: false },
                        ticks: { font: { size: 8, weight: 500 }, color: '#374151', maxRotation: 90, minRotation: 45 }
                    },
                    y: {
                        grid: { color: '#f3f4f6' },
                        ticks: { font: { size: 10 }, stepSize: 1, color: '#9ca3af' },
                        beginAtZero: true
                    },
                }
            }
        });

        @if ($isNasional)
            const tindakanData = @json($tindakanPerKomoditas);
            if (tindakanData.length > 0) {
                const komoditasSet = new Set(tindakanData.map(d => d.komoditas));
                const tindakanSet  = new Set(tindakanData.map(d => d.tindakan));
                const komoditas    = Array.from(komoditasSet).sort();
                const tindakanList = Array.from(tindakanSet);

                const tindakanColors = {
                    'pelepasan':  { bg: 'rgba(34,197,94,0.85)',  border: '#16a34a' },
                    'penolakan':  { bg: 'rgba(239,68,68,0.85)',  border: '#991b1b' },
                    'perlakuan':  { bg: 'rgba(249,115,22,0.85)', border: '#c2410c' },
                    'pemusnahan': { bg: 'rgba(168,85,247,0.85)', border: '#6d28d9' },
                };

                // X-axis: komoditas, Bars/Series: tindakan
                const komoditasLabels = komoditas;
                const datasets = tindakanList.map(t => {
                    const data = komoditas.map(k => {
                        const found = tindakanData.find(d => d.komoditas === k && d.tindakan === t);
                        return found ? found.jumlah : 0;
                    });
                    const tColor = tindakanColors[t] || { bg: 'rgba(168,85,247,0.85)', border: '#6d28d9' };
                    return {
                        label: t.charAt(0).toUpperCase() + t.slice(1),
                        data,
                        backgroundColor: tColor.bg,
                        borderColor: tColor.border,
                        borderWidth: 1.5,
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.75,
                    };
                });

                new Chart(document.getElementById('chartTindakan'), {
                    type: 'bar',
                    data: { labels: komoditasLabels, datasets },
                    options: {
                        responsive: true, maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 11, weight: 600 }, boxWidth: 12, padding: 15, usePointStyle: true, color: '#374151' }
                            },
                            tooltip: {
                                mode: 'index', intersect: false,
                                backgroundColor: 'rgba(0,0,0,0.9)', padding: 12,
                                titleFont: { size: 12, weight: 700 }, bodyFont: { size: 11 },
                                filter: item => item.raw > 0,
                            },
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11, weight: 600 }, color: '#374151' } },
                            y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 }, stepSize: 1, color: '#9ca3af' }, beginAtZero: true },
                        }
                    }
                });
            }

            // ── Chart Tindakan per UPT ──────────────────
            const tindakanUptData = @json($tindakanPerUpt);
            if (tindakanUptData.length > 0) {
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
                                backgroundColor: 'rgba(34,197,94,0.85)',
                                borderColor: '#16a34a',
                                borderWidth: 1,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'Penolakan',
                                data: penolakan,
                                backgroundColor: 'rgba(239,68,68,0.85)',
                                borderColor: '#991b1b',
                                borderWidth: 1,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'Perlakuan',
                                data: perlakuan,
                                backgroundColor: 'rgba(249,115,22,0.85)',
                                borderColor: '#c2410c',
                                borderWidth: 1,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'Pemusnahan',
                                data: pemusnahan,
                                backgroundColor: 'rgba(168,85,247,0.85)',
                                borderColor: '#6d28d9',
                                borderWidth: 1,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { size: 10, weight: 600 }, boxWidth: 10, padding: 12, usePointStyle: true } },
                            tooltip: { mode: 'index', intersect: false, backgroundColor: 'rgba(0,0,0,0.8)', padding: 10 },
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 9, weight: 500 }, rotation: 45, minRotation: 0 } },
                            y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 9 }, stepSize: 1 }, beginAtZero: true, stacked: false },
                        },
                    }
                });
            }

            // ── Chart Jenis Karantina ──────────────────
            const jenisData = @json($jenisKarantina);
            if (jenisData.length > 0) {
                const jenisLabels = jenisData.map(d => d.jenis);
                const jenisJumlah = jenisData.map(d => d.jumlah);
                const jenisColors = {
                    'Hewan': '#f97316',
                    'Ikan': '#3b82f6',
                    'Tumbuhan': '#22c55e',
                };
                const jenisBgColors = jenisLabels.map(j => jenisColors[j] || '#a855f7');
                const jenisBorderColors = jenisBgColors.map(c => {
                    const darker = ['#c2410c', '#1d4ed8', '#16a34a'];
                    return jenisBgColors.indexOf(c) === 0 ? darker[0] : jenisBgColors.indexOf(c) === 1 ? darker[1] : darker[2];
                });

                new Chart(document.getElementById('chartJenis'), {
                    type: 'bar',
                    data: {
                        labels: jenisLabels,
                        datasets: [{
                            label: 'Total Pemeriksaan',
                            data: jenisJumlah,
                            backgroundColor: jenisBgColors,
                            borderColor: jenisBorderColors,
                            borderWidth: 2,
                            borderRadius: 5,
                            borderSkipped: false,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { size: 10, weight: 600 }, boxWidth: 10, padding: 12, usePointStyle: true } },
                            tooltip: { backgroundColor: 'rgba(0,0,0,0.8)', padding: 10, titleFont: { size: 11 }, bodyFont: { size: 10 } },
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 10, weight: 600 } } },
                            y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 9 }, stepSize: 1 }, beginAtZero: true },
                        },
                    }
                });
            }
        @endif

        document.getElementById('filterUpt').addEventListener('input', function () {
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
    </script>
@endpush

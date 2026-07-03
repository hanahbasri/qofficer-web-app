@extends('layouts.app')

@section('title', 'Unduh Laporan Pemeriksaan')
@section('page-title', 'Unduh Laporan')
@section('suppress-warning-alert', '1')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('pimpinan.dashboard') }}" data-label="Dashboard" class="nav-link">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('pimpinan.ekspor') }}" data-label="Unduh Laporan" class="nav-link active">
            <i class="bi bi-download"></i><span>Unduh Laporan</span>
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
        .quick-filter-btn {
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            padding: .5rem 1rem;
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            background: #fff;
            color: #4b5563;
        }

        .quick-filter-btn:hover {
            border-color: var(--primary);
            background: #f0f9ff;
            color: var(--primary);
        }

        .quick-filter-btn.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .summary-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 100%);
            border: 1px solid #e0f2fe;
            border-radius: .75rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: var(--primary);
            opacity: .05;
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .summary-card-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .summary-card-icon {
            width: 56px;
            height: 56px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .summary-card-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }

        .summary-card-label {
            font-size: .75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
            margin-top: .25rem;
        }

        .filter-section {
            border: 1px solid #e5e7eb;
            border-radius: .75rem;
            padding: 1.5rem;
            background: #fafbfc;
        }

        .download-btn-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }

        .download-btn-group .btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .28rem;
            padding: .7rem .85rem;
            font-size: .8rem;
            line-height: 1.25;
            border-radius: .75rem !important;
            min-height: 88px;
        }

        .download-btn-group .btn i {
            font-size: 1rem !important;
        }

        .download-btn-group .btn > span:nth-child(2) {
            font-weight: 700;
        }

        .download-btn-group .btn-label {
            font-size: .66rem;
            opacity: .75;
            font-weight: 400;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: .15;
        }

        .empty-state-text {
            color: #6b7280;
            font-size: .9rem;
            margin-bottom: .5rem;
        }

        .empty-state-hint {
            font-size: .8rem;
            color: #9ca3af;
        }

        .filter-info-item {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem 0;
        }

        .filter-info-item .label {
            font-size: .75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .filter-info-item .value {
            font-weight: 600;
            color: #111827;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 360px;
            background: #fff;
            border-radius: .75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            z-index: 1050;
            animation: slideInRight .3s ease-out;
        }

        .toast-notification.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fef08a 100%);
            border: 1px solid #facf30;
            color: #924f0f;
        }

        .toast-notification-icon {
            flex-shrink: 0;
            font-size: 1.5rem;
        }

        .toast-notification-content {
            flex: 1;
        }

        .toast-notification-title {
            font-weight: 600;
            font-size: .95rem;
            margin-bottom: .25rem;
        }

        .toast-notification-message {
            font-size: .85rem;
            opacity: .9;
            line-height: 1.4;
        }

        .toast-notification-close {
            flex-shrink: 0;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            opacity: .6;
            padding: 0;
            line-height: 1;
            transition: opacity .2s;
        }

        .toast-notification-close:hover {
            opacity: 1;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-notification.hide {
            animation: slideOutRight .3s ease-in forwards;
        }

        @media (max-width: 991px) {
            .download-btn-group {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .toast-notification {
                min-width: auto;
                left: 10px;
                right: 10px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Toast Notification Container --}}
    @if (session()->has('warning'))
        <div class="toast-notification warning" id="toastNotification" role="alert">
            <div class="toast-notification-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="toast-notification-content">
                <div class="toast-notification-title">Data Tidak Ditemukan</div>
                <div class="toast-notification-message">{{ session('warning') }}</div>
            </div>
            <button type="button" class="toast-notification-close" id="closeToast" aria-label="Tutup notifikasi">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <div class="mb-4">
        <h1 class="page-heading mb-2">Unduh Laporan Pemeriksaan</h1>
        <p class="text-muted mb-0" style="font-size:.85rem">Pilih rentang tanggal dan UPT untuk mengunduh data pemeriksaan dalam format CSV atau PDF</p>
    </div>

<div class="row g-3">

    {{-- Filter Panel --}}
    <div class="col-lg-7">
        <div class="filter-section">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-funnel" style="color:var(--primary)"></i>
                Atur Filter
            </h6>

            {{-- Quick Filter Buttons --}}
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:.85rem">Periode Cepat</label>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="quick-filter-btn" data-periodo="hari_ini">
                        <i class="bi bi-calendar-day me-1"></i>Hari Ini
                    </button>
                    <button type="button" class="quick-filter-btn" data-periodo="7_hari">
                        <i class="bi bi-calendar-week me-1"></i>7 Hari
                    </button>
                    <button type="button" class="quick-filter-btn" data-periodo="1_bulan">
                        <i class="bi bi-calendar-month me-1"></i>1 Bulan
                    </button>
                    <button type="button" class="quick-filter-btn" data-periodo="custom">
                        <i class="bi bi-calendar-range me-1"></i>Custom
                    </button>
                </div>
            </div>

            <form method="GET" action="{{ route('pimpinan.ekspor') }}" id="filterForm">

                {{-- UPT Filter --}}
                @if ($isMultiUpt)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unit Pelaksana Teknis (UPT)</label>
                        <select name="upt" class="form-select" id="uptFilter">
                            <option value="">Semua UPT</option>
                            @foreach ($uptList as $upt)
                                <option value="{{ $upt->kode }}" {{ request('upt') == $upt->kode ? 'selected' : '' }}>
                                    {{ $upt->short_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unit Pelaksana Teknis (UPT)</label>
                        <div class="alert alert-light mb-0 py-2 px-3" style="border:1px solid #e5e7eb">
                            <div style="font-size:.9rem;color:#4b5563">
                                <i class="bi bi-building me-2" style="color:var(--primary)"></i>
                                <strong>{{ $uptList->first()?->short_name ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Date Range --}}
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Dari Tanggal</label>
                        <input type="date" name="dari" class="form-control" id="dariFilter" value="{{ request('dari') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Sampai Tanggal</label>
                        <input type="date" name="sampai" class="form-control" id="sampaiFilter" value="{{ request('sampai') }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-brand w-100">
                    <i class="bi bi-search me-1"></i>Tampilkan Jumlah Data
                </button>
            </form>
        </div>
    </div>

    {{-- Summary & Download Panel --}}
    <div class="col-lg-5">
        <div class="d-flex flex-column h-100 gap-3">

            {{-- Summary Card --}}
            <div class="summary-card">
                <div class="summary-card-content">
                    <div class="summary-card-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div style="flex:1">
                        <div class="summary-card-number">{{ $jumlahData }}</div>
                        <div class="summary-card-label">Baris Data Tersedia</div>
                    </div>
                </div>
            </div>

            {{-- Filter Information --}}
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:.75rem;padding:1rem">
                <div style="font-size:.8rem;font-weight:600;color:#6b7280;text-transform:uppercase;margin-bottom:.75rem">
                    <i class="bi bi-info-circle me-1"></i>Filter Terpilih
                </div>

                @if (request('dari') && request('sampai'))
                    <div class="filter-info-item">
                        <span class="label">Periode:</span>
                        <span class="value">{{ \Carbon\Carbon::parse(request('dari'))->format('d M Y') }} — {{ \Carbon\Carbon::parse(request('sampai'))->format('d M Y') }}</span>
                    </div>
                @else
                    <div class="filter-info-item">
                        <span class="label">Periode:</span>
                        <span class="value" style="color:#9ca3af">Semua periode</span>
                    </div>
                @endif

                @if (request('upt') && $isMultiUpt)
                    <div class="filter-info-item">
                        <span class="label">UPT:</span>
                        <span class="value">{{ $uptList->firstWhere('kode', request('upt'))?->short_name ?? request('upt') }}</span>
                    </div>
                @elseif(!$isMultiUpt)
                    <div class="filter-info-item">
                        <span class="label">UPT:</span>
                        <span class="value">{{ $uptList->first()?->short_name ?? '-' }}</span>
                    </div>
                @else
                    <div class="filter-info-item">
                        <span class="label">UPT:</span>
                        <span class="value" style="color:#9ca3af">Semua UPT</span>
                    </div>
                @endif
            </div>

            {{-- Download Buttons --}}
            @if ($jumlahData > 0)
                <div class="download-btn-group">
                    <a href="{{ route('pimpinan.ekspor.unduh', request()->all()) }}" class="btn btn-brand download-link"
                        data-format="csv">
                        <span><i class="bi bi-file-earmark-spreadsheet" style="font-size:1.25rem"></i></span>
                        <span>Unduh CSV</span>
                        <span class="download-btn-label">Buka di Excel</span>
                    </a>
                    <a href="{{ route('pimpinan.ekspor.cetak-pdf', request()->all()) }}" class="btn btn-danger download-link"
                        target="_blank" data-format="pdf">
                        <span><i class="bi bi-file-earmark-pdf" style="font-size:1.25rem"></i></span>
                        <span>Cetak / PDF</span>
                        <span class="download-btn-label">Ctrl+P → Simpan PDF</span>
                    </a>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div class="empty-state-text">Tidak ada data untuk diunduh</div>
                    <div class="empty-state-hint">Coba sesuaikan filter periode atau UPT</div>
                </div>
                <div class="download-btn-group">
                    <button class="btn btn-light" disabled style="opacity:.5">
                        <span><i class="bi bi-file-earmark-spreadsheet" style="font-size:1.25rem"></i></span>
                        <span>Unduh CSV</span>
                        <span class="download-btn-label">Tidak tersedia</span>
                    </button>
                    <button class="btn btn-light" disabled style="opacity:.5">
                        <span><i class="bi bi-file-earmark-pdf" style="font-size:1.25rem"></i></span>
                        <span>Cetak / PDF</span>
                        <span class="download-btn-label">Tidak tersedia</span>
                    </button>
                </div>
            @endif

            {{-- Info Box --}}
            <div style="background:#f0f9ff;border-left:3px solid var(--primary);border-radius:.5rem;padding:.75rem 1rem">
                <div style="font-size:.8rem;color:#0369a1;line-height:1.5">
                    <i class="bi bi-lightbulb-fill me-1"></i>
                    <strong>💡 Tips:</strong> Data CSV berisi informasi lengkap tentang pemeriksaan, jenis karantina, dan tindakan yang diambil. Format PDF cocok untuk laporan resmi dan arsip.
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
    <script>
        // Toast Notification Handler
        function initToastNotification() {
            const toast = document.getElementById('toastNotification');
            if (!toast) return;

            const closeBtn = document.getElementById('closeToast');

            function dismissToast() {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 300);
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', dismissToast);
            }

            // Auto-dismiss after 6 seconds
            setTimeout(dismissToast, 6000);
        }

        function formatDateLocal(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        // Quick Filter Buttons
        document.querySelectorAll('.quick-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const periodo = this.dataset.periodo;
                const today = new Date();
                const dariInput = document.getElementById('dariFilter');
                const sampaiInput = document.getElementById('sampaiFilter');
                const form = document.getElementById('filterForm');

                // Remove active class from all buttons
                document.querySelectorAll('.quick-filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Set dates based on selection
                if (periodo === 'hari_ini') {
                    const dateStr = formatDateLocal(today);
                    dariInput.value = dateStr;
                    sampaiInput.value = dateStr;
                    form.submit();
                } else if (periodo === '7_hari') {
                    const sevenDaysAgo = new Date(today);
                    sevenDaysAgo.setDate(today.getDate() - 7);
                    dariInput.value = formatDateLocal(sevenDaysAgo);
                    sampaiInput.value = formatDateLocal(today);
                    form.submit();
                } else if (periodo === '1_bulan') {
                    const oneMonthAgo = new Date(today);
                    oneMonthAgo.setMonth(today.getMonth() - 1);
                    dariInput.value = formatDateLocal(oneMonthAgo);
                    sampaiInput.value = formatDateLocal(today);
                    form.submit();
                } else if (periodo === 'custom') {
                    dariInput.value = '';
                    sampaiInput.value = '';
                    dariInput.focus();
                }
            });
        });

        // Auto-submit on date change (after a short delay)
        let submitTimeout;
        ['dariFilter', 'sampaiFilter'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', function() {
                clearTimeout(submitTimeout);
                submitTimeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 500);
            });
        });

        // Download button loading state
        document.querySelectorAll('.download-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.hasAttribute('disabled')) {
                    e.preventDefault();
                } else {
                    const icon = this.querySelector('i');
                    const text = this.querySelector('span:nth-child(2)');
                    const originalIcon = icon.className;
                    const originalText = text.textContent;

                    icon.className = 'bi bi-hourglass-split spinner';
                    text.textContent = 'Memproses...';

                    // For CSV (direct download), restore immediately
                    if (this.dataset.format === 'csv') {
                        setTimeout(() => {
                            icon.className = originalIcon;
                            text.textContent = originalText;
                        }, 1000);
                    }
                    // For PDF (opens in new tab), restore when user comes back
                    else {
                        window.addEventListener('focus', function restore() {
                            icon.className = originalIcon;
                            text.textContent = originalText;
                            window.removeEventListener('focus', restore);
                        });
                    }
                }
            });
        });

        // Add spinner animation
        const style = document.createElement('style');
        style.textContent = `
            .spinner {
                display: inline-block;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // Restore active button on page load based on filter
        window.addEventListener('load', function() {
            initToastNotification();

            const dariVal = document.getElementById('dariFilter').value;
            const sampaiVal = document.getElementById('sampaiFilter').value;

            document.querySelectorAll('.quick-filter-btn').forEach(b => b.classList.remove('active'));

            if (dariVal && sampaiVal) {
                const today = new Date();
                const todayStr = formatDateLocal(today);

                const sevenDaysAgo = new Date(today);
                sevenDaysAgo.setDate(today.getDate() - 7);
                const sevenDaysAgoStr = formatDateLocal(sevenDaysAgo);

                const oneMonthAgo = new Date(today);
                oneMonthAgo.setMonth(today.getMonth() - 1);
                const oneMonthAgoStr = formatDateLocal(oneMonthAgo);

                if (dariVal === todayStr && sampaiVal === todayStr) {
                    document.querySelector('[data-periodo="hari_ini"]')?.classList.add('active');
                } else if (dariVal === sevenDaysAgoStr && sampaiVal === todayStr) {
                    document.querySelector('[data-periodo="7_hari"]')?.classList.add('active');
                } else if (dariVal === oneMonthAgoStr && sampaiVal === todayStr) {
                    document.querySelector('[data-periodo="1_bulan"]')?.classList.add('active');
                } else {
                    document.querySelector('[data-periodo="custom"]')?.classList.add('active');
                }
            }
        });
    </script>
@endpush

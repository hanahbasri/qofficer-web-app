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
@endsection

@section('content')

<div class="page-heading">
    Ringkasan UPT
    <small>{{ Auth::user()->upt?->nama ?? 'UPT tidak diketahui' }}</small>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff3e0">
                    <i class="bi bi-file-earmark-text" style="color:#e65100"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stAktif }}</div>
                    <div class="stat-label">ST Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8f5e9">
                    <i class="bi bi-check-circle" style="color:#2e7d32"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $stSelesaiHari }}</div>
                    <div class="stat-label">Selesai Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff8e1">
                    <i class="bi bi-hourglass-split" style="color:#f57f17"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $menungguReview }}</div>
                    <div class="stat-label">Menunggu Review</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#ede7f6">
                    <i class="bi bi-people" style="color:#512da8"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $petugasAktif }}</div>
                    <div class="stat-label">Petugas Aktif</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-3">
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:6px;height:20px;background:#522E2E;border-radius:3px"></div>
                <h6 class="mb-0 fw-bold" style="font-size:.9rem">Aksi Cepat</h6>
            </div>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('koordinator.hasil-periksa') }}?status=belum_direview"
                    class="btn btn-brand btn-sm">
                    <i class="bi bi-clipboard2-check me-2"></i>Review Hasil Pemeriksaan
                </a>
                <a href="{{ route('koordinator.hasil-periksa') }}"
                    class="btn btn-sm" style="border:1.5px solid #ede8e4;color:#5a4040;font-weight:600">
                    <i class="bi bi-list-ul me-2"></i>Semua Hasil Pemeriksaan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 h-100" style="background:linear-gradient(135deg,#fdf6f0,#faf0ee)">
            <div class="d-flex align-items-start gap-3">
                <div style="width:38px;height:38px;background:rgba(82,46,46,.1);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-phone" style="color:#522E2E;font-size:1.1rem"></i>
                </div>
                <div>
                    <div class="fw-bold mb-1" style="font-size:.85rem;color:#2d1a1a">Buat Surat Tugas</div>
                    <p class="mb-0 text-muted" style="font-size:.78rem;line-height:1.5">
                        Pembuatan dan pengiriman Surat Tugas dilakukan melalui aplikasi mobile Q-Officer Koordinator.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

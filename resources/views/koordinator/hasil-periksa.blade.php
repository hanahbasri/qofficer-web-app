@extends('layouts.app')

@section('title', 'Hasil Pemeriksaan')
@section('page-title', 'Hasil Pemeriksaan')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('koordinator.dashboard') }}" data-label="Dashboard" class="nav-link">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.hasil-periksa') }}" data-label="Hasil Pemeriksaan" class="nav-link active">
            <i class="bi bi-clipboard2-check"></i><span>Hasil Pemeriksaan</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('koordinator.petugas') }}" data-label="Petugas" class="nav-link">
            <i class="bi bi-people-fill"></i><span>Petugas</span>
        </a>
    </li>
@endsection

@section('content')
<div class="page-heading">
    Daftar Hasil Pemeriksaan K-3.7b
    <small>{{ Auth::user()->upt?->nama }}</small>
</div>

{{-- Filter Bar --}}
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-sm-4">
            <label class="form-label">Status Review</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="belum_direview" {{ request('status') == 'belum_direview' ? 'selected' : '' }}>
                    Belum Direview
                </option>
                <option value="sudah_direview" {{ request('status') == 'sudah_direview' ? 'selected' : '' }}>
                    Sudah Direview
                </option>
            </select>
        </div>
        <div class="col-auto d-flex gap-2">
            <button type="submit" class="btn btn-brand btn-sm">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
            <a href="{{ route('koordinator.hasil-periksa') }}" class="btn btn-sm"
                style="border:1.5px solid #ede8e4;color:#5a4040;font-weight:600">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>No Surat Tugas</th>
                    <th>Petugas</th>
                    <th>Tgl Periksa</th>
                    <th>Komoditas</th>
                    <th>Temuan</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilList as $hasil)
                <tr>
                    <td>
                        <span class="fw-semibold" style="font-size:.82rem;color:#522E2E">
                            {{ $hasil->suratTugas?->no_st ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:.85rem">{{ $hasil->petugas?->nama }}</div>
                        <div class="text-muted" style="font-size:.73rem">{{ $hasil->petugas?->nip }}</div>
                    </td>
                    <td style="font-size:.82rem;white-space:nowrap">
                        {{ $hasil->tgl_periksa?->format('d/m/Y') }}
                        <div class="text-muted" style="font-size:.72rem">{{ $hasil->tgl_periksa?->format('H:i') }} WIB</div>
                    </td>
                    <td style="font-size:.82rem">{{ $hasil->komoditas ?? '-' }}</td>
                    <td style="font-size:.82rem">{{ Str::limit($hasil->temuan, 45) }}</td>
                    <td>
                        @if($hasil->status_review === 'sudah_direview')
                            <span class="badge badge-status-aktif">
                                <i class="bi bi-check-circle me-1"></i>Sudah Direview
                            </span>
                        @else
                            <span class="badge badge-status-tertunda">
                                <i class="bi bi-clock me-1"></i>Belum Direview
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('koordinator.hasil-periksa.detail', $hasil->id) }}"
                            class="btn btn-sm btn-brand-outline">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-clipboard2-x" style="font-size:2rem;opacity:.3;display:block;margin-bottom:.5rem"></i>
                            Belum ada data pemeriksaan.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($hasilList->hasPages())
    <div class="px-3 py-2 border-top" style="background:#faf7f5">
        {{ $hasilList->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

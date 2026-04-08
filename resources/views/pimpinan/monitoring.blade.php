@extends('layouts.app')

@section('title', 'Monitoring Pemeriksaan')
@section('page-title', 'Monitoring Pemeriksaan')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('pimpinan.dashboard') }}" data-label="Dashboard" class="nav-link">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('pimpinan.monitoring') }}" data-label="Monitoring" class="nav-link active">
            <i class="bi bi-binoculars-fill"></i><span>Monitoring</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('pimpinan.surat-tugas') }}" data-label="Surat Tugas" class="nav-link">
            <i class="bi bi-file-earmark-text-fill"></i><span>Surat Tugas</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('pimpinan.ekspor') }}" data-label="Ekspor Data" class="nav-link">
            <i class="bi bi-download"></i><span>Ekspor Data</span>
        </a>
    </li>
@endsection

@section('content')
<div class="page-heading">
    Monitoring Hasil Pemeriksaan
    <small>Semua UPT — data real-time</small>
</div>

{{-- Filter Card --}}
<div class="card p-3 mb-3">
    <form method="GET">
        <div class="row g-2 align-items-end">
            @if($isMultiUpt)
            <div class="col-sm-6 col-md-3">
                <label class="form-label">UPT</label>
                <select name="upt" class="form-select form-select-sm">
                    <option value="">Semua UPT</option>
                    @foreach($uptList as $upt)
                        <option value="{{ $upt->kode }}" {{ request('upt') == $upt->kode ? 'selected' : '' }}>
                            {{ $upt->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-sm-6 col-md-2">
                <label class="form-label">Jenis Karantina</label>
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="H" {{ request('jenis') == 'H' ? 'selected' : '' }}>Hewan</option>
                    <option value="T" {{ request('jenis') == 'T' ? 'selected' : '' }}>Tumbuhan</option>
                    <option value="I" {{ request('jenis') == 'I' ? 'selected' : '' }}>Ikan</option>
                </select>
            </div>
            <div class="col-sm-6 col-md-2">
                <label class="form-label">Status Review</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="belum_direview" {{ request('status') == 'belum_direview' ? 'selected' : '' }}>Belum</option>
                    <option value="sudah_direview" {{ request('status') == 'sudah_direview' ? 'selected' : '' }}>Sudah</option>
                </select>
            </div>
            <div class="col-sm-3 col-md-2">
                <label class="form-label">Dari</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-sm-3 col-md-2">
                <label class="form-label">Sampai</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-sm-6 col-md-1 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-brand btn-sm flex-fill">
                    <i class="bi bi-funnel"></i>
                </button>
                <a href="{{ route('pimpinan.monitoring') }}" class="btn btn-ghost btn-sm flex-fill">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>No ST</th>
                    <th>UPT</th>
                    <th>Petugas</th>
                    <th>Tgl Periksa</th>
                    <th>Jenis</th>
                    <th>Komoditas</th>
                    <th>Review</th>
                    <th>Tindakan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilList as $hasil)
                <tr>
                    <td>
                        <span style="font-size:.82rem;font-weight:600;color:var(--primary)">
                            {{ $hasil->suratTugas?->no_st ?? '-' }}
                        </span>
                    </td>
                    <td style="font-size:.8rem;max-width:160px">
                        <div class="fw-semibold">{{ $hasil->suratTugas?->upt?->nama ?? '-' }}</div>
                    </td>
                    <td style="font-size:.82rem">{{ $hasil->petugas?->nama ?? '-' }}</td>
                    <td style="font-size:.8rem;white-space:nowrap">
                        {{ $hasil->tgl_periksa?->format('d/m/Y') }}
                        <div class="text-muted" style="font-size:.72rem">{{ $hasil->tgl_periksa?->format('H:i') }}</div>
                    </td>
                    <td>
                        @php $jenisMap = ['H'=>'Hewan','T'=>'Tumbuhan','I'=>'Ikan']; @endphp
                        <span class="badge" style="background:rgba(19,49,57,.1);color:var(--primary)">
                            {{ $jenisMap[$hasil->suratTugas?->jenis_karantina] ?? '-' }}
                        </span>
                    </td>
                    <td style="font-size:.82rem">{{ Str::limit($hasil->komoditas, 30) ?? '-' }}</td>
                    <td>
                        @if($hasil->status_review === 'sudah_direview')
                            <span class="badge badge-status-aktif">Sudah</span>
                        @else
                            <span class="badge badge-status-tertunda">Belum</span>
                        @endif
                    </td>
                    <td>
                        @if($hasil->rekomendasi)
                            @php
                                $badgeMap = [
                                    'pelepasan'  => 'badge-rekomendasi-pelepasan',
                                    'penolakan'  => 'badge-rekomendasi-penolakan',
                                    'perlakuan'  => 'badge-rekomendasi-perlakuan',
                                    'pemusnahan' => 'badge-rekomendasi-pemusnahan',
                                ];
                                $t = $hasil->rekomendasi->tindakan;
                            @endphp
                            <span class="badge {{ $badgeMap[$t] ?? '' }}">{{ ucfirst($t) }}</span>
                        @else
                            <span class="text-muted" style="font-size:.78rem">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pimpinan.monitoring.detail', $hasil->id) }}"
                            class="btn btn-outline-brand btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-search" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                        <span class="text-muted">Tidak ada data pemeriksaan.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($hasilList->hasPages())
    <div class="px-3 py-2 border-top" style="background:#f8fafc">
        {{ $hasilList->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

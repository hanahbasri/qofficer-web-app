@extends('layouts.app')

@section('title', 'Daftar Penugasan Karantina')
@section('page-title', 'Penugasan')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('pimpinan.dashboard') }}" data-label="Dashboard" class="nav-link">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('pimpinan.monitoring') }}" data-label="Monitoring" class="nav-link">
            <i class="bi bi-binoculars-fill"></i><span>Monitoring</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('pimpinan.surat-tugas') }}" data-label="Surat Tugas" class="nav-link active">
            <i class="bi bi-file-earmark-text-fill"></i><span>Surat Tugas</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('pimpinan.ekspor') }}" data-label="Unduh Laporan" class="nav-link">
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
    .ptg-chips { display:flex; flex-wrap:wrap; gap:.2rem; }
    .ptg-chip {
        background:#eef5f7; color:var(--primary-mid);
        border-radius:.3rem; padding:.1em .5em;
        font-size:.69rem; font-weight:600; white-space:nowrap;
    }
    .st-no { font-weight:700; font-size:.84rem; color:var(--primary); letter-spacing:.02em; }
    .st-jenis-badge { background:rgba(19,49,57,.09); color:var(--primary); }
    .table tbody tr td { vertical-align:middle; }
    .tbl-date small { display:block; font-size:.7rem; color:var(--text-muted); margin-top:.08rem; }
    .col-info-strip {
        background:#f4f7f8;
        border: 1px solid var(--border);
        border-radius:.5rem;
        padding:.55rem 1rem;
        display:flex; align-items:center; gap:.6rem;
        font-size:.78rem; color:var(--text-muted);
        margin-bottom:1rem;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    Daftar Penugasan Karantina
    <small>Rekap surat tugas seluruh UPT &mdash; dapat difilter per UPT atau status</small>
</div>

{{-- Filter --}}
<div class="card p-3 mb-3">
    <form method="GET">
        <div class="row g-2 align-items-end">
            @if($isMultiUpt)
            <div class="col-sm-6 col-md-4">
                <label class="form-label">Unit Pelaksana Teknis (UPT)</label>
                <select name="upt" class="form-select form-select-sm">
                    <option value="">Semua UPT</option>
                    @foreach($uptList as $upt)
                        <option value="{{ $upt->kode }}" {{ request('upt') == $upt->kode ? 'selected' : '' }}>
                            {{ $upt->short_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-sm-6 col-md-3">
                <label class="form-label">Status Penugasan</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="tertunda" {{ request('status') == 'tertunda' ? 'selected' : '' }}>Tertunda (Belum Dimulai)</option>
                    <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif (Sedang Berjalan)</option>
                    <option value="dikirim"  {{ request('status') == 'dikirim'  ? 'selected' : '' }}>Dikirim (Menunggu Konfirmasi)</option>
                    <option value="selesai"  {{ request('status') == 'selesai'  ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-brand btn-sm">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('pimpinan.surat-tugas') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="col-info-strip">
    <i class="bi bi-table"></i>
    <span>Total <strong>{{ $stList->total() }}</strong> penugasan ditemukan</span>
    @if(request()->hasAny(['upt','status']))
        <span class="ms-1" style="color:var(--primary);font-weight:600">&mdash; filter aktif</span>
    @endif
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Nomor Surat Tugas</th>
                    <th>UPT</th>
                    <th>Tanggal</th>
                    <th>Perihal / Kegiatan</th>
                    <th>Jenis Karantina</th>
                    <th>Petugas Ditugaskan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stList as $st)
                <tr>
                    <td>
                        <span class="st-no">{{ $st->no_st }}</span>
                    </td>
                    <td style="font-size:.8rem;max-width:170px">
                        <div class="fw-semibold" style="color:var(--text)">
                            {{ $st->upt?->short_name ?? ($st->upt?->nama ?? $st->upt_id) }}
                        </div>
                    </td>
                    <td class="tbl-date">
                        {{ $st->tanggal?->format('d M Y') }}
                        <small>{{ $st->tanggal?->format('l') }}</small>
                    </td>
                    <td style="font-size:.82rem;max-width:230px" title="{{ $st->perihal }}">{{ Str::limit($st->perihal, 55) }}</td>
                    <td>
                        <span class="badge st-jenis-badge">
                            {{ ['H'=>'Hewan','T'=>'Tumbuhan','I'=>'Ikan'][$st->jenis_karantina] ?? '-' }}
                        </span>
                    </td>
                    <td style="max-width:180px">
                        @if($st->petugas->isEmpty())
                            <span class="text-muted" style="font-size:.78rem">—</span>
                        @else
                            <div class="ptg-chips">
                                @foreach($st->petugas as $ptg)
                                    <span class="ptg-chip">{{ $ptg->nama }}</span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeMap = [
                                'tertunda' => 'badge-status-tertunda',
                                'aktif'    => 'badge-status-aktif',
                                'dikirim'  => 'badge-status-dikirim',
                                'selesai'  => 'badge-status-selesai',
                            ];
                            $labelMap = [
                                'tertunda' => 'Tertunda',
                                'aktif'    => 'Sedang Berjalan',
                                'dikirim'  => 'Dikirim',
                                'selesai'  => 'Selesai',
                            ];
                        @endphp
                        <span class="badge {{ $badgeMap[$st->status] ?? '' }}">
                            {{ $labelMap[$st->status] ?? ucfirst($st->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-file-earmark-x" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                        <span class="text-muted">Tidak ada penugasan ditemukan.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stList->hasPages())
    <div class="px-3 py-2 border-top" style="background:#f8fafc">
        {{ $stList->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

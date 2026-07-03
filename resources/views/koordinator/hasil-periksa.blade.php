@extends('layouts.app')

@section('title', 'Hasil Pemeriksaan')
@section('page-title', 'Hasil Pemeriksaan')

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
        <a href="{{ route('koordinator.keamanan') }}" data-label="Keamanan Akun"
            class="nav-link {{ request()->routeIs('koordinator.keamanan') ? 'active' : '' }}">
            <i class="bi bi-key-fill"></i><span>Keamanan Akun</span>
        </a>
    </li>
@endsection

@push('styles')
<style>
    .hasil-filter-note {
        font-size: .73rem;
        color: var(--text-muted);
        line-height: 1.45;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    Daftar Hasil Pemeriksaan K-3.7b
    <small>{{ Auth::user()->upt?->nama ?? 'UPT tidak diketahui' }}</small>
</div>

{{-- Filter Bar --}}
<div class="card p-3 mb-3">
    <form method="GET" id="hasilFilterForm" class="row g-2 align-items-end">
        <div class="col-sm-4 col-md-3">
            <label class="form-label">Cari</label>
            <input type="text" name="cari" id="hasilCariInput" class="form-control form-control-sm"
                placeholder="Komoditas, temuan, atau petugas..." value="{{ request('cari') }}">
        </div>
        <div class="col-sm-4 col-md-2">
            <label class="form-label">Status Review</label>
            <select name="status" class="form-select form-select-sm hasil-auto-filter">
                <option value="">Semua Status</option>
                <option value="belum_direview" {{ request('status') == 'belum_direview' ? 'selected' : '' }}>
                    Belum Direview
                </option>
                <option value="sudah_direview" {{ request('status') == 'sudah_direview' ? 'selected' : '' }}>
                    Sudah Direview
                </option>
            </select>
        </div>
        <div class="col-sm-4 col-md-2">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="tgl_dari" class="form-control form-control-sm hasil-auto-filter"
                value="{{ request('tgl_dari') }}">
        </div>
        <div class="col-sm-4 col-md-2">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="tgl_sampai" class="form-control form-control-sm hasil-auto-filter"
                value="{{ request('tgl_sampai') }}">
        </div>
        <div class="col-sm-4 col-md-3">
            <div class="hasil-filter-note mb-2">
                Filter otomatis saat memilih. Ketik untuk cari.
            </div>
            <a href="{{ route('koordinator.hasil-periksa') }}" class="btn btn-ghost btn-sm">Reset</a>
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilList as $hasil)
                <tr>
                    <td>
                        <span class="fw-semibold" style="font-size:.82rem;color:var(--primary)">
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
                            class="btn btn-sm btn-ghost" style="padding:.3rem .5rem;color:var(--primary)">
                            <i class="bi bi-eye"></i>
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

@push('scripts')
<script>
    (function() {
        const form = document.getElementById('hasilFilterForm');
        const cariInput = document.getElementById('hasilCariInput');
        const autoFilters = document.querySelectorAll('.hasil-auto-filter');

        if (!form) return;

        autoFilters.forEach(function(el) {
            el.addEventListener('change', function() {
                form.requestSubmit();
            });
        });

        if (cariInput) {
            let timer = null;
            cariInput.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(function() { form.requestSubmit(); }, 350);
            });
            cariInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); clearTimeout(timer); form.requestSubmit(); }
            });
        }
    })();
</script>
@endpush

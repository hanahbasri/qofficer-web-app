@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')
@section('page-title', 'Detail Pemeriksaan')

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

@push('styles')
    <style>
        .foto-thumb {
            height: 140px;
            width: 100%;
            object-fit: cover;
            border-radius: .45rem;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color .15s, transform .15s;
        }

        .foto-thumb:hover {
            border-color: var(--primary);
            transform: scale(1.02);
        }

        .modal-content {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('pimpinan.monitoring') }}" class="btn btn-ghost btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="page-heading mb-0">
            Detail Pemeriksaan K-3.7b
            <small>No. ST: {{ $hasil->suratTugas?->no_st ?? '-' }}</small>
        </div>
        @if ($hasil->status_review === 'sudah_direview')
            <span class="badge badge-status-aktif ms-auto">Sudah Direview</span>
        @else
            <span class="badge badge-status-tertunda ms-auto">Belum Direview</span>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-lg-8">

            <div class="card p-4 mb-3">
                <div class="section-divider">
                    <div class="bar"></div>
                    <h6>Informasi Pemeriksaan</h6>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="info-label">No Surat Tugas</div>
                        <div class="info-value fw-bold" style="color:var(--primary)">{{ $hasil->suratTugas?->no_st ?? '-' }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Jenis Karantina</div>
                        <div class="info-value">
                            {{ ['H' => 'Hewan', 'T' => 'Tumbuhan', 'I' => 'Ikan'][$hasil->suratTugas?->jenis_karantina] ?? '-' }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Tanggal Pemeriksaan</div>
                        <div class="info-value">{{ $hasil->tgl_periksa?->format('d M Y, H:i') }} WIB</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">UPT</div>
                        <div class="info-value">{{ $hasil->suratTugas?->upt?->nama ?? $hasil->suratTugas?->upt_id }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Petugas</div>
                        <div class="info-value">{{ $hasil->petugas?->nama }}</div>
                        <div class="text-muted" style="font-size:.75rem">NIP: {{ $hasil->petugas?->nip }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Target / Sasaran</div>
                        <div class="info-value">{{ $hasil->target ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Komoditas</div>
                        <div class="info-value">{{ $hasil->komoditas ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Metode Pemeriksaan</div>
                        <div class="info-value">{{ $hasil->metode ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Temuan</div>
                        <div class="info-value">{{ $hasil->temuan ?? '-' }}</div>
                    </div>
                    @if ($hasil->lat && $hasil->long)
                        <div class="col-12">
                            <div class="info-label">Koordinat GPS</div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="info-value font-monospace" style="font-size:.82rem">{{ $hasil->lat }},
                                    {{ $hasil->long }}</span>
                                <a href="https://maps.google.com/?q={{ $hasil->lat }},{{ $hasil->long }}"
                                    target="_blank" rel="noopener noreferrer" class="btn btn-ghost btn-sm">
                                    <i class="bi bi-map me-1"></i>Buka Maps
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($hasil->catatan)
                        <div class="col-12">
                            <div class="info-label">Catatan Tambahan</div>
                            <div class="p-3 rounded"
                                style="background:#f8fafc;border:1px solid var(--border);font-size:.85rem;line-height:1.6">
                                {{ $hasil->catatan }}</div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($hasil->dokumentasi->count() > 0)
                <div class="card p-4">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>Dokumentasi Foto ({{ $hasil->dokumentasi->count() }})</h6>
                    </div>
                    <div class="row g-2">
                        @foreach ($hasil->dokumentasi as $foto)
                            <div class="col-6 col-md-3">
                                @if ($foto->foto_path)
                                    <img src="{{ asset('storage/' . $foto->foto_path) }}" class="foto-thumb" alt="Foto"
                                        data-bs-toggle="modal" data-bs-target="#fotoModal"
                                        data-src="{{ asset('storage/' . $foto->foto_path) }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded"
                                        style="height:140px;background:#f5f0ed;border:2px dashed #d1d5db">
                                        <span class="text-muted" style="font-size:.75rem">Lokal</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card p-4" style="position:sticky;top:72px">
                <div class="section-divider">
                    <div class="bar"></div>
                    <h6>Rekomendasi Koordinator</h6>
                </div>

                @if ($hasil->rekomendasi)
                    @php
                        $t = $hasil->rekomendasi->tindakan;
                        $badgeMap = [
                            'pelepasan' => 'badge-rekomendasi-pelepasan',
                            'penolakan' => 'badge-rekomendasi-penolakan',
                            'perlakuan' => 'badge-rekomendasi-perlakuan',
                            'pemusnahan' => 'badge-rekomendasi-pemusnahan',
                        ];
                    @endphp
                    <div class="mb-3 p-3 rounded" style="background:#f8fafc;border:1px solid var(--border)">
                        <div class="info-label mb-2">Tindakan</div>
                        <span class="badge {{ $badgeMap[$t] ?? '' }}"
                            style="font-size:.85rem;padding:.4em .85em">{{ ucfirst($t) }}</span>
                    </div>
                    <div class="mb-3">
                        <div class="info-label">Alasan / Catatan</div>
                        <div class="mt-1 p-2 rounded"
                            style="background:#f8fafc;font-size:.82rem;line-height:1.6;border:1px solid var(--border)">
                            {{ $hasil->rekomendasi->catatan }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="info-label">Koordinator</div>
                        <div class="info-value" style="font-size:.85rem">{{ $hasil->rekomendasi->koordinator?->nama }}
                        </div>
                        <div class="text-muted" style="font-size:.73rem">
                            {{ $hasil->rekomendasi->created_at?->format('d M Y, H:i') }}</div>
                    </div>

                    {{-- Status Pengiriman ke BEST-TRUST (Simulasi) --}}
                    <div class="p-3 rounded mb-3" style="background:#f0f9f7;border:1px solid #c9e8e0">
                        <div class="info-label mb-2">Status Pengiriman (BEST-TRUST)</div>
                        @php
                            $btStatus = $hasil->rekomendasi->best_trust_status ?? 'success';
                            $btBadge = match ($btStatus) {
                                'success' => 'badge-status-aktif',
                                'failed' => 'badge-rekomendasi-penolakan',
                                default => 'badge-status-dikirim',
                            };
                        @endphp
                        <span class="badge {{ $btBadge }}" style="font-size:.82rem">
                            {{ $btStatus === 'success' ? '✓ Terkirim (Simulasi)' : strtoupper($btStatus) }}
                        </span>
                        @if ($hasil->rekomendasi->best_trust_response)
                            @php
                                $resp = json_decode($hasil->rekomendasi->best_trust_response, true);
                            @endphp
                            <div class="text-muted mt-1" style="font-size:.72rem">
                                Ref: {{ $resp['reference'] ?? 'N/A' }} | {{ $resp['timestamp'] ?? '' }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-hourglass"
                            style="font-size:1.5rem;opacity:.25;display:block;margin-bottom:.5rem"></i>
                        <p class="text-muted mb-0" style="font-size:.83rem">Belum ada rekomendasi dari Koordinator UPT.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="fotoModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="background:#111">
                <div class="modal-header" style="background:#111;border-color:#333">
                    <h6 class="modal-title text-white">Foto Dokumentasi</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img id="modalFotoSrc" src="" class="img-fluid rounded" alt="Foto"
                        style="max-height:75vh;object-fit:contain">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('[data-bs-target="#fotoModal"]').forEach(el => {
                el.addEventListener('click', () => {
                    document.getElementById('modalFotoSrc').src = el.dataset.src;
                });
            });
        </script>
    @endpush
@endsection

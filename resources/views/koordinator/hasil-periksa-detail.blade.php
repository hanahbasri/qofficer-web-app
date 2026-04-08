@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')
@section('page-title', 'Detail Pemeriksaan')

{{-- Success Notification --}}
@if (session()->has('success'))
    <div class="success-notification" id="successNotification" role="alert">
        <div class="success-notification-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="success-notification-content">
            <div class="success-notification-title">Berhasil!</div>
            <div class="success-notification-message">{{ session('success') }}</div>
        </div>
        <button type="button" class="success-notification-close" id="closeSuccessNotif" aria-label="Tutup notifikasi">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif

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

@push('styles')
    <style>
        .info-label {
            font-size: .73rem;
            font-weight: 600;
            color: #9a7a7a;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .2rem;
        }

        .info-value {
            font-size: .9rem;
            color: #1a1a2e;
            font-weight: 500;
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .section-divider .bar {
            width: 4px;
            height: 18px;
            background: #522E2E;
            border-radius: 2px;
        }

        .section-divider h6 {
            margin: 0;
            font-weight: 700;
            font-size: .92rem;
        }

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
            border-color: #522E2E;
            transform: scale(1.02);
        }

        /* Success Notification */
        .success-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #b1dfbb;
            border-radius: .75rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            z-index: 9999;
            min-width: 360px;
            animation: slideInRight .4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .success-notification.hide {
            animation: slideOutRight .4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .success-notification-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .success-notification-content {
            flex: 1;
        }

        .success-notification-title {
            font-weight: 600;
            color: #155724;
            margin-bottom: .25rem;
        }

        .success-notification-message {
            font-size: .85rem;
            color: #155724;
            opacity: .9;
        }

        .success-notification-close {
            flex-shrink: 0;
            background: none;
            border: none;
            cursor: pointer;
            color: #155724;
            font-size: 1.25rem;
            padding: 0;
            line-height: 1;
            opacity: .6;
            transition: opacity .2s;
        }

        .success-notification-close:hover {
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

        @media (max-width: 576px) {
            .success-notification {
                min-width: auto;
                left: 10px;
                right: 10px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Back + Title --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('koordinator.hasil-periksa') }}" class="btn btn-sm"
            style="border:1.5px solid #ede8e4;color:#5a4040;padding:.4rem .75rem">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="page-heading mb-0">
            Detail Pemeriksaan K-3.7b
            <small>No. ST: {{ $hasil->suratTugas?->no_st ?? '-' }}</small>
        </div>
        @if ($hasil->status_review === 'sudah_direview')
            <span class="badge badge-status-aktif ms-auto">
                <i class="bi bi-check-circle me-1"></i>Sudah Direview
            </span>
        @else
            <span class="badge badge-status-tertunda ms-auto">
                <i class="bi bi-clock me-1"></i>Menunggu Review
            </span>
        @endif
    </div>

    <div class="row g-3">

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Info Pemeriksaan --}}
            <div class="card p-4 mb-3">
                <div class="section-divider">
                    <div class="bar"></div>
                    <h6>Informasi Pemeriksaan</h6>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="info-label">No Surat Tugas</div>
                        <div class="info-value fw-bold" style="color:#522E2E">{{ $hasil->suratTugas?->no_st ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Jenis Karantina</div>
                        <div class="info-value">
                            @php $jenis = ['H' => 'Hewan', 'T' => 'Tumbuhan', 'I' => 'Ikan']; @endphp
                            {{ $jenis[$hasil->suratTugas?->jenis_karantina] ?? '-' }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-label">Tanggal Pemeriksaan</div>
                        <div class="info-value">{{ $hasil->tgl_periksa?->format('d M Y, H:i') }} WIB</div>
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
                                <span class="info-value font-monospace" style="font-size:.82rem">
                                    {{ $hasil->lat }}, {{ $hasil->long }}
                                </span>
                                <a href="https://maps.google.com/?q={{ $hasil->lat }},{{ $hasil->long }}"
                                    target="_blank" rel="noopener noreferrer" class="btn btn-sm"
                                    style="border:1.5px solid #ede8e4;color:#522E2E;font-size:.78rem;font-weight:600">
                                    <i class="bi bi-map me-1"></i>Buka Maps
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($hasil->catatan)
                        <div class="col-12">
                            <div class="info-label">Catatan Tambahan</div>
                            <div class="p-3 rounded"
                                style="background:#faf7f5;border:1px solid #ede8e4;font-size:.85rem;line-height:1.6">
                                {{ $hasil->catatan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Dokumentasi Foto --}}
            @if ($hasil->dokumentasi->count() > 0)
                <div class="card p-4">
                    <div class="section-divider">
                        <div class="bar"></div>
                        <h6>Dokumentasi Foto <span class="text-muted fw-normal">({{ $hasil->dokumentasi->count() }})</span>
                        </h6>
                    </div>
                    <div class="row g-2">
                        @foreach ($hasil->dokumentasi as $foto)
                            <div class="col-6 col-md-3">
                                @if ($foto->foto_path)
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $foto->foto_path) }}" class="foto-thumb"
                                            alt="Dokumentasi pemeriksaan" data-bs-toggle="modal" data-bs-target="#fotoModal"
                                            data-src="{{ asset('storage/' . $foto->foto_path) }}">
                                        <div class="position-absolute bottom-0 start-0 end-0 p-2"
                                            style="background:linear-gradient(to top, rgba(0,0,0,.8), transparent);border-radius:0 0 .45rem .45rem">
                                            <small class="text-white" style="font-size:.70rem;display:block">
                                                <i class="bi bi-camera me-1" style="opacity:.8"></i>
                                                @if ($foto->created_at)
                                                    {{ $foto->created_at->format('H:i') }}
                                                @else
                                                    Lokal
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @elseif($foto->foto_display || $foto->foto_server)
                                    {{-- Base64 photo dari mobile --}}
                                    <div class="position-relative">
                                        <img src="data:image/jpeg;base64,{{ $foto->foto_display ?? $foto->foto_server }}"
                                            class="foto-thumb" alt="Dokumentasi pemeriksaan" data-bs-toggle="modal"
                                            data-bs-target="#fotoModal"
                                            data-src="data:image/jpeg;base64,{{ $foto->foto_display ?? $foto->foto_server }}"
                                            style="cursor:pointer">
                                        <div class="position-absolute bottom-0 start-0 end-0 p-2"
                                            style="background:linear-gradient(to top, rgba(0,0,0,.8), transparent);border-radius:0 0 .45rem .45rem">
                                            <small class="text-white" style="font-size:.70rem;display:block">
                                                <i class="bi bi-camera me-1" style="opacity:.8"></i>
                                                @if ($foto->created_at)
                                                    {{ $foto->created_at->format('H:i') }}
                                                @else
                                                    Mobile
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded"
                                        style="height:140px;background:#f5f0ed;border:2px dashed #ded0c8">
                                        <div class="text-center">
                                            <i class="bi bi-image text-muted" style="font-size:1.5rem;opacity:.4"></i>
                                            <div class="text-muted mt-1" style="font-size:.7rem">Kosong</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Rekomendasi --}}
        <div class="col-lg-4">
            <div class="card p-4" style="position:sticky;top:72px">
                <div class="section-divider">
                    <div class="bar"></div>
                    <h6>Rekomendasi Tindakan</h6>
                </div>

                @if ($hasil->rekomendasi)
                    {{-- Sudah Ada Rekomendasi --}}
                    <div class="mb-3 p-3 rounded" style="background:#faf7f5;border:1px solid #ede8e4">
                        <div class="info-label mb-2">Tindakan Diputuskan</div>
                        @php
                            $tindakan = $hasil->rekomendasi->tindakan;
                            $badgeMap = [
                                'pelepasan' => 'badge-rekomendasi-pelepasan',
                                'penolakan' => 'badge-rekomendasi-penolakan',
                                'perlakuan' => 'badge-rekomendasi-perlakuan',
                                'pemusnahan' => 'badge-rekomendasi-pemusnahan',
                            ];
                        @endphp
                        <span class="badge {{ $badgeMap[$tindakan] ?? '' }}" style="font-size:.82rem;padding:.4em .85em">
                            {{ ucfirst($tindakan) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <div class="info-label">Alasan / Catatan</div>
                        <div class="mt-1 p-2 rounded"
                            style="background:#faf7f5;font-size:.82rem;line-height:1.6;border:1px solid #ede8e4">
                            {{ $hasil->rekomendasi->catatan }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="info-label">Ditetapkan Oleh</div>
                        <div class="info-value" style="font-size:.85rem">{{ $hasil->rekomendasi->koordinator?->nama }}
                        </div>
                        <div class="text-muted" style="font-size:.73rem">
                            {{ $hasil->rekomendasi->created_at?->format('d M Y, H:i') }}
                        </div>
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
                    {{-- Form Rekomendasi Baru --}}
                    <p class="text-muted mb-3" style="font-size:.82rem">
                        <i class="bi bi-info-circle me-1"></i>
                        Belum ada rekomendasi. Isi form di bawah untuk menentukan tindakan karantina.
                    </p>
                    <form method="POST" action="{{ route('koordinator.rekomendasi.simpan') }}">
                        @csrf
                        <input type="hidden" name="id_hasil_pemeriksaan" value="{{ $hasil->id }}">

                        <div class="mb-3">
                            <label class="form-label">Tindakan <span class="text-danger">*</span></label>
                            <select name="tindakan"
                                class="form-select form-select-sm @error('tindakan') is-invalid @enderror" required>
                                <option value="">— Pilih Tindakan —</option>
                                <option value="pelepasan" {{ old('tindakan') == 'pelepasan' ? 'selected' : '' }}>
                                    Pelepasan</option>
                                <option value="penolakan" {{ old('tindakan') == 'penolakan' ? 'selected' : '' }}>
                                    Penolakan</option>
                                <option value="perlakuan" {{ old('tindakan') == 'perlakuan' ? 'selected' : '' }}>
                                    Perlakuan</option>
                                <option value="pemusnahan" {{ old('tindakan') == 'pemusnahan' ? 'selected' : '' }}>
                                    Pemusnahan</option>
                            </select>
                            @error('tindakan')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alasan / Catatan <span class="text-danger">*</span></label>
                            <textarea name="catatan" rows="4" class="form-control form-control-sm @error('catatan') is-invalid @enderror"
                                placeholder="Jelaskan alasan rekomendasi tindakan karantina..." required minlength="10">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-brand btn-sm w-100">
                            <i class="bi bi-check2-circle me-1"></i>Simpan Rekomendasi
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Foto Lightbox Modal --}}
    <div class="modal fade" id="fotoModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="background:#1a1a1a;border:none">
                <div class="modal-header" style="background:#1a1a1a;border-bottom:1px solid #333">
                    <h6 class="modal-title text-white">Foto Dokumentasi</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img id="modalFotoSrc" src="" class="img-fluid rounded" alt="Foto pemeriksaan"
                        style="max-height:75vh;object-fit:contain">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Notification Handler
            function initSuccessNotification() {
                const notif = document.getElementById('successNotification');
                if (!notif) return;

                const closeBtn = document.getElementById('closeSuccessNotif');

                function dismissNotification() {
                    notif.classList.add('hide');
                    setTimeout(() => {
                        notif.style.display = 'none';
                    }, 400);
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', dismissNotification);
                }

                // Auto-dismiss after 6 seconds
                setTimeout(dismissNotification, 6000);
            }

            // Foto Lightbox
            document.querySelectorAll('[data-bs-target="#fotoModal"]').forEach(el => {
                el.addEventListener('click', () => {
                    document.getElementById('modalFotoSrc').src = el.dataset.src;
                });
            });

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', initSuccessNotification);
        </script>
    @endpush
@endsection

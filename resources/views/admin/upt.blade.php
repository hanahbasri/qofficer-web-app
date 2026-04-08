@extends('layouts.app')

@section('title', 'Manajemen UPT')
@section('page-title', 'Manajemen UPT')

@section('sidebar-menu')
    <li class="nav-item">
        <a href="{{ route('admin.pengguna') }}" data-label="Pengguna"
            class="nav-link {{ request()->routeIs('admin.pengguna*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Pengguna</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.role') }}" data-label="Role"
            class="nav-link {{ request()->routeIs('admin.role') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i><span>Role</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.upt') }}" data-label="UPT"
            class="nav-link {{ request()->routeIs('admin.upt') ? 'active' : '' }}">
            <i class="bi bi-building"></i><span>UPT</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.profil') }}" data-label="Profil"
            class="nav-link {{ request()->routeIs('admin.profil') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i><span>Profil Saya</span>
        </a>
    </li>
@endsection

@push('styles')
    <style>
        .modal-content {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
        }

        .modal-header {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 1rem 1.25rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: .7;
        }

        .modal-body {
            padding: 1.5rem 1.25rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: .9rem 1.25rem;
            background: #f8fafc;
        }
    </style>
@endpush

@section('content')
    {{-- Flash messages --}}
    @if (session('success'))
        <div class="alert d-flex align-items-center gap-2 mb-4 py-2 px-3"
            style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.65rem;font-size:.84rem;color:#166534">
            <i class="bi bi-check-circle-fill flex-shrink-0" style="color:#16a34a;font-size:1rem"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error') || $errors->any())
        <div class="alert d-flex align-items-center gap-2 mb-4 py-2 px-3"
            style="background:#fef2f2;border:1px solid #fecaca;border-radius:.65rem;font-size:.84rem;color:#991b1b">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0" style="color:#dc2626;font-size:1rem"></i>
            <span>{{ session('error') ?? $errors->first() }}</span>
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="page-heading mb-0">
            Daftar UPT
            <small>Unit Pelaksana Teknis Karantina</small>
        </div>
        <button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#tambahUptModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah UPT
        </button>
    </div>

    {{-- Search bar --}}
    <div class="mb-3" style="max-width:340px">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white border-end-0" style="border-color:#dee2e6">
                <i class="bi bi-search" style="color:#9ca3af;font-size:.85rem"></i>
            </span>
            <input type="text" id="uptSearch" class="form-control border-start-0 ps-0"
                placeholder="Cari kode, nama, wilayah…" style="border-color:#dee2e6;font-size:.84rem">
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle" id="uptTable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama UPT</th>
                        <th>Tampil di Dashboard</th>
                        <th>Wilayah</th>
                        <th class="text-center">Pengguna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($uptList as $upt)
                        <tr class="upt-row">
                            <td>
                                <span class="font-monospace fw-bold upt-kode"
                                    style="font-size:.82rem;color:var(--primary)">{{ $upt->kode }}</span>
                            </td>
                            <td style="font-size:.85rem">
                                <div class="fw-semibold upt-nama" style="line-height:1.4">{{ $upt->nama }}</div>
                                @if ($upt->nama_satpel && $upt->nama_satpel !== 'UPT Induk')
                                    <div class="upt-wilayah-text" style="font-size:.78rem;color:#6b7280;margin-top:.2rem">
                                        {{ $upt->nama_satpel }}</div>
                                @endif
                            </td>
                            <td style="font-size:.82rem">
                                {{-- Short name yang muncul di chart/tabel pimpinan --}}
                                <span class="fw-semibold" style="color:var(--primary)">{{ $upt->short_name }}</span>
                                @if ($upt->alias)
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:.1rem">
                                        dari alias manual
                                    </div>
                                @else
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:.1rem">
                                        auto-generate
                                    </div>
                                @endif
                            </td>
                            <td class="upt-wilayah" style="font-size:.82rem;color:#6b7280">{{ $upt->wilayah ?? '—' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.pengguna', ['upt' => $upt->kode]) }}"
                                    class="badge text-decoration-none" title="Lihat pengguna UPT ini"
                                    style="background:rgba(19,49,57,.1);color:var(--primary)">
                                    {{ $upt->users_count }}
                                    <i class="bi bi-box-arrow-up-right ms-1" style="font-size:.65rem"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-ghost btn-sm" title="Edit UPT" data-bs-toggle="modal"
                                    data-bs-target="#editUptModal" data-kode="{{ $upt->kode }}"
                                    data-nama="{{ $upt->nama }}" data-alias="{{ $upt->alias }}"
                                    data-wilayah="{{ $upt->wilayah }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-building"
                                    style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                                <span class="text-muted">Belum ada data UPT.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- No results row (search) --}}
            <div id="noSearchResult" class="text-center py-5 d-none">
                <i class="bi bi-search" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem"></i>
                <span class="text-muted" style="font-size:.85rem">Tidak ada UPT yang cocok dengan pencarian.</span>
            </div>
        </div>
        @if (method_exists($uptList, 'hasPages') && $uptList->hasPages())
            <div class="px-3 py-2 border-top" style="background:#f8fafc">
                {{ $uptList->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah UPT --}}
    <div class="modal fade" id="tambahUptModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.upt.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">
                        <i class="bi bi-building-add me-2"></i>Tambah UPT
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode UPT <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control" required maxlength="20"
                            placeholder="1101">
                        <div class="form-text">Gunakan kode satpel 4 digit sesuai data Barantin.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama UPT <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required
                            placeholder="BBKHIT Aceh — Satpel Lhokseumawe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alias / Singkatan
                            <span class="text-muted fw-normal" style="font-size:.78rem">(opsional)</span>
                        </label>
                        <input type="text" name="alias" class="form-control" maxlength="60"
                            placeholder="BBKHIT Aceh">
                        <div class="form-text">Nama singkat yang ditampilkan di dashboard pimpinan.</div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Wilayah</label>
                        <input type="text" name="wilayah" class="form-control" placeholder="Aceh">
                    </div>
                </div>
                <div class="modal-footer gap-2">
                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand btn-sm">
                        <i class="bi bi-check2 me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit UPT --}}
    <div class="modal fade" id="editUptModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="editUptForm" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>Edit UPT
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode UPT</label>
                        <input type="text" id="editKode" class="form-control font-monospace" disabled>
                        <div class="form-text">Kode tidak dapat diubah.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama UPT <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alias / Singkatan
                            <span class="text-muted fw-normal" style="font-size:.78rem">(opsional)</span>
                        </label>
                        <input type="text" name="alias" id="editAlias" class="form-control" maxlength="60"
                            placeholder="contoh: BBKHIT DKI JKT">
                        <div class="form-text">Nama singkat yang ditampilkan di dashboard pimpinan.</div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Wilayah</label>
                        <input type="text" name="wilayah" id="editWilayah" class="form-control">
                    </div>
                </div>
                <div class="modal-footer gap-2">
                    <button type="button" class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand btn-sm">
                        <i class="bi bi-check2 me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Populate edit modal
            document.getElementById('editUptModal').addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                document.getElementById('editKode').value = btn.dataset.kode;
                document.getElementById('editNama').value = btn.dataset.nama;
                document.getElementById('editAlias').value = btn.dataset.alias || '';
                document.getElementById('editWilayah').value = btn.dataset.wilayah || '';
                document.getElementById('editUptForm').action = '/admin/upt/' + btn.dataset.kode;
            });

            // Client-side search filter
            document.getElementById('uptSearch').addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('#uptTable .upt-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const kode = row.querySelector('.upt-kode')?.textContent.toLowerCase() ?? '';
                    const nama = row.querySelector('.upt-nama')?.textContent.toLowerCase() ?? '';
                    const wil = row.querySelector('.upt-wilayah')?.textContent.toLowerCase() ?? '';
                    const match = !q || kode.includes(q) || nama.includes(q) || wil.includes(q);
                    row.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });

                const noResult = document.getElementById('noSearchResult');
                if (noResult) noResult.classList.toggle('d-none', visibleCount > 0 || !q);
            });
        </script>
    @endpush
@endsection

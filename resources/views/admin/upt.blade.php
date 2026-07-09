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
        <a href="{{ route('admin.log-sistem') }}" data-label="Log Sistem"
            class="nav-link {{ request()->routeIs('admin.log-sistem') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i><span>Log Sistem</span>
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

        .modal-action-btn {
            width: 118px;
            height: 38px;
            padding: 0 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            line-height: 1;
        }

        .modal-action-btn .bi {
            margin-right: 0 !important;
            font-size: .95rem;
        }

        .upt-search {
            display: flex;
            align-items: center;
            gap: .55rem;
            max-width: 340px;
            background: #fff;
            border: 1.5px solid #dee2e6;
            border-radius: .75rem;
            padding: .15rem .8rem;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .upt-search:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 .18rem rgba(19, 49, 57, .12);
        }

        .upt-search-icon {
            color: #9ca3af;
            font-size: .9rem;
            line-height: 1;
            flex-shrink: 0;
        }

        .upt-search-input {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: .42rem 0 !important;
            font-size: .84rem !important;
        }

        .upt-search-input:focus {
            border: none !important;
            box-shadow: none !important;
        }
    </style>
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert d-flex align-items-center gap-2 mb-4 py-2 px-3"
            style="background:#fef2f2;border:1px solid #fecaca;border-radius:.65rem;font-size:.84rem;color:#991b1b">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0" style="color:#dc2626;font-size:1rem"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="page-heading mb-0">
            Daftar UPT
            <small>Unit Pelaksana Teknis Karantina</small>
        </div>
        {{-- Tombol Tambah UPT dihilangkan: daftar UPT sudah baku sesuai ketetapan Badan Karantina Indonesia (Administrator hanya dapat mengedit) --}}
    </div>

    {{-- Search bar --}}
    <div class="mb-3">
        <div class="upt-search">
            <i class="bi bi-search upt-search-icon"></i>
            <input type="text" id="uptSearch" class="form-control upt-search-input" autocomplete="off"
                placeholder="Cari kode, nama, wilayah...">
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle" id="uptTable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama UPT</th>
                        <th>Alias</th>
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
                                <span class="fw-semibold" style="color:var(--primary)">{{ $upt->alias ?: '-' }}</span>
                            </td>
                            <td class="upt-wilayah" style="font-size:.82rem;color:#6b7280">{{ $upt->wilayah ?? '-' }}</td>
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

    {{-- Modal Tambah UPT dihilangkan — fitur Tambah UPT ditiadakan, Administrator hanya dapat mengedit data UPT yang sudah ada --}}

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
                        <label class="form-label">Alias
                            <span class="text-muted fw-normal" style="font-size:.78rem">(opsional)</span>
                        </label>
                        <input type="text" name="alias" id="editAlias" class="form-control" maxlength="60"
                            placeholder="contoh: BBKHIT DKI JKT">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Wilayah</label>
                        <input type="text" name="wilayah" id="editWilayah" class="form-control">
                    </div>
                </div>
                <div class="modal-footer gap-2">
                    <button type="button" class="btn btn-outline-brand btn-sm modal-action-btn"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand btn-sm modal-action-btn">
                        <i class="bi bi-check2"></i>Simpan
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
                document.getElementById('editUptForm').action = '{{ route("admin.upt") }}' + '/' + btn.dataset.kode;
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

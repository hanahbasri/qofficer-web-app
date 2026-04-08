<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemeriksaan Karantina</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #111;
            background: #fff;
        }

        .page-wrapper {
            padding: 20px 28px;
        }

        /* Header kop */
        .kop {
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 3px solid #133139;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .kop-logo {
            width: 52px;
            height: 52px;
            background: #133139;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            font-weight: 900;
            flex-shrink: 0;
            letter-spacing: -1px;
        }

        .kop-text h1 {
            font-size: 13px;
            font-weight: 900;
            color: #133139;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .kop-text p {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        /* Info filter */
        .filter-info {
            display: flex;
            gap: 24px;
            background: #f3f4f6;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 10.5px;
        }

        .filter-info .fi-item strong { color: #133139; }

        /* Tabel data */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        thead tr {
            background: #133139;
            color: #fff;
        }

        thead th {
            padding: 6px 7px;
            text-align: left;
            font-weight: 700;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        tbody tr:nth-child(even) { background: #f9fafb; }

        tbody td {
            padding: 5px 7px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .badge-tindakan {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }

        .tindakan-pelepasan  { background: #dcfce7; color: #15803d; }
        .tindakan-penolakan  { background: #fee2e2; color: #991b1b; }
        .tindakan-perlakuan  { background: #ffedd5; color: #c2410c; }
        .tindakan-pemusnahan { background: #ede9fe; color: #6d28d9; }

        .footer-note {
            margin-top: 14px;
            font-size: 9.5px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
        }

        /* Tombol cetak — tidak ikut tercetak */
        .print-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: #133139;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
            font-size: 13px;
        }

        .print-bar button {
            background: #fff;
            color: #133139;
            border: none;
            border-radius: 6px;
            padding: 6px 18px;
            font-weight: 700;
            cursor: pointer;
            font-size: 13px;
        }

        .print-bar a {
            color: #d1fae5;
            font-size: 11px;
            text-decoration: none;
        }

        @media print {
            .print-bar { display: none !important; }
            .page-wrapper { padding: 12px 16px; }
            body { font-size: 10px; }
        }
    </style>
</head>
<body>

    {{-- Toolbar cetak (tidak tercetak) --}}
    <div class="print-bar">
        <div>
            <strong>Laporan Pemeriksaan Karantina</strong>
            <span style="margin-left:12px;opacity:.7">— Klik "Cetak / Simpan PDF" untuk mencetak atau menyimpan sebagai PDF</span>
        </div>
        <div style="display:flex;gap:10px;align-items:center">
            <a href="javascript:history.back()">&#8592; Kembali</a>
            <button onclick="window.print()">🖨 Cetak / Simpan PDF</button>
        </div>
    </div>

    <div class="page-wrapper" style="margin-top:50px">
        {{-- Kop laporan --}}
        <div class="kop">
            <div class="kop-logo">BQ</div>
            <div class="kop-text">
                <h1>Laporan Hasil Pemeriksaan Karantina</h1>
                <p>Badan Karantina Indonesia (Barantin) &mdash; Sistem Informasi Q-Officer</p>
            </div>
        </div>

        {{-- Info filter --}}
        <div class="filter-info">
            <div class="fi-item">
                <strong>UPT:</strong> {{ $filterUpt }}
            </div>
            @if($filterDari && $filterSampai)
            <div class="fi-item">
                <strong>Periode:</strong> {{ $filterDari }} s.d. {{ $filterSampai }}
            </div>
            @else
            <div class="fi-item">
                <strong>Periode:</strong> Semua periode
            </div>
            @endif
            <div class="fi-item">
                <strong>Jumlah Data:</strong> {{ $data->count() }} baris
            </div>
            <div class="fi-item">
                <strong>Dicetak:</strong> {{ now()->format('d M Y, H:i') }} WIB
            </div>
        </div>

        {{-- Tabel --}}
        <table>
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:10%">No. ST</th>
                    <th style="width:12%">UPT</th>
                    <th style="width:11%">Petugas</th>
                    <th style="width:9%">Tgl Periksa</th>
                    <th style="width:8%">Jenis</th>
                    <th style="width:14%">Komoditas</th>
                    <th style="width:14%">Temuan</th>
                    <th style="width:9%">Status Review</th>
                    <th style="width:9%">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @php $jenisMap = ['H' => 'Hewan', 'T' => 'Tumbuhan', 'I' => 'Ikan']; @endphp
                @forelse($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight:600;color:#133139">{{ $row->suratTugas?->no_st ?? '-' }}</td>
                    <td>{{ $row->suratTugas?->upt?->short_name ?? ($row->suratTugas?->upt_id ?? '-') }}</td>
                    <td>
                        {{ $row->petugas?->nama ?? '-' }}
                        @if($row->petugas?->nip)
                            <br><span style="color:#9ca3af;font-size:9px">{{ $row->petugas->nip }}</span>
                        @endif
                    </td>
                    <td>{{ $row->tgl_periksa?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $jenisMap[$row->suratTugas?->jenis_karantina] ?? '-' }}</td>
                    <td>{{ $row->komoditas ?? '-' }}</td>
                    <td>{{ $row->temuan ?? '-' }}</td>
                    <td>
                        @if($row->status_review === 'sudah_direview')
                            <span style="color:#15803d;font-weight:700">Sudah</span>
                        @else
                            <span style="color:#c2410c">Belum</span>
                        @endif
                    </td>
                    <td>
                        @if($row->rekomendasi?->tindakan)
                            @php $t = strtolower($row->rekomendasi->tindakan); @endphp
                            <span class="badge-tindakan tindakan-{{ $t }}">
                                {{ ucfirst($t) }}
                            </span>
                        @else
                            <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:20px;color:#9ca3af">
                        Tidak ada data ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer-note">
            <span>Dokumen ini dicetak dari Sistem Q-Officer Barantin &mdash; Bersifat resmi dan rahasia.</span>
            <span>Halaman 1 / 1 &mdash; Total {{ $data->count() }} data pemeriksaan</span>
        </div>
    </div>

</body>
</html>

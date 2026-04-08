<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HasilPemeriksaan;
use App\Models\SuratTugas;
use App\Models\Upt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PimpinanController extends Controller
{
    /**
     * FR-W15, FR-W16: Dashboard rekap UPT (disaring sesuai upt_id pimpinan).
     */
    public function dashboard(Request $request): View
    {
        $periode    = $request->query('periode', 'hari_ini');
        $dateRange  = $this->getPeriodeRange($periode, $request);
        $uptScope   = $this->uptScope();
        $isNasional = empty($uptScope);

        // ── KPI dari hasil pemeriksaan & tindakan karantina ──────────
        $baseHasil = HasilPemeriksaan::whereBetween('tgl_periksa', $dateRange)
            ->when($uptScope, fn($q) => $q->whereHas('suratTugas', fn($s) => $s->whereIn('upt_id', $uptScope)));

        $totalPemeriksaan = (clone $baseHasil)->count();

        $totalPelepasan = (clone $baseHasil)
            ->whereHas('rekomendasi', fn($q) => $q->where('tindakan', 'pelepasan'))
            ->count();

        $totalPerluTindakan = (clone $baseHasil)
            ->whereHas('rekomendasi', fn($q) => $q->whereIn('tindakan', ['penolakan', 'perlakuan', 'pemusnahan']))
            ->count();

        // ── Rekap per UPT induk: start dari UPT supaya yang kosong tetap muncul ─
        $indukList = Upt::where('nama_satpel', 'UPT Induk')
            ->when($uptScope, fn($q) => $q->whereIn('kode', $uptScope))
            ->orderBy('nama')
            ->get(['kode', 'nama', 'alias']);

        $totalUpt = $indukList->count();

        // Hitung pemeriksaan per UPT dengan subquery (termasuk satpel se-UPT)
        $periksaStats = \DB::table('hasil_pemeriksaan as hp')
            ->join('surat_tugas as st', 'hp.id_surat_tugas', '=', 'st.id')
            ->join('upt as u_satpel', 'st.upt_id', '=', 'u_satpel.kode')
            ->join('upt as u_induk', 'u_satpel.nama', '=', 'u_induk.nama')
            ->leftJoin('rekomendasi_karantina as rk', 'hp.id', '=', 'rk.id_hasil_pemeriksaan')
            ->whereBetween('hp.tgl_periksa', $dateRange)
            ->where('u_induk.nama_satpel', 'UPT Induk')
            ->when($uptScope, fn($q) => $q->whereIn('u_induk.kode', $uptScope))
            ->groupBy('u_induk.kode')
            ->selectRaw('
                u_induk.kode,
                COUNT(hp.id)                                               as total_periksa_count,
                SUM(rk.tindakan = "pelepasan")                             as pelepasan_count,
                SUM(rk.tindakan IN ("penolakan","perlakuan","pemusnahan")) as perlu_tindakan_count
            ')
            ->get()
            ->keyBy('kode');

        $rekapUpt = $indukList->map(function ($upt) use ($periksaStats) {
            $stats = $periksaStats->get($upt->kode);
            $upt->display_name          = $upt->alias ?: $upt->nama;
            $upt->nama_lengkap          = $upt->nama;
            $upt->total_periksa_count   = (int) ($stats?->total_periksa_count   ?? 0);
            $upt->pelepasan_count       = (int) ($stats?->pelepasan_count       ?? 0);
            $upt->perlu_tindakan_count  = (int) ($stats?->perlu_tindakan_count  ?? 0);
            return $upt;
        })->sortByDesc('total_periksa_count')->values();

        // Chart: semua UPT (termasuk yang kosong dengan 0 data)
        $chartLabels        = $rekapUpt->map(fn($u) => \Str::limit($u->display_name, 20))->values();
        $chartPelepasan     = $rekapUpt->pluck('pelepasan_count')->values();
        $chartPerluTindakan = $rekapUpt->pluck('perlu_tindakan_count')->values();

        // ── Tindakan per komoditas (pimpinan nasional) ────────────────
        $tindakanPerKomoditas = collect();
        if ($isNasional) {
            $komoditasMap = ['H' => 'Hewan', 'I' => 'Ikan', 'T' => 'Tumbuhan'];
            $tindakanPerKomoditas = HasilPemeriksaan::selectRaw(
                    "hasil_pemeriksaan.komoditas, rekomendasi_karantina.tindakan, COUNT(*) as jumlah"
                )
                ->join('rekomendasi_karantina', 'hasil_pemeriksaan.id', '=', 'rekomendasi_karantina.id_hasil_pemeriksaan')
                ->whereBetween('hasil_pemeriksaan.tgl_periksa', $dateRange)
                ->groupBy('komoditas', 'tindakan')
                ->get()
                ->map(fn($row) => [
                    'komoditas' => $komoditasMap[$row->komoditas] ?? $row->komoditas,
                    'tindakan'  => $row->tindakan,
                    'jumlah'    => $row->jumlah,
                ]);
        }

        // ── Tindakan per UPT (semua: pelepasan, penolakan, perlakuan, pemusnahan) ────────
        $tindakanPerUpt = collect();
        if ($isNasional) {
            $tindakanPerUpt = \DB::table('hasil_pemeriksaan as hp')
                ->join('surat_tugas as st', 'hp.id_surat_tugas', '=', 'st.id')
                ->join('upt as u_satpel', 'st.upt_id', '=', 'u_satpel.kode')
                ->join('upt as u_induk', 'u_satpel.nama', '=', 'u_induk.nama')
                ->join('rekomendasi_karantina as rk', 'hp.id', '=', 'rk.id_hasil_pemeriksaan')
                ->whereBetween('hp.tgl_periksa', $dateRange)
                ->where('u_induk.nama_satpel', 'UPT Induk')
                ->selectRaw(
                    "u_induk.kode,
                    COALESCE(u_induk.alias, u_induk.nama) as display_name,
                    SUM(rk.tindakan = 'pelepasan') as pelepasan_count,
                    SUM(rk.tindakan = 'penolakan') as penolakan_count,
                    SUM(rk.tindakan = 'perlakuan') as perlakuan_count,
                    SUM(rk.tindakan = 'pemusnahan') as pemusnahan_count"
                )
                ->groupBy('u_induk.kode', 'u_induk.nama', 'u_induk.alias')
                ->orderBy('u_induk.nama')
                ->get()
                ->map(fn($row) => [
                    'nama' => $row->display_name,
                    'pelepasan' => (int)$row->pelepasan_count,
                    'penolakan' => (int)$row->penolakan_count,
                    'perlakuan' => (int)$row->perlakuan_count,
                    'pemusnahan' => (int)$row->pemusnahan_count,
                ]);
        }

        // ── Jenis Karantina (H/I/T) dari surat tugas periode ini ────────
        $jenisKarantina = collect();
        if ($isNasional) {
            $jenisMap = ['H' => 'Hewan', 'I' => 'Ikan', 'T' => 'Tumbuhan'];
            $jenisKarantina = \DB::table('hasil_pemeriksaan as hp')
                ->join('surat_tugas as st', 'hp.id_surat_tugas', '=', 'st.id')
                ->whereBetween('hp.tgl_periksa', $dateRange)
                ->selectRaw("st.jenis_karantina, COUNT(hp.id) as jumlah")
                ->groupBy('st.jenis_karantina')
                ->get()
                ->map(fn($row) => [
                    'jenis' => $jenisMap[$row->jenis_karantina] ?? $row->jenis_karantina,
                    'jumlah' => $row->jumlah,
                ]);
        }

        return view('pimpinan.dashboard', compact(
            'totalPemeriksaan', 'totalPelepasan', 'totalPerluTindakan', 'totalUpt',
            'rekapUpt', 'periode',
            'chartLabels', 'chartPelepasan', 'chartPerluTindakan',
            'isNasional', 'tindakanPerKomoditas', 'tindakanPerUpt', 'jenisKarantina'
        ));
    }

    /**
     * FR-W17: Monitoring hasil pemeriksaan dengan filter.
     */
    public function monitoring(Request $request): View
    {
        $uptScope = $this->uptScope();
        $uptList  = $this->uptList();

        $query = HasilPemeriksaan::with([
            'petugas:id,nama,nip,upt_id',
            'suratTugas:id,no_st,upt_id,jenis_karantina',
            'rekomendasi:id,id_hasil_pemeriksaan,tindakan',
        ]);

        // Selalu scope ke UPT pimpinan (jika bukan nasional)
        if ($uptScope) {
            $query->whereHas('suratTugas', fn($q) => $q->whereIn('upt_id', $uptScope));
        }

        // Filter tambahan (hanya tampil jika pimpinan multi-UPT / nasional)
        if (!$uptScope && $request->filled('upt')) {
            $query->whereHas('suratTugas', fn($q) => $q->where('upt_id', $request->upt));
        }

        if ($request->filled('jenis')) {
            $query->whereHas('suratTugas', fn($q) => $q->where('jenis_karantina', $request->jenis));
        }
        if ($request->filled('status')) {
            $query->where('status_review', $request->status);
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tgl_periksa', [$request->dari, $request->sampai . ' 23:59:59']);
        }

        $hasilList   = $query->orderByDesc('tgl_periksa')->paginate(25)->withQueryString();
        $isMultiUpt  = empty($uptScope); // true = pimpinan nasional, false = satu UPT

        return view('pimpinan.monitoring', compact('hasilList', 'uptList', 'isMultiUpt'));
    }

    /**
     * FR-W18: Detail hasil pemeriksaan.
     */
    public function monitoringDetail(string $id): View
    {
        $hasil = HasilPemeriksaan::with([
            'petugas:id,nama,nip,upt_id',
            'suratTugas.lokasi',
            'suratTugas.komoditas',
            'dokumentasi',
            'rekomendasi.koordinator:id,nama,nip',
        ])->findOrFail($id);

        return view('pimpinan.monitoring-detail', compact('hasil'));
    }

    /**
     * FR-W19: Halaman pratinjau ekspor (bukan langsung download).
     */
    public function ekspor(Request $request): View
    {
        $uptScope  = $this->uptScope();
        $uptList   = $this->uptList();
        $isMultiUpt = empty($uptScope);

        // Hitung jumlah data yang akan dieksport sesuai filter
        $query = HasilPemeriksaan::query();
        if ($uptScope) {
            $query->whereHas('suratTugas', fn($q) => $q->whereIn('upt_id', $uptScope));
        }
        if (!$uptScope && $request->filled('upt')) {
            $query->whereHas('suratTugas', fn($q) => $q->where('upt_id', $request->upt));
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tgl_periksa', [$request->dari, $request->sampai . ' 23:59:59']);
        }

        $jumlahData = $query->count();

        // Flash message jika tidak ada data
        if ($request->filled('dari') || $request->filled('sampai') || $request->filled('upt')) {
            if ($jumlahData == 0) {
                session()->flash('warning', 'Tidak ada data pemeriksaan yang sesuai dengan filter yang Anda pilih. Coba ubah periode atau UPT.');
            }
        }

        return view('pimpinan.ekspor', compact('uptList', 'isMultiUpt', 'jumlahData'));
    }

    /**
     * FR-W19: Unduh CSV hasil ekspor.
     */
    public function eksporUnduh(Request $request): Response
    {
        $uptScope = $this->uptScope();

        $query = HasilPemeriksaan::with([
            'petugas:id,nama,nip',
            'suratTugas:id,no_st,upt_id,jenis_karantina',
            'suratTugas.upt:kode,nama,alias,nama_satpel',
            'rekomendasi:id,id_hasil_pemeriksaan,tindakan'
        ])->orderByDesc('tgl_periksa');

        if ($uptScope) {
            $query->whereHas('suratTugas', fn($q) => $q->whereIn('upt_id', $uptScope));
        } elseif ($request->filled('upt')) {
            $query->whereHas('suratTugas', fn($q) => $q->where('upt_id', $request->upt));
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tgl_periksa', [$request->dari, $request->sampai . ' 23:59:59']);
        }

        $data = $query->get();

        $csv  = "\xEF\xBB\xBF"; // BOM UTF-8 agar Excel baca benar
        $csv .= "ID Pemeriksaan,No ST,UPT,Petugas,NIP,Tgl Periksa,Jenis Karantina,Target,Metode,Temuan,Komoditas,Status Review,Tindakan\n";
        foreach ($data as $row) {
            $jenisMap = ['H' => 'Hewan', 'T' => 'Tumbuhan', 'I' => 'Ikan'];
            $csv .= implode(',', array_map(
                fn($v) => '"' . str_replace('"', '""', (string) $v) . '"',
                [
                    $row->id,
                    $row->suratTugas?->no_st ?? '-',
                    $row->suratTugas?->upt?->short_name ?? ($row->suratTugas?->upt_id ?? '-'),
                    $row->petugas?->nama ?? '-',
                    $row->petugas?->nip ?? '-',
                    $row->tgl_periksa?->format('d/m/Y H:i') ?? '-',
                    $jenisMap[$row->suratTugas?->jenis_karantina] ?? '-',
                    $row->target ?? '-',
                    $row->metode ?? '-',
                    $row->temuan ?? '-',
                    $row->komoditas ?? '-',
                    $row->status_review ?? '-',
                    $row->rekomendasi?->tindakan ?? '-',
                ]
            )) . "\n";
        }

        $filename = 'laporan-pemeriksaan-' . now()->format('Ymd-His') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * FR-W20: Daftar surat tugas (disaring sesuai UPT pimpinan).
     */
    public function suratTugas(Request $request): View
    {
        $uptScope  = $this->uptScope();
        $uptList   = $this->uptList();
        $isMultiUpt = empty($uptScope);

        $query = SuratTugas::with(['koordinator:id,nama,nip', 'upt', 'petugas:id,nama,nip'])
            ->orderByDesc('tanggal');

        if ($uptScope) {
            $query->whereIn('upt_id', $uptScope);
        } elseif ($request->filled('upt')) {
            $query->where('upt_id', $request->upt);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $stList = $query->paginate(25)->withQueryString();

        return view('pimpinan.surat-tugas', compact('stList', 'uptList', 'isMultiUpt'));
    }

    /**
     * Cetak PDF — tampilkan view print-friendly untuk disimpan sebagai PDF.
     */
    public function eksporCetakPdf(Request $request): \Illuminate\View\View
    {
        $uptScope = $this->uptScope();
        $uptList  = $this->uptList();

        $query = HasilPemeriksaan::with([
            'petugas:id,nama,nip,upt_id',
            'suratTugas:id,no_st,upt_id,jenis_karantina',
            'rekomendasi:id,id_hasil_pemeriksaan,tindakan',
        ])->orderByDesc('tgl_periksa');

        if ($uptScope) {
            $query->whereHas('suratTugas', fn($q) => $q->whereIn('upt_id', $uptScope));
        } elseif ($request->filled('upt')) {
            $query->whereHas('suratTugas', fn($q) => $q->where('upt_id', $request->upt));
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tgl_periksa', [$request->dari, $request->sampai . ' 23:59:59']);
        }

        $data = $query->get();
        $filterUpt = $request->filled('upt')
            ? ($uptList->firstWhere('kode', $request->upt)?->nama ?? $request->upt)
            : 'Semua UPT';
        $filterDari   = $request->dari   ? \Carbon\Carbon::parse($request->dari)->format('d M Y')   : null;
        $filterSampai = $request->sampai ? \Carbon\Carbon::parse($request->sampai)->format('d M Y') : null;

        return view('pimpinan.ekspor-print', compact('data', 'filterUpt', 'filterDari', 'filterSampai'));
    }

    // ── Helpers ────────────────────────────────────────────────────

    /**
     * Kembalikan array kode UPT yang boleh dilihat pimpinan ini.
     * Null = nasional (lihat semua).
     * Kantor Pusat (1000) = juga lihat semua.
     */
    private function uptScope(): ?array
    {
        $upt = Auth::user()->upt_id;
        // Kantor pusat (1000) atau null = nasional, lihat semua
        return ($upt && $upt !== '1000') ? [$upt] : null;
    }

    /**
     * Daftar UPT untuk dropdown filter.
     * Jika pimpinan punya upt_id → hanya UPT-nya.
     * Jika tidak → semua UPT induk.
     */
    private function uptList()
    {
        $uptScope = $this->uptScope();
        $query    = Upt::where(fn($q) => $q->where('nama_satpel', 'UPT Induk')->orWhere('kode', '1000'))
                        ->orderBy('nama');
        if ($uptScope) {
            $query->whereIn('kode', $uptScope);
        }
        return $query->get();
    }

    private function getPeriodeRange(string $periode, Request $request): array
    {
        return match ($periode) {
            '7_hari'  => [now()->subDays(7)->startOfDay(), now()->endOfDay()],
            '1_bulan' => [now()->subMonth()->startOfDay(), now()->endOfDay()],
            'custom'  => [$request->dari . ' 00:00:00', $request->sampai . ' 23:59:59'],
            default   => [today()->startOfDay(), today()->endOfDay()],
        };
    }
}

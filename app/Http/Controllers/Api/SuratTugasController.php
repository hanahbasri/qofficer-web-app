<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuratTugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuratTugasController extends Controller
{
    /**
     * FR-P07: Daftar ST masuk (tertunda/aktif) untuk petugas yang login
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $stList = $user->suratTugasDitugaskan()
            ->with(['lokasi', 'komoditas', 'koordinator:id,nama,nip'])
            ->whereIn('surat_tugas.status', ['tertunda', 'aktif'])
            ->orderByDesc('surat_tugas.tanggal')
            ->get()
            ->map(fn($st) => $this->formatSt($st));

        return response()->json(['data' => $stList]);
    }

    /**
     * FR-P10: ST aktif yang sedang dikerjakan petugas
     */
    public function aktif(Request $request): JsonResponse
    {
        $user = $request->user();

        $st = $user->suratTugasDitugaskan()
            ->with(['lokasi', 'komoditas', 'koordinator:id,nama,nip', 'hasilPemeriksaan.dokumentasi'])
            ->where('surat_tugas.status', 'aktif')
            ->first();

        if (!$st) {
            return response()->json(['data' => null]);
        }

        return response()->json(['data' => $this->formatSt($st)]);
    }

    /**
     * FR-P11: Riwayat ST selesai dengan filter rentang waktu
     */
    public function selesai(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = $user->suratTugasDitugaskan()
            ->with(['lokasi', 'komoditas'])
            ->where('surat_tugas.status', 'selesai');

        // Filter rentang waktu
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('surat_tugas.tanggal', [$request->dari, $request->sampai]);
        }

        $stList = $query->orderByDesc('surat_tugas.tanggal')
            ->paginate(20)
            ->through(fn($st) => $this->formatSt($st));

        return response()->json($stList);
    }

    /**
     * FR-P08: Detail satu surat tugas
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        $st = $user->suratTugasDitugaskan()
            ->with([
                'lokasi',
                'komoditas',
                'koordinator:id,nama,nip',
                'petugas:id,nama,nip,golongan,pangkat',
                'hasilPemeriksaan.dokumentasi',
            ])
            ->where('surat_tugas.id', $id)
            ->firstOrFail();

        return response()->json(['data' => $this->formatSt($st)]);
    }

    /**
     * FR-P09: Terima tugas (ubah status tertunda → aktif)
     */
    public function terima(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        // Cek apakah sudah ada tugas aktif
        $adaTugasAktif = $user->suratTugasDitugaskan()
            ->where('surat_tugas.status', 'aktif')
            ->exists();

        if ($adaTugasAktif) {
            return response()->json([
                'message' => 'Tidak dapat menerima tugas baru saat masih ada tugas aktif.',
            ], 409);
        }

        // Ambil pivot entry
        $pivot = \DB::table('surat_tugas_petugas')
            ->where('surat_tugas_id', $id)
            ->where('petugas_id', $user->id)
            ->first();

        if (!$pivot) {
            return response()->json(['message' => 'Surat tugas tidak ditemukan.'], 404);
        }

        if ($pivot->status_penerimaan === 'diterima') {
            return response()->json(['message' => 'Tugas sudah diterima sebelumnya.'], 409);
        }

        // Update pivot
        \DB::table('surat_tugas_petugas')
            ->where('surat_tugas_id', $id)
            ->where('petugas_id', $user->id)
            ->update([
                'status_penerimaan' => 'diterima',
                'diterima_at'       => now(),
                'updated_at'        => now(),
            ]);

        // Update status ST → aktif
        SuratTugas::where('id', $id)->update(['status' => 'aktif']);

        return response()->json(['message' => 'Tugas berhasil diterima.']);
    }

    // ── Koordinator: buat ST baru ─────────────────────────────────
    /**
     * FR-K04–K08: Buat Surat Tugas baru oleh Koordinator
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'no_st'                => 'nullable|string|max:100',
            'ptk_id'               => 'nullable|string|max:100',
            'tanggal'              => 'required|date',
            'perihal'              => 'required|string',
            'dasar_hukum'          => 'nullable|string',
            'nama_penandatangan'   => 'nullable|string',
            'nip_penandatangan'    => 'nullable|string|max:30',
            'jenis_karantina'      => 'required|in:H,T,I',
            'lokasi'                   => 'required|array|min:1',
            'lokasi.*.nama_lokasi'     => 'required|string',
            'lokasi.*.satpel_id'       => 'nullable|string',
            'lokasi.*.lat'             => 'nullable|string',
            'lokasi.*.long'            => 'nullable|string',
            'lokasi.*.detail_lokasi'   => 'nullable|string',
            'komoditas'                        => 'nullable|array',
            'komoditas.*.komoditas_id'         => 'nullable|string',
            'komoditas.*.nama_komoditas'       => 'nullable|string',
            'komoditas.*.nama_latin'           => 'nullable|string',
            'komoditas.*.volume'               => 'nullable|numeric',
            'komoditas.*.satuan'               => 'nullable|string',
            'komoditas.*.jenis_karantina'      => 'required|in:H,T,I',
            'petugas_ids'          => 'required|array|min:1',
            'petugas_ids.*'        => 'required|exists:users,id',
        ]);

        $user = $request->user();
        $stId = (string) Str::uuid();

        $noSt = $request->no_st ?? $this->generateNoSt($user, $request->tanggal);

        $st = SuratTugas::create([
            'id'                => $stId,
            'ptk_id'            => $request->ptk_id,
            'no_st'             => $noSt,
            'tanggal'           => $request->tanggal,
            'perihal'           => $request->perihal,
            'dasar_hukum'       => $request->dasar_hukum,
            'nama_penandatangan'=> $request->nama_penandatangan,
            'nip_penandatangan' => $request->nip_penandatangan,
            'jenis_karantina'   => $request->jenis_karantina,
            'status'            => 'tertunda',
            'koordinator_id'    => $user->id,
            'upt_id'            => $user->upt_id,
        ]);

        // Simpan lokasi
        foreach ($request->lokasi as $lok) {
            $st->lokasi()->create($lok);
        }

        // Simpan komoditas
        foreach ($request->komoditas ?? [] as $kom) {
            $st->komoditas()->create($kom);
        }

        // Assign petugas
        $pivotData = collect($request->petugas_ids)->mapWithKeys(fn($id) => [
            $id => ['status_penerimaan' => 'tertunda', 'created_at' => now(), 'updated_at' => now()],
        ])->all();
        $st->petugas()->attach($pivotData);

        return response()->json([
            'message' => 'Surat tugas berhasil dibuat.',
            'data'    => ['id' => $stId, 'no_st' => $st->no_st],
        ], 201);
    }

    /**
     * FR-K15: Riwayat ST yang dibuat oleh Koordinator
     */
    public function riwayatKoordinator(Request $request): JsonResponse
    {
        $stList = SuratTugas::with(['lokasi', 'komoditas', 'petugas:id,nama,nip'])
            ->where('koordinator_id', $request->user()->id)
            ->orderByDesc('tanggal')
            ->paginate(20);

        return response()->json($stList);
    }

    // ── Helpers ───────────────────────────────────────────────────
    private function generateNoSt($user, string $tanggal): string
    {
        $upt    = strtoupper($user->upt_id ?? 'UPT');
        $year   = date('Y', strtotime($tanggal));
        $month  = date('m', strtotime($tanggal));
        $seq    = SuratTugas::where('upt_id', $user->upt_id)
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->count() + 1;
        return sprintf('ST/%s/%s/%s/%04d', $upt, $year, $month, $seq);
    }

    private function formatSt(SuratTugas $st): array
    {
        return [
            'id'                 => $st->id,
            'ptk_id'             => $st->ptk_id,
            'no_st'              => $st->no_st,
            'tanggal'            => $st->tanggal?->toDateString(),
            'perihal'            => $st->perihal,
            'dasar_hukum'        => $st->dasar_hukum,
            'nama_penandatangan' => $st->nama_penandatangan,
            'nip_penandatangan'  => $st->nip_penandatangan,
            'status'             => $st->status,
            'jenis_karantina'    => $st->jenis_karantina,
            'link'               => $st->link,
            'koordinator'        => $st->relationLoaded('koordinator') ? $st->koordinator : null,
            'lokasi'             => $st->relationLoaded('lokasi') ? $st->lokasi : [],
            'komoditas'          => $st->relationLoaded('komoditas') ? $st->komoditas : [],
            'petugas'            => $st->relationLoaded('petugas') ? $st->petugas : [],
            'status_penerimaan'  => $st->pivot?->status_penerimaan,
        ];
    }
}

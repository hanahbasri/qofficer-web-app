<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
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
            ->with(['lokasi', 'komoditas', 'koordinator:id,nama,nip', 'petugas:id,nama,nip,golongan,pangkat'])
            ->whereIn('surat_tugas.status', ['tertunda', 'aktif', 'dikirim'])
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
            ->with(['lokasi', 'komoditas', 'koordinator:id,nama,nip', 'petugas:id,nama,nip,golongan,pangkat', 'hasilPemeriksaan.dokumentasi'])
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
            ->with(['lokasi', 'komoditas', 'petugas:id,nama,nip,golongan,pangkat'])
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

        // Ambil pivot entry
        $pivot = DB::table('surat_tugas_petugas')
            ->where('surat_tugas_id', $id)
            ->where('petugas_id', $user->id)
            ->first();

        if (!$pivot) {
            return response()->json(['message' => 'Surat tugas tidak ditemukan.'], 404);
        }

        if ($pivot->status_penerimaan === 'diterima') {
            return response()->json(['message' => 'Tugas sudah diterima sebelumnya.'], 409);
        }

        // Update penerimaan petugas + status ST dalam satu transaksi atomik.
        // Bila salah satu gagal, keduanya di-rollback agar tidak terjadi
        // kondisi tidak sinkron (pivot 'diterima' tetapi ST belum 'aktif').
        DB::transaction(function () use ($id, $user) {
            // Update pivot
            DB::table('surat_tugas_petugas')
                ->where('surat_tugas_id', $id)
                ->where('petugas_id', $user->id)
                ->update([
                    'status_penerimaan' => 'diterima',
                    'diterima_at'       => now(),
                    'updated_at'        => now(),
                ]);

            // Update status ST → aktif
            SuratTugas::where('id', $id)->update(['status' => 'aktif']);
        });

        // Notifikasi koordinator: petugas terima tugas
        $st = SuratTugas::find($id);
        if ($st?->koordinator_id) {
            $this->notifKoordinator(
                $st->koordinator_id,
                'Tugas Diterima',
                "{$user->nama} telah menerima ST {$st->no_st}.",
                'st_diterima',
                $id
            );
        }

        return response()->json(['message' => 'Tugas berhasil diterima.']);
    }

    /**
     * Petugas berangkat ke lokasi penugasan
     * POST /api/surat-tugas/{id}/berangkat
     */
    public function berangkat(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        $pivot = DB::table('surat_tugas_petugas')
            ->where('surat_tugas_id', $id)
            ->where('petugas_id', $user->id)
            ->first();

        if (!$pivot) {
            return response()->json(['message' => 'Surat tugas tidak ditemukan.'], 404);
        }

        if ($pivot->status_penerimaan !== 'diterima') {
            return response()->json(['message' => 'Tugas harus diterima terlebih dahulu.'], 409);
        }

        DB::table('surat_tugas_petugas')
            ->where('surat_tugas_id', $id)
            ->where('petugas_id', $user->id)
            ->update([
                'status_penerimaan' => 'berangkat',
                'berangkat_at'      => now(),
                'updated_at'        => now(),
            ]);

        // Notifikasi koordinator: petugas berangkat
        $st = SuratTugas::find($id);
        if ($st?->koordinator_id) {
            $this->notifKoordinator(
                $st->koordinator_id,
                'Petugas Berangkat',
                "{$user->nama} telah berangkat ke lokasi untuk ST {$st->no_st}.",
                'st_berangkat',
                $id
            );
        }

        return response()->json(['message' => 'Status berangkat berhasil dicatat.']);
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

        $noSt = $this->resolveNoSt(
            $user,
            $request->tanggal,
            $request->jenis_karantina,
            $request->no_st,
        );

        // Pembuatan ST beserta lokasi, komoditas, dan penugasan petugas
        // dijalankan dalam satu transaksi atomik. Bila ada satu langkah gagal,
        // seluruh perubahan di-rollback agar tidak tersisa ST "setengah jadi"
        // (mis. ST tanpa petugas).
        $st = DB::transaction(function () use ($request, $stId, $noSt, $user) {
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

            return $st;
        });

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

    /**
     * Generate nomor surat tugas sesuai format resmi ESPS Karantina Indonesia.
     * Format: {TAHUN}-{JENIS}1.0-{KODE_UPT}-K.2.2-{NNNNNN}/2
     * Contoh: 2026-H1.0-3200.0-K.2.2-000008/2
     *         2026-T1.0-1501.2-K.2.2-000140/2
     */
    /**
     * Simpan notifikasi in-app + kirim FCM push ke koordinator.
     */
    private function notifKoordinator(int $koordinatorId, string $judul, string $pesan, string $type, string $referensiId): void
    {
        Notifikasi::create([
            'user_id'      => $koordinatorId,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'type'         => $type,
            'referensi_id' => $referensiId,
        ]);

        $this->kirimFcmKeUser($koordinatorId, $judul, $pesan, [
            'type'  => $type,
            'st_id' => $referensiId,
        ]);
    }

    /**
     * Kirim FCM push notification ke satu user berdasarkan ID.
     */
    private function kirimFcmKeUser(int $userId, string $title, string $body, array $data = []): void
    {
        $fcmToken = \App\Models\User::find($userId)?->fcm_token;
        if (!$fcmToken) return;

        $serverKey = config('services.firebase.server_key');
        if (!$serverKey) {
            \Illuminate\Support\Facades\Log::info('FCM koordinator (simulasi)', compact('title', 'body', 'data'));
            return;
        }

        try {
            \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => "key={$serverKey}",
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to'           => $fcmToken,
                'notification' => ['title' => $title, 'body' => $body],
                'data'         => $data,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('FCM koordinator error: ' . $e->getMessage());
        }
    }

    private function resolveNoSt($user, string $tanggal, string $jenisKarantina = 'H', ?string $requestedNoSt = null): string
    {
        $requestedNoSt = trim((string) $requestedNoSt);

        if ($requestedNoSt !== '' && $this->isValidNoStFormat($requestedNoSt, $jenisKarantina, $tanggal, $user->upt_id)) {
            return $requestedNoSt;
        }

        return $this->generateNoSt($user, $tanggal, $jenisKarantina);
    }

    private function generateNoSt($user, string $tanggal, string $jenisKarantina = 'H'): string
    {
        $year   = date('Y', strtotime($tanggal));

        // Kode UPT — gunakan as-is jika sudah ada titik (e.g. "3200.0"),
        // tambahkan ".0" jika belum (e.g. "3200" → "3200.0")
        $uptRaw = $user->upt_id ?? '0000';
        $uptKode = str_contains((string) $uptRaw, '.') ? $uptRaw : $uptRaw . '.0';

        // Jenis karantina: H → H1.0, T → T1.0, I → I1.0
        $jenisCode = strtoupper($jenisKarantina) . '1.0';

        // Nomor urut: per UPT, per tahun, per jenis karantina (6 digit)
        $seq = SuratTugas::where('upt_id', $user->upt_id)
                ->where('jenis_karantina', strtoupper($jenisKarantina))
                    ->whereYear('tanggal', $year)
                    ->count() + 1;

        return sprintf('%s-%s-%s-K.2.2-%06d/2', $year, $jenisCode, $uptKode, $seq);
    }

    private function isValidNoStFormat(string $noSt, string $jenisKarantina, string $tanggal, $uptId): bool
    {
        $year = date('Y', strtotime($tanggal));
        $uptRaw = (string) ($uptId ?? '0000');
        $uptKode = str_contains($uptRaw, '.') ? $uptRaw : $uptRaw . '.0';
        $jenisCode = strtoupper($jenisKarantina) . '1.0';
        $pattern = sprintf(
            '/^%s\-%s\-%s\-K\.2\.2\-\d{6}\/2$/',
            preg_quote($year, '/'),
            preg_quote($jenisCode, '/'),
            preg_quote($uptKode, '/'),
        );

        return (bool) preg_match($pattern, $noSt);
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DokumentasiPeriksa;
use App\Models\HasilPemeriksaan;
use App\Models\SuratTugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HasilPemeriksaanController extends Controller
{
    /**
     * FR-P19: Submit hasil pemeriksaan K-3.7b
     * Terima data (online) dan simpan ke server.
     * Jika UUID sudah ada, update (idempotent untuk sync offline).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id'              => 'required|string|max:50',
            'id_surat_tugas'  => 'required|string|exists:surat_tugas,id',
            'lat'             => 'nullable|string',
            'long'            => 'nullable|string',
            'target'          => 'nullable|string',
            'metode'          => 'nullable|string',
            'temuan'          => 'nullable|string',
            'catatan'         => 'nullable|string',
            'komoditas'       => 'nullable|string',
            'tgl_periksa'     => 'required|date',
            'foto'            => 'nullable|array|max:4',
            'foto.*.id'       => 'required|string|max:50',
            'foto.*.foto_display' => 'nullable|string', // base64
            'foto.*.foto_server'  => 'nullable|string', // base64 compressed
        ]);

        $user = $request->user();

        DB::transaction(function () use ($request, $user) {
            $isNew = !HasilPemeriksaan::where('id', $request->id)->exists();

            // Upsert hasil pemeriksaan (FR-P23: jangan timpa data yang lebih baru)
            HasilPemeriksaan::updateOrCreate(
                ['id' => $request->id],
                [
                    'id_surat_tugas' => $request->id_surat_tugas,
                    'id_petugas'     => $user->id,
                    'lat'            => $request->lat,
                    'long'           => $request->long,
                    'target'         => $request->target,
                    'metode'         => $request->metode,
                    'temuan'         => $request->temuan,
                    'catatan'        => $request->catatan,
                    'komoditas'      => $request->komoditas,
                    'tgl_periksa'    => $request->tgl_periksa,
                ]
            );

            // Simpan dokumentasi foto
            if ($request->has('foto')) {
                foreach ($request->foto as $foto) {
                    DokumentasiPeriksa::updateOrCreate(
                        ['id' => $foto['id']],
                        [
                            'id_pemeriksaan' => $request->id,
                            'foto_display'   => $foto['foto_display'] ?? null,
                            'foto_server'    => $foto['foto_server'] ?? null,
                        ]
                    );
                }
            }

            // Update status ST → dikirim
            SuratTugas::where('id', $request->id_surat_tugas)
                ->where('status', 'aktif')
                ->update(['status' => 'dikirim']);

            // Notifikasi koordinator (in-app + FCM push)
            $st = SuratTugas::find($request->id_surat_tugas);
            if ($st?->koordinator_id) {
                if ($isNew) {
                    $judul = 'Pemeriksaan Dimulai';
                    $pesan = "{$user->nama} mulai mengisi laporan pemeriksaan untuk ST {$st->no_st}.";
                    $type  = 'pemeriksaan_mulai';
                } else {
                    $judul = 'Laporan Disubmit';
                    $pesan = "{$user->nama} telah mengirimkan laporan pemeriksaan untuk ST {$st->no_st}.";
                    $type  = 'pemeriksaan_submit';
                }
                \App\Models\Notifikasi::create([
                    'user_id'      => $st->koordinator_id,
                    'judul'        => $judul,
                    'pesan'        => $pesan,
                    'type'         => $type,
                    'referensi_id' => $request->id_surat_tugas,
                ]);
                $this->kirimFcmKeUser($st->koordinator_id, $judul, $pesan, [
                    'type'  => $type,
                    'st_id' => $request->id_surat_tugas,
                ]);
            }
        });

        return response()->json(['message' => 'Hasil pemeriksaan berhasil disimpan.'], 201);
    }

    /**
     * Sync batch: untuk FR-P22 sinkronisasi otomatis saat koneksi pulih
     * Petugas mengirim array hasil pemeriksaan yang belum tersync
     */
    public function syncBatch(Request $request): JsonResponse
    {
        $request->validate([
            'data'                  => 'required|array',
            'data.*.id'             => 'required|string|max:50',
            'data.*.id_surat_tugas' => 'required|string|exists:surat_tugas,id',
            'data.*.tgl_periksa'    => 'required|date',
        ]);

        $synced = [];
        $errors = [];

        foreach ($request->data as $item) {
            try {
                HasilPemeriksaan::updateOrCreate(
                    ['id' => $item['id']],
                    array_merge($item, ['id_petugas' => $request->user()->id])
                );

                if (isset($item['foto'])) {
                    foreach ($item['foto'] as $foto) {
                        DokumentasiPeriksa::updateOrCreate(
                            ['id' => $foto['id']],
                            ['id_pemeriksaan' => $item['id'], 'foto_display' => $foto['foto_display'] ?? null, 'foto_server' => $foto['foto_server'] ?? null]
                        );
                    }
                }

                SuratTugas::where('id', $item['id_surat_tugas'])
                    ->where('status', 'aktif')
                    ->update(['status' => 'dikirim']);

                $synced[] = $item['id'];
            } catch (\Throwable $e) {
                $errors[] = ['id' => $item['id'], 'error' => $e->getMessage()];
            }
        }

        return response()->json([
            'synced' => $synced,
            'errors' => $errors,
        ]);
    }

    /**
     * Unified detail — routing berdasarkan role user
     */
    public function showByRole(Request $request, string $id): JsonResponse
    {
        if ($request->user()->getRoleName() === 'koordinator-upt') {
            return $this->showKoordinator($request, $id);
        }
        return $this->show($request, $id);
    }

    /**
     * Detail hasil pemeriksaan milik petugas
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $hasil = HasilPemeriksaan::with('dokumentasi', 'suratTugas')
            ->where('id', $id)
            ->where('id_petugas', $request->user()->id)
            ->firstOrFail();

        return response()->json(['data' => $hasil]);
    }

    /**
     * Selesaikan ST setelah pemeriksaan dikirim
     */
    public function selesaikan(Request $request, string $idSt): JsonResponse
    {
        // Pastikan petugas yang request memang terdaftar di ST ini
        $st = SuratTugas::whereHas('petugas', fn($q) => $q->where('petugas_id', $request->user()->id))
            ->findOrFail($idSt);

        $st->update(['status' => 'selesai']);

        // Notifikasi ke koordinator (in-app + FCM push)
        if ($st->koordinator_id) {
            $judul = 'Laporan Masuk';
            $pesan = "Petugas {$request->user()->nama} telah menyelesaikan ST {$st->no_st}.";
            \App\Models\Notifikasi::create([
                'user_id'      => $st->koordinator_id,
                'judul'        => $judul,
                'pesan'        => $pesan,
                'type'         => 'st_selesai',
                'referensi_id' => $idSt,
            ]);
            $this->kirimFcmKeUser($st->koordinator_id, $judul, $pesan, [
                'type'  => 'st_selesai',
                'st_id' => $idSt,
            ]);
        }

        return response()->json(['message' => 'Surat tugas diselesaikan.']);
    }

    /**
     * FR-W06: Daftar hasil pemeriksaan untuk Koordinator dengan dokumentasi
     * GET /api/hasil-pemeriksaan (dengan middleware role:koordinator-upt)
     */
    public function indexKoordinator(Request $request): JsonResponse
    {
        $uptId = $request->user()->upt_id;

        $query = HasilPemeriksaan::with(['petugas:id,nama,nip', 'suratTugas:id,no_st,jenis_karantina', 'dokumentasi'])
            ->whereHas('suratTugas', fn($q) => $q->where('upt_id', $uptId));

        if ($request->filled('status')) {
            $query->where('status_review', $request->status);
        }
        if ($request->filled('tgl_dari')) {
            $query->whereDate('tgl_periksa', '>=', $request->tgl_dari);
        }
        if ($request->filled('tgl_sampai')) {
            $query->whereDate('tgl_periksa', '<=', $request->tgl_sampai);
        }
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('komoditas', 'like', "%{$cari}%")
                  ->orWhere('temuan', 'like', "%{$cari}%")
                  ->orWhereHas('petugas', fn($p) => $p->where('nama', 'like', "%{$cari}%"));
            });
        }

        $hasil = $query->orderByDesc('tgl_periksa')->paginate(20);

        return response()->json($hasil);
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
            Log::info('FCM koordinator (simulasi)', compact('title', 'body', 'data'));
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => "key={$serverKey}",
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to'           => $fcmToken,
                'notification' => ['title' => $title, 'body' => $body],
                'data'         => $data,
            ]);
        } catch (\Throwable $e) {
            Log::error('FCM koordinator error: ' . $e->getMessage());
        }
    }

    /**
     * FR-W07, FR-W08: Detail hasil pemeriksaan dengan dokumentasi untuk Koordinator
     */
    public function showKoordinator(Request $request, string $id): JsonResponse
    {
        $uptId = $request->user()->upt_id;

        $hasil = HasilPemeriksaan::with(['petugas:id,nama,nip,golongan,pangkat', 'suratTugas', 'dokumentasi'])
            ->whereHas('suratTugas', fn($q) => $q->where('upt_id', $uptId))
            ->findOrFail($id);

        // Expose foto_display untuk tampilan di koordinator (hidden by default)
        $hasil->dokumentasi->each->makeVisible(['foto_display']);

        return response()->json(['data' => $hasil]);
    }
}

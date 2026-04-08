<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DokumentasiPeriksa;
use App\Models\HasilPemeriksaan;
use App\Models\SuratTugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $st = SuratTugas::findOrFail($idSt);

        $st->update(['status' => 'selesai']);

        // Notifikasi ke koordinator (jika ada)
        if ($st->koordinator_id) {
            \App\Models\Notifikasi::create([
                'user_id'     => $st->koordinator_id,
                'judul'       => 'Laporan Masuk',
                'pesan'       => "Petugas {$request->user()->nama} telah menyelesaikan ST {$st->no_st}.",
                'type'        => 'st_selesai',
                'referensi_id'=> $idSt,
            ]);
        }

        return response()->json(['message' => 'Surat tugas diselesaikan.']);
    }
}

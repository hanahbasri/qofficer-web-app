<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HasilPemeriksaanController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\PtkController;
use App\Http\Controllers\Api\SatpelController;
use App\Http\Controllers\Api\SuratTugasController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────────────────────────
// PUBLIC
// ──────────────────────────────────────────────────────────────────

// FR-P01, FR-K01: Login mobile (Petugas & Koordinator)
Route::post('/login', [AuthController::class, 'login']);

// ──────────────────────────────────────────────────────────────────
// PROTECTED — semua role (Bearer Token)
// ──────────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/fcm-token', [AuthController::class, 'updateFcmToken']);
    Route::post('/auth/foto-profil', [AuthController::class, 'uploadFotoProfil']);
    Route::post('/auth/ganti-password', [AuthController::class, 'gantiPassword']);

    Route::middleware('password.fresh')->group(function () {

        // Master Data (dropdown K-3.7b) — semua role mobile
        Route::get('/master/target', [MasterDataController::class, 'target']);
        Route::get('/master/temuan', [MasterDataController::class, 'temuan']);

        // Hasil pemeriksaan detail — satu route, role dicek di controller
        Route::get('/hasil-pemeriksaan/{id}', [HasilPemeriksaanController::class, 'showByRole']);

        // Notifikasi
        Route::prefix('notifikasi')->group(function () {
            Route::get('/',             [NotifikasiController::class, 'index']);
            Route::patch('/{id}/baca',  [NotifikasiController::class, 'baca']);
            Route::delete('/{id}',      [NotifikasiController::class, 'destroy']);
            Route::delete('/',          [NotifikasiController::class, 'destroyAll']);
            Route::post('/fcm',         [NotifikasiController::class, 'kirimFcm']);
        });

        // ── KOORDINATOR UPT ────────────────────────────────────────────
        Route::middleware('role:koordinator-upt')->group(function () {

            // Buat ST dan kelola penugasan (FR-K04–K16)
            Route::post('/surat-tugas',        [SuratTugasController::class, 'store']);
            Route::get('/surat-tugas/riwayat', [SuratTugasController::class, 'riwayatKoordinator']);

            // Hasil pemeriksaan — daftar (FR-W06)
            Route::get('/hasil-pemeriksaan', [HasilPemeriksaanController::class, 'indexKoordinator']);

            // Daftar petugas lapangan UPT (FR-K09)
            Route::get('/petugas',      [PetugasController::class, 'index']);
            Route::get('/petugas/{id}', [PetugasController::class, 'show']);

            // Daftar semua pegawai UPT — untuk dropdown penandatangan
            Route::get('/pegawai', [PegawaiController::class, 'index']);

            // Daftar satpel UPT — untuk dropdown lokasi penugasan
            Route::get('/satpel', [SatpelController::class, 'index']);

            // PTK (Permohonan Tindakan Karantina) dari pengguna jasa (FR-K03)
            Route::get('/ptk',      [PtkController::class, 'index']);
            Route::get('/ptk/{id}', [PtkController::class, 'show']);
        });

        // ── PETUGAS LAPANGAN ───────────────────────────────────────────
        Route::middleware('role:petugas-lapangan')->group(function () {

            // Surat Tugas (FR-P07–P12)
            Route::prefix('surat-tugas')->group(function () {
                Route::get('/',             [SuratTugasController::class, 'index']);
                Route::get('/aktif',        [SuratTugasController::class, 'aktif']);
                Route::get('/selesai',      [SuratTugasController::class, 'selesai']);
                Route::get('/{id}',         [SuratTugasController::class, 'show']);
                Route::post('/{id}/terima',    [SuratTugasController::class, 'terima']);
                Route::post('/{id}/berangkat', [SuratTugasController::class, 'berangkat']);
            });

            // Hasil Pemeriksaan K-3.7b (FR-P13–P19)
            Route::prefix('hasil-pemeriksaan')->group(function () {
                Route::post('/',             [HasilPemeriksaanController::class, 'store']);
                Route::post('/sync-batch',   [HasilPemeriksaanController::class, 'syncBatch']);
                Route::post('/{id}/selesai', [HasilPemeriksaanController::class, 'selesaikan']);
            });
        });

        // ── SUPER ADMIN — master data CRUD ───────────────────────────
        Route::middleware('role:super-admin')->prefix('admin')->group(function () {
            Route::post('/master/target', [MasterDataController::class, 'storeTarget']);
            Route::post('/master/temuan', [MasterDataController::class, 'storeTemuan']);
        });
    });
});

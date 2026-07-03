<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\KoordinatorController;
use App\Http\Controllers\Web\PasswordController;
use App\Http\Controllers\Web\PimpinanController;
use Illuminate\Support\Facades\Route;

// Redirect root ke /qofficer/login
Route::get('/', fn() => redirect()->route('login'));

// ──────────────────────────────────────────────────────────────────
// SEMUA WEB ROUTES DI BAWAH PREFIX /qofficer
// ──────────────────────────────────────────────────────────────────
Route::prefix('qofficer')->group(function () {

    // ── AUTH (FR-W01–W03) ──────────────────────────────────────────
    Route::middleware('guest')
        ->group(function () {
            Route::get('/login',   [AuthWebController::class, 'showLogin'])->name('login');
            Route::post('/login',  [AuthWebController::class, 'login'])->name('login.post');
        });

    Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

    // ── KOORDINATOR UPT (FR-W04–W14) ──────────────────────────────
    Route::middleware(['auth', 'role:koordinator-upt'])
        ->prefix('koordinator')
        ->name('koordinator.')
        ->group(function () {

        Route::middleware('password.fresh')->group(function () {
        Route::get('/dashboard', [KoordinatorController::class, 'dashboard'])->name('dashboard');

        // Hasil Pemeriksaan
        Route::get('/hasil-periksa',      [KoordinatorController::class, 'hasilPeriksa'])->name('hasil-periksa');
        Route::get('/hasil-periksa/{id}', [KoordinatorController::class, 'hasilPeriksaDetail'])->name('hasil-periksa.detail');

        // Rekomendasi (FR-W09–W11)
        Route::post('/rekomendasi', [KoordinatorController::class, 'simpanRekomendasi'])->name('rekomendasi.simpan');

        // Daftar & reset password Petugas
        Route::get('/petugas', [KoordinatorController::class, 'listPetugas'])->name('petugas');
        Route::post('/petugas/{id}/reset-password', [KoordinatorController::class, 'resetPetugasPassword'])
            ->name('petugas.reset-password');
        });

        Route::get('/keamanan', [PasswordController::class, 'edit'])->name('keamanan');
        Route::put('/keamanan', [PasswordController::class, 'update'])->name('keamanan.update');
    });

    // ── PIMPINAN (FR-W15–W19) ─────────────────────────────────────
    Route::middleware(['auth', 'role:pimpinan'])
        ->prefix('pimpinan')
        ->name('pimpinan.')
        ->group(function () {

        Route::middleware('password.fresh')->group(function () {
        Route::get('/dashboard',           [PimpinanController::class, 'dashboard'])->name('dashboard');
        Route::get('/monitoring',          [PimpinanController::class, 'monitoring'])->name('monitoring');
        Route::get('/monitoring/{id}',     [PimpinanController::class, 'monitoringDetail'])->name('monitoring.detail');
        Route::get('/surat-tugas',         [PimpinanController::class, 'suratTugas'])->name('surat-tugas');
        Route::get('/ekspor',              [PimpinanController::class, 'ekspor'])->name('ekspor');
        Route::get('/ekspor/unduh',        [PimpinanController::class, 'eksporUnduh'])->name('ekspor.unduh');
        Route::get('/ekspor/cetak-pdf',    [PimpinanController::class, 'eksporCetakPdf'])->name('ekspor.cetak-pdf');
        });

        Route::get('/keamanan', [PasswordController::class, 'edit'])->name('keamanan');
        Route::put('/keamanan', [PasswordController::class, 'update'])->name('keamanan.update');
    });

    // ── SUPER ADMIN (FR-W21–W25) ───────────────────────────────────
    Route::middleware(['auth', 'role:super-admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        // Manajemen Pengguna
        Route::get('/pengguna',                     [AdminController::class, 'indexPengguna'])->name('pengguna');
        Route::get('/pengguna/tambah',              [AdminController::class, 'createPengguna'])->name('pengguna.tambah');
        Route::post('/pengguna',                    [AdminController::class, 'storePengguna'])->name('pengguna.store');
        Route::get('/pengguna/{id}/edit',           [AdminController::class, 'editPengguna'])->name('pengguna.edit');
        Route::put('/pengguna/{id}',                [AdminController::class, 'updatePengguna'])->name('pengguna.update');
        Route::patch('/pengguna/{id}/toggle-aktif', [AdminController::class, 'toggleAktif'])->name('pengguna.toggle-aktif');
        Route::post('/pengguna/bulk-reset-password', [AdminController::class, 'bulkResetPassword'])->name('pengguna.bulk-reset-password');
        Route::get('/pengguna/bulk-reset-download',  [AdminController::class, 'bulkResetDownload'])->name('pengguna.bulk-reset-download');

        // Profil Admin
        Route::get('/profil',          [AdminController::class, 'profil'])->name('profil');
        Route::put('/profil',          [AdminController::class, 'updateProfil'])->name('profil.update');

        // Manajemen Role
        Route::get('/role',            [AdminController::class, 'indexRole'])->name('role');
        Route::patch('/role/{userId}', [AdminController::class, 'updateRole'])->name('role.update');

        // Manajemen UPT
        Route::get('/upt',         [AdminController::class, 'indexUpt'])->name('upt');
        Route::post('/upt',        [AdminController::class, 'storeUpt'])->name('upt.store');
        Route::put('/upt/{kode}',  [AdminController::class, 'updateUpt'])->name('upt.update');

        // Log Sistem
        Route::get('/log-sistem',  [AdminController::class, 'indexSystemLog'])->name('log-sistem');
    });
});

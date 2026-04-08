<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HasilPemeriksaan;
use App\Models\RekomendasiKarantina;
use App\Models\SuratTugas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KoordinatorController extends Controller
{
    /**
     * FR-W04: Dashboard ringkasan UPT Koordinator
     */
    public function dashboard(): View
    {
        $user  = Auth::user();
        $uptId = $user->upt_id;

        $stAktif       = SuratTugas::where('upt_id', $uptId)->where('status', 'aktif')->count();
        $stSelesaiHari = SuratTugas::where('upt_id', $uptId)->where('status', 'selesai')
                            ->whereDate('updated_at', today())->count();
        $menungguReview = HasilPemeriksaan::whereHas('suratTugas', fn($q) => $q->where('upt_id', $uptId))
                            ->where('status_review', 'belum_direview')->count();
        $petugasAktif  = User::where('upt_id', $uptId)->where('is_active', true)
                            ->whereHas('role', fn($q) => $q->where('name', 'petugas-lapangan'))->count();

        return view('koordinator.dashboard', compact(
            'stAktif', 'stSelesaiHari', 'menungguReview', 'petugasAktif'
        ));
    }

    /**
     * FR-W06: Daftar hasil pemeriksaan UPT dengan filter status review
     */
    public function hasilPeriksa(Request $request): View
    {
        $uptId = Auth::user()->upt_id;

        $query = HasilPemeriksaan::with(['petugas:id,nama,nip', 'suratTugas', 'rekomendasi'])
            ->whereHas('suratTugas', fn($q) => $q->where('upt_id', $uptId));

        if ($request->filled('status')) {
            $query->where('status_review', $request->status);
        }

        $hasilList = $query->orderByDesc('tgl_periksa')->paginate(25);

        return view('koordinator.hasil-periksa', compact('hasilList'));
    }

    /**
     * FR-W07, FR-W08: Detail hasil pemeriksaan + foto lightbox + peta
     */
    public function hasilPerikasDetail(string $id): View
    {
        $uptId = Auth::user()->upt_id;

        $hasil = HasilPemeriksaan::with([
            'petugas:id,nama,nip,golongan,pangkat',
            'suratTugas.lokasi',
            'suratTugas.komoditas',
            'dokumentasi',
            'rekomendasi.koordinator:id,nama,nip',
        ])
        ->whereHas('suratTugas', fn($q) => $q->where('upt_id', $uptId))
        ->findOrFail($id);

        return view('koordinator.hasil-periksa-detail', compact('hasil'));
    }

    /**
     * FR-W09, FR-W10: Simpan rekomendasi tindakan karantina
     */
    public function simpanRekomendasi(Request $request): RedirectResponse
    {
        $request->validate([
            'id_hasil_pemeriksaan' => 'required|string|exists:hasil_pemeriksaan,id',
            'tindakan'             => 'required|in:pelepasan,penolakan,perlakuan,pemusnahan',
            'catatan'              => 'required|string|min:10',
        ]);

        $user = Auth::user();

        // Pastikan data dari UPT koordinator sendiri (FR-W05)
        $hasil = HasilPemeriksaan::whereHas('suratTugas', fn($q) => $q->where('upt_id', $user->upt_id))
            ->findOrFail($request->id_hasil_pemeriksaan);

        // Buat atau update rekomendasi
        $rekomendasi = RekomendasiKarantina::updateOrCreate(
            ['id_hasil_pemeriksaan' => $hasil->id],
            [
                'koordinator_id'      => $user->id,
                'tindakan'            => $request->tindakan,
                'catatan'             => $request->catatan,
                // Simulasi pengiriman ke BEST-TRUST otomatis
                'best_trust_status'   => 'success',
                'best_trust_response' => json_encode([
                    'status'      => 'success',
                    'message'     => 'Data rekomendasi karantina diterima dan diproses (simulasi)',
                    'timestamp'   => now()->toDateTimeString(),
                    'reference'   => 'BT-' . strtoupper(bin2hex(random_bytes(4))),
                ]),
            ]
        );

        $hasil->update(['status_review' => 'sudah_direview']);

        session()->flash('success', "✓ Rekomendasi tindakan '{$request->tindakan}' berhasil disimpan untuk ST {$hasil->suratTugas?->no_st}");

        return redirect()->route('koordinator.hasil-periksa.detail', $hasil->id);
    }

    /**
     * Daftar Petugas Lapangan dalam UPT yang sama (untuk Koordinator).
     */
    public function listPetugas(): View
    {
        $uptId   = Auth::user()->upt_id;
        $petugas = User::with('role')
            ->where('upt_id', $uptId)
            ->whereHas('role', fn($q) => $q->where('name', 'petugas-lapangan'))
            ->orderBy('nama')
            ->get();

        return view('koordinator.petugas', compact('petugas'));
    }

    /**
     * Reset password Petugas oleh Koordinator UPT (hanya dalam UPT yang sama).
     */
     public function resetPetugasPassword(Request $request, int $id): RedirectResponse
     {
         $request->validate([
             'password' => 'required|string|min:8|confirmed',
         ]);

         $koordinator = Auth::user();

         $petugas = User::where('id', $id)
             ->where('upt_id', $koordinator->upt_id)
             ->whereHas('role', fn ($q) => $q->where('name', 'petugas-lapangan'))
             ->firstOrFail();

         $petugas->update([
             'password' => Hash::make($request->password),
         ]);

         return back()->with('success', 'Password petugas berhasil direset oleh Koordinator UPT.');
     }
}

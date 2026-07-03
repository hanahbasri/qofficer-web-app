<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HasilPemeriksaan;
use App\Models\RekomendasiKarantina;
use App\Models\SuratTugas;
use App\Models\User;
use App\Support\PasswordPolicyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Preview 5 hasil pemeriksaan terbaru yang belum direview
        $hasilMenunggu = HasilPemeriksaan::with(['petugas:id,nama', 'suratTugas:id,no_st,jenis_karantina'])
            ->whereHas('suratTugas', fn($q) => $q->where('upt_id', $uptId))
            ->where('status_review', 'belum_direview')
            ->orderByDesc('tgl_periksa')
            ->limit(5)
            ->get();

        return view('koordinator.dashboard', compact(
            'stAktif', 'stSelesaiHari', 'menungguReview', 'petugasAktif', 'hasilMenunggu'
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

        $hasilList = $query->orderByDesc('tgl_periksa')->paginate(25);

        return view('koordinator.hasil-periksa', compact('hasilList'));
    }

    /**
     * FR-W07, FR-W08: Detail hasil pemeriksaan + foto lightbox + peta
     */
    public function hasilPeriksaDetail(string $id): View
    {
        $uptId = Auth::user()->upt_id;

        $hasil = HasilPemeriksaan::with([
            'petugas:id,nama,nip,golongan,pangkat',
            'suratTugas.lokasi',
            'suratTugas.komoditas',
            'suratTugas.petugas',
            'suratTugas.hasilPemeriksaan' => fn ($q) => $q
                ->with('petugas:id,nama,nip')
                ->orderBy('tgl_periksa'),
            'dokumentasi',
            'rekomendasi.koordinator:id,nama,nip',
        ])
        ->whereHas('suratTugas', fn($q) => $q->where('upt_id', $uptId))
        ->findOrFail($id);

        $st = $hasil->suratTugas;

        // GPS points dari semua hasil pemeriksaan (untuk polyline peta)
        $gpsPoints = $st->hasilPemeriksaan
            ->filter(fn ($h) => $h->lat && $h->long)
            ->map(fn ($h) => [
                'lat'    => (float) $h->lat,
                'lng'    => (float) $h->long,
                'label'  => $h->petugas?->nama,
                'time'   => $h->tgl_periksa?->format('d M Y, H:i'),
                'active' => $h->id === $hasil->id,
            ])
            ->values();

        // Log aktivitas gabungan, diurutkan kronologis
        $logEvents = collect();
        foreach ($st->petugas as $ptg) {
            $pvt = $ptg->pivot;
            if ($pvt->diterima_at) {
                $logEvents->push([
                    'type'    => 'terima',
                    'at'      => \Carbon\Carbon::parse($pvt->diterima_at),
                    'nama'    => $ptg->nama,
                    'lat'     => null,
                    'long'    => null,
                ]);
            }
            if ($pvt->berangkat_at) {
                $logEvents->push([
                    'type'    => 'berangkat',
                    'at'      => \Carbon\Carbon::parse($pvt->berangkat_at),
                    'nama'    => $ptg->nama,
                    'lat'     => null,
                    'long'    => null,
                ]);
            }
        }
        foreach ($st->hasilPemeriksaan as $hp) {
            $logEvents->push([
                'type'    => 'periksa',
                'at'      => $hp->tgl_periksa,
                'nama'    => $hp->petugas?->nama,
                'lat'     => $hp->lat,
                'long'    => $hp->long,
                'hp_id'   => $hp->id,
                'active'  => $hp->id === $hasil->id,
            ]);
        }
        if ($st->status === 'selesai') {
            $logEvents->push([
                'type' => 'selesai',
                'at'   => $st->updated_at,
                'nama' => null,
                'lat'  => null,
                'long' => null,
            ]);
        }
        $logEvents = $logEvents->sortBy('at')->values();

        // Status selesai per petugas (sudah submit hasil = selesai)
        $petugasSelesaiIds = $st->hasilPemeriksaan->pluck('id_petugas')->unique();

        return view('koordinator.hasil-periksa-detail', compact(
            'hasil', 'st', 'gpsPoints', 'logEvents', 'petugasSelesaiIds'
        ));
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

        session()->flash(
            'success',
            "Rekomendasi tindakan '{$request->tindakan}' berhasil disimpan untuk ST {$hasil->suratTugas?->no_st}."
        );

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
            ->whereHas('role', fn($q) => $q->where('name', 'petugas-lapangan'))
            ->firstOrFail();

        $petugas->update(
            PasswordPolicyService::managedPasswordData((string) $request->input('password'))
        );

        return back()->with(
            'success',
            'Password petugas berhasil direset. Password baru wajib diganti saat login dan berlaku 30 hari.'
        );
    }
}

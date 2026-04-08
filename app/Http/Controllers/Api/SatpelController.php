<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SatpelController extends Controller
{
    /**
     * Daftar satpel untuk UPT tertentu (untuk dropdown lokasi penugasan).
     * GET /satpel?upt={kode_upt}
     *
     * Setiap UPT memiliki satu nama_satpel. Endpoint ini mengembalikan
     * array sehingga mobile bisa menampilkan dropdown.
     */
    public function index(Request $request): JsonResponse
    {
        $uptId = $request->query('upt', $request->user()->upt_id);

        $upt = Upt::where('kode', $uptId)->first();

        if (!$upt) {
            return response()->json(['data' => []]);
        }

        $satpelList = [];

        if (!empty($upt->nama_satpel)) {
            // nama_satpel bisa berisi beberapa satpel dipisah koma
            $items = array_map('trim', explode(',', $upt->nama_satpel));
            foreach ($items as $index => $nama) {
                if ($nama !== '') {
                    $satpelList[] = [
                        'id'   => $uptId . '_' . ($index + 1),
                        'kode' => $uptId,
                        'nama' => $nama,
                    ];
                }
            }
        }

        // Fallback: gunakan nama UPT sendiri jika satpel belum diisi
        if (empty($satpelList)) {
            $satpelList[] = [
                'id'   => $uptId,
                'kode' => $uptId,
                'nama' => $upt->nama,
            ];
        }

        return response()->json(['data' => $satpelList]);
    }
}

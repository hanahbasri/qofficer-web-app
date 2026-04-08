<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomoditasSt extends Model
{
    protected $table = 'komoditas_st';

    protected $fillable = [
        'surat_tugas_id', 'nama_komoditas', 'nama_latin',
        'volume', 'satuan', 'jenis_karantina',
    ];

    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'surat_tugas_id');
    }
}

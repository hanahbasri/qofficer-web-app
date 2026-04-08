<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiSt extends Model
{
    protected $table = 'lokasi_st';

    protected $fillable = [
        'surat_tugas_id', 'nama_lokasi', 'lat', 'long', 'detail_lokasi',
    ];

    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'surat_tugas_id');
    }
}

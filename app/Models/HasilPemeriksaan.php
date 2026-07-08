<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPemeriksaan extends Model
{
    protected $table = 'hasil_pemeriksaan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_surat_tugas', 'id_petugas',
        'id_lokasi', 'nama_lokasi',
        'lat', 'long', 'target', 'metode', 'temuan',
        'catatan', 'komoditas', 'status_review', 'tgl_periksa',
    ];

    protected $casts = [
        'tgl_periksa' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'id_surat_tugas');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }

    public function dokumentasi()
    {
        return $this->hasMany(DokumentasiPeriksa::class, 'id_pemeriksaan');
    }

    public function rekomendasi()
    {
        return $this->hasOne(RekomendasiKarantina::class, 'id_hasil_pemeriksaan');
    }
}

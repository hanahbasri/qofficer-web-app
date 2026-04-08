<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    protected $table = 'surat_tugas';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'ptk_id', 'no_st', 'tanggal', 'perihal',
        'dasar_hukum', 'nama_penandatangan', 'nip_penandatangan',
        'status', 'jenis_karantina', 'koordinator_id', 'upt_id', 'link',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function koordinator()
    {
        return $this->belongsTo(User::class, 'koordinator_id');
    }

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'upt_id', 'kode');
    }

    public function petugas()
    {
        return $this->belongsToMany(User::class, 'surat_tugas_petugas', 'surat_tugas_id', 'petugas_id')
            ->withPivot(['status_penerimaan', 'diterima_at'])
            ->withTimestamps();
    }

    public function lokasi()
    {
        return $this->hasMany(LokasiSt::class, 'surat_tugas_id');
    }

    public function komoditas()
    {
        return $this->hasMany(KomoditasSt::class, 'surat_tugas_id');
    }

    public function hasilPemeriksaan()
    {
        return $this->hasMany(HasilPemeriksaan::class, 'id_surat_tugas');
    }
}

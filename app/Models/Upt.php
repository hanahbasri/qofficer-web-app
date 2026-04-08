<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SuratTugas;
use App\Models\HasilPemeriksaan;

class Upt extends Model
{
    protected $table = 'upt';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode', 'nama', 'alias', 'nama_satpel', 'wilayah'];

    /**
     * Nama singkat untuk tampilan chart/tabel pimpinan.
     * Prioritas: alias manual → nama_satpel (jika bukan "UPT Induk") → auto-generate dari pola nama → nama penuh
     */
    public function getShortNameAttribute(): string
    {
        if ($this->alias) {
            return $this->alias;
        }
        if ($this->nama_satpel && $this->nama_satpel !== 'UPT Induk') {
            return $this->nama_satpel;
        }
        // Auto-generate: "Balai Besar Karantina Hewan, Ikan, dan Tumbuhan X" → "BBKHIT X"
        //                "Balai Karantina Hewan, Ikan, dan Tumbuhan X"        → "BKHIT X"
        $nama = $this->nama;
        if (preg_match('/^Balai Besar Karantina Hewan, Ikan, dan Tumbuhan\s+(.+)$/i', $nama, $m)) {
            return 'BBKHIT ' . trim($m[1]);
        }
        if (preg_match('/^Balai Karantina Hewan, Ikan, dan Tumbuhan\s+(.+)$/i', $nama, $m)) {
            return 'BKHIT ' . trim($m[1]);
        }
        return $nama;
    }

    public function users()
    {
        return $this->hasMany(User::class, 'upt_id', 'kode');
    }

    public function suratTugas()
    {
        return $this->hasMany(SuratTugas::class, 'upt_id', 'kode');
    }

    public function hasilPemeriksaan()
    {
        return $this->hasManyThrough(
            HasilPemeriksaan::class,
            SuratTugas::class,
            'upt_id',           // FK di surat_tugas → upt
            'id_surat_tugas',   // FK di hasil_pemeriksaan → surat_tugas
            'kode',             // PK upt
            'id'                // PK surat_tugas
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiPeriksa extends Model
{
    protected $table = 'dokumentasi_periksa';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_pemeriksaan', 'foto_path', 'foto_display', 'foto_server',
    ];

    // Sembunyikan base64 besar dari response default
    protected $hidden = ['foto_display', 'foto_server'];

    public function pemeriksaan()
    {
        return $this->belongsTo(HasilPemeriksaan::class, 'id_pemeriksaan');
    }
}

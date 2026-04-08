<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiKarantina extends Model
{
    protected $table = 'rekomendasi_karantina';

    protected $fillable = [
        'id_hasil_pemeriksaan', 'koordinator_id',
        'tindakan', 'catatan',
        'best_trust_status', 'best_trust_response',
    ];

    public function hasilPemeriksaan()
    {
        return $this->belongsTo(HasilPemeriksaan::class, 'id_hasil_pemeriksaan');
    }

    public function koordinator()
    {
        return $this->belongsTo(User::class, 'koordinator_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    protected $table = 'komoditas';

    protected $fillable = ['kategori_id', 'nama', 'kode_hs'];

    public function kategori()
    {
        return $this->belongsTo(KomoditasKategori::class, 'kategori_id');
    }
}

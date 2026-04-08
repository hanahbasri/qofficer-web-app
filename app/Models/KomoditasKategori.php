<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomoditasKategori extends Model
{
    protected $table = 'komoditas_kategori';

    protected $fillable = ['nama'];

    public function komoditas()
    {
        return $this->hasMany(Komoditas::class, 'kategori_id');
    }
}

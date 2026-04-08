<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTemuan extends Model
{
    protected $table = 'master_temuan';

    protected $fillable = ['nama', 'jenis_karantina', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeJenis($query, string $jenis)
    {
        return $query->where('jenis_karantina', $jenis);
    }
}

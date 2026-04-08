<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id', 'judul', 'pesan',
        'type', 'referensi_id', 'sudah_dibaca',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

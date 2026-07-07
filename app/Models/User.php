<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nip', 'nama', 'email', 'password',
        'upt_id', 'role_id', 'is_active',
        'foto_profil', 'golongan', 'pangkat', 'jabatan', 'fcm_token',
        'must_change_password', 'password_changed_at', 'password_expires_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'must_change_password' => 'boolean',
        'password_changed_at' => 'datetime',
        'password_expires_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'upt_id', 'kode');
    }

    public function suratTugasDibuat()
    {
        return $this->hasMany(SuratTugas::class, 'koordinator_id');
    }

    public function suratTugasDitugaskan()
    {
        return $this->belongsToMany(SuratTugas::class, 'surat_tugas_petugas', 'petugas_id', 'surat_tugas_id')
            ->withPivot(['status_penerimaan', 'diterima_at'])
            ->withTimestamps();
    }

    public function hasilPemeriksaan()
    {
        return $this->hasMany(HasilPemeriksaan::class, 'id_petugas');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function systemLogs()
    {
        return $this->hasMany(SystemLog::class);
    }

    // ── Helpers ──────────────────────────────────────────────────
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function getRoleName(): ?string
    {
        return $this->role?->name;
    }

    public function isPasswordExpired(): bool
    {
        return $this->password_expires_at?->isPast() ?? false;
    }

    public function needsPasswordChange(): bool
    {
        return $this->must_change_password || $this->isPasswordExpired();
    }
}

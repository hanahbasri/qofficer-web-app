<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordPolicyService
{
    public const EXPIRY_DAYS = 30;

    public static function managedPasswordData(string $plainPassword, bool $mustChange = true): array
    {
        $changedAt = now();

        return [
            'password' => Hash::make($plainPassword),
            'must_change_password' => $mustChange,
            'password_changed_at' => $changedAt,
            'password_expires_at' => $changedAt->copy()->addDays(self::EXPIRY_DAYS),
        ];
    }

    public static function applyManagedPassword(User $user, string $plainPassword, bool $mustChange = true): void
    {
        $user->forceFill(self::managedPasswordData($plainPassword, $mustChange))->save();
    }

    public static function buildStatus(User $user): array
    {
        return [
            'must_change_password' => (bool) $user->must_change_password,
            'password_expired' => $user->isPasswordExpired(),
            'password_expires_at' => $user->password_expires_at?->toISOString(),
            'expiry_days' => self::EXPIRY_DAYS,
        ];
    }

    public static function refreshMessage(User $user): string
    {
        if ($user->must_change_password) {
            return 'Password awal atau hasil reset wajib diganti sebelum melanjutkan.';
        }

        if ($user->isPasswordExpired()) {
            return 'Masa berlaku password telah habis. Silakan ganti password untuk melanjutkan.';
        }

        return 'Silakan perbarui password akun Anda.';
    }
}

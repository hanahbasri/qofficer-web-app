<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountProfileController extends Controller
{
    private const EMAIL_DOMAIN = 'karantinaindonesia.go.id';

    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_unless($this->supportsWebProfilePage($user), 403);

        return view('account.profil', [
            'user' => $user,
            'dashboardRoute' => $this->dashboardRouteName($user),
            'updateRoute' => $this->profileUpdateRouteName($user),
            'securityRoute' => $this->securityRouteName($user),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($this->supportsWebProfilePage($user), 403);

        /** @var User $user */
        $before = [
            'nama' => $user->nama,
            'email' => $user->email,
            'golongan' => $user->golongan,
            'pangkat' => $user->pangkat,
        ];

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $email = strtolower((string) $value);

                    if (!str_ends_with($email, '@' . self::EMAIL_DOMAIN)) {
                        $fail('Email harus menggunakan domain @' . self::EMAIL_DOMAIN . '.');
                    }
                },
            ],
            'golongan' => ['nullable', 'string', 'max:10'],
            'pangkat' => ['nullable', 'string', 'max:100'],
        ]);

        $user->update($validated);

        SystemLogService::record(
            $request,
            'profil',
            'ubah',
            'Memperbarui profil ' . ($user->role?->display_name ?? 'pengguna') . '.',
            $user,
            [
                'nama_lama' => $before['nama'],
                'nama_baru' => $user->nama,
                'email_lama' => $before['email'],
                'email_baru' => $user->email,
                'golongan_lama' => $before['golongan'] ?? '-',
                'golongan_baru' => $user->golongan ?? '-',
                'pangkat_lama' => $before['pangkat'] ?? '-',
                'pangkat_baru' => $user->pangkat ?? '-',
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    private function supportsWebProfilePage(User $user): bool
    {
        return in_array($user->getRoleName(), ['koordinator-upt', 'pimpinan'], true);
    }

    private function dashboardRouteName(User $user): string
    {
        return match ($user->getRoleName()) {
            'koordinator-upt' => 'koordinator.dashboard',
            'pimpinan' => 'pimpinan.dashboard',
            default => abort(403, 'Role tidak memiliki halaman profil web.'),
        };
    }

    private function profileUpdateRouteName(User $user): string
    {
        return match ($user->getRoleName()) {
            'koordinator-upt' => 'koordinator.profil.update',
            'pimpinan' => 'pimpinan.profil.update',
            default => abort(403, 'Role tidak memiliki halaman profil web.'),
        };
    }

    private function securityRouteName(User $user): string
    {
        return match ($user->getRoleName()) {
            'koordinator-upt' => 'koordinator.keamanan',
            'pimpinan' => 'pimpinan.keamanan',
            default => abort(403, 'Role tidak memiliki halaman keamanan web.'),
        };
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PasswordPolicyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_unless($this->supportsWebPasswordPage($user), 403);

        return view('account.keamanan', [
            'user' => $user,
            'dashboardRoute' => $this->dashboardRouteName($user),
            'passwordMessage' => $user->needsPasswordChange()
                ? PasswordPolicyService::refreshMessage($user)
                : null,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($this->supportsWebPasswordPage($user), 403);

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check((string) $request->input('current_password'), (string) $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        if (Hash::check((string) $request->input('password'), (string) $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Password baru harus berbeda dari password saat ini.',
            ]);
        }

        $user->update(PasswordPolicyService::managedPasswordData(
            (string) $request->input('password'),
            false
        ));

        return redirect()
            ->route($this->dashboardRouteName($user))
            ->with('success', 'Password berhasil diperbarui. Masa berlaku password diperpanjang 30 hari ke depan.');
    }

    private function supportsWebPasswordPage(User $user): bool
    {
        return in_array($user->getRoleName(), ['koordinator-upt', 'pimpinan'], true);
    }

    private function dashboardRouteName(User $user): string
    {
        return match ($user->getRoleName()) {
            'koordinator-upt' => 'koordinator.dashboard',
            'pimpinan' => 'pimpinan.dashboard',
            default => abort(403, 'Role tidak memiliki halaman keamanan web.'),
        };
    }
}

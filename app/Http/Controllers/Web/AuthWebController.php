<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PasswordPolicyService;
use App\Support\SystemLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthWebController extends Controller
{
    /**
     * FR-W01: Halaman login web
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * FR-W01: Proses login, redirect ke dashboard sesuai role
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'nip'      => 'required|string',
            'password' => 'required|string',
        ]);

        // Verifikasi reCAPTCHA (skip jika key belum diset / test key)
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        if ($secretKey && $secretKey !== '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe') {
            $recaptcha = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);
            if (!($recaptcha->json('success') ?? false)) {
                return back()->withErrors(['nip' => 'Verifikasi reCAPTCHA gagal. Coba lagi.'])->withInput();
            }
        }

        if (!Auth::attempt(['nip' => $request->nip, 'password' => $request->password], $request->boolean('remember'))) {
            return back()->withErrors(['nip' => 'Username atau password salah.'])->withInput();
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors(['nip' => 'Akun tidak aktif.']);
        }

        $request->session()->regenerate();

        if ($user->hasRole('super-admin')) {
            $user->loadMissing('role');

            SystemLogService::recordForUser(
                $request,
                $user,
                'autentikasi',
                'login',
                'Login ke dashboard super admin.',
                $user,
                ['role' => $user->role?->display_name]
            );
        }

        return $this->redirectByRole($user);
    }

    /**
     * FR-W03: Logout web
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user?->hasRole('super-admin')) {
            $user->loadMissing('role');

            SystemLogService::recordForUser(
                $request,
                $user,
                'autentikasi',
                'logout',
                'Logout dari dashboard super admin.',
                $user,
                ['role' => $user->role?->display_name]
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(User $user): RedirectResponse
    {
        $role = $user->getRoleName();

        if ($user->needsPasswordChange()) {
            return match ($role) {
                'koordinator-upt' => redirect()
                    ->route('koordinator.keamanan')
                    ->with('warning', PasswordPolicyService::refreshMessage($user)),
                'pimpinan' => redirect()
                    ->route('pimpinan.keamanan')
                    ->with('warning', PasswordPolicyService::refreshMessage($user)),
                'super-admin' => redirect()
                    ->route('admin.profil')
                    ->with('warning', PasswordPolicyService::refreshMessage($user)),
                default => $this->denyAccess(
                    'Password akun Anda perlu diperbarui. Silakan gunakan kanal yang didukung untuk mengganti password.',
                    $role
                ),
            };
        }

        return match ($role) {
            'koordinator-upt' => redirect()->route('koordinator.dashboard'),
            'super-admin'     => redirect()->route('admin.pengguna'),
            'pimpinan'        => redirect()->route('pimpinan.dashboard'),
            default           => $this->denyAccess(
                'Petugas lapangan menggunakan aplikasi mobile. Silakan gunakan aplikasi Q-Officer di smartphone Anda.',
                'petugas-lapangan'
            ),
        };
    }

    private function denyAccess(string $message, ?string $role = null): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('login')->with([
            'access_denied' => $message,
            'denied_role' => $role,
        ]);
    }
}

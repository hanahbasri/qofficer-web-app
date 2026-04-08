<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                /** @var \App\Models\User $user */
                $user = Auth::guard($guard)->user();
                // Redirect ke dashboard sesuai role
                return match ($user->getRoleName()) {
                    'koordinator-upt' => redirect()->route('koordinator.dashboard'),
                    'super-admin'     => redirect()->route('admin.pengguna'),
                    'pimpinan'        => redirect()->route('pimpinan.dashboard'),
                    default           => redirect()->route('login'),
                };
            }
        }

        return $next($request);
    }
}

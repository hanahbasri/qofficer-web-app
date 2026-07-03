<?php

namespace App\Http\Middleware;

use App\Support\PasswordPolicyService;
use Closure;
use Illuminate\Http\Request;

class EnsurePasswordIsFresh
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();

        if (!$user || !$user->needsPasswordChange() || app()->isLocal()) {
            return $next($request);
        }

        $message = PasswordPolicyService::refreshMessage($user);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'password_policy' => PasswordPolicyService::buildStatus($user),
            ], 403);
        }

        $routeName = match ($user->getRoleName()) {
            'koordinator-upt' => 'koordinator.keamanan',
            'pimpinan' => 'pimpinan.keamanan',
            default => null,
        };

        return $routeName
            ? redirect()->route($routeName)->with('warning', $message)
            : $next($request);
    }
}

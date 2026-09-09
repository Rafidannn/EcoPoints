<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if (empty($roles)) {
            return $next($request);
        }

        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $singleRole) {
                $trimmed = trim($singleRole);
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        if ($user->hasRole($allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}

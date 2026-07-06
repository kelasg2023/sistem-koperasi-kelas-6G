<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = session('user');

        if (!$user) {
            return redirect('/login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }

        if (in_array($user['role'], $roles)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->withErrors(['access' => 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.']);
    }
}

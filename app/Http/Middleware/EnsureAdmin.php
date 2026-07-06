<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');
        
        if (!$user || $user['role'] !== 'admin') {
            return redirect()->route('dashboard')->withErrors(['access' => 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.']);
        }

        return $next($request);
    }
}

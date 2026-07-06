<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('api_token');
        \Illuminate\Support\Facades\Log::info('EnsureAuthenticated called. session(user): ' . (session()->has('user') ? 'YES' : 'NO') . ', token: ' . ($token ? 'YES' : 'NO'));

        // Jika session kosong (misalnya karena Redis direstart)
        if (!session()->has('user') && !$request->cookie('api_token')) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        return $next($request);
    }
}

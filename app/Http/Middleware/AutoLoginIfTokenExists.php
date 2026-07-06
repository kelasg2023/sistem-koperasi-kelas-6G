<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class AutoLoginIfTokenExists
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('api_token');

        // Jika session kosong tapi cookie token ada (misalnya Redis restart)
        if (!session()->has('user') && $token) {
            try {
                $baseUrl = env('API_BASE_URL', 'http://localhost:8000/api');
                $response = Http::withToken($token)
                                ->withHeaders(['Accept' => 'application/json'])
                                ->get($baseUrl . '/auto-login');
                
                $result = $response->json();
                
                if ($response->successful() && isset($result['data']['user'])) {
                    // Bangun ulang session menggunakan data dari backend
                    session(['user' => $result['data']['user']]);
                } else {
                    // Token tidak valid lagi (misal expired di server), bersihkan cookie
                    Cookie::queue(Cookie::forget('api_token'));
                }
            } catch (\Exception $e) {
                // Biarkan saja, mungkin backend sedang down
            }
        }

        return $next($request);
    }
}

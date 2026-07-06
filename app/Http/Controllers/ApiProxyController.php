<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class ApiProxyController extends Controller
{
    public function proxy(Request $request, $any)
    {
        $token = Cookie::get('api_token');
        $baseUrl = env('API_BASE_URL', 'http://localhost:8000/api');
        $targetUrl = $baseUrl . '/' . $any;

        // Siapkan HTTP Client dengan Token jika ada
        $httpClient = Http::withHeaders([
            'Accept' => 'application/json',
        ]);
        
        // Pass specific frontend headers to backend (e.g. Idempotency Key)
        if ($request->hasHeader('X-Idempotency-Key')) {
            $httpClient->withHeaders(['X-Idempotency-Key' => $request->header('X-Idempotency-Key')]);
        }
        
        if ($token) {
            $httpClient->withToken($token);
        }

        $method = strtolower($request->method());
        \Illuminate\Support\Facades\Log::info("API_PROXY {$method} {$targetUrl} | Token: " . ($token ? substr($token, 0, 10) . '...' : 'NO_TOKEN'));

        try {
            if ($method === 'get') {
                $response = $httpClient->get($targetUrl, $request->query());
            } elseif ($method === 'post') {
                $response = $httpClient->post($targetUrl, $request->all());
            } elseif ($method === 'put') {
                $response = $httpClient->put($targetUrl, $request->all());
            } elseif ($method === 'patch') {
                $response = $httpClient->patch($targetUrl, $request->all());
            } elseif ($method === 'delete') {
                $response = $httpClient->delete($targetUrl, $request->all());
            } else {
                return response()->json(['message' => 'Method not allowed'], 405);
            }

            \Illuminate\Support\Facades\Log::info("API_PROXY_RESPONSE {$method} {$targetUrl} | Status: " . $response->status());

            // Return exact response from backend
            return response($response->body(), $response->status())
                ->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("API_PROXY_EXCEPTION: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Proxy Error: Tidak dapat terhubung ke Backend API'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IdempotencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Hanya memproses method POST, PUT, PATCH, DELETE
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $next($request);
        }

        $idempotencyKey = $request->header('X-Idempotency-Key');

        // Jika tidak ada key, kita izinkan lewat atau tolak (Di sini kita tolak untuk menegaskan sistem)
        if (!$idempotencyKey) {
            return response()->json([
                'success' => false,
                'message' => 'X-Idempotency-Key header is missing'
            ], 400);
        }

        $lockKey = 'idempotency_' . $idempotencyKey;
        
        // Gunakan store 'redis' untuk atomic lock
        $lock = Cache::store('redis')->lock($lockKey, 30); // Lock selama 30 detik

        if (!$lock->get()) {
            Log::warning("Idempotency conflict detected for key: {$idempotencyKey}");
            return response()->json([
                'success' => false,
                'message' => 'Request sedang diproses atau sudah pernah diproses'
            ], 409);
        }

        try {
            return $next($request);
        } finally {
            // Setelah request selesai diproses (sukses/gagal), kita bisa mempertahankan lock 
            // agar request kembar berikutnya (jika telat datang) tetap diblokir.
            // Tidak perlu release lock, biarkan expire secara otomatis.
            // Tapi jika ingin release:
            // $lock->release(); 
        }
    }
}

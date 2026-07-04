<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route di sini otomatis dapat prefix /api
| Contoh: Route::get('/voucher') → accessible di /api/voucher
|
| TODO: Tambahkan middleware auth:sanctum setelah Sanctum diinstall
|--------------------------------------------------------------------------
*/

// Voucher Routes
Route::prefix('voucher')->group(function () {
    Route::get('/',                    [VoucherController::class, 'index']);   // GET    /api/voucher
    Route::post('/',                   [VoucherController::class, 'store']);   // POST   /api/voucher
    Route::get('/check/{kode}',        [VoucherController::class, 'check']);   // GET    /api/voucher/check/{kode}
    Route::post('/claim',              [VoucherController::class, 'claim']);   // POST   /api/voucher/claim
    Route::post('/use',                [VoucherController::class, 'use']);     // POST   /api/voucher/use
    Route::get('/{id}',                [VoucherController::class, 'show']);    // GET    /api/voucher/{id}
    Route::put('/{id}',                [VoucherController::class, 'update']);  // PUT    /api/voucher/{id}
    Route::delete('/{id}',             [VoucherController::class, 'destroy']); // DELETE /api/voucher/{id}
});


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Voucher Routes
Route::prefix('voucher')->group(function () {
    Route::get('/',                    [VoucherController::class, 'index']);
    Route::post('/',                   [VoucherController::class, 'store']);
    Route::get('/check/{kode}',        [VoucherController::class, 'check']);
    Route::post('/claim',              [VoucherController::class, 'claim']);
    Route::post('/use',                [VoucherController::class, 'use']);
    Route::get('/{id}',                [VoucherController::class, 'show']);
    Route::put('/{id}',                [VoucherController::class, 'update']);
    Route::delete('/{id}',             [VoucherController::class, 'destroy']);
});

// Barang (Produk) Routes
Route::prefix('barang')->group(function () {
    Route::get('/',           [BarangController::class, 'index']);
    Route::post('/',          [BarangController::class, 'store']);
    Route::get('/{id}',       [BarangController::class, 'show']);
    Route::put('/{id}',       [BarangController::class, 'update']);
    Route::delete('/{id}',    [BarangController::class, 'destroy']);
});

// Kategori Routes
Route::prefix('kategori')->group(function () {
    Route::get('/',           [KategoriController::class, 'index']);
    Route::post('/',          [KategoriController::class, 'store']);
    Route::get('/{id}',       [KategoriController::class, 'show']);
    Route::put('/{id}',       [KategoriController::class, 'update']);
    Route::delete('/{id}',    [KategoriController::class, 'destroy']);
});
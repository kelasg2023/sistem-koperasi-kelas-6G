<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MLController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Webhook Midtrans
Route::post('/wallet/webhook', [\App\Http\Controllers\WalletController::class, 'webhook']);

// Rute ini dibutuhkan oleh sistem notifikasi Laravel untuk men-generate link di dalam email
Route::get('/reset-password/{token}', function (string $token, Request $request) {
    $frontendUrl = env('FRONTEND_URL', 'http://localhost:8100');
    return redirect()->away($frontendUrl . '/reset-password/' . $token . '?email=' . urlencode($request->email));
})->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auto-login', [AuthController::class, 'autoLogin']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // Endpoint profil user
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::patch('/profile', [AuthController::class, 'updateProfile']);

    // Endpoint khusus admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::post('/reset-password', [AuthController::class, 'adminResetPassword']);
        
        // Manajemen User (Admin)
        Route::get('/users-legacy', [AuthController::class, 'getUsersAdmin']);
        Route::patch('/users-legacy/{username}', [AuthController::class, 'updateUserAdmin']);
        
        // CRUD User (Khusus Admin)
        Route::apiResource('users', UserController::class);
        
        // Manajemen Voucher (Khusus Admin)
        Route::prefix('voucher')->group(function () {
            Route::post('/', [VoucherController::class, 'store']);
            Route::put('/{id}', [VoucherController::class, 'update']);
            Route::delete('/{id}', [VoucherController::class, 'destroy']);
        });
        
        // Manajemen Kategori (Khusus Admin)
        Route::prefix('kategori')->group(function () {
            Route::post('/', [KategoriController::class, 'store']);
            Route::put('/{id}', [KategoriController::class, 'update']);
            Route::delete('/{id}', [KategoriController::class, 'destroy']);
        });

        // Manajemen Barang (Khusus Admin)
        Route::prefix('barang')->group(function () {
            Route::post('/', [BarangController::class, 'store']);
            Route::put('/{id}', [BarangController::class, 'update']);
            Route::delete('/{id}', [BarangController::class, 'destroy']);
        });

        // Prediksi & Alert Stok ML (Khusus Admin)
        Route::prefix('stok')->group(function () {
            Route::get('/prediksi', [MLController::class, 'getStokPrediksi']);
            Route::get('/alert', [MLController::class, 'getStokAlert']);
            Route::post('/safety/{id}', [MLController::class, 'getSafetyStock']);
        });
        
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'getAdminDashboard']);
    });

    // Endpoint khusus admin dan staff
    Route::middleware('role:admin,staff')->group(function () {
        // Transaksi (View all & Update status)
        Route::get('/transactions/all', [\App\Http\Controllers\TransactionController::class, 'getAllTransactions']);
        Route::put('/transaction/{id}/status', [\App\Http\Controllers\TransactionController::class, 'updateStatus']);
    });

    // Endpoint khusus staff
    Route::middleware('role:staff')->prefix('staff')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'getStaffDashboard']);
    });

    // Endpoint khusus supplier
    Route::middleware('role:supplier')->prefix('supplier')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'getSupplierDashboard']);
        Route::get('/barang', [\App\Http\Controllers\SupplierFeatureController::class, 'getBarangList']);
        Route::post('/barang', [\App\Http\Controllers\SupplierFeatureController::class, 'storeBarang']);
        Route::post('/kategori', [\App\Http\Controllers\SupplierFeatureController::class, 'storeKategori']);
        Route::post('/pasokan', [\App\Http\Controllers\SupplierFeatureController::class, 'addPasokan'])->middleware(['idempotent']);
    });

    // Endpoint khusus manager
    Route::middleware('role:manager')->prefix('manager')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'getManagerDashboard']);
        Route::get('/reports/sales', [\App\Http\Controllers\ReportController::class, 'getSalesReports']);
        Route::get('/reports/customers', [\App\Http\Controllers\ReportController::class, 'getCustomerReports']);
    });

    // Fitur Kategori (Read-only) untuk User / Authenticated
    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index']);
        Route::get('/{id}', [KategoriController::class, 'show']);
    });

    // Fitur Barang (Read-only) untuk User / Authenticated
    Route::prefix('barang')->group(function () {
        Route::get('/', [BarangController::class, 'index']);
        Route::get('/{id}', [BarangController::class, 'show']);
    });

    // Fitur Rekomendasi & Analitik (Machine Learning)
    Route::get('/produk/laris', [MLController::class, 'getProdukLaris']);
    Route::get('/rekomendasi', [MLController::class, 'getRekomendasi']);

    // Fitur Voucher untuk User / Authenticated
    Route::prefix('voucher')->group(function () {
        Route::get('/', [VoucherController::class, 'index']);
        Route::get('/check/{kode}', [VoucherController::class, 'check']);
        Route::post('/claim', [VoucherController::class, 'claim']);
        Route::post('/use', [VoucherController::class, 'use']);
        Route::get('/{id}', [VoucherController::class, 'show']);
    });

    // Fitur Wallet
    Route::post('/wallet/topup', [\App\Http\Controllers\WalletController::class, 'topup'])->middleware(['idempotent', 'throttle:10,1']);
    Route::post('/wallet/check-status', [\App\Http\Controllers\WalletController::class, 'checkStatus']);

    // Fitur Transaksi (Checkout & Tracking) untuk User / Authenticated
    Route::prefix('transaction')->group(function () {
        Route::post('/checkout', [\App\Http\Controllers\TransactionController::class, 'checkout'])->middleware(['idempotent', 'throttle:10,1']);
        Route::get('/history', [\App\Http\Controllers\TransactionController::class, 'history']);
        Route::get('/{id}/track', [\App\Http\Controllers\TransactionController::class, 'track']);
        Route::post('/{id}/cancel', [\App\Http\Controllers\TransactionController::class, 'cancel']);
    });

    // Endpoint dinamis untuk dashboard (berdasarkan role user saat ini)
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'getDynamicDashboard']);

    // Akses File Private (Gambar Profil, Resi, dll)
    Route::get('/file/private/{path}', function (string $path) {
        // Mencegah directory traversal attack
        $path = str_replace('..', '', $path);
        
        $fullPath = storage_path('app/private/' . $path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan');
        }
        
        $mimeType = \Illuminate\Support\Facades\File::mimeType($fullPath);
        
        return response()->file($fullPath, [
            'Content-Type' => $mimeType
        ]);
    })->where('path', '.*');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Rute ini dibutuhkan oleh sistem notifikasi Laravel untuk men-generate link di dalam email
Route::get('/reset-password/{token}', function (string $token, Request $request) {
    // Di aplikasi nyata (SPA/Frontend terpisah), rute ini harusnya me-redirect ke URL Frontend kamu
    // Misalnya: return redirect()->away('http://localhost:3000/reset-password?token=' . $token . '&email=' . $request->email);
    return response()->json([
        'message' => 'Gunakan token ini beserta email baru dan konfirmasi password untuk di POST ke /api/reset-password',
        'token' => $token,
        'email' => $request->email
    ]);
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
        Route::get('/users', [AuthController::class, 'getUsersAdmin']);
        Route::patch('/users/{username}', [AuthController::class, 'updateUserAdmin']);
        
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
        
        Route::get('/dashboard', function (Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'Admin Dashboard',
                'data' => ['total_users' => 10, 'pending_approvals' => 2]
            ]);
        });
    });

    // Endpoint khusus staff
    Route::middleware('role:staff')->prefix('staff')->group(function () {
        Route::get('/dashboard', function (Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'Staff Dashboard',
                'data' => ['sales_today' => 50]
            ]);
        });
    });

    // Endpoint khusus supplier
    Route::middleware('role:supplier')->prefix('supplier')->group(function () {
        Route::get('/dashboard', function (Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier Dashboard',
                'data' => ['pending_orders' => 5]
            ]);
        });
    });

    // Endpoint khusus manager
    Route::middleware('role:manager')->prefix('manager')->group(function () {
        Route::get('/dashboard', function (Request $request) {
            return response()->json([
                'success' => true,
                'message' => 'Manager Dashboard',
                'data' => ['monthly_revenue' => 10000000]
            ]);
        });
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

    // Fitur Voucher untuk User / Authenticated
    Route::prefix('voucher')->group(function () {
        Route::get('/', [VoucherController::class, 'index']);
        Route::get('/check/{kode}', [VoucherController::class, 'check']);
        Route::post('/claim', [VoucherController::class, 'claim']);
        Route::post('/use', [VoucherController::class, 'use']);
        Route::get('/{id}', [VoucherController::class, 'show']);
    });

    // Fitur Transaksi (Checkout & Tracking) untuk User / Authenticated
    Route::prefix('transaction')->group(function () {
        Route::post('/checkout', [\App\Http\Controllers\TransactionController::class, 'checkout']);
        Route::get('/history', [\App\Http\Controllers\TransactionController::class, 'history']);
        Route::get('/{id}/track', [\App\Http\Controllers\TransactionController::class, 'track']);
    });

    // Endpoint dinamis untuk dashboard (berdasarkan role user saat ini)
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();
        
        // Kalian bisa menambahkan data spesifik per role di sini nantinya
        $dashboardData = [];
        if ($user->role === 'admin') {
            $dashboardData = ['total_users' => 10, 'pending_approvals' => 2]; // Contoh
        } elseif ($user->role === 'staff') {
            $dashboardData = ['sales_today' => 50]; // Contoh
        }

        return response()->json([
            'success' => true,
            'message' => 'Selamat datang di dashboard ' . ucfirst($user->role),
            'data' => [
                'user' => $user,
                'role' => $user->role,
                'dashboard_metrics' => $dashboardData
            ]
        ]);
    });
});

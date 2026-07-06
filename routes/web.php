<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ApiProxyController;
use App\Http\Middleware\EnsureAuthenticated;

/*
|--------------------------------------------------------------------------
| Public Routes (Landing & Auth)
|--------------------------------------------------------------------------
*/

// Endpoint to display private images
Route::get('/private-image/{path}', function ($path) {
    $fullPath = storage_path('app/private/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('private.image');


// Landing page — load produk & kategori dari API
Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');
Route::get('/beranda', [DashboardController::class, 'welcome'])->name('beranda');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/lupa-password', [AuthController::class, 'showForgotPassword'])->name('lupa-password');
Route::post('/lupa-password/email', [AuthController::class, 'forgotPassword'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('preview-sandi-baru');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/preview-otp', fn() => view('auth.verifikasi_otp'))->name('preview-otp');
Route::get('/preview-sukses', fn() => view('auth.verifikasi_sandi_baru'))->name('preview-sukses');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (require login via EnsureAuthenticated)
|--------------------------------------------------------------------------
*/
Route::middleware(EnsureAuthenticated::class)->group(function () {

    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk & Kategori
    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/{kategori?}', [ProductController::class, 'index'])->name('produk.kategori');
    Route::get('/produk/detail/{id}', fn($id) => view('produk.detail', ['barangId' => $id]))->name('produk.detail');

    // Keranjang belanja
    Route::get('/keranjang', fn() => view('keranjang_belanja'))->name('keranjang.index');

    // Transaksi & Riwayat
    Route::get('/transaksi', fn() => view('templates.user.transaksi_user'))->name('transaksi.index');
    Route::get('/transaksi/riwayat', fn() => view('templates.user.riwayat_belanja'))->name('riwayat.index');

    // Checkout
    Route::get('/checkout', fn() => view('checkout.index'))->name('checkout.index');
    Route::get('/pembayaran', fn() => view('templates.user.metode_pembayaran'))->name('metode-pembayaran.index');

    // Informasi terkait user
    Route::get('/poin', fn() => view('templates.user.poin_user'))->name('poin.index');
    Route::get('/simpanan', fn() => view('templates.user.simpanan_user'))->name('simpanan.index');
    Route::get('/total-belanja', fn() => view('templates.user.total_belanja'))->name('total-belanja.index');
    Route::get('/untung-bersama', fn() => view('templates.user.untung_bersama'))->name('untung-bersama');
    Route::get('/voucher', fn() => view('templates.user.voucher_user'))->name('voucher.index');

    // Pengajuan member & Pengaturan
    Route::get('/pengajuan-member', fn() => view('templates.user.pengajuan_member'))->name('pengajuan-member.index');
    Route::get('/pengaturan', fn() => view('templates.user.pengaturan_user'))->name('pengaturan.index');

    // Pesanan
    Route::get('/pesanan/{id}', fn($id) => view('templates.user.detail_pesanan', ['transactionId' => $id]))->name('pesanan.detail');
    Route::get('/pesanan/{id}/feedback', fn($id) => view('templates.user.feedback_pesanan_berhasil', ['transactionId' => $id]))->name('pesanan.feedback');

    /*
    |--------------------------------------------------------------------------
    | Role-Specific Routes (checked via CheckRole alias 'role:...')
    |--------------------------------------------------------------------------
    */
    
    // Admin Routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/kategori', fn() => view('admin.kategori.index'))->name('admin.kategori.index');
        Route::get('/kategori/create', fn() => view('admin.kategori.create'))->name('admin.kategori.create');
        Route::get('/kategori/{id}/edit', fn($id) => view('admin.kategori.edit', ['id' => $id]))->name('admin.kategori.edit');
        Route::get('/produk', fn() => view('templates.admin.kelola_produk'))->name('admin.produk.index');
    });

    // Staff Routes
    Route::prefix('staff')->middleware('role:staff')->group(function () {
        Route::get('/dashboard', fn() => view('templates.staff.dashboard_staff'))->name('staff.dashboard');
    });

    // Manager Routes
    Route::prefix('manager')->middleware('role:manager')->group(function () {
        Route::get('/dashboard', fn() => view('templates.manager.dashboard_manager'))->name('manager.dashboard');
    });

    // Supplier Routes
    Route::prefix('supplier')->middleware('role:supplier')->group(function () {
        Route::get('/dashboard', fn() => view('templates.supplier.dashboard_supplier'))->name('supplier.dashboard');
    });
});

/*
|--------------------------------------------------------------------------
| API Proxy Route (BFF Pattern)
|--------------------------------------------------------------------------
| Semua AJAX dari frontend (AlpineJS/Axios) diarahkan ke sini.
| Laravel Frontend menyisipkan Bearer Token dari HttpOnly Cookie secara otomatis.
*/
Route::any('/api-proxy/{any}', [ApiProxyController::class, 'proxy'])->where('any', '.*');
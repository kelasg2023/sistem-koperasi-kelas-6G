<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Halaman Onboarding & Beranda
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/beranda', function () { return view('welcome'); })->name('beranda');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (session('user.role') === 'admin') {
        return redirect('/admin/kategori');
    }
    return view('dashboard_user');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', function () {
    session()->forget('user');
    return redirect('/');
})->name('logout');

// ... (Simpan bagian lupa password & preview UI di sini jika masih diperlukan) ...

/*
|--------------------------------------------------------------------------
| Produk (Gabungan fiturmu dan Dzaki)
|--------------------------------------------------------------------------
*/
// Rute untuk kategori produk (Pekerjaanmu)
Route::get('/produk/{kategori?}', [ProductController::class, 'index'])->name('produk.kategori');
// Rute untuk detail produk (Pekerjaan Dzaki)
Route::get('/produk/detail/{slug}', [ProductController::class, 'show'])->name('produk.show');

/*
|--------------------------------------------------------------------------
| Halaman Admin
|--------------------------------------------------------------------------
*/
Route::middleware('role:admin')->prefix('admin')->group(function () {

    Route::get('/produk', function () { return view('templates.admin.kelola_produk'); });
    Route::get('/kategori', function () { return view('admin.kategori.index'); });
    Route::get('/kategori/tambah', function () { return view('admin.kategori.create'); });
    Route::get('/kategori/edit/{id}', function ($id) {
        return view('admin.kategori.edit', ['id' => $id]);
    });

});
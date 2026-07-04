<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Halaman Onboarding (Ini akan tampil di http://127.0.0.1:8000/)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Halaman Utama (Ini akan tampil di http://127.0.0.1:8000/beranda)
|--------------------------------------------------------------------------
*/
Route::get('/beranda', function () {
    return view('welcome');
})->name('beranda');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
| Admin tidak punya "dashboard belanja" seperti user biasa, jadi kalau
| ada admin yang mengakses /dashboard (misal mengetik manual di address
| bar), langsung dialihkan ke halaman kelola kategori.
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

Route::get('/lupa-password', function () {
    return view('auth.lupa_password');
})->name('lupa-password');

// Rute untuk memproses submit form email
Route::post('/lupa-password/email', function (\Illuminate\Http\Request $request) {
    // Nanti logika untuk mengirim kode OTP atau integrasi ke AuthService diletakkan di sini
    return back()->with('status', 'Permintaan reset password sedang diproses.');
})->name('password.email');

// Rute sementara untuk melihat UI OTP
Route::get('/preview-otp', function () {
    return view('auth.verifikasi_otp');
})->name('preview-otp');

// Rute sementara untuk melihat UI Buat Sandi Baru
Route::get('/preview-sandi-baru', function () {
    return view('auth.buat_sandi_baru');
})->name('preview-sandi-baru');

// Rute untuk menangani saat tombol 'Simpan' diklik
Route::post('/preview-sandi-baru', function () {
    return redirect('/')->with('status', 'Kata sandi berhasil diubah!');
});

// Rute sementara untuk melihat UI Sandi Berhasil Diubah
Route::get('/preview-sukses', function () {
    return view('auth.verifikasi_sandi_baru');
})->name('preview-sukses');

Route::post('/logout', function () {
    session()->forget('user');
    return redirect('/');
})->name('logout');

Route::get('/produk/{kategori?}', [ProductController::class, 'index'])->name('produk.kategori');

/*
|--------------------------------------------------------------------------
| Halaman Admin (Dilindungi middleware role:admin)
|--------------------------------------------------------------------------
| Hanya user dengan session('user.role') === 'admin' yang bisa mengakses
| route-route di bawah ini. User lain akan mendapat 403 Forbidden,
| dan yang belum login akan diarahkan ke halaman /login.
|--------------------------------------------------------------------------
*/
Route::middleware('role:admin')->prefix('admin')->group(function () {

    Route::get('/produk', function () {
        return view('templates.admin.kelola_produk');
    });

    Route::get('/kategori', function () {
        return view('admin.kategori.index');
    });

    Route::get('/kategori/tambah', function () {
        return view('admin.kategori.create');
    });

    // Perbaikan: tambahkan parameter {id} agar tombol edit di tabel
    // (yang mengarah ke /admin/kategori/edit/{id_kategori}) tidak 404.
    Route::get('/kategori/edit/{id}', function ($id) {
        return view('admin.kategori.edit', ['id' => $id]);
    });

});
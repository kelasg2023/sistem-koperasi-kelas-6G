<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    return view('beranda');
})->name('beranda');

Route::get('/dashboard', function () {
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

// TAMBAHKAN RUTE INI: Rute untuk memproses submit form email
Route::post('/lupa-password/email', function (\Illuminate\Http\Request $request) {
    // Nanti logika untuk mengirim kode OTP atau integrasi ke AuthService diletakkan di sini
    
    // Sementara kita return back agar halaman tidak error saat tombol diklik
    return back()->with('status', 'Permintaan reset password sedang diproses.');
})->name('password.email');

// Rute sementara untuk melihat UI OTP
Route::get('/preview-otp', function () {
    return view('auth.verifikasi_otp'); 
})->name('preview-otp'); // <-- Tambahkan ini
// Rute sementara untuk melihat UI Buat Sandi Baru (Ini yang sudah kamu buat)
Route::get('/preview-sandi-baru', function () {
    return view('auth.buat_sandi_baru'); 
})->name('preview-sandi-baru');

// TAMBAHKAN INI: Rute untuk menangani saat tombol 'Simpan' diklik
Route::post('/preview-sandi-baru', function () {
    // Di masa depan, ini adalah tempat kamu memanggil fungsi update password dari AuthService
    
    // Sementara, kita buat pura-pura berhasil dan kembali ke halaman depan/login
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
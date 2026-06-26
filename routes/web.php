<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Utama (dengan navbar + sidebar)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('beranda');
})->name('beranda');

/*
|--------------------------------------------------------------------------
| Auth — no-chrome (login, register, dll.)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    // return view('auth.register'); // buat nanti
    return redirect()->route('login');
})->name('register');

// Stub logout — ganti dengan controller Auth saat sudah siap
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

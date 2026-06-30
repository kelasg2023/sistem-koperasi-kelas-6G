<?php

use Illuminate\Support\Facades\Route;

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

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');
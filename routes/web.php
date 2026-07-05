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

Route::post('/lupa-password/email', function (\Illuminate\Http\Request $request) {
    return back()->with('status', 'Permintaan reset password sedang diproses.');
})->name('password.email');

Route::get('/preview-otp', function () {
    return view('auth.verifikasi_otp');
})->name('preview-otp');

Route::get('/preview-sandi-baru', function () {
    return view('auth.buat_sandi_baru');
})->name('preview-sandi-baru');

Route::post('/preview-sandi-baru', function () {
    return redirect('/')->with('status', 'Kata sandi berhasil diubah!');
});

Route::get('/preview-sukses', function () {
    return view('auth.verifikasi_sandi_baru');
})->name('preview-sukses');

Route::post('/logout', function () {
    session()->forget('user');
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Produk
|--------------------------------------------------------------------------
| PENTING: filter kategori dan detail produk TIDAK BOLEH sama-sama
| memakai pola "/produk/{satu-segmen}", karena akan saling menabrak
| (Laravel hanya akan mencocokkan salah satu, yang lain tidak pernah
| terpanggil). Filter kategori dipisah ke /produk/kategori/{kategori}.
|--------------------------------------------------------------------------
*/
Route::get('/produk', [ProductController::class, 'index'])
    ->name('produk.index');

// Nama route tetap 'produk.kategori' supaya kompatibel dengan
// route('produk.kategori', $slug) yang sudah dipakai di welcome.blade.php.
Route::get('/produk/kategori/{kategori}', [ProductController::class, 'index'])
    ->name('produk.kategori');

Route::get('/produk/{slug}', [ProductController::class, 'show'])
    ->name('produk.show');

// Rute untuk menampilkan halaman keranjang belanja
Route::get('/keranjang', function () {
    $keranjang = [];
    return view('keranjang_belanja', compact('keranjang'));
})->name('keranjang.index');

/*
|--------------------------------------------------------------------------
| Menu Sidebar User
|--------------------------------------------------------------------------
| Sebelumnya route ini belum ada sama sekali, sehingga klik menu sidebar
| bisa "nyasar" ke route lain yang polanya kebetulan cocok. Tambahkan
| view sesuai yang sudah kamu buat di resources/views/ (sesuaikan nama
| file view di bawah kalau berbeda).
|--------------------------------------------------------------------------
*/
Route::get('/kategori', function () {
    return view('user.kategori');
})->name('kategori');

Route::get('/pengaturan', function () {
    return view('templates.user.pengaturan_user');
})->name('pengaturan.index');

Route::get('/transaksi', function () {
    return view('templates.user.transaksi_user');
})->name('transaksi.index');

Route::get('/transaksi', function () {
    return view('templates.user.transaksi_user');
})->name('transaksi.index');

Route::get('/untung-bersama', function () {
    return view('templates.user.untung_bersama'); // Sesuaikan path ini
})->name('untung-bersama');

Route::get('/metode-pembayaran', function () {
    return view('templates.user.metode_pembayaran'); // Ganti dengan nama file view-mu nanti
})->name('metode-pembayaran.index');

Route::get('/total-belanja', function () {
    return view('templates.user.total_belanja');
})->name('total-belanja.index');

Route::get('/voucher', function () {
    return view('templates.user.voucher_user');
})->name('voucher.index');

Route::get('/simpanan', function () {
    return view('templates.user.simpanan_user');
})->name('simpanan.index');

Route::get('/poin', function () {
    return view('templates.user.poin_user');
})->name('poin.index');

Route::get('/pengajuan-member', function () {
    return view('templates.user.pengajuan_member');
})->name('pengajuan-member.index');

Route::get('/riwayat-belanja', function () {
    return view('templates.user.riwayat_belanja');
})->name('riwayat-belanja.index');

Route::get('/detail-pesanan', function () {
    return view('templates.user.detail_pesanan');
})->name('detail-pesanan.index');
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

    Route::get('/kategori/edit/{id}', function ($id) {
        return view('admin.kategori.edit', ['id' => $id]);
    });

   
});
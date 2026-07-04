<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct()
    {
        $pdo = DB::connection()->getPdo();
        $this->authService = new AuthService($pdo);
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Perbaikan: Tangkap 'username' atau 'email', lalu paksa (cast) menjadi string
        // Jika keduanya kosong, berikan string kosong ('') agar tidak terjadi error 'null given'
        $loginIdentifier = (string) ($request->input('username') ?? $request->input('email') ?? '');
        $password = (string) $request->input('password');

        $result = $this->authService->login($loginIdentifier, $password);

        if (!$result['success']) {
            // Perbaikan: Tambahkan withInput agar inputan username/email tidak hilang saat gagal
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['login' => $result['message']]);
        }

        session(['user' => $result['user']]);

        return redirect()->route('beranda');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $result = $this->authService->register($request->all());

        if (!$result['success']) {
            // Perbaikan: Tambahkan withInput agar form (nama, email, no hp) tidak kosong lagi
            // except digunakan agar password tidak ikut dikembalikan demi keamanan
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['register' => $result['message']]);
        }

        return redirect()->route('login')->with('success', 'Register berhasil, silakan login');
    }
}
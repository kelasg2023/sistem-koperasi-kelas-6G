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
        $result = $this->authService->login(
            $request->input('username'),
            $request->input('password')
        );

        if (!$result['success']) {
            return back()->withErrors(['login' => $result['message']]);
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
            return back()->withErrors(['register' => $result['message']]);
        }

        return redirect()->route('login')->with('success', 'Register berhasil, silakan login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    private function getApiUrl($path)
    {
        return env('API_BASE_URL', 'http://localhost:8000/api') . $path;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginIdentifier = (string) ($request->input('username') ?? $request->input('email') ?? '');
        $password = (string) $request->input('password');
        $remember = $request->boolean('remember', false);

        try {
            $response = Http::post($this->getApiUrl('/login'), [
                'username' => $loginIdentifier,
                'password' => $password,
                'remember' => $remember
            ]);

            $result = $response->json();

            if (!$response->successful() || !isset($result['data']['access_token'])) {
                $message = $result['message'] ?? 'Gagal menghubungi server.';
                return back()
                    ->withInput($request->except('password'))
                    ->withErrors(['login' => $message]);
            }

            // Simpan data user ke session Laravel (Frontend)
            session(['user' => $result['data']['user']]);

            // Ambil token dari respons backend
            $token = $result['data']['access_token'];
            
            // Hitung masa berlaku cookie (sama dengan backend: 7 hari atau 1 hari)
            $minutes = $remember ? (7 * 24 * 60) : (24 * 60);

            // Buat HttpOnly Cookie
            // name, value, minutes, path, domain, secure, httpOnly
            $cookie = Cookie::make('api_token', $token, $minutes, null, null, false, true);

            $role = $result['data']['user']['role'] ?? 'customer';
            if ($role === 'admin') {
                return redirect()->route('admin.produk.index')->withCookie($cookie);
            } elseif ($role === 'staff') {
                return redirect()->route('staff.dashboard')->withCookie($cookie);
            } elseif ($role === 'manager') {
                return redirect()->route('manager.dashboard')->withCookie($cookie);
            } elseif ($role === 'supplier') {
                return redirect()->route('supplier.dashboard')->withCookie($cookie);
            }

            return redirect()->route('welcome')->withCookie($cookie);

        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['login' => 'Server tidak dapat diakses saat ini.']);
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $response = Http::post($this->getApiUrl('/register'), $request->all());
            $result = $response->json();

            if (!$response->successful()) {
                $message = $result['message'] ?? 'Gagal mendaftar.';
                // Jika error adalah validasi, backend akan mengirimkan response berstruktur
                $errors = $result['data'] ?? ['register' => $message];
                
                return back()
                    ->withInput($request->except(['password', 'password_confirmation']))
                    ->withErrors($errors);
            }

            return redirect()->route('login')->with('success', 'Register berhasil, silakan login');

        } catch (\Exception $e) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['register' => 'Server tidak dapat diakses saat ini.']);
        }
    }

    public function logout(Request $request)
    {
        $token = Cookie::get('api_token');
        
        if ($token) {
            Http::withToken($token)->post($this->getApiUrl('/logout'));
        }

        session()->forget('user');
        $cookie = Cookie::forget('api_token');
        
        return redirect()->route('welcome')->withCookie($cookie);
    }

    public function showForgotPassword()
    {
        return view('auth.lupa_password');
    }

    public function forgotPassword(Request $request)
    {
        try {
            $response = Http::post($this->getApiUrl('/forgot-password'), [
                'email' => $request->input('email'),
            ]);

            $result = $response->json();
            $message = $result['message'] ?? 'Jika email terdaftar, link reset telah dikirim.';

            return back()->with('status', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Server tidak dapat diakses saat ini.']);
        }
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.buat_sandi_baru', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $response = Http::post($this->getApiUrl('/reset-password'), [
                'token'                 => $request->input('token'),
                'email'                 => $request->input('email'),
                'password'              => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
            ]);

            $result = $response->json();

            if (!$response->successful()) {
                $message = $result['message'] ?? 'Reset password gagal.';
                return back()
                    ->withErrors(['password' => $message])
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login.');

        } catch (\Exception $e) {
            return back()->withErrors(['password' => 'Server tidak dapat diakses saat ini.']);
        }
    }
}
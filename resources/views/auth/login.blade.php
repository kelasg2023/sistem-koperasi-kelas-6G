@extends('layouts.app')
@section('title', 'Masuk')

@section('no-chrome', 'true')

@section('content')
<div class="min-h-screen flex flex-col justify-between items-center py-6 px-4 bg-slate-50 dark:bg-gray-950">

    <!-- Card Container Utama -->
    <div class="flex flex-col md:flex-row max-w-4xl w-full bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-xl border border-gray-100 dark:border-gray-800 my-auto">
        
        <!-- Sisi Kiri (Ilustrasi & Deskripsi) -->
        <div class="w-full md:w-1/2 bg-[#f0f7f4] dark:bg-emerald-950/20 p-8 md:p-12 flex flex-col justify-center items-center relative overflow-hidden">
            <!-- Ornamen Dekoratif Bulat -->
            <div class="absolute -top-10 -left-10 w-28 h-28 bg-[#dbece3] dark:bg-emerald-900/30 rounded-full opacity-60"></div>
            <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-[#fdf8e2] dark:bg-amber-950/20 rounded-full opacity-80 z-0"></div>

            <div class="relative z-10 flex flex-col items-center">
                <!-- Wrapper Gambar Ilustrasi -->
                <div class="relative w-full max-w-sm mb-6 rounded-2xl overflow-hidden shadow-sm bg-white dark:bg-gray-800 p-2">
                    <!-- Gunakan placeholder ilustrasi supermarket yang representatif -->
                    <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=600" 
                         alt="Belanja Lebih Hemat" 
                         class="w-full h-56 object-cover rounded-xl" />
                    
                    <!-- Badge "Segar" (Kanan Atas) -->
                    <span class="absolute top-4 right-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-[#207a44] dark:text-emerald-400 text-xs font-semibold px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M17 8C15.9 8 14.85 8.4 14.05 9.05C13 8.35 11.65 8 10.15 8C6.75 8 4 10.75 4 14.15C4 17 6 19.4 8.7 19.9C9.2 20 9.7 20 10.2 20C14.7 20 18.25 16.5 18.25 12C18.25 11.5 18.2 11 18.1 10.5C18.7 9.8 19 8.9 19 8H17ZM10.25 18C7.9 18 6 16.1 6 13.75C6 11.4 7.9 9.5 10.25 9.5C12.6 9.5 14.5 11.4 14.5 13.75C14.5 16.1 12.6 18 10.25 18Z"/>
                        </svg>
                        Segar
                    </span>

                    <!-- Badge "Umum" (Kiri Bawah) -->
                    <span class="absolute bottom-4 left-4 bg-white/95 dark:bg-gray-900/95 text-xs text-gray-700 dark:text-gray-300 font-semibold px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5 border border-gray-100 dark:border-gray-800">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Untung
                    </span>
                </div>

                <!-- Informasi Bawah -->
                <h3 class="text-[#207a44] dark:text-emerald-400 font-bold text-xl md:text-2xl mb-2 text-center">
                    Belanja Lebih Hemat
                </h3>
                <p class="text-gray-500 dark:text-gray-400 text-xs md:text-sm text-center max-w-xs leading-relaxed">
                    Dapatkan keuntungan lebih sebagai anggota koperasi kami yang terpercaya.
                </p>
            </div>
        </div>

        <!-- Sisi Kanan (Formulir Login) -->
        <div class="w-full md:w-1/2 p-8 md:p-10 bg-white dark:bg-gray-900 flex flex-col justify-between">
            <div>
                <!-- Brand / Logo Atas -->
                <div class="text-center md:text-right mb-4">
                    <span class="text-[#207a44] dark:text-emerald-500 text-xl font-bold tracking-tight">Koperasi 6G</span>
                </div>

                <!-- Judul Selamat Datang -->
                <div class="mb-6">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        Selamat Datang! <span class="text-yellow-500">👋</span>
                    </h2>
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Masuk untuk mulai belanja kebutuhan Anda
                    </p>
                </div>

                <!-- Tampilan Alert Error Laravel -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 p-3 rounded-xl text-xs border border-red-100 dark:border-red-900/40">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulir Input -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Input Username/Email -->
                    <div>
                        <label for="username" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Username atau Email
                        </label>
                        <div class="relative flex items-center">
                            <!-- Ikon User -->
                            <span class="absolute left-3.5 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700
                                          bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                                          focus:ring-2 focus:ring-[#207a44]/20 focus:border-[#207a44] outline-none
                                          transition text-sm"
                                   placeholder="Contoh: member123 atau email@koperasi.com">
                        </div>
                    </div>

                    <!-- Input Kata Sandi -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative flex items-center">
                            <!-- Ikon Gembok -->
                            <span class="absolute left-3.5 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" required
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700
                                          bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400
                                          focus:ring-2 focus:ring-[#207a44]/20 focus:border-[#207a44] outline-none
                                          transition text-sm"
                                   placeholder="Masukkan kata sandi">
                            
                            <!-- Toggle Lihat Password (Ikon Mata) -->
                            <button type="button" id="togglePassword" class="absolute right-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none">
                                <i id="eyeIcon" class="fa-regular fa-eye text-sm text-gray-400"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Ingat Saya & Lupa Kata Sandi -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 text-gray-600 dark:text-gray-400 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-700 text-[#207a44] focus:ring-[#207a44]" />
                            Ingat Saya
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[#ca6a3b] dark:text-[#ea7d4b] hover:underline font-semibold">
                                Lupa Kata Sandi?
                            </a>
                        @else
                            <a href="{{ route('lupa-password') }}" class="text-[#ca6a3b] dark:text-[#ea7d4b] hover:underline font-semibold">
                                Lupa Kata Sandi?
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Masuk -->
                    <button type="submit"
                            class="w-full mt-2 py-3 bg-[#207a44] hover:bg-[#195f34] text-white font-semibold rounded-xl text-sm shadow-md transition-colors duration-150">
                        Masuk
                    </button>
                </form>

                <!-- Divider Pilihan Masuk Lainnya -->
                <div class="relative flex py-5 items-center">
                    <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                    <span class="flex-shrink mx-4 text-gray-400 dark:text-gray-500 text-[11px] font-medium tracking-wide">atau masuk dengan</span>
                    <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
                </div>

                <!-- Tombol Google & Facebook -->
                <div class="flex gap-4">
                    <!-- Google -->
                    <a href="#" class="flex-1 flex items-center justify-center gap-2 border border-gray-200 dark:border-gray-800 py-2 rounded-xl text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4" alt="Google">
                        Google
                    </a>
                    <!-- Facebook -->
                    <a href="#" class="flex-1 flex items-center justify-center gap-2 border border-gray-200 dark:border-gray-800 py-2 rounded-xl text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-4 h-4" alt="Facebook">
                        Facebook
                    </a>
                </div>
            </div>

            <!-- Banner Penawaran Khusus & Daftar Akun -->
            <div class="mt-6">
                <!-- Promo Banner -->
                <div class="bg-[#ebf6ef] dark:bg-emerald-950/20 border border-[#d2eadb] dark:border-emerald-900/40 rounded-xl p-3 flex items-start gap-2.5 text-xs text-[#1e5c33] dark:text-emerald-400 leading-relaxed">
                    <span class="text-sm">🎁</span>
                    <p>
                        Daftar sekarang dan dapatkan voucher <strong>Rp 25.000</strong> untuk belanja pertama!
                    </p>
                </div>

                <!-- Tautan Daftar -->
                <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-5">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-[#207a44] dark:text-emerald-400 hover:underline">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>

    </div>

    <!-- Copyright Footer -->
    <div class="text-center text-xs text-gray-400 dark:text-gray-600 mt-4">
        &copy; 2026 Koperasi 6G - Untung Bersama
    </div>
</div>

<!-- Logika JS Sederhana untuk Toggle Iklan/Sandi Aktif -->
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Ubah bentuk ikon FontAwesome mata saat ditekan
        if (type === 'password') {
            eyeIcon.className = 'fa-regular fa-eye text-sm text-gray-400';
        } else {
            eyeIcon.className = 'fa-regular fa-eye-slash text-sm text-gray-400';
        }
    });
</script>
@endsection
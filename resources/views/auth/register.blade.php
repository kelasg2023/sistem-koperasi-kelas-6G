<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Koperasi 6G</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#EFF6F2] min-h-screen flex flex-col justify-between">

    <!-- ========================================== -->
    <!-- HEADER / NAVIGATION BAR                    -->
    <!-- ========================================== -->
    <header class="bg-white border-b border-gray-100 shadow-sm px-3 sm:px-4 md:px-8 py-3 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-2 sm:gap-4">
            
            <!-- Logo -->
            <a href="/" class="text-[#2D7A42] font-extrabold text-lg sm:text-2xl tracking-tight shrink-0">
                Koperasi <span class="text-gray-900">6G</span>
            </a>

           <!-- Menu Links (Desktop) -->
<nav class="hidden xl:flex items-center gap-6 text-sm font-semibold text-gray-600">
    <a href="/" class="hover:text-[#2D7A42] transition">Beranda</a>
    <a href="/#produk-kategori" class="hover:text-[#2D7A42] transition">Produk</a>
    <a href="/#promo-hari-ini" class="hover:text-[#2D7A42] transition">Promo</a>
    <a href="/#tentang-kami" class="hover:text-[#2D7A42] transition">Tentang Kami</a>
    <a href="/#lokasi-toko" class="hover:text-[#2D7A42] transition">Lokasi Toko</a>
    <a href="/#kontak" class="hover:text-[#2D7A42] transition">Kontak</a>
</nav>

            <!-- Search & Actions -->
            <div class="flex items-center gap-2 sm:gap-4 flex-1 max-w-md justify-end xl:flex-none">
                <!-- Search Bar -->
                <div class="relative w-full max-w-[240px] hidden sm:block">
                    <input type="text" placeholder="Cari kebutuhan harian..." 
                           class="w-full pl-4 pr-10 py-2 bg-gray-100 border border-transparent rounded-full text-xs focus:bg-white focus:border-gray-200 focus:outline-none focus:ring-0 transition">
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Cart Icon -->
                <a href="#" class="hidden sm:inline-flex text-gray-600 hover:text-[#2D7A42] transition relative shrink-0" aria-label="Keranjang">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>

                <!-- User Profile / Member Login -->
                <a href="#" class="hidden sm:inline-flex text-gray-600 hover:text-[#2D7A42] transition shrink-0" aria-label="Profil">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>

                <!-- Masuk Button -->
                <a href="/login" class="px-3.5 sm:px-5 py-2 bg-[#0D5C34] hover:bg-emerald-950 text-white font-bold text-[11px] sm:text-xs md:text-sm rounded-full shadow-sm transition shrink-0 whitespace-nowrap">
                    Masuk<span class="hidden sm:inline"> Member</span>
                </a>
            </div>

        </div>
    </header>

    <!-- ========================================== -->
    <!-- CONTENT CONTAINER                          -->
    <!-- ========================================== -->
    <main class="flex-grow flex flex-col items-center justify-center py-8 sm:py-12 px-3 sm:px-4">
        
        <!-- ========================================== -->
        <!-- STEPPER (INDICATOR)                        -->
        <!-- ========================================== -->
        <div class="w-full max-w-xl mb-6 sm:mb-10 relative">
            <!-- Garis Penghubung antar step -->
            <div class="absolute top-4 sm:top-5 left-[12%] right-[12%] h-0.5 bg-gray-200 z-0"></div>
            <!-- Progress Line (Active Step 1 ke 2) -->
            <div class="absolute top-4 sm:top-5 left-[12%] w-[25%] h-0.5 bg-[#0D5C34] z-0"></div>

            <div class="relative flex justify-between z-10">
                <!-- Step 1: Data Akun (Active) -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#0D5C34] text-white flex items-center justify-center font-bold text-sm shadow">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-[9px] sm:text-xs font-bold text-[#0D5C34] mt-1.5 sm:mt-2 leading-tight">Data Akun</span>
                </div>

                <!-- Step 2: Data Diri (Inactive) -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white border-2 border-gray-200 text-gray-400 flex items-center justify-center font-bold text-sm shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-[9px] sm:text-xs font-semibold text-gray-400 mt-1.5 sm:mt-2 leading-tight">Data Diri</span>
                </div>

                <!-- Step 3: Verifikasi (Inactive) -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white border-2 border-gray-200 text-gray-400 flex items-center justify-center font-bold text-sm shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-[9px] sm:text-xs font-semibold text-gray-400 mt-1.5 sm:mt-2 leading-tight">Verifikasi</span>
                </div>

                <!-- Step 4: Selesai (Inactive) -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white border-2 border-gray-200 text-gray-400 flex items-center justify-center font-bold text-sm shadow-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <span class="text-[9px] sm:text-xs font-semibold text-gray-400 mt-1.5 sm:mt-2 leading-tight">Selesai</span>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MAIN CARD FORM                             -->
        <!-- ========================================== -->
        <div class="w-full max-w-2xl bg-white rounded-2xl sm:rounded-[2rem] shadow-sm p-5 sm:p-8 md:p-12 border border-gray-100/50">
            
            <!-- Card Header -->
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-[#0D5C34] tracking-tight">
                    Buat Akun Koperasi 6G
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1.5">
                    Isi informasi akun untuk mulai berbelanja
                </p>
            </div>

            <!-- Laravel Form -->
            <form action="{{ route('register.store') }}" method="POST" class="space-y-5 sm:space-y-6">
                @error('register')
    <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
    </div>
@enderror
                @csrf

                <!-- Input Nama Lengkap -->
                <div class="space-y-1.5">
                    <label for="name" class="text-xs sm:text-sm font-semibold text-gray-600 block">
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Lisa Manobal" 
                           class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
                    @error('name')
                        <p class="text-red-500 text-[11px] font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Username -->
<div class="space-y-1.5 mt-4">
    <label for="username" class="text-xs sm:text-sm font-semibold text-gray-600 block">
        Username
    </label>
    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Contoh: ardian_putra" 
           class="w-full px-4 py-3 border @error('username') border-red-500 @else border-gray-300 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
    @error('username')
        <p class="text-red-500 text-[11px] font-medium mt-1">{{ $message }}</p>
    @enderror
</div>

                <!-- Input Row (Email & Nomor HP) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs sm:text-sm font-semibold text-gray-600 block">
                            Email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" 
                               class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
                        @error('email')
                            <p class="text-red-500 text-[11px] font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP dengan Prefix -->
                    <div class="space-y-1.5">
                        <label for="phone" class="text-xs sm:text-sm font-semibold text-gray-600 block">
                            Nomor HP
                        </label>
                        <div class="flex rounded-xl border @error('phone') border-red-500 @else border-gray-300 @enderror overflow-hidden focus-within:ring-2 focus-within:ring-[#0D5C34]/20 focus-within:border-[#0D5C34] transition">
                            <span class="inline-flex items-center px-3 sm:px-4 bg-gray-50 border-r border-gray-200 text-gray-500 text-xs sm:text-sm font-semibold select-none">
                                +62
                            </span>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="8123456789" 
                                   class="flex-1 min-w-0 px-3 sm:px-4 py-3 text-xs sm:text-sm focus:outline-none bg-transparent">
                        </div>
                        @error('phone')
                            <p class="text-red-500 text-[11px] font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

               <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    
    <div class="space-y-1.5">
        <label for="password" class="text-xs sm:text-sm font-semibold text-gray-600 block">
            Password
        </label>
        
        <div class="relative">
            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" 
                   class="w-full px-4 py-3 pr-12 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
            
            <button type="button" onclick="togglePassword('password', 'eye-pass', 'eye-slash-pass')" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                <i id="eye-pass" class="fa-regular fa-eye text-sm text-gray-400"></i>
                <i id="eye-slash-pass" class="fa-regular fa-eye-slash text-sm text-gray-400 hidden"></i>
            </button>
        </div>
        
        <div class="flex gap-1 mt-2 px-0.5">
            <span id="strength-bar-1" class="h-1 flex-1 rounded bg-gray-200 transition-colors"></span>
            <span id="strength-bar-2" class="h-1 flex-1 rounded bg-gray-200 transition-colors"></span>
            <span id="strength-bar-3" class="h-1 flex-1 rounded bg-gray-200 transition-colors"></span>
        </div>
        @error('password')
            <p class="text-red-500 text-[11px] font-medium mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-1.5">
        <label for="password_confirmation" class="text-xs sm:text-sm font-semibold text-gray-600 block">
            Konfirmasi Password
        </label>
        
        <div class="relative">
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" 
                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
            
            <button type="button" onclick="togglePassword('password_confirmation', 'eye-conf', 'eye-slash-conf')" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                <i id="eye-conf" class="fa-regular fa-eye text-sm text-gray-400"></i>
                <i id="eye-slash-conf" class="fa-regular fa-eye-slash text-sm text-gray-400 hidden"></i>
            </button>
        </div>
    </div>
</div>

                <!-- ========================================== -->
                <!-- TOGGLE SWITCH BOX (Daftar Anggota)        -->
                <!-- ========================================== -->
                <div class="bg-[#F5F5F5] rounded-2xl p-3.5 sm:p-4 flex items-center justify-between gap-3 sm:gap-4">
                    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                        <!-- Icon Komunitas Bulat -->
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#0D5C34] text-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">
                                Daftar sebagai Anggota Koperasi
                            </h4>
                            <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">
                                Dapatkan keuntungan lebih & dividen
                            </p>
                        </div>
                    </div>

                    <!-- Toggle Switch & Info Icon -->
                    <div class="flex items-center gap-2 shrink-0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="register_as_member" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0D5C34]"></div>
                        </label>
                        <button type="button" class="hidden sm:inline-flex text-gray-400 hover:text-gray-600 transition" aria-label="Informasi">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#0D5C34] hover:bg-emerald-950 active:scale-[0.98] text-white font-extrabold py-3.5 rounded-2xl shadow-sm hover:shadow-md transition text-center text-xs sm:text-sm">
                    Lanjutkan
                </button>
                
            </form>
        </div>
    </main>

    <script>
    function togglePassword(inputId, iconEyeId, iconEyeSlashId) {
        const inputField = document.getElementById(inputId);
        const iconEye = document.getElementById(iconEyeId);
        const iconEyeSlash = document.getElementById(iconEyeSlashId);

        if (inputField.type === "password") {
            // Ubah ke teks & ganti icon ke icon coret
            inputField.type = "text";
            iconEye.classList.add("hidden");
            iconEyeSlash.classList.remove("hidden");
        } else {
            // Kembalikan ke password & ganti icon ke icon mata
            inputField.type = "password";
            iconEye.classList.remove("hidden");
            iconEyeSlash.classList.add("hidden");
        }
    }

    // Password Strength Meter
    const passInput = document.getElementById('password');
    const bar1 = document.getElementById('strength-bar-1');
    const bar2 = document.getElementById('strength-bar-2');
    const bar3 = document.getElementById('strength-bar-3');

    if(passInput) {
        passInput.addEventListener('input', function() {
            const val = passInput.value;
            let strength = 0;

            if (val.length >= 8) strength += 1;
            if (val.match(/[A-Z]/) && val.match(/[a-z]/)) strength += 1;
            if (val.match(/[0-9]/) || val.match(/[\W]/)) strength += 1;

            bar1.className = 'h-1 flex-1 rounded bg-gray-200 transition-colors';
            bar2.className = 'h-1 flex-1 rounded bg-gray-200 transition-colors';
            bar3.className = 'h-1 flex-1 rounded bg-gray-200 transition-colors';

            if (val.length === 0) return;

            if (strength === 1) {
                bar1.classList.replace('bg-gray-200', 'bg-red-500');
            } else if (strength === 2) {
                bar1.classList.replace('bg-gray-200', 'bg-yellow-400');
                bar2.classList.replace('bg-gray-200', 'bg-yellow-400');
            } else if (strength >= 3) {
                bar1.classList.replace('bg-gray-200', 'bg-[#0D5C34]');
                bar2.classList.replace('bg-gray-200', 'bg-[#0D5C34]');
                bar3.classList.replace('bg-gray-200', 'bg-[#0D5C34]');
            }
        });
    }
</script>

    <!-- Footer Copyright Sederhana -->
    <footer class="py-6 text-center text-[11px] text-gray-400 px-4">
        &copy; 2026 Koperasi 6G. Hak Cipta Dilindungi.
    </footer>

    @include('templates.toast')
</body>
</html>
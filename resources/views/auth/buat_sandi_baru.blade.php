<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kata Sandi Baru - Koperasi 6G</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="bg-gray-50 font-sans antialiased text-gray-800 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white max-w-md w-full rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 relative">
        
        <div class="mx-auto w-14 h-14 bg-green-50 rounded-full flex items-center justify-center mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-[#0D5C34]">
                <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
            </svg>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-xl font-bold text-gray-900 mb-2">Buat Kata Sandi Baru</h1>
            <p class="text-xs text-gray-500 leading-relaxed px-2">
                Langkah terakhir untuk mengamankan kembali akun Koperasi 6G Anda.
            </p>
        </div>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="mb-4">
                <label for="password" class="block text-[11px] font-bold text-gray-700 mb-1.5">Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi baru" 
                           class="w-full pl-4 pr-10 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
                    
                    <button type="button" onclick="togglePassword('password', 'eye-pass', 'eye-slash-pass')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i id="eye-pass" class="fa-regular fa-eye text-sm"></i>
                        <i id="eye-slash-pass" class="fa-regular fa-eye-slash text-sm hidden"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[11px] text-gray-500">Kekuatan: <span id="strength-text" class="text-gray-400">Sangat Lemah</span></span>
                </div>
                <div class="h-1 w-full bg-gray-100 rounded-full overflow-hidden flex gap-1">
                    <div id="bar-1" class="h-full w-1/4 bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar-2" class="h-full w-1/4 bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar-3" class="h-full w-1/4 bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar-4" class="h-full w-1/4 bg-gray-200 transition-colors duration-300"></div>
                </div>
            </div>

            <div class="bg-[#fafafa] rounded-xl p-4 mb-6">
                <p class="text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-3">Persyaratan Keamanan</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2.5 gap-x-2 text-[11px] text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <svg id="req-length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 text-gray-300 transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Minimal 8 Karakter
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg id="req-case" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 text-gray-300 transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Huruf Besar & Kecil
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg id="req-num" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 text-gray-300 transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Minimal 1 Angka
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg id="req-special" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 text-gray-300 transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Karakter Spesial (@#!)
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-[11px] font-bold text-gray-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi baru" 
                           class="w-full pl-4 pr-10 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
                    
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-conf', 'eye-slash-conf')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i id="eye-conf" class="fa-regular fa-eye text-sm"></i>
                        <i id="eye-slash-conf" class="fa-regular fa-eye-slash text-sm hidden"></i>
                    </button>
                </div>
                <p id="match-error" class="text-red-500 text-[10px] font-medium mt-1.5 hidden">Kata sandi tidak cocok.</p>
            </div>

            <button type="submit" id="submit-btn" class="w-full bg-[#85A98F] hover:bg-[#6f9279] text-white font-medium py-3 rounded-xl transition text-sm mb-6 disabled:opacity-70 disabled:cursor-not-allowed">
                Simpan Kata Sandi Baru
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('preview-otp') }}" class="text-[11px] font-bold text-[#0D5C34] hover:underline">
                Kembali ke verifikasi
            </a>
        </div>
    </div>

    <script>
        // Fungsi Toggle Show/Hide Password
        function togglePassword(inputId, iconEyeId, iconEyeSlashId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(iconEyeId);
            const eyeSlash = document.getElementById(iconEyeSlashId);

            if (input.type === "password") {
                input.type = "text";
                eye.classList.add("hidden");
                eyeSlash.classList.remove("hidden");
            } else {
                input.type = "password";
                eye.classList.remove("hidden");
                eyeSlash.classList.add("hidden");
            }
        }

        // Fungsi Validasi & Kekuatan Sandi
        document.addEventListener('DOMContentLoaded', function() {
            const passInput = document.getElementById('password');
            const confInput = document.getElementById('password_confirmation');
            const matchError = document.getElementById('match-error');
            const submitBtn = document.getElementById('submit-btn');

            // Elemen Persyaratan
            const reqLength = document.getElementById('req-length');
            const reqCase = document.getElementById('req-case');
            const reqNum = document.getElementById('req-num');
            const reqSpecial = document.getElementById('req-special');

            // Elemen Kekuatan Bar
            const bar1 = document.getElementById('bar-1');
            const bar2 = document.getElementById('bar-2');
            const bar3 = document.getElementById('bar-3');
            const bar4 = document.getElementById('bar-4');
            const strengthText = document.getElementById('strength-text');

            function checkStrength(password) {
                let strength = 0;

                // Cek 8 Karakter
                if (password.length >= 8) {
                    reqLength.classList.replace('text-gray-300', 'text-[#0D5C34]');
                    strength++;
                } else {
                    reqLength.classList.replace('text-[#0D5C34]', 'text-gray-300');
                }

                // Cek Huruf Besar & Kecil
                if (/(?=.*[a-z])(?=.*[A-Z])/.test(password)) {
                    reqCase.classList.replace('text-gray-300', 'text-[#0D5C34]');
                    strength++;
                } else {
                    reqCase.classList.replace('text-[#0D5C34]', 'text-gray-300');
                }

                // Cek Angka
                if (/\d/.test(password)) {
                    reqNum.classList.replace('text-gray-300', 'text-[#0D5C34]');
                    strength++;
                } else {
                    reqNum.classList.replace('text-[#0D5C34]', 'text-gray-300');
                }

                // Cek Karakter Spesial
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    reqSpecial.classList.replace('text-gray-300', 'text-[#0D5C34]');
                    strength++;
                } else {
                    reqSpecial.classList.replace('text-[#0D5C34]', 'text-gray-300');
                }

                // Update UI Bar Kekuatan
                // Reset warna
                [bar1, bar2, bar3, bar4].forEach(bar => bar.className = 'h-full w-1/4 bg-gray-200 transition-colors duration-300');
                
                if (password.length === 0) {
                    strengthText.textContent = "Sangat Lemah";
                    strengthText.className = "text-gray-400 font-semibold";
                } else if (strength === 1) {
                    bar1.classList.replace('bg-gray-200', 'bg-red-500');
                    strengthText.textContent = "Lemah";
                    strengthText.className = "text-red-500 font-semibold";
                } else if (strength === 2) {
                    bar1.classList.replace('bg-gray-200', 'bg-orange-400');
                    bar2.classList.replace('bg-gray-200', 'bg-orange-400');
                    strengthText.textContent = "Sedang";
                    strengthText.className = "text-orange-400 font-semibold";
                } else if (strength === 3) {
                    bar1.classList.replace('bg-gray-200', 'bg-green-400');
                    bar2.classList.replace('bg-gray-200', 'bg-green-400');
                    bar3.classList.replace('bg-gray-200', 'bg-green-400');
                    strengthText.textContent = "Kuat";
                    strengthText.className = "text-green-500 font-semibold";
                } else if (strength === 4) {
                    bar1.classList.replace('bg-gray-200', 'bg-[#0D5C34]');
                    bar2.classList.replace('bg-gray-200', 'bg-[#0D5C34]');
                    bar3.classList.replace('bg-gray-200', 'bg-[#0D5C34]');
                    bar4.classList.replace('bg-gray-200', 'bg-[#0D5C34]');
                    strengthText.textContent = "Sangat Kuat";
                    strengthText.className = "text-[#0D5C34] font-bold";
                }

                return strength;
            }

            function validateMatch() {
                const pass = passInput.value;
                const conf = confInput.value;
                
                if (conf.length > 0 && pass !== conf) {
                    matchError.classList.remove('hidden');
                    return false;
                } else {
                    matchError.classList.add('hidden');
                    return conf.length > 0 && pass === conf;
                }
            }

            // Event Listeners
            passInput.addEventListener('input', function() {
                checkStrength(this.value);
                validateMatch();
            });

            confInput.addEventListener('input', validateMatch);
        });
    </script>
    @include('templates.toast')
</body>
</html>
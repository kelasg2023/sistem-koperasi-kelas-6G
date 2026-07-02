<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Koperasi 6G</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <div class="min-h-screen bg-white max-w-md mx-auto relative flex flex-col shadow-sm">
        
        <div class="p-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#0D5C34] hover:text-green-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Masuk
            </a>
        </div>

        <div class="flex-1 px-6 flex flex-col items-center pt-4">
            
            <div class="relative w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 border border-green-100 shadow-sm">
                <svg class="absolute -top-1 -left-2 w-6 h-6 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 text-[#0D5C34]">
                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
                </svg>
                <div class="absolute -bottom-1 -right-1 bg-amber-600 rounded p-1 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-white">
                        <path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Lupa Kata Sandi?</h1>
            <p class="text-sm text-gray-500 text-center mb-8 px-4 leading-relaxed">
                Jangan khawatir, masukkan identitas Anda untuk mengatur ulang kata sandi.
            </p>

            <div class="w-full flex bg-gray-100 rounded-full p-1 mb-6">
                <button type="button" id="tab-email" onclick="switchTab('email')" class="flex-1 py-2.5 text-sm font-semibold rounded-full bg-[#0D5C34] text-white shadow transition-all duration-300">
                    Email
                </button>
                <button type="button" id="tab-sms" onclick="switchTab('sms')" class="flex-1 py-2.5 text-sm font-semibold rounded-full text-gray-500 hover:text-gray-700 transition-all duration-300">
                    SMS / WhatsApp
                </button>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="w-full w-full block" id="form-email">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" placeholder="nama@email.com" class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#0D5C34] hover:bg-green-800 text-white font-medium py-3.5 rounded-xl transition flex items-center justify-center gap-2 text-sm shadow-md shadow-green-900/10">
                    Kirim Kode Verifikasi
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>

            <form action="#" method="POST" class="w-full hidden" id="form-sms">
                @csrf
                <div class="mb-5">
                    <label for="phone" class="block text-xs font-semibold text-gray-600 mb-1.5">Nomor Handphone</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-sm text-gray-500 font-medium">+62</span>
                        </div>
                        <input type="text" name="phone" id="phone" placeholder="81234567890" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/20 focus:border-[#0D5C34] transition">
                    </div>
                </div>

                <button type="button" class="w-full bg-[#0D5C34] hover:bg-green-800 text-white font-medium py-3.5 rounded-xl transition flex items-center justify-center gap-2 text-sm shadow-md shadow-green-900/10">
                    Kirim Kode OTP
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                Butuh bantuan lebih lanjut? 
                <a href="#" class="text-[#0D5C34] font-semibold hover:underline">Hubungi CS</a>
            </div>
        </div>

        <div class="py-6 border-t border-gray-200 bg-gray-50 flex items-center justify-center gap-1.5 text-xs text-gray-400 mt-auto">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-[#0D5C34]">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Koperasi 6G - Untung Bersama
</div>

    </div>

    <script>
        function switchTab(tab) {
            const btnEmail = document.getElementById('tab-email');
            const btnSms = document.getElementById('tab-sms');
            const formEmail = document.getElementById('form-email');
            const formSms = document.getElementById('form-sms');

            // Reset gaya tombol
            btnEmail.className = 'flex-1 py-2.5 text-sm font-semibold rounded-full text-gray-500 hover:text-gray-700 transition-all duration-300';
            btnSms.className = 'flex-1 py-2.5 text-sm font-semibold rounded-full text-gray-500 hover:text-gray-700 transition-all duration-300';
            
            // Sembunyikan semua form
            formEmail.classList.add('hidden');
            formSms.classList.add('hidden');

            // Aktifkan tab yang dipilih
            if (tab === 'email') {
                btnEmail.className = 'flex-1 py-2.5 text-sm font-semibold rounded-full bg-[#0D5C34] text-white shadow transition-all duration-300';
                formEmail.classList.remove('hidden');
            } else if (tab === 'sms') {
                btnSms.className = 'flex-1 py-2.5 text-sm font-semibold rounded-full bg-[#0D5C34] text-white shadow transition-all duration-300';
                formSms.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kata Sandi Berhasil Diubah - Koperasi 6G</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="bg-gray-50 font-sans antialiased text-gray-800 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white max-w-sm w-full rounded-2xl shadow-sm border border-gray-100 p-8 relative text-center">
        
        <div class="mx-auto w-16 h-16 bg-[#eaf5eb] rounded-full flex items-center justify-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-[#0D5C34]">
                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" />
            </svg>
        </div>

        <h1 class="text-[17px] font-bold text-gray-900 mb-3">Kata Sandi Berhasil Diubah!</h1>
        <p class="text-[12px] text-gray-500 leading-relaxed mb-8 px-1">
            Silakan masuk kembali dengan menggunakan kata sandi baru Anda untuk melanjutkan belanja kebutuhan pokok.
        </p>

        <div class="mb-6 text-left">
            <p class="text-[11px] text-gray-500 font-semibold mb-2" id="countdown-text">
                Mengarahkan ke halaman masuk dalam <span id="countdown-number">5</span> detik...
            </p>
            <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                <div id="progress-bar" class="h-full bg-[#0D5C34] w-full" style="transition: width 1s linear;"></div>
            </div>
        </div>

        <a href="{{ route('login') }}" class="w-full bg-[#0D5C34] hover:bg-green-800 text-white font-medium py-3 rounded-xl transition flex items-center justify-center gap-2 text-sm shadow-md shadow-green-900/10 mb-6">
            Masuk Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
        </a>

        <div class="text-[11px] text-gray-500">
            Ada kendala? <a href="#" class="font-bold text-[#0D5C34] hover:underline">Hubungi Layanan Anggota</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 5; // Waktu hitung mundur (detik)
            const countdownNumber = document.getElementById('countdown-number');
            const progressBar = document.getElementById('progress-bar');
            
            // URL tujuan setelah timer habis (Pastikan route 'login' tersedia di web.php)
            const loginUrl = "{{ route('login') }}"; 

            // Trigger animasi CSS menyusut perlahan
            setTimeout(() => {
                progressBar.style.transitionDuration = '5s';
                progressBar.style.width = '0%';
            }, 100);

            // Interval untuk mengubah angka teks
            const timer = setInterval(() => {
                timeLeft--;
                countdownNumber.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    window.location.href = loginUrl; // Arahkan ke halaman login
                }
            }, 1000);
        });
    </script>
    @include('templates.toast')
</body>
</html>
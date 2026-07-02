<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Koperasi 6G</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <div class="min-h-screen bg-white max-w-md mx-auto relative flex flex-col justify-center px-6 py-12 shadow-sm">
        
        <div class="mx-auto w-20 h-20 bg-[#a6f792] rounded-full flex items-center justify-center mb-6 relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-[#0D5C34]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-amber-700 absolute top-4 left-3 transform -rotate-45">
                <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.896 28.896 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z" />
            </svg>
        </div>

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi Kode OTP</h1>
            <p class="text-sm text-gray-500 leading-relaxed px-4">
                Masukkan 6 digit kode yang telah kami kirimkan ke nomor WhatsApp Anda.
            </p>
        </div>

        <div class="bg-orange-100 text-orange-800 text-xs font-medium py-3 px-4 rounded-lg flex items-center justify-center gap-2 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            Kode berlaku selama 10 menit
        </div>

        <form action="#" method="POST">
            @csrf
            
            <div class="flex justify-between gap-2 mb-6" id="otp-container">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-semibold text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/30 focus:border-[#0D5C34] transition bg-gray-50" autofocus>
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-semibold text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/30 focus:border-[#0D5C34] transition bg-gray-50">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-semibold text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/30 focus:border-[#0D5C34] transition bg-gray-50">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-semibold text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/30 focus:border-[#0D5C34] transition bg-gray-50">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-semibold text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/30 focus:border-[#0D5C34] transition bg-gray-50">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-semibold text-gray-800 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0D5C34]/30 focus:border-[#0D5C34] transition bg-gray-50">
                
                <input type="hidden" name="otp_code" id="otp_hidden">
            </div>

            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-1 text-gray-500 text-sm mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="timer">01:30</span>
                </div>
                <p class="text-sm text-gray-500">
                    Tidak menerima kode? <button type="button" class="text-gray-800 font-semibold hover:underline cursor-not-allowed opacity-50" id="resend-btn" disabled>Kirim Ulang</button>
                </p>
            </div>

            <button type="submit" id="submit-btn" class="w-full bg-[#35823c] hover:bg-green-800 text-white font-medium py-3.5 rounded-xl transition text-sm shadow-md shadow-green-900/10 mb-6">
                Konfirmasi Kode
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('lupa-password') }}" class="inline-flex items-center justify-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Ganti Nomor / Email
            </a>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otp_hidden');

            // 1. Logika Input OTP (Pindah Otomatis)
            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    // Hanya izinkan angka
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                    
                    if (e.target.value !== '') {
                        // Pindah ke kotak berikutnya jika ada
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    }
                    updateHiddenInput();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && e.target.value === '') {
                        // Mundur ke kotak sebelumnya jika kosong
                        if (index > 0) {
                            inputs[index - 1].focus();
                        }
                    }
                });

                // Mendukung Paste (Salin Tempel)
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, inputs.length);
                    
                    pastedData.split('').forEach((char, i) => {
                        if (i < inputs.length) {
                            inputs[i].value = char;
                        }
                    });
                    
                    // Fokus ke kotak terakhir yang terisi
                    const focusIndex = Math.min(pastedData.length, inputs.length - 1);
                    inputs[focusIndex].focus();
                    updateHiddenInput();
                });
            });

            function updateHiddenInput() {
                let otpValue = '';
                inputs.forEach(input => {
                    otpValue += input.value;
                });
                hiddenInput.value = otpValue;
            }

            // 2. Logika Countdown Timer
            let timeLeft = 90; // 90 detik = 1 menit 30 detik
            const timerElement = document.getElementById('timer');
            const resendBtn = document.getElementById('resend-btn');

            const countdown = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    timerElement.textContent = "00:00";
                    timerElement.classList.add("text-red-500");
                    
                    // Aktifkan tombol kirim ulang
                    resendBtn.classList.remove('cursor-not-allowed', 'opacity-50');
                    resendBtn.classList.add('text-[#0D5C34]');
                    resendBtn.disabled = false;
                } else {
                    let minutes = Math.floor(timeLeft / 60);
                    let seconds = timeLeft % 60;
                    
                    // Format waktu agar selalu 2 digit
                    timerElement.textContent = `0${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    timeLeft -= 1;
                }
            }, 1000);
        });
    </script>
</body>
</html>
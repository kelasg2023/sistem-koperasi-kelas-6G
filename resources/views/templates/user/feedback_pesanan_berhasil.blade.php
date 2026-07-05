@extends('layouts.app')

@section('content')
<div class="w-full px-4 py-8 lg:py-12 max-w-2xl mx-auto flex flex-col items-center">
    
    {{-- ========================================== --}}
    {{-- 1. HEADER SUCCESS ANIMATION                --}}
    {{-- ========================================== --}}
    <div class="text-center mb-8">
        <div class="relative inline-block mb-4">
            <div class="absolute inset-0 bg-[#2D7A42] rounded-full animate-ping opacity-20"></div>
            <div class="w-20 h-20 bg-[#E8F5EC] rounded-full flex items-center justify-center text-[#2D7A42] text-4xl shadow-sm border-4 border-white relative z-10 mx-auto">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">Pembelian Berhasil!</h1>
        <p class="text-gray-500 text-sm">Terima kasih atas partisipasi Anda membangun Koperasi.</p>
    </div>

    {{-- ========================================== --}}
    {{-- 2. STRUK DIGITAL (RECEIPT)                 --}}
    {{-- ========================================== --}}
    <div class="w-full bg-white shadow-lg rounded-b-2xl rounded-t-md relative overflow-hidden mb-6 border border-gray-100">
        {{-- Aksen Header Struk --}}
        <div class="h-2 w-full bg-[#2D7A42]"></div>
        
        <div class="p-6 sm:p-8">
            {{-- Header Koperasi --}}
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-gray-50 text-[#2D7A42] rounded-full flex items-center justify-center text-xl mx-auto mb-3 border border-gray-100">
                    <i class="fa-solid fa-people-group"></i>
                </div>
                <h2 class="text-lg font-extrabold text-gray-900 tracking-wide uppercase">Koperasi 6G</h2>
                <p class="text-[11px] text-gray-500 mt-1">Jl. Koperasi No. 123, Kota Sejahtera</p>
                <p class="text-[10px] text-gray-400">Telp: (021) 555-1234 | No: 182/BH/M.KUKM</p>
            </div>

            <div class="border-b-2 border-dashed border-gray-200 mb-4"></div>

            {{-- Meta Data Transaksi --}}
            <div class="space-y-2 mb-4 text-xs sm:text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">No. Transaksi</span>
                    <span class="font-mono font-bold text-gray-900">KOP-<?php echo date('Ymd'); ?>-8821</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-medium text-gray-900"><?php echo date('d M Y, H:i'); ?> WIB</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kasir / Terminal</span>
                    <span class="font-medium text-gray-900">Sistem Online / Web</span>
                </div>
                <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-50">
                    <span class="text-gray-500">Metode Bayar</span>
                    <span class="bg-[#E8F5EC] text-[#2D7A42] font-bold px-2 py-1 rounded text-[10px] uppercase">Saldo Anggota</span>
                </div>
            </div>

            <div class="border-b-2 border-dashed border-gray-200 mb-4"></div>

            {{-- Detail Anggota --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-100">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-id-card text-gray-500"></i> Data Anggota
                </div>
                <div class="space-y-1 text-xs sm:text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama</span>
                        <span class="font-bold text-gray-900">Shady</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Anggota</span>
                        <span class="font-mono text-gray-900">ID-ANG-4098</span>
                    </div>
                </div>
            </div>

            <div class="border-b-2 border-dashed border-gray-200 mb-4"></div>

            {{-- Daftar Belanjaan --}}
            <div class="mb-4">
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-100">
                            <th class="pb-2 font-semibold uppercase text-[10px] w-1/2">Barang</th>
                            <th class="pb-2 font-semibold uppercase text-[10px] text-center">Qty</th>
                            <th class="pb-2 font-semibold uppercase text-[10px] text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        <tr>
                            <td class="py-2 pr-2">Beras Sentra Ramos 5kg</td>
                            <td class="py-2 text-center">1</td>
                            <td class="py-2 text-right font-medium">Rp 72.500</td>
                        </tr>
                        <tr>
                            <td class="py-2 pr-2">Minyak Goreng Bimoli 2L</td>
                            <td class="py-2 text-center">2</td>
                            <td class="py-2 text-right font-medium">Rp 76.000</td>
                        </tr>
                        <tr>
                            <td class="py-2 pr-2">Buku Tulis Sidu Pack</td>
                            <td class="py-2 text-center">1</td>
                            <td class="py-2 text-right font-medium">Rp 45.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="border-b-2 border-dashed border-gray-200 mb-4"></div>

            {{-- Rincian Biaya Akhir --}}
            <div class="space-y-2 text-xs sm:text-sm mb-6">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium text-gray-900">Rp 193.500</span>
                </div>
                <div class="flex justify-between text-red-500">
                    <span>Diskon Anggota (5%)</span>
                    <span class="font-medium">- Rp 9.675</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pajak (PPN 11%)</span>
                    <span class="font-medium text-gray-900">Rp 20.220</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-gray-200 mt-2">
                    <span class="font-extrabold text-gray-900 text-sm">TOTAL AKHIR</span>
                    <span class="font-extrabold text-xl text-[#2D7A42]">Rp 204.045</span>
                </div>
            </div>

            {{-- Keuntungan Koperasi --}}
            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-orange-50 rounded-xl p-3 border border-orange-100 flex flex-col justify-center">
                    <span class="text-[10px] text-orange-600 font-bold uppercase mb-1"><i class="fa-solid fa-piggy-bank mr-1"></i> Poin SHU</span>
                    <span class="text-sm font-extrabold text-orange-600">+38 Poin</span>
                </div>
                <div class="bg-blue-50 rounded-xl p-3 border border-blue-100 flex flex-col justify-center">
                    <span class="text-[10px] text-blue-600 font-bold uppercase mb-1"><i class="fa-solid fa-wallet mr-1"></i> Sisa Saldo</span>
                    <span class="text-sm font-extrabold text-blue-600">Rp 1.254.155</span>
                </div>
            </div>

            {{-- Barcode / Footer --}}
            <div class="text-center">
                <p class="text-[11px] font-medium text-gray-500 mb-1">"Koperasi Maju, Anggota Sejahtera"</p>
                <div class="w-48 h-10 bg-gray-200 mx-auto rounded-md flex items-center justify-around px-2 opacity-50 mb-1 overflow-hidden" style="background: repeating-linear-gradient(90deg, #000, #000 2px, transparent 2px, transparent 4px, #000 4px, #000 5px, transparent 5px, transparent 8px);">
                    <!-- Fake Barcode CSS -->
                </div>
                <p class="text-[9px] tracking-[0.2em] font-mono text-gray-400"><?php echo date('Ymd'); ?>8821</p>
            </div>
        </div>
        
        {{-- Efek Kertas Kasir (Zig-Zag Bawah) Menggunakan CSS --}}
        <div class="h-4 w-full" style="background-image: radial-gradient(circle at 10px 10px, transparent 12px, #fff 13px); background-size: 20px 20px; background-position: -10px -10px; transform: rotate(180deg); margin-top: -5px;"></div>
    </div>

    {{-- ========================================== --}}
    {{-- 3. TOMBOL AKSI CETAK & KEMBALI             --}}
    {{-- ========================================== --}}
    <div class="w-full flex flex-col sm:flex-row gap-3 mb-8">
        <button onclick="window.print()" class="flex-1 py-3 bg-white border-2 border-gray-200 text-gray-700 hover:border-[#2D7A42] hover:text-[#2D7A42] font-bold text-sm rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak Struk
        </button>
        <a href="{{ route('dashboard') }}" class="flex-1 py-3 bg-[#2D7A42] text-white hover:bg-[#1E5C2F] font-bold text-sm rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Toko
        </a>
    </div>

    {{-- ========================================== --}}
    {{-- 4. FORM FEEDBACK (SISTEM RATING BINTANG)   --}}
    {{-- ========================================== --}}
    <div class="w-full bg-white border border-gray-100 rounded-2xl shadow-sm p-6 text-center">
        <h3 class="text-base font-bold text-gray-900 mb-1">Bagaimana Pengalaman Belanja Anda?</h3>
        <p class="text-xs text-gray-500 mb-4">Bantu kami meningkatkan pelayanan Koperasi.</p>
        
        {{-- Deretan Bintang --}}
        <div class="flex justify-center gap-2 mb-4" id="star-rating">
            @for ($i = 1; $i <= 5; $i++)
                <button type="button" class="star-btn text-2xl text-gray-300 hover:text-amber-400 transition-colors focus:outline-none" data-rating="{{ $i }}">
                    <i class="fa-solid fa-star"></i>
                </button>
            @endfor
        </div>

        {{-- Form Komentar (Tersembunyi sampai bintang diklik) --}}
        <div id="feedback-form" class="hidden">
            <textarea class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all mb-3" rows="3" placeholder="Tuliskan saran atau masukan Anda (opsional)..."></textarea>
            <button id="submit-feedback" class="w-full py-2.5 bg-gray-800 text-white font-bold text-xs rounded-xl hover:bg-gray-900 transition-colors">
                Kirim Umpan Balik
            </button>
        </div>

        {{-- Pesan Sukses Umpan Balik --}}
        <div id="feedback-success" class="hidden text-sm font-bold text-[#2D7A42] bg-[#E8F5EC] p-3 rounded-xl flex items-center justify-center gap-2">
            <i class="fa-solid fa-circle-check"></i> Terima kasih atas masukan berharga Anda!
        </div>
    </div>

</div>

{{-- SCRIPT UNTUK INTERAKSI BINTANG RATING --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-btn');
        const feedbackForm = document.getElementById('feedback-form');
        const submitBtn = document.getElementById('submit-feedback');
        const successMsg = document.getElementById('feedback-success');
        let currentRating = 0;

        // Logika saat bintang diklik
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                currentRating = rating;

                // Tampilkan form textarea
                feedbackForm.classList.remove('hidden');
                
                // Ubah warna bintang
                stars.forEach(s => {
                    if (s.getAttribute('data-rating') <= rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-amber-400');
                    } else {
                        s.classList.remove('text-amber-400');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });

        // Logika saat form dikirim
        submitBtn.addEventListener('click', function() {
            // Sembunyikan form dan tombol bintang
            feedbackForm.classList.add('hidden');
            document.getElementById('star-rating').classList.add('hidden');
            
            // Tampilkan pesan sukses
            successMsg.classList.remove('hidden');
        });
    });
</script>

{{-- Sembunyikan Header/Navigasi saat dicetak (Print) --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .max-w-2xl, .max-w-2xl * {
            visibility: visible;
        }
        .max-w-2xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        /* Sembunyikan tombol cetak dan form feedback di hasil kertas */
        .flex.flex-col.sm\\:flex-row, .text-center > h3, #star-rating, #feedback-form, #feedback-success, p.text-xs.text-gray-500.mb-4 {
            display: none !important;
        }
        .w-full.bg-white.shadow-lg {
            box-shadow: none !important;
            border: 1px solid #000 !important;
        }
    }
</style>
@endsection
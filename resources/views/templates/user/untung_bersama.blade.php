@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-8">
    
    {{-- Header Halaman --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Untung Bersama</h1>
        <p class="text-gray-500 text-sm">Pantau perkembangan simpanan, poin, dan estimasi Sisa Hasil Usaha (SHU) Anda.</p>
    </div>

    {{-- 3 Kartu Ringkasan Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        {{-- Kartu 1: Total Simpanan --}}
        <div class="bg-gradient-to-br from-[#2D7A42] to-[#1A622A] rounded-2xl p-6 text-white shadow-md relative overflow-hidden">
            <i class="fa-solid fa-wallet absolute -right-4 -bottom-4 text-8xl opacity-20"></i>
            <p class="text-sm font-medium text-green-100 mb-1">Total Simpanan Aktif</p>
            <h2 class="text-3xl font-extrabold mb-4">Rp 2.450.000</h2>
            <div class="flex items-center gap-2 text-xs font-medium bg-white/20 w-fit px-3 py-1.5 rounded-lg backdrop-blur-sm relative z-10">
                <i class="fa-solid fa-arrow-trend-up"></i> Naik 12% dari bulan lalu
            </div>
        </div>

        {{-- Kartu 2: Estimasi SHU --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-[#E8F5EC] text-[#2D7A42] rounded-xl flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Estimasi SHU Tahun Ini</p>
                    <h2 class="text-2xl font-extrabold text-gray-900">Rp 450.000</h2>
                </div>
            </div>
            <p class="text-xs text-gray-400">Didapat dari aktivitas belanja dan simpanan Anda.</p>
        </div>

        {{-- Kartu 3: Poin Koperasi --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Poin Koperasi 6G</p>
                    <h2 class="text-2xl font-extrabold text-gray-900">1,250 Poin</h2>
                </div>
            </div>
            <p class="text-xs text-gray-400">Tukarkan poin dengan potongan harga belanja.</p>
        </div>
    </div>

    {{-- Bagian Detail: Rincian Simpanan & Riwayat --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        
        {{-- Kiri: Rincian Simpanan --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 lg:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">Rincian Simpanan Anda</h3>
            
            <div class="space-y-6">
                {{-- Simpanan Pokok --}}
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-semibold text-gray-700">Simpanan Pokok</span>
                        <span class="font-bold text-[#2D7A42]">Rp 500.000</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-[#2D7A42] h-2 rounded-full" style="width: 100%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Dibayarkan satu kali saat mendaftar.</p>
                </div>

                {{-- Simpanan Wajib --}}
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-semibold text-gray-700">Simpanan Wajib</span>
                        <span class="font-bold text-[#2D7A42]">Rp 1.200.000</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 60%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Dibayarkan rutin setiap bulan (Rp 100.000/bln).</p>
                </div>

                {{-- Simpanan Sukarela --}}
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-semibold text-gray-700">Simpanan Sukarela</span>
                        <span class="font-bold text-[#2D7A42]">Rp 750.000</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-teal-400 h-2 rounded-full" style="width: 35%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Dapat ditarik kapan saja sesuai ketentuan.</p>
                </div>
            </div>

            <button class="w-full mt-8 py-3 rounded-xl border-2 border-[#2D7A42] text-[#2D7A42] font-bold text-sm hover:bg-[#E8F5EC] transition-colors">
                Tambah Simpanan Sukarela
            </button>
        </div>

        {{-- Kanan: Riwayat SHU / Keuntungan Terakhir --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 lg:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-50 pb-4">Riwayat Keuntungan (SHU)</h3>
            
            <div class="space-y-1">
                {{-- Riwayat 1 --}}
                <div class="flex items-start gap-4 py-4 border-b border-gray-50 last:border-0">
                    <div class="w-12 h-12 rounded-full bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center shrink-0 text-lg">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <div class="flex-1 pt-1">
                        <h4 class="text-sm font-bold text-gray-800">Pembagian SHU Tahun 2025</h4>
                        <p class="text-xs text-gray-500 mt-1">Ditransfer ke Simpanan Sukarela</p>
                    </div>
                    <div class="text-right pt-1">
                        <span class="text-sm font-extrabold text-[#2D7A42]">+ Rp 320.000</span>
                        <p class="text-[11px] font-medium text-gray-400 mt-1">20 Jan 2026</p>
                    </div>
                </div>

                {{-- Riwayat 2 --}}
                <div class="flex items-start gap-4 py-4 border-b border-gray-50 last:border-0">
                    <div class="w-12 h-12 rounded-full bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center shrink-0 text-lg">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <div class="flex-1 pt-1">
                        <h4 class="text-sm font-bold text-gray-800">Pembagian SHU Tahun 2024</h4>
                        <p class="text-xs text-gray-500 mt-1">Ditarik ke Rekening Bank</p>
                    </div>
                    <div class="text-right pt-1">
                        <span class="text-sm font-extrabold text-[#2D7A42]">+ Rp 280.500</span>
                        <p class="text-[11px] font-medium text-gray-400 mt-1">22 Jan 2025</p>
                    </div>
                </div>
                
                {{-- Riwayat 3 --}}
                <div class="flex items-start gap-4 py-4 border-b border-gray-50 last:border-0">
                    <div class="w-12 h-12 rounded-full bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center shrink-0 text-lg">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                    <div class="flex-1 pt-1">
                        <h4 class="text-sm font-bold text-gray-800">Pembagian SHU Tahun 2023</h4>
                        <p class="text-xs text-gray-500 mt-1">Ditransfer ke Simpanan Sukarela</p>
                    </div>
                    <div class="text-right pt-1">
                        <span class="text-sm font-extrabold text-[#2D7A42]">+ Rp 150.000</span>
                        <p class="text-[11px] font-medium text-gray-400 mt-1">15 Jan 2024</p>
                    </div>
                </div>
            </div>

            <button class="w-full mt-6 py-2.5 bg-gray-50 text-[#2D7A42] rounded-xl font-semibold text-sm hover:bg-gray-100 transition-colors">
                Lihat Semua Riwayat
            </button>
        </div>

    </div>
</div>
@endsection
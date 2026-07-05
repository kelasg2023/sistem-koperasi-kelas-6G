@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto">
    
    {{-- Header Halaman dengan Tombol Kembali --}}
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-[#2D7A42] transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Rincian Total Belanja</h1>
            <p class="text-gray-500 text-sm">Pantau aktivitas pengeluaran Anda di Koperasi 6G.</p>
        </div>
    </div>

    {{-- Kartu Laporan Utama --}}
    <div class="bg-gradient-to-r from-[#2D7A42] to-[#1A622A] rounded-2xl p-6 lg:p-8 text-white shadow-md mb-6 relative overflow-hidden">
        <i class="fa-solid fa-chart-line absolute -right-4 -bottom-4 text-8xl opacity-10"></i>
        
        <p class="text-sm font-medium text-green-100 mb-2">Total Belanja (Semua Waktu)</p>
        <h2 class="text-4xl lg:text-5xl font-extrabold mb-6">Rp 1.250.000</h2>
        
        <div class="grid grid-cols-2 gap-4 lg:gap-8 border-t border-white/20 pt-6">
            <div>
                <p class="text-xs text-green-200 mb-1">Bulan Ini (Juli)</p>
                <p class="text-lg font-bold">Rp 350.000</p>
            </div>
            <div>
                <p class="text-xs text-green-200 mb-1">Bulan Lalu (Juni)</p>
                <p class="text-lg font-bold">Rp 420.000</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kiri: Kategori Belanja --}}
        <div class="lg:col-span-1 bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-6 h-fit">
            <h3 class="text-base font-bold text-gray-900 mb-5 border-b border-gray-50 pb-4">Pengeluaran per Kategori</h3>
            
            <div class="space-y-4">
                {{-- Kategori 1 --}}
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="font-semibold text-gray-700">Sembako</span>
                        <span class="font-bold text-gray-900">60%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-[#2D7A42] h-2 rounded-full" style="width: 60%"></div>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">Rp 750.000</p>
                </div>

                {{-- Kategori 2 --}}
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="font-semibold text-gray-700">Peralatan Mandi</span>
                        <span class="font-bold text-gray-900">25%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-emerald-400 h-2 rounded-full" style="width: 25%"></div>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">Rp 312.500</p>
                </div>

                {{-- Kategori 3 --}}
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="font-semibold text-gray-700">Lainnya</span>
                        <span class="font-bold text-gray-900">15%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-teal-300 h-2 rounded-full" style="width: 15%"></div>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">Rp 187.500</p>
                </div>
            </div>
        </div>

        {{-- Kanan: Riwayat Transaksi Terbaru --}}
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-6">
            <div class="flex justify-between items-center mb-5 border-b border-gray-50 pb-4">
                <h3 class="text-base font-bold text-gray-900">Aktivitas Belanja Terbaru</h3>
                <a href="{{ route('transaksi.index') }}" class="text-xs font-bold text-[#2D7A42] hover:underline">Lihat Semua</a>
            </div>
            
            <div class="space-y-0">
                {{-- Transaksi 1 --}}
                <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Beras Pandan Wangi 5 Kg</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">05 Jul 2026 • Sembako</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-gray-900">- Rp 68.500</span>
                    </div>
                </div>

                {{-- Transaksi 2 --}}
                <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Minyak Goreng 2 Liter</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">01 Jul 2026 • Sembako</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-gray-900">- Rp 34.000</span>
                    </div>
                </div>
                
                {{-- Transaksi 3 --}}
                <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800">Sabun Mandi Cair 450ml</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">28 Jun 2026 • Peralatan Mandi</p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-gray-900">- Rp 25.000</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
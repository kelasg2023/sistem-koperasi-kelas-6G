@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-5 p-4 lg:p-7 flex-1">
    
    {{-- KOLOM KIRI --}}
    <div class="min-w-0">

        {{-- HERO --}}
        <div class="bg-gradient-to-br from-[#1E5C2F] to-[#2D7A42] rounded-2xl p-7 lg:p-8 text-white relative overflow-hidden mb-6">
            {{-- Hiasan Background Hero --}}
            <div class="absolute right-8 top-1/2 -translate-y-1/2 w-24 h-24 border-[20px] border-white/10 rounded-full hidden sm:block"></div>
            <i class="fa-solid fa-seedling absolute right-12 top-1/2 -translate-y-1/2 text-5xl text-white/20 hidden sm:block"></i>
            
            <h2 class="text-2xl font-extrabold mb-2 relative z-10">Selamat Pagi, Ibu Siti! 👋</h2>
            <p class="text-[13.5px] opacity-90 max-w-[380px] leading-relaxed mb-5 relative z-10">Belanja mingguan makin hemat dengan paket sembako khusus anggota Koperasi 6G. Dapatkan cashback simpanan setiap transaksi.</p>
            <a href="#" class="inline-flex items-center gap-2 bg-[#F5820A] text-white font-bold text-sm py-2.5 px-5 rounded-xl hover:opacity-90 transition-opacity relative z-10">
                <i class="fa-solid fa-tag"></i> Cek Promo Hari Ini
            </a>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-4 border border-gray-200">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5"><i class="fa-solid fa-bag-shopping text-[#2D7A42]"></i> Total Belanja</div>
                <div class="text-2xl font-extrabold text-gray-900 leading-none">Rp 1.250<span class="text-[13px] font-semibold text-gray-500">k</span></div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-200">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5"><i class="fa-solid fa-ticket text-[#F5820A]"></i> Voucher</div>
                <div class="text-2xl font-extrabold text-gray-900 leading-none">5 <span class="text-[13px] font-semibold text-gray-500">Tersedia</span></div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-200">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5"><i class="fa-solid fa-piggy-bank text-blue-500"></i> Simpanan</div>
                <div class="text-2xl font-extrabold text-gray-900 leading-none">Rp 4.500<span class="text-[13px] font-semibold text-gray-500">k</span></div>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-200">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5"><i class="fa-solid fa-star text-amber-500"></i> Poin</div>
                <div class="text-2xl font-extrabold text-gray-900 leading-none">840 <span class="text-[13px] font-semibold text-gray-500">Pts</span></div>
            </div>
        </div>

        {{-- KATEGORI --}}
<div class="flex items-center justify-between mb-4">
    <h3 class="text-[17px] font-extrabold text-gray-900">Kategori Pilihan</h3>
    <a href="#" class="text-[13px] font-semibold text-[#2D7A42] flex items-center gap-1 hover:underline">
        Lihat Semua <i class="fa-solid fa-arrow-right text-[11px]"></i>
    </a>
</div>

{{-- Container kategori diperluas dengan gap yang lebih lega --}}
<div class="flex gap-6 mb-8 overflow-x-auto pb-2 scrollbar-hide pr-4">
    @php
        $kategoris = [
            ['icon' => '🌾', 'label' => 'Sembako'], 
            ['icon' => '🥦', 'label' => 'Sayuran'],
            ['icon' => '🥩', 'label' => 'Daging'], 
            ['icon' => '🍎', 'label' => 'Buah'],
            ['icon' => '🥛', 'label' => 'Susu'], 
            ['icon' => '🧹', 'label' => 'Kebersihan'],
            ['icon' => '✏️', 'label' => 'Alat Tulis'],
            ['icon' => '🍪', 'label' => 'Cemilan'],
        ];
    @endphp
    @foreach($kategoris as $kat)
    <div class="flex flex-col items-center gap-2 cursor-pointer group min-w-[80px]">
        {{-- Ikon diperbesar ke 70px dengan efek shadow --}}
        <div class="w-[70px] h-[70px] rounded-2xl bg-[#E8F5EC] flex items-center justify-center text-2xl group-hover:-translate-y-1 group-hover:bg-[#d1edda] transition-all shadow-sm">
            {{ $kat['icon'] }}
        </div>
        <span class="text-[12px] font-semibold text-gray-600 whitespace-nowrap">{{ $kat['label'] }}</span>
    </div>
    @endforeach
</div>

        {{-- PRODUK --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[17px] font-extrabold text-gray-900">Rekomendasi Bulanan</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @php
                $produks = [
                    ['emoji' => '🌾', 'badge' => 'hemat', 'diskon' => 'HEMAT 15%', 'kategori' => 'SEMBAKO', 'nama' => 'Beras Pandan Wangi 5kg', 'harga' => 'Rp 68.500', 'coret' => 'Rp 80.000'],
                    ['emoji' => '🫙', 'badge' => 'hemat', 'diskon' => 'HEMAT 15%', 'kategori' => 'SEMBAKO', 'nama' => 'Minyak Goreng 2 Liter', 'harga' => 'Rp 34.200', 'coret' => 'Rp 40.235'],
                    ['emoji' => '🥬', 'badge' => 'fresh', 'diskon' => 'FRESH TODAY', 'kategori' => 'SAYURAN', 'nama' => 'Paket Sayur Sup Segar', 'harga' => 'Rp 12.500', 'coret' => null],
                    ['emoji' => '🥚', 'badge' => null, 'diskon' => null, 'kategori' => 'SUSU & TELUR', 'nama' => 'Telur Ayam Negeri 1kg', 'harga' => 'Rp 26.000', 'coret' => null],
                ];
            @endphp
            @foreach($produks as $p)
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <div class="w-full h-[150px] object-cover bg-gray-100 flex items-center justify-center text-5xl relative">
                    {{ $p['emoji'] }}
                    @if($p['badge'] === 'hemat')
                        <span class="absolute top-2.5 right-2.5 bg-[#F5820A] text-white text-[11px] font-bold px-2 py-1 rounded-md">{{ $p['diskon'] }}</span>
                    @elseif($p['badge'] === 'fresh')
                        <span class="absolute top-2.5 left-2.5 bg-[#2D7A42] text-white text-[10px] font-bold px-2 py-1 rounded-md">{{ $p['diskon'] }}</span>
                    @endif
                </div>
                <div class="p-4">
                    <div class="mb-1.5">
                        <span class="inline-flex items-center gap-1 bg-[#E8F5EC] text-[#2D7A42] text-[10px] font-bold px-2 py-0.5 rounded-md">{{ $p['kategori'] }}</span>
                        <span class="inline-flex items-center gap-1 text-[10px] text-gray-400 ml-1.5"><i class="fa-solid fa-circle text-[6px] text-green-500"></i> Stok Tersedia</span>
                    </div>
                    <div class="text-sm font-bold text-gray-900 mb-1.5 leading-snug">{{ $p['nama'] }}</div>
                    <div>
                        <span class="text-base font-extrabold text-[#2D7A42]">{{ $p['harga'] }}</span>
                        @if($p['coret'])
                            <span class="text-xs font-medium text-gray-400 line-through ml-1.5">{{ $p['coret'] }}</span>
                        @endif
                    </div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 block mt-1">MEMBER PRICE</div>
                    <button class="w-full flex items-center justify-center gap-2 p-2.5 border-2 border-[#2D7A42] bg-transparent text-[#2D7A42] font-bold text-[13px] rounded-xl hover:bg-[#2D7A42] hover:text-white transition-colors">
                        <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                    </button>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="flex flex-col gap-4">

        {{-- PESANAN BERJALAN --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-sm font-extrabold text-gray-900 mb-4 flex items-center justify-between">
                Pesanan Berjalan
                <span class="bg-green-100 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded-full">1 AKTIF</span>
            </div>
            <div class="flex items-center gap-3 p-3 bg-[#F6F8F6] rounded-xl mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center text-lg shrink-0"><i class="fa-solid fa-truck"></i></div>
                <div>
                    <div class="text-[10px] font-bold text-[#2D7A42] uppercase tracking-wider">SEDANG DIKIRIM</div>
                    <div class="text-sm font-bold text-gray-900">#TRX-98234</div>
                </div>
            </div>
            <div class="my-2.5">
                <div class="flex justify-between text-[11px] text-gray-500 font-semibold mb-1.5">
                    <span>Progress</span>
                    <span class="text-[#2D7A42]">75%</span>
                </div>
                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#2D7A42] to-green-500 rounded-full w-[75%]"></div>
                </div>
            </div>
            <div class="text-[11px] text-gray-500 flex items-center gap-1 mt-1.5 mb-4">
                <i class="fa-regular fa-clock"></i>
                Estimasi tiba dalam: <strong class="text-gray-900">15 Menit</strong>
            </div>
            <a href="#" class="block text-center text-[13px] font-bold text-[#2D7A42] p-2.5 border-2 border-[#2D7A42] rounded-xl hover:bg-[#2D7A42] hover:text-white transition-colors">
                <i class="fa-solid fa-location-dot"></i> Lacak Pengiriman
            </a>
        </div>

       {{-- RIWAYAT BELANJA --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-sm font-extrabold text-gray-900 mb-4 flex items-center justify-between">
                Riwayat Belanja
                <a href="#" class="text-[12px] font-semibold text-[#2D7A42] hover:underline">Lihat Semua</a>
            </div>
            @php
                $riwayats = [
                    ['icon' => '🌾', 'nama' => 'Sembako', 'date' => '12 Des 2025 • 8 Produk', 'harga' => 'Rp 420.000'],
                    ['icon' => '🥦', 'nama' => 'Sayur & Buah', 'date' => '08 Des 2025 • 4 Produk', 'harga' => 'Rp 85.000'],
                    ['icon' => '🧹', 'nama' => 'Kebersihan', 'date' => '01 Des 2025 • 3 Produk', 'harga' => 'Rp 112.500'],
                ];
            @endphp
            @foreach($riwayats as $r)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-100 last:border-0 last:pb-0">
                <div class="w-9 h-9 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center text-[15px] shrink-0">{{ $r['icon'] }}</div>
                <div class="flex-1">
                    <div class="text-[13px] font-bold text-gray-900">{{ $r['nama'] }}</div>
                    <div class="text-[11px] text-gray-400 mt-0.5">{{ $r['date'] }}</div>
                </div>
                <div class="text-right">
                    <div class="text-[13px] font-bold text-gray-900">{{ $r['harga'] }}</div>
                    <span class="text-[10px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-md inline-block mt-1">Selesai</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- DISKON CARD - Dibuat lebih rapat --}}
        <div class="bg-gradient-to-br from-[#F5820A] to-[#ff9a3c] rounded-2xl p-5 text-white mt-4">
            <div class="text-[10px] font-bold uppercase tracking-wider opacity-90 mb-2">⭐ Diskon Khusus</div>
            <div class="text-sm font-bold leading-relaxed mb-4">Undang teman & dapatkan voucher Rp 50.000</div>
            <a href="#" class="flex items-center justify-center gap-2 bg-white text-[#F5820A] font-bold text-[13px] p-2.5 rounded-xl hover:opacity-90 transition-opacity">
                <i class="fa-solid fa-user-plus"></i> Ajak Teman
            </a>
        </div>

    </div>
</div>
@endsection
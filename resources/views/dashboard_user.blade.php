@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-4 lg:gap-5 p-3 sm:p-4 lg:p-7 flex-1 items-start">
    
    {{-- KOLOM KIRI --}}
    <div class="min-w-0 order-1 lg:order-1">

        {{-- HERO --}}
        <div class="bg-gradient-to-br from-[#1E5C2F] to-[#2D7A42] rounded-2xl p-5 sm:p-7 lg:p-8 text-white relative overflow-hidden mb-5 sm:mb-6">
            <div class="absolute right-8 top-1/2 -translate-y-1/2 w-24 h-24 border-[20px] border-white/10 rounded-full hidden sm:block"></div>
            <i class="fa-solid fa-seedling absolute right-12 top-1/2 -translate-y-1/2 text-5xl text-white/20 hidden sm:block"></i>
            
            <h2 class="text-lg sm:text-xl lg:text-2xl font-extrabold mb-2 relative z-10">Selamat Pagi, Ibu Siti! 👋</h2>
            <p class="text-[12.5px] sm:text-[13.5px] opacity-90 max-w-full sm:max-w-[380px] leading-relaxed mb-4 sm:mb-5 relative z-10">Belanja mingguan makin hemat dengan paket sembako khusus anggota Koperasi 6G. Dapatkan cashback simpanan setiap transaksi.</p>
            <a href="#" class="inline-flex items-center gap-2 bg-[#F5820A] text-white font-bold text-xs sm:text-sm py-2.5 px-4 sm:px-5 rounded-xl hover:opacity-90 transition-opacity relative z-10">
                <i class="fa-solid fa-tag"></i> Cek Promo Hari Ini
            </a>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-7 sm:mb-8">
           <a href="{{ route('total-belanja.index') }}" class="block bg-white rounded-2xl p-3.5 sm:p-4 border border-gray-200 hover:border-[#2D7A42] hover:shadow-md transition-all cursor-pointer group">
    <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5 sm:mb-2 flex items-center gap-1.5">
        <i class="fa-solid fa-bag-shopping text-[#2D7A42]"></i> Total Belanja
    </div>
    <div class="flex items-center justify-between">
        <div class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-none group-hover:text-[#2D7A42] transition-colors">
            Rp 1.250<span class="text-xs sm:text-[13px] font-semibold text-gray-500 group-hover:text-[#2D7A42]/70">k</span>
        </div>
        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-[#2D7A42] transition-colors text-sm"></i>
    </div>
</a>
            <a href="{{ route('voucher.index') }}" class="block bg-white rounded-2xl p-3.5 sm:p-4 border border-gray-200 hover:border-[#F5820A] hover:shadow-md transition-all cursor-pointer group">
    <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5 sm:mb-2 flex items-center gap-1.5">
        <i class="fa-solid fa-ticket text-[#F5820A]"></i> Voucher
    </div>
    <div class="flex items-center justify-between">
        <div class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-none group-hover:text-[#F5820A] transition-colors">
            5 <span class="text-xs sm:text-[13px] font-semibold text-gray-500 group-hover:text-[#F5820A]/70">Tersedia</span>
        </div>
        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-[#F5820A] transition-colors text-sm"></i>
    </div>
</a>
           <a href="{{ route('simpanan.index') }}" class="block bg-white rounded-2xl p-3.5 sm:p-4 border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all cursor-pointer group">
    <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5 sm:mb-2 flex items-center gap-1.5">
        <i class="fa-solid fa-piggy-bank text-blue-500"></i> Simpanan
    </div>
    <div class="flex items-center justify-between">
        <div class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-none group-hover:text-blue-500 transition-colors">
            Rp 4.500<span class="text-xs sm:text-[13px] font-semibold text-gray-500 group-hover:text-blue-500/70">k</span>
        </div>
        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-blue-500 transition-colors text-sm"></i>
    </div>
</a>
            <a href="{{ route('poin.index') }}" class="block bg-white rounded-2xl p-3.5 sm:p-4 border border-gray-200 hover:border-amber-500 hover:shadow-md transition-all cursor-pointer group">
    <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5 sm:mb-2 flex items-center gap-1.5">
        <i class="fa-solid fa-star text-amber-500"></i> Poin
    </div>
    <div class="flex items-center justify-between">
        <div class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-none group-hover:text-amber-600 transition-colors">
            840 <span class="text-xs sm:text-[13px] font-semibold text-gray-500 group-hover:text-amber-500/70">Pts</span>
        </div>
        <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-amber-500 transition-colors text-sm"></i>
    </div>
</a>
        </div>

        {{-- KATEGORI --}}
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h3 class="text-[15px] sm:text-[17px] font-extrabold text-gray-900">Kategori Pilihan</h3>
            <a href="{{ route('produk.index') }}" class="text-xs sm:text-[13px] font-semibold text-[#2D7A42] flex items-center gap-1 hover:underline shrink-0">
            Lihat Semua
            <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        {{-- Scroll horizontal dengan padding kanan agar item terakhir tidak terpotong --}}
        <div class="flex gap-4 sm:gap-6 mb-7 sm:mb-8 overflow-x-auto pb-2 scrollbar-hide -mx-3 sm:mx-0 px-3 sm:px-0">
            @php
                // Catatan: 'slug' di bawah ini disesuaikan dengan daftar kategori
                // yang ada di ProductController (categoryList()). Beberapa label
                // (Daging, Alat Tulis, Cemilan) belum punya slug spesifik yang
                // benar-benar cocok, jadi sementara diarahkan ke 'sembako-lainnya'.
                // Sesuaikan lagi kalau kamu menambah kategori baru yang lebih pas.
                $kategoris = [
                    ['icon' => '🌾', 'label' => 'Sembako', 'slug' => 'sembako-lainnya'],
                    ['icon' => '🥦', 'label' => 'Sayuran', 'slug' => 'sembako-lainnya'],
                    ['icon' => '🥩', 'label' => 'Daging', 'slug' => 'sembako-lainnya'],
                    ['icon' => '🍎', 'label' => 'Buah', 'slug' => 'sembako-lainnya'],
                    ['icon' => '🥛', 'label' => 'Susu', 'slug' => 'minuman'],
                    ['icon' => '🧹', 'label' => 'Kebersihan', 'slug' => 'sabun-kebersihan'],
                    ['icon' => '✏️', 'label' => 'Alat Tulis', 'slug' => 'sembako-lainnya'],
                    ['icon' => '🍪', 'label' => 'Cemilan', 'slug' => 'sembako-lainnya'],
                ];
            @endphp
            @foreach($kategoris as $kat)
            <a href="{{ route('produk.kategori', $kat['slug']) }}" class="flex flex-col items-center gap-2 cursor-pointer group min-w-[64px] sm:min-w-[80px] shrink-0">
                <div class="w-[56px] h-[56px] sm:w-[70px] sm:h-[70px] rounded-2xl bg-[#E8F5EC] flex items-center justify-center text-xl sm:text-2xl group-hover:-translate-y-1 group-hover:bg-[#d1edda] transition-all shadow-sm">
                    {{ $kat['icon'] }}
                </div>
                <span class="text-[11px] sm:text-[12px] font-semibold text-gray-600 whitespace-nowrap">{{ $kat['label'] }}</span>
            </a>
            @endforeach
        </div>

        {{-- PRODUK --}}
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h3 class="text-[15px] sm:text-[17px] font-extrabold text-gray-900">Rekomendasi Bulanan</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 sm:gap-4">
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
                <div class="w-full h-[110px] sm:h-[150px] object-cover bg-gray-100 flex items-center justify-center text-4xl sm:text-5xl relative">
                    {{ $p['emoji'] }}
                    @if($p['badge'] === 'hemat')
                        <span class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 bg-[#F5820A] text-white text-[9px] sm:text-[11px] font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-md">{{ $p['diskon'] }}</span>
                    @elseif($p['badge'] === 'fresh')
                        <span class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 bg-[#2D7A42] text-white text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-md">{{ $p['diskon'] }}</span>
                    @endif
                </div>
                <div class="p-3 sm:p-4">
                    <div class="mb-1.5 flex flex-wrap items-center gap-1">
                        <span class="inline-flex items-center gap-1 bg-[#E8F5EC] text-[#2D7A42] text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-md">{{ $p['kategori'] }}</span>
                        <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] text-gray-400"><i class="fa-solid fa-circle text-[6px] text-green-500"></i> Stok Tersedia</span>
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-gray-900 mb-1.5 leading-snug line-clamp-2">{{ $p['nama'] }}</div>
                    <div>
                        <span class="text-sm sm:text-base font-extrabold text-[#2D7A42]">{{ $p['harga'] }}</span>
                        @if($p['coret'])
                            <span class="text-[10px] sm:text-xs font-medium text-gray-400 line-through ml-1.5 block sm:inline">{{ $p['coret'] }}</span>
                        @endif
                    </div>
                    <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 sm:mb-2.5 block mt-1">MEMBER PRICE</div>
                    <button class="w-full flex items-center justify-center gap-1.5 sm:gap-2 p-2 sm:p-2.5 border-2 border-[#2D7A42] bg-transparent text-[#2D7A42] font-bold text-[11px] sm:text-[13px] rounded-xl hover:bg-[#2D7A42] hover:text-white transition-colors">
                        <i class="fa-solid fa-cart-plus"></i> <span class="hidden xs:inline">Tambah ke</span> Keranjang
                    </button>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="flex flex-col gap-4 order-2 lg:order-2">

       {{-- PESANAN BERJALAN --}}
<div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 shadow-sm">
    <div class="text-[13px] sm:text-sm font-extrabold text-gray-900 mb-3 sm:mb-4 flex items-center justify-between flex-wrap gap-1.5">
        Pesanan Berjalan
        <span class="bg-green-100 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded-full">1 AKTIF</span>
    </div>

    {{-- Kotak ini sekarang menjadi Link ke Detail Pesanan --}}
    <a href="{{ route('detail-pesanan.index') }}" class="flex items-center gap-3 p-3 bg-[#F6F8F6] rounded-xl mb-3 hover:bg-[#E8F5EC] transition-colors border border-transparent hover:border-[#2D7A42]/20 group">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center text-base sm:text-lg shrink-0 group-hover:bg-white transition-colors">
            <i class="fa-solid fa-truck"></i>
        </div>
        <div class="min-w-0">
            <div class="text-[9px] sm:text-[10px] font-bold text-[#2D7A42] uppercase tracking-wider mb-0.5">SEDANG DIKIRIM</div>
            <div class="text-[13px] sm:text-sm font-bold text-gray-900 truncate group-hover:text-[#2D7A42] transition-colors">#TRX-98234</div>
        </div>
        <i class="fa-solid fa-chevron-right ml-auto text-gray-400 text-xs"></i>
    </a>

    {{-- Progress Bar --}}
    <div class="my-3">
        <div class="flex justify-between text-[10px] sm:text-[11px] text-gray-500 font-semibold mb-1.5">
            <span>Progress</span>
            <span class="text-[#2D7A42]">75%</span>
        </div>
        <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-[#2D7A42] to-green-500 rounded-full w-[75%]"></div>
        </div>
    </div>

    {{-- Estimasi Waktu --}}
    <div class="text-[10px] sm:text-[11px] text-gray-500 flex items-center gap-1.5 mt-2 mb-4">
        <i class="fa-regular fa-clock"></i>
        Estimasi tiba dalam: <strong class="text-gray-900">15 Menit</strong>
    </div>

    {{-- Tombol Lacak (Terhubung ke Detail Pesanan) --}}
    <a href="{{ route('detail-pesanan.index') }}" class="block text-center text-xs sm:text-[13px] font-bold text-[#2D7A42] p-2.5 border-2 border-[#2D7A42] rounded-xl hover:bg-[#2D7A42] hover:text-white transition-all shadow-sm">
        <i class="fa-solid fa-location-dot mr-1"></i> Lacak Pengiriman
    </a>
</div>
        {{-- RIWAYAT BELANJA --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5">
            <div class="text-[13px] sm:text-sm font-extrabold text-gray-900 mb-3 sm:mb-4 flex items-center justify-between">
                Riwayat Belanja
                <a href="{{ route('riwayat-belanja.index') }}" class="text-[11px] sm:text-[12px] font-semibold text-[#2D7A42] hover:underline shrink-0">Lihat Semua</a>
    </div>
            
            @php
                $riwayats = [
                    ['icon' => '🌾', 'nama' => 'Sembako', 'date' => '12 Des 2025 • 8 Produk', 'harga' => 'Rp 420.000'],
                    ['icon' => '🥦', 'nama' => 'Sayur & Buah', 'date' => '08 Des 2025 • 4 Produk', 'harga' => 'Rp 85.000'],
                    ['icon' => '🧹', 'nama' => 'Kebersihan', 'date' => '01 Des 2025 • 3 Produk', 'harga' => 'Rp 112.500'],
                ];
            @endphp
            @foreach($riwayats as $r)
            <div class="flex items-center gap-2.5 sm:gap-3 py-2.5 border-b border-gray-100 last:border-0 last:pb-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center text-sm sm:text-[15px] shrink-0">{{ $r['icon'] }}</div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs sm:text-[13px] font-bold text-gray-900 truncate">{{ $r['nama'] }}</div>
                    <div class="text-[10px] sm:text-[11px] text-gray-400 mt-0.5 truncate">{{ $r['date'] }}</div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-xs sm:text-[13px] font-bold text-gray-900">{{ $r['harga'] }}</div>
                    <span class="text-[9px] sm:text-[10px] font-bold text-green-600 bg-green-100 px-1.5 sm:px-2 py-0.5 rounded-md inline-block mt-1">Selesai</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- DISKON CARD --}}
        <div class="bg-gradient-to-br from-[#F5820A] to-[#ff9a3c] rounded-2xl p-4 sm:p-5 text-white mt-1 sm:mt-4">
            <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider opacity-90 mb-2">⭐ Diskon Khusus</div>
            <div class="text-[13px] sm:text-sm font-bold leading-relaxed mb-3 sm:mb-4">Undang teman & dapatkan voucher Rp 50.000</div>
            <a href="#" class="flex items-center justify-center gap-2 bg-white text-[#F5820A] font-bold text-xs sm:text-[13px] p-2.5 rounded-xl hover:opacity-90 transition-opacity">
                <i class="fa-solid fa-user-plus"></i> Ajak Teman
            </a>
        </div>

    </div>
</div>
@endsection
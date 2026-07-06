@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-4 lg:gap-5 p-3 sm:p-4 lg:p-7 flex-1 items-start">
    
    {{-- KOLOM KIRI --}}
    <div class="min-w-0 order-1 lg:order-1">

        {{-- HERO --}}
        @php
            $namaUser = $profile['name'] ?? $user['username'] ?? 'Member';
            $jam = (int) date('H');
            $sapaan = $jam < 11 ? 'Pagi' : ($jam < 15 ? 'Siang' : ($jam < 18 ? 'Sore' : 'Malam'));
            $isMember = $dashboard['dashboard_metrics']['is_member'] ?? false;
        @endphp
        <div class="bg-gradient-to-br from-[#1E5C2F] to-[#2D7A42] rounded-2xl p-5 sm:p-7 lg:p-8 text-white relative overflow-hidden mb-5 sm:mb-6">
            {{-- Hiasan Background Hero --}}
            <div class="absolute right-8 top-1/2 -translate-y-1/2 w-24 h-24 border-[20px] border-white/10 rounded-full hidden sm:block"></div>
            <i class="fa-solid fa-seedling absolute right-12 top-1/2 -translate-y-1/2 text-5xl text-white/20 hidden sm:block"></i>
            
            <div class="flex items-center gap-3 mb-2 relative z-10">
                <h2 class="text-lg sm:text-xl lg:text-2xl font-extrabold m-0">Selamat {{ $sapaan }}, {{ $namaUser }}! 👋</h2>
                @if($isMember)
                    <span class="bg-[#FFD700] text-amber-900 text-[10px] font-bold px-2 py-0.5 rounded flex items-center gap-1 shrink-0">
                        <i class="fa-solid fa-crown"></i> MEMBER
                    </span>
                @else
                    <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded flex items-center gap-1 shrink-0">
                        <i class="fa-solid fa-user"></i> REGULAR
                    </span>
                @endif
            </div>
            <p class="text-[12.5px] sm:text-[13.5px] opacity-90 max-w-full sm:max-w-[380px] leading-relaxed mb-4 sm:mb-5 relative z-10">Belanja mingguan makin hemat dengan paket sembako khusus anggota Koperasi 6G. Dapatkan cashback simpanan setiap transaksi.</p>
            <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 bg-[#F5820A] text-white font-bold text-xs sm:text-sm py-2.5 px-4 sm:px-5 rounded-xl hover:opacity-90 transition-opacity relative z-10">
                <i class="fa-solid fa-tag"></i> Cek Promo Hari Ini
            </a>
        </div>

        {{-- STATS --}}
        @php
            $totalBelanja = $dashboard['dashboard_metrics']['total_spent'] ?? (collect($riwayat)->sum('total_harga') ?? 0);
            $totalVoucher = count($vouchers ?? []);
            $saldoWallet  = $dashboard['dashboard_metrics']['wallet_balance'] ?? 0;
            $poinUser     = $dashboard['dashboard_metrics']['points'] ?? ($user['customer']['point'] ?? 0);
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-7 sm:mb-8">
            <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-gray-200">
                <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5 sm:mb-2 flex items-center gap-1.5"><i class="fa-solid fa-bag-shopping text-[#2D7A42]"></i> Total Belanja</div>
                <div class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-none">
                    Rp {{ number_format($totalBelanja / 1000, 0, ',', '.') }}<span class="text-xs sm:text-[13px] font-semibold text-gray-500">k</span>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-gray-200">
                <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5 sm:mb-2 flex items-center gap-1.5"><i class="fa-solid fa-ticket text-[#F5820A]"></i> Voucher</div>
                <div class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-none">{{ $totalVoucher }} <span class="text-xs sm:text-[13px] font-semibold text-gray-500">Tersedia</span></div>
            </div>
            <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-gray-200">
                <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5 sm:mb-2 flex items-center gap-1.5"><i class="fa-solid fa-wallet text-blue-500"></i> Wallet</div>
                <div class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-none">
                    Rp {{ number_format($saldoWallet / 1000, 0, ',', '.') }}<span class="text-xs sm:text-[13px] font-semibold text-gray-500">k</span>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-3.5 sm:p-4 border border-gray-200">
                <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5 sm:mb-2 flex items-center gap-1.5"><i class="fa-solid fa-star text-amber-500"></i> Poin</div>
                <div class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-none">{{ $poinUser }} <span class="text-xs sm:text-[13px] font-semibold text-gray-500">Pts</span></div>
            </div>
        </div>

        {{-- KATEGORI --}}
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h3 class="text-[15px] sm:text-[17px] font-extrabold text-gray-900">Kategori Pilihan</h3>
            <a href="{{ route('produk.index') }}" class="text-xs sm:text-[13px] font-semibold text-[#2D7A42] flex items-center gap-1 hover:underline shrink-0">
                Lihat Semua <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        <div class="flex gap-4 sm:gap-6 mb-7 sm:mb-8 overflow-x-auto pb-2 scrollbar-hide -mx-3 sm:mx-0 px-3 sm:px-0">
            @forelse($kategoris as $kat)
            <a href="{{ route('produk.kategori', $kat['id_kategori']) }}" class="flex flex-col items-center gap-2 cursor-pointer group min-w-[64px] sm:min-w-[80px] shrink-0">
                <div class="w-[56px] h-[56px] sm:w-[70px] sm:h-[70px] rounded-2xl bg-[#E8F5EC] flex items-center justify-center text-xl sm:text-2xl group-hover:-translate-y-1 group-hover:bg-[#d1edda] transition-all shadow-sm">
                    🛒
                </div>
                <span class="text-[11px] sm:text-[12px] font-semibold text-gray-600 whitespace-nowrap text-center">{{ $kat['nama_kategori'] }}</span>
            </a>
            @empty
            @php
                $kategorisFallback = [
                    ['icon' => '🌾', 'label' => 'Sembako'], 
                    ['icon' => '🥦', 'label' => 'Sayuran'],
                    ['icon' => '🥩', 'label' => 'Daging'], 
                    ['icon' => '🍎', 'label' => 'Buah'],
                ];
            @endphp
            @foreach($kategorisFallback as $kat)
            <div class="flex flex-col items-center gap-2 cursor-pointer group min-w-[64px] sm:min-w-[80px] shrink-0">
                <div class="w-[56px] h-[56px] sm:w-[70px] sm:h-[70px] rounded-2xl bg-[#E8F5EC] flex items-center justify-center text-xl sm:text-2xl group-hover:-translate-y-1 group-hover:bg-[#d1edda] transition-all shadow-sm">
                    {{ $kat['icon'] }}
                </div>
                <span class="text-[11px] sm:text-[12px] font-semibold text-gray-600 whitespace-nowrap">{{ $kat['label'] }}</span>
            </div>
            @endforeach
            @endforelse
        </div>

        {{-- PRODUK --}}
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h3 class="text-[15px] sm:text-[17px] font-extrabold text-gray-900">Rekomendasi Bulanan</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 sm:gap-4">
            @forelse($barangs as $b)
            @php
                $hargaAsli   = (float) ($b['harga'] ?? 0);
                $diskon      = (float) ($b['diskon_persen'] ?? 0);
                $hargaDiskon = $diskon > 0 ? $hargaAsli * (1 - $diskon / 100) : $hargaAsli;
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <div class="w-full h-[110px] sm:h-[150px] bg-gray-100 flex items-center justify-center text-4xl sm:text-5xl relative">
                    🛒
                    @if($diskon > 0)
                        <span class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 bg-[#F5820A] text-white text-[9px] sm:text-[11px] font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-md">HEMAT {{ (int)$diskon }}%</span>
                    @endif
                </div>
                <div class="p-3 sm:p-4">
                    <div class="mb-1.5 flex flex-wrap items-center gap-1">
                        <span class="inline-flex items-center gap-1 bg-[#E8F5EC] text-[#2D7A42] text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-md">{{ strtoupper($b['kategori']['nama_kategori'] ?? 'PRODUK') }}</span>
                        <span class="inline-flex items-center gap-1 text-[9px] sm:text-[10px] text-gray-400">
                            <i class="fa-solid fa-circle text-[6px] {{ ($b['stok'] ?? 0) > 0 ? 'text-green-500' : 'text-red-400' }}"></i>
                            {{ ($b['stok'] ?? 0) > 0 ? 'Stok Tersedia' : 'Habis' }}
                        </span>
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-gray-900 mb-1.5 leading-snug line-clamp-2">{{ $b['nama'] }}</div>
                    <div>
                        <span class="text-sm sm:text-base font-extrabold text-[#2D7A42]">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                        @if($diskon > 0)
                            <span class="text-[10px] sm:text-xs font-medium text-gray-400 line-through ml-1.5 block sm:inline">Rp {{ number_format($hargaAsli, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 sm:mb-2.5 block mt-1">MEMBER PRICE</div>
                    <a href="{{ route('produk.detail', $b['barang_id']) }}" class="w-full flex items-center justify-center gap-1.5 sm:gap-2 p-2 sm:p-2.5 border-2 border-[#2D7A42] bg-transparent text-[#2D7A42] font-bold text-[11px] sm:text-[13px] rounded-xl hover:bg-[#2D7A42] hover:text-white transition-colors">
                        <i class="fa-solid fa-cart-plus"></i> <span class="hidden xs:inline">Lihat</span> Produk
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-10 text-gray-400 text-sm">
                <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                Tidak ada produk tersedia saat ini.
            </div>
            @endforelse
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="flex flex-col gap-4 order-2 lg:order-2">

        {{-- PESANAN BERJALAN --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 shadow-sm">
            <div class="text-[13px] sm:text-sm font-extrabold text-gray-900 mb-3 sm:mb-4 flex items-center justify-between flex-wrap gap-1.5">
                Pesanan Berjalan
                @if($activeOrder)
                    <span class="bg-green-100 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded-full">1 AKTIF</span>
                @else
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded-full">TIDAK ADA</span>
                @endif
            </div>

            @if($activeOrder)
            @php
                $trxId   = $activeOrder['transaction_id'];
                $statusPengiriman = $activeOrder['status_pengiriman'] ?? 'pending';
                $progressMap = ['pending' => 25, 'dikemas' => 50, 'dikirim' => 75, 'selesai' => 100];
                $progress = $progressMap[$statusPengiriman] ?? 25;
                $statusLabel = ['pending' => 'MENUNGGU', 'dikemas' => 'DIKEMAS', 'dikirim' => 'SEDANG DIKIRIM', 'selesai' => 'SELESAI'][$statusPengiriman] ?? 'DALAM PROSES';
            @endphp
            <a href="{{ route('pesanan.detail', $trxId) }}" class="flex items-center gap-3 p-3 bg-[#F6F8F6] rounded-xl mb-3 hover:bg-[#E8F5EC] transition-colors border border-transparent hover:border-[#2D7A42]/20 group">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center text-base sm:text-lg shrink-0 group-hover:bg-white transition-colors">
                    <i class="fa-solid fa-truck"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-[9px] sm:text-[10px] font-bold text-[#2D7A42] uppercase tracking-wider mb-0.5">{{ $statusLabel }}</div>
                    <div class="text-[13px] sm:text-sm font-bold text-gray-900 truncate group-hover:text-[#2D7A42] transition-colors">#TRX-{{ $trxId }}</div>
                </div>
                <i class="fa-solid fa-chevron-right ml-auto text-gray-400 text-xs"></i>
            </a>

            <div class="my-3">
                <div class="flex justify-between text-[10px] sm:text-[11px] text-gray-500 font-semibold mb-1.5">
                    <span>Progress</span>
                    <span class="text-[#2D7A42]">{{ $progress }}%</span>
                </div>
                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#2D7A42] to-green-500 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
            </div>

            <div class="text-[10px] sm:text-[11px] text-gray-500 flex items-center gap-1.5 mt-2 mb-4">
                <i class="fa-regular fa-clock"></i>
                @if($activeOrder['nomor_resi'] ?? false)
                    Resi: <strong class="text-gray-900">{{ $activeOrder['nomor_resi'] }}</strong>
                @else
                    Status: <strong class="text-gray-900">{{ $statusLabel }}</strong>
                @endif
            </div>

            <a href="{{ route('pesanan.detail', $trxId) }}" class="block text-center text-xs sm:text-[13px] font-bold text-[#2D7A42] p-2.5 border-2 border-[#2D7A42] rounded-xl hover:bg-[#2D7A42] hover:text-white transition-all shadow-sm">
                <i class="fa-solid fa-location-dot mr-1"></i> Lacak Pengiriman
            </a>
            @else
            <div class="text-center py-6 text-gray-400">
                <i class="fa-solid fa-box-open text-3xl mb-2 block opacity-40"></i>
                <p class="text-xs font-medium">Tidak ada pesanan berjalan</p>
                <a href="{{ route('produk.index') }}" class="inline-block mt-3 text-xs font-bold text-[#2D7A42] border border-[#2D7A42] px-4 py-1.5 rounded-lg hover:bg-[#2D7A42] hover:text-white transition-colors">Mulai Belanja</a>
            </div>
            @endif
        </div>

        {{-- RIWAYAT BELANJA --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5">
            <div class="text-[13px] sm:text-sm font-extrabold text-gray-900 mb-3 sm:mb-4 flex items-center justify-between">
                Riwayat Belanja
                <a href="{{ route('riwayat.index') }}" class="text-[11px] sm:text-[12px] font-semibold text-[#2D7A42] hover:underline shrink-0">Lihat Semua</a>
            </div>
            
            @forelse($riwayat as $r)
            @php
                $statusBadge = match($r['status'] ?? 'proses') {
                    'berhasil' => ['label' => 'Selesai',   'class' => 'text-green-600 bg-green-100'],
                    'proses'   => ['label' => 'Diproses',  'class' => 'text-blue-600 bg-blue-100'],
                    'gagal'    => ['label' => 'Gagal',     'class' => 'text-red-600 bg-red-100'],
                    'refund'   => ['label' => 'Refund',    'class' => 'text-orange-600 bg-orange-100'],
                    default    => ['label' => 'Proses',    'class' => 'text-gray-600 bg-gray-100'],
                };
                $jumlahItem = count($r['details'] ?? []);
                $tglBeli = \Carbon\Carbon::parse($r['created_at'] ?? now())->translatedFormat('d M Y');
            @endphp
            <div class="flex items-center gap-2.5 sm:gap-3 py-2.5 border-b border-gray-100 last:border-0 last:pb-0">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#E8F5EC] text-[#2D7A42] flex items-center justify-center text-sm sm:text-[15px] shrink-0">🛒</div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs sm:text-[13px] font-bold text-gray-900 truncate">#TRX-{{ $r['transaction_id'] }}</div>
                    <div class="text-[10px] sm:text-[11px] text-gray-400 mt-0.5 truncate">{{ $tglBeli }} {{ $jumlahItem > 0 ? "• $jumlahItem Produk" : '' }}</div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-xs sm:text-[13px] font-bold text-gray-900">Rp {{ number_format($r['total_harga'] ?? 0, 0, ',', '.') }}</div>
                    <span class="text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-md inline-block mt-1 {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-gray-400 text-xs">
                <i class="fa-solid fa-receipt text-2xl mb-2 block opacity-40"></i>
                Belum ada riwayat belanja.
            </div>
            @endforelse
        </div>

        {{-- DISKON CARD --}}
        <div class="bg-gradient-to-br from-[#F5820A] to-[#ff9a3c] rounded-2xl p-4 sm:p-5 text-white mt-1 sm:mt-4">
            <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider opacity-90 mb-2">⭐ Diskon Khusus</div>
            <div class="text-[13px] sm:text-sm font-bold leading-relaxed mb-3 sm:mb-4">Undang teman & dapatkan voucher Rp 50.000</div>
            <a href="{{ route('voucher.index') }}" class="flex items-center justify-center gap-2 bg-white text-[#F5820A] font-bold text-xs sm:text-[13px] p-2.5 rounded-xl hover:opacity-90 transition-opacity">
                <i class="fa-solid fa-ticket"></i> Lihat Voucher Saya
            </a>
        </div>

    </div>
</div>
@endsection
<aside id="sidebar" class="w-[220px] bg-white flex flex-col py-6 px-4 fixed inset-y-0 left-0 border-r border-gray-200 z-[100] transition-transform duration-300 transform -translate-x-full lg:translate-x-0">

    <div class="flex items-center justify-between mb-8 pl-1">
        <div class="flex items-center gap-2.5 text-lg font-extrabold text-[#2D7A42]">
            <i class="fa-solid fa-seedling text-[22px]"></i>
            Koperasi 6G
        </div>

        {{-- Tombol Close untuk mobile --}}
        <button onclick="toggleSidebar(false)" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    @if(session('user.role') === 'admin')
        {{-- ============================== --}}
        {{-- 1. MENU KHUSUS ADMIN           --}}
        {{-- ============================== --}}
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2">Dashboard Admin</div>

        <a href="/admin/kategori" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->is('admin/kategori*') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-tags w-[18px] text-center text-[15px]"></i> Kelola Kategori
        </a>

        <a href="/admin/produk" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->is('admin/produk*') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-box-open w-[18px] text-center text-[15px]"></i> Kelola Produk
        </a>

        <a href="/admin/laporan" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->is('admin/laporan*') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-chart-pie w-[18px] text-center text-[15px]"></i> Laporan
        </a>

        <div class="flex-1"></div>

        {{-- Tombol Logout Admin --}}
        <div class="mt-4 pt-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 font-medium text-sm hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket w-[18px] text-center text-[15px]"></i> Keluar
                </button>
            </form>
        </div>

    @else
      {{-- ============================== --}}
        {{-- 2. MENU KHUSUS USER            --}}
        {{-- ============================== --}}
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2">Menu</div>

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-house w-[18px] text-center text-[15px]"></i> Beranda
        </a>

        <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->routeIs('produk.index') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-grip w-[18px] text-center text-[15px]"></i> Kategori
        </a>

        <a href="{{ route('transaksi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->routeIs('transaksi.index') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-receipt w-[18px] text-center text-[15px]"></i> Transaksi
        </a>
        
        <a href="{{ route('untung-bersama') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->routeIs('untung-bersama') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-handshake w-[18px] text-center text-[15px]"></i> Untung Bersama
        </a>

        {{-- Menu Metode Pembayaran (Baru) --}}
        <a href="{{ route('metode-pembayaran.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->routeIs('metode-pembayaran.index') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-credit-card w-[18px] text-center text-[15px]"></i> Metode Pembayaran
        </a>

        <a href="{{ route('pengaturan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm mb-0.5 transition-colors {{ request()->routeIs('pengaturan.index') ? 'bg-[#2D7A42] text-white font-semibold shadow-sm' : 'text-gray-500 font-medium hover:bg-[#E8F5EC] hover:text-[#2D7A42]' }}">
            <i class="fa-solid fa-gear w-[18px] text-center text-[15px]"></i> Pengaturan
        </a>

        <div class="flex-1"></div>
{{-- Tombol Jadi Member --}}
        <a href="{{ route('pengajuan-member.index') }}" class="flex flex-col items-center justify-center gap-2 bg-[#2D7A42] text-white font-bold text-sm p-4 rounded-2xl hover:bg-[#1E5C2F] transition-all shadow-md group mb-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-id-card text-lg"></i>
                <span>Jadi Member</span>
            </div>
            <span class="text-[10px] opacity-80 font-normal">Dapatkan akses harga khusus</span>
        </a>

        {{-- Tombol Logout User --}}
        <div class="pt-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 font-medium text-sm hover:bg-red-50 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket w-[18px] text-center text-[15px]"></i> Keluar
                </button>
            </form>
        </div>

    @endif

</aside>
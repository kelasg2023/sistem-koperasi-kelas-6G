<aside id="sidebar" class="w-[220px] bg-white flex flex-col py-6 px-4 fixed inset-y-0 left-0 border-r border-gray-200 z-[100] transition-transform duration-300 transform -translate-x-full lg:translate-x-0">
    
    <div class="flex items-center justify-between mb-8 pl-1">
        <div class="flex items-center gap-2.5 text-lg font-extrabold text-[#2D7A42]">
            <i class="fa-solid fa-seedling text-[22px]"></i>
            Koperasi 6G
        </div>

        {{-- Tombol Close, hanya tampil di mobile --}}
        <button onclick="toggleSidebar(false)" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2 mb-2">Menu</div>
    
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white bg-[#2D7A42] font-semibold text-sm mb-0.5">
        <i class="fa-solid fa-house w-[18px] text-center text-[15px]"></i> Beranda
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 font-medium text-sm hover:bg-[#E8F5EC] hover:text-[#2D7A42] transition-colors mb-0.5">
        <i class="fa-solid fa-grip w-[18px] text-center text-[15px]"></i> Kategori
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 font-medium text-sm hover:bg-[#E8F5EC] hover:text-[#2D7A42] transition-colors mb-0.5">
        <i class="fa-solid fa-receipt w-[18px] text-center text-[15px]"></i> Transaksi
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 font-medium text-sm hover:bg-[#E8F5EC] hover:text-[#2D7A42] transition-colors mb-0.5">
        <i class="fa-solid fa-handshake w-[18px] text-center text-[15px]"></i> Untung Bersama
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 font-medium text-sm hover:bg-[#E8F5EC] hover:text-[#2D7A42] transition-colors mb-0.5">
        <i class="fa-solid fa-gear w-[18px] text-center text-[15px]"></i> Pengaturan
    </a>

    {{-- Sidebar spacer --}}
    <div class="flex-1"></div>

    {{-- Tombol Daftar Member --}}
    <a href="#" class="flex flex-col items-center justify-center gap-2 bg-[#2D7A42] text-white font-bold text-sm p-4 rounded-2xl hover:bg-[#1E5C2F] transition-all shadow-md group">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-id-card text-lg"></i>
            <span>Jadi Member</span>
        </div>
        <span class="text-[10px] opacity-80 font-normal">Dapatkan akses harga khusus</span>
    </a>
</aside>
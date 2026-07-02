{{-- Tombol Hamburger Mobile (satu-satunya, dikelola di sini) --}}
<button class="lg:hidden text-xl text-gray-500 hover:text-gray-700 transition-colors shrink-0" onclick="toggleSidebar(true)">
    <i class="fa-solid fa-bars"></i>
</button>

{{-- Search Bar --}}
<div class="flex-1 w-full max-w-lg relative mx-auto lg:mx-0">
    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
    <input type="text" 
           placeholder="Cari beras, minyak, sayur segar..." 
           class="w-full py-2.5 pr-4 pl-10 border border-gray-200 rounded-xl text-[13.5px] bg-[#F6F8F6] text-[#1A1A1A] focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all">
</div>

{{-- Topbar Actions --}}
<div class="flex items-center gap-2 sm:gap-3 shrink-0">
    
    {{-- Icon Notifikasi --}}
    <div class="w-9 h-9 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-500 relative hover:bg-gray-50 cursor-pointer transition-colors shrink-0">
        <i class="fa-regular fa-bell"></i>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
    </div>
    
    {{-- Icon Bantuan --}}
    <div class="hidden sm:flex w-9 h-9 rounded-full border border-gray-200 bg-white items-center justify-center text-gray-500 hover:bg-gray-50 cursor-pointer transition-colors shrink-0">
        <i class="fa-regular fa-circle-question"></i>
    </div>
    
    {{-- User Pill --}}
    <div class="flex items-center gap-2 py-1.5 pr-3 pl-1.5 border border-gray-200 rounded-full cursor-pointer hover:bg-gray-50 transition-colors shrink-0">
        <div class="w-7 h-7 rounded-full bg-[#F5820A] text-white font-bold text-[13px] flex items-center justify-center shrink-0">S</div>
        <span class="hidden sm:block font-semibold text-[13.5px] text-[#1A1A1A] whitespace-nowrap">Siti Aisha</span>
        <i class="fa-solid fa-chevron-down text-[11px] text-gray-400 hidden sm:block shrink-0"></i>
    </div>

</div>
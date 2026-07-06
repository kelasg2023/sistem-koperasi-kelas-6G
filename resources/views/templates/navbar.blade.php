{{-- Tombol Hamburger Mobile (satu-satunya, dikelola di sini) --}}
<button class="lg:hidden text-xl text-gray-500 hover:text-gray-700 transition-colors shrink-0" onclick="toggleSidebar(true)">
    <i class="fa-solid fa-bars"></i>
</button>

{{-- Search Bar --}}
<form action="{{ route('produk.index') }}" method="GET" id="navbarSearchForm" class="flex-1 w-full max-w-lg relative mx-auto lg:mx-0">
    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
    <input type="text" name="q" id="navbarSearchInput" value="{{ request('q') }}"
           placeholder="Cari beras, minyak, sayur segar..." 
           class="w-full py-2.5 pr-4 pl-10 border border-gray-200 rounded-xl text-[13.5px] bg-[#F6F8F6] text-[#1A1A1A] focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all">
</form>

<script>
    let searchTimeout = null;
    document.getElementById('navbarSearchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                // Berada di halaman produk, update q di sidebar lalu submit agar filter tetap jalan
                let qInput = filterForm.querySelector('input[name="q"]');
                if (!qInput) {
                    qInput = document.createElement('input');
                    qInput.type = 'hidden';
                    qInput.name = 'q';
                    filterForm.appendChild(qInput);
                }
                qInput.value = this.value;
                if (typeof triggerFilter === 'function') {
                    triggerFilter();
                } else {
                    filterForm.submit();
                }
            } else {
                // Di luar halaman produk, submit form navbar
                document.getElementById('navbarSearchForm').submit();
            }
        }, 600); // jeda 600ms (live reload debounce)
    });
</script>

{{-- Topbar Actions --}}
<div class="flex items-center gap-2 sm:gap-3 shrink-0" x-data>
    
    {{-- Icon Keranjang --}}
    <a href="{{ route('keranjang.index') }}" class="w-9 h-9 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-500 relative hover:bg-gray-50 cursor-pointer transition-colors shrink-0">
        <i class="fa-solid fa-cart-shopping"></i>
        <span x-cloak x-show="$store.cart.count > 0" class="absolute -top-1 -right-1 bg-[#1A622A] text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white" x-text="$store.cart.count"></span>
    </a>
    
    {{-- Icon Notifikasi --}}
    <div class="w-9 h-9 rounded-full border border-gray-200 bg-white flex items-center justify-center text-gray-500 relative hover:bg-gray-50 cursor-pointer transition-colors shrink-0">
        <i class="fa-regular fa-bell"></i>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
    </div>
    
    {{-- Icon Bantuan --}}
    <div class="hidden sm:flex w-9 h-9 rounded-full border border-gray-200 bg-white items-center justify-center text-gray-500 hover:bg-gray-50 cursor-pointer transition-colors shrink-0">
        <i class="fa-regular fa-circle-question"></i>
    </div>
    
   {{-- Logika Session --}}
@if(session()->has('user'))
    @php
        $userData = session('user');
        $fullName = !empty($userData['profile']['name']) ? $userData['profile']['name'] : $userData['username'];
        $initial = strtoupper(substr($fullName, 0, 1));
    @endphp

    <div class="relative shrink-0">
        {{-- User Pill (Trigger) --}}
        <div id="nav-user-menu-button" class="flex items-center gap-2 py-1.5 pr-3 pl-1.5 border border-gray-200 rounded-full cursor-pointer hover:bg-gray-50 transition-colors shrink-0 select-none">
            <div class="w-7 h-7 rounded-full bg-[#F5820A] text-white font-bold text-[13px] flex items-center justify-center shrink-0">
                {{ $initial }}
            </div>
            <span class="hidden sm:block font-semibold text-[13.5px] text-[#1A1A1A] whitespace-nowrap">
                {{ $fullName }}
            </span>
            <i id="nav-user-menu-icon" class="fa-solid fa-chevron-down text-[11px] text-gray-400 hidden sm:block shrink-0 transition-transform duration-200"></i>
        </div>

        {{-- Dropdown Menu (Hidden secara default) --}}
        <div id="nav-user-dropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg z-50 hidden opacity-0 scale-95 origin-top-right transition-all duration-200">
            <div class="py-1">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-[13.5px] text-[#1A1A1A] hover:bg-gray-50 transition-colors font-medium">
                    Profile
                </a>
                <hr class="my-1 border-gray-100">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-[13.5px] text-red-600 hover:bg-red-50 font-medium transition-colors">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
@else
    {{-- Tombol Masuk & Daftar (Jika Belum Login) --}}
    <div class="flex items-center gap-2 shrink-0">
        <a href="{{ route('login') }}" class="px-4 py-1.5 text-[13px] font-semibold text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-colors">
            Masuk
        </a>
        <a href="{{ route('register') }}" class="px-4 py-1.5 text-[13px] font-semibold text-white bg-[#2D7A42] rounded-full hover:bg-[#1f5c30] transition-colors shadow-sm">
            Daftar
        </a>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const navMenuBtn = document.getElementById('nav-user-menu-button');
    const navDropdown = document.getElementById('nav-user-dropdown');
    const navMenuIcon = document.getElementById('nav-user-menu-icon');

    if (navMenuBtn && navDropdown) {
        navMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = navDropdown.classList.contains('hidden');
            
            if (isHidden) {
                // Munculkan menu
                navDropdown.classList.remove('hidden');
                setTimeout(() => {
                    navDropdown.classList.remove('scale-95', 'opacity-0');
                    navDropdown.classList.add('scale-100', 'opacity-100');
                    // Putar ikon FontAwesome
                    if(navMenuIcon) navMenuIcon.style.transform = 'rotate(180deg)';
                }, 10);
            } else {
                // Sembunyikan menu
                navDropdown.classList.remove('scale-100', 'opacity-100');
                navDropdown.classList.add('scale-95', 'opacity-0');
                if(navMenuIcon) navMenuIcon.style.transform = 'rotate(0deg)';
                setTimeout(() => {
                    navDropdown.classList.add('hidden');
                }, 200); 
            }
        });

        // Menutup dropdown jika user mengklik area lain
        document.addEventListener('click', (e) => {
            if (!navMenuBtn.contains(e.target) && !navDropdown.contains(e.target)) {
                if (!navDropdown.classList.contains('hidden')) {
                    navDropdown.classList.remove('scale-100', 'opacity-100');
                    navDropdown.classList.add('scale-95', 'opacity-0');
                    if(navMenuIcon) navMenuIcon.style.transform = 'rotate(0deg)';
                    setTimeout(() => {
                        navDropdown.classList.add('hidden');
                    }, 200);
                }
            }
        });
    }
});
</script>

</div>
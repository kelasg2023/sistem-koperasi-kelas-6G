@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pengaturan Akun</h1>
        <p class="text-gray-500 text-sm">Kelola informasi data pribadi dan keamanan akun Anda di sini.</p>
    </div>

    {{-- Alert Pesan Sukses (Contoh jika ada flash message dari Controller) --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-lg"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        
       

        {{-- BAGIAN KANAN: Form Pengaturan --}}
        <div class="w-full lg:w-3/4 space-y-6 lg:space-y-8">
            
            {{-- ========================================= --}}
            {{-- FORM DATA PRIBADI                         --}}
            {{-- ========================================= --}}
            <div id="profil" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8 scroll-mt-24">
                <h3 class="text-lg font-bold text-gray-900 mb-5 lg:mb-6 border-b border-gray-50 pb-4">Data Pribadi</h3>
                
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- Avatar / Foto Profil --}}
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6 mb-8 text-center sm:text-left">
                        <div class="relative shrink-0">
                            <img src="https://ui-avatars.com/api/?name=Ardian+Putra&background=2D7A42&color=fff&size=100" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover border-4 border-gray-50 shadow-sm">
                            <button type="button" class="absolute bottom-0 right-0 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:text-[#2D7A42] hover:border-[#2D7A42] transition-colors shadow-sm">
                                <i class="fa-solid fa-camera text-xs"></i>
                            </button>
                        </div>
                        <div class="w-full">
                            <h4 class="text-sm font-bold text-gray-800">Foto Profil</h4>
                            <p class="text-[11px] sm:text-xs text-gray-500 mt-1 mb-3">Format JPEG, PNG, atau JPG. Maksimal 2MB.</p>
                            <input type="file" name="foto" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#E8F5EC] file:text-[#2D7A42] hover:file:bg-[#D4E0D9] cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                            <div class="relative">
                                <i class="fa-solid fa-id-badge absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="nama" value="Ardian Putra" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">
                            </div>
                        </div>

                        {{-- Nomor HP --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon</label>
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="no_hp" value="081234567890" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">
                            </div>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Lahir</label>
                            <div class="relative">
                                <i class="fa-solid fa-calendar absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="date" name="tanggal_lahir" value="1995-08-17" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">
                            </div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin</label>
                            <div class="relative">
                                <i class="fa-solid fa-venus-mars absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <select name="jenis_kelamin" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white appearance-none">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                    <option value="O">Lainnya / Tidak ingin menyebutkan</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="email" name="email" value="ardian.putra@email.com" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">
                            </div>
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                                <textarea name="alamat" rows="3" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">Jl. Koperasi No. 123, RT 01/RW 02, Kec. Sukamaju, Kota Sejahtera.</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 sm:py-2.5 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- ========================================= --}}
            {{-- FORM KEAMANAN AKUN (Ubah Password)        --}}
            {{-- ========================================= --}}
            <div id="keamanan" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8 scroll-mt-24">
                <h3 class="text-lg font-bold text-gray-900 mb-5 lg:mb-6 border-b border-gray-50 pb-4">Keamanan Akun</h3>
                
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5 mb-6">
                        {{-- Password Lama --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi Saat Ini</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="password" name="current_password" id="current_password" placeholder="Masukkan kata sandi lama" class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">
                                <button type="button" onclick="togglePassword('current_password', 'icon_current')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i id="icon_current" class="fa-solid fa-eye-slash text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi Baru</label>
                            <div class="relative">
                                <i class="fa-solid fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="password" name="new_password" id="new_password" placeholder="Masukkan kata sandi baru" class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">
                                <button type="button" onclick="togglePassword('new_password', 'icon_new')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i id="icon_new" class="fa-solid fa-eye-slash text-sm"></i>
                                </button>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1.5">Minimal 8 karakter, kombinasi huruf dan angka.</p>
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <div class="relative">
                                <i class="fa-solid fa-shield-check absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="Ulangi kata sandi baru" class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">
                                <button type="button" onclick="togglePassword('new_password_confirmation', 'icon_confirm')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i id="icon_confirm" class="fa-solid fa-eye-slash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 sm:py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Script untuk melihat password (Show/Hide Password) --}}
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            icon.classList.add('text-[#2D7A42]'); // Mengubah warna ikon saat sandi terlihat
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            icon.classList.remove('text-[#2D7A42]');
        }
    }
</script>
@endsection
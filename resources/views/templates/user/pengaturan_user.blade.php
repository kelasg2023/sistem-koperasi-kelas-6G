@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8" x-data="settingsPage()">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pengaturan Akun</h1>
        <p class="text-gray-500 text-sm">Kelola informasi data pribadi dan keamanan akun Anda di sini.</p>
    </div>

    {{-- Alert Messages --}}
    <div x-show="message.text" :class="message.type === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'" class="mb-6 border px-4 py-3 rounded-xl flex items-center gap-3" x-cloak>
        <i class="fa-solid fa-circle-info text-lg"></i>
        <span class="text-sm font-medium" x-text="message.text"></span>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        {{-- BAGIAN KANAN: Form Pengaturan --}}
        <div class="w-full lg:w-3/4 space-y-6 lg:space-y-8">
            
            {{-- ========================================= --}}
            {{-- FORM DATA PRIBADI                         --}}
            {{-- ========================================= --}}
            <div id="profil" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8 scroll-mt-24 relative">
                <div x-show="isLoadingProfile" class="absolute inset-0 bg-white/50 z-10 flex items-center justify-center rounded-2xl">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#2D7A42]"></div>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-5 lg:mb-6 border-b border-gray-50 pb-4">Data Pribadi</h3>
                
                <form @submit.prevent="updateProfile" enctype="multipart/form-data">
                    {{-- Avatar / Foto Profil --}}
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6 mb-8 text-center sm:text-left">
                        <div class="relative shrink-0">
                            <img :src="profile.profile_picture ? profile.profile_picture : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(profile.nama) + '&background=2D7A42&color=fff&size=100'" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover border-4 border-gray-50 shadow-sm">
                            <button type="button" class="absolute bottom-0 right-0 w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-600 hover:text-[#2D7A42] hover:border-[#2D7A42] transition-colors shadow-sm">
                                <i class="fa-solid fa-camera text-xs"></i>
                            </button>
                        </div>
                        <div class="w-full">
                            <h4 class="text-sm font-bold text-gray-800">Foto Profil</h4>
                            <p class="text-[11px] sm:text-xs text-gray-500 mt-1 mb-3">Format JPEG, PNG, atau JPG. Maksimal 2MB.</p>
                            <input type="file" @change="handleFileChange" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#E8F5EC] file:text-[#2D7A42] hover:file:bg-[#D4E0D9] cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                            <div class="relative">
                                <i class="fa-solid fa-id-badge absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" x-model="profile.nama" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white" required>
                            </div>
                        </div>

                        {{-- Nomor HP --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon</label>
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" x-model="profile.no_hp" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white" required>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="email" x-model="profile.email" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white" disabled>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah.</p>
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                                <textarea x-model="profile.alamat" rows="3" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="isSaving" class="w-full sm:w-auto px-6 py-3 sm:py-2.5 bg-[#2D7A42] hover:bg-[#1E5C2F] disabled:opacity-50 text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
                            <span x-show="!isSaving">Simpan Perubahan</span>
                            <span x-show="isSaving">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ========================================= --}}
            {{-- FORM KEAMANAN AKUN (Ubah Password)        --}}
            {{-- ========================================= --}}
            <div id="keamanan" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8 scroll-mt-24">
                <h3 class="text-lg font-bold text-gray-900 mb-5 lg:mb-6 border-b border-gray-50 pb-4">Keamanan Akun</h3>
                
                <form @submit.prevent="changePassword">
                    <div class="space-y-5 mb-6">
                        {{-- Password Lama --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi Saat Ini</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input :type="showOld ? 'text' : 'password'" x-model="passwords.current_password" placeholder="Masukkan kata sandi lama" class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white" required>
                                <button type="button" @click="showOld = !showOld" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i :class="showOld ? 'fa-eye text-[#2D7A42]' : 'fa-eye-slash'" class="fa-solid text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi Baru</label>
                            <div class="relative">
                                <i class="fa-solid fa-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input :type="showNew ? 'text' : 'password'" x-model="passwords.new_password" placeholder="Masukkan kata sandi baru" class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white" required>
                                <button type="button" @click="showNew = !showNew" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i :class="showNew ? 'fa-eye text-[#2D7A42]' : 'fa-eye-slash'" class="fa-solid text-sm"></i>
                                </button>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1.5">Minimal 8 karakter.</p>
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <div class="relative">
                                <i class="fa-solid fa-shield-check absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input :type="showConfirm ? 'text' : 'password'" x-model="passwords.new_password_confirmation" placeholder="Ulangi kata sandi baru" class="w-full pl-10 pr-12 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white" required>
                                <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i :class="showConfirm ? 'fa-eye text-[#2D7A42]' : 'fa-eye-slash'" class="fa-solid text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" :disabled="isChangingPassword" class="w-full sm:w-auto px-6 py-3 sm:py-2.5 bg-gray-800 hover:bg-gray-900 disabled:opacity-50 text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
                            <span x-show="!isChangingPassword">Perbarui Kata Sandi</span>
                            <span x-show="isChangingPassword">Memperbarui...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('settingsPage', () => ({
        profile: {
            nama: '',
            no_hp: '',
            email: '',
            alamat: '',
            profile_picture: null,
            photoFile: null
        },
        passwords: {
            current_password: '',
            new_password: '',
            new_password_confirmation: ''
        },
        showOld: false,
        showNew: false,
        showConfirm: false,
        isLoadingProfile: true,
        isSaving: false,
        isChangingPassword: false,
        message: { text: '', type: '' },

        async init() {
            this.fetchProfile();
        },

        showMessage(text, type = 'success') {
            this.message = { text, type };
            setTimeout(() => this.message.text = '', 5000);
        },

        async fetchProfile() {
            try {
                const res = await fetch('/api-proxy/profile');
                const json = await res.json();
                if (json.success && json.data) {
                    this.profile.nama = json.data.name || '';
                    this.profile.no_hp = json.data.phone || '';
                    this.profile.email = json.data.user?.email || '';
                    this.profile.alamat = json.data.address || '';
                    this.profile.profile_picture = json.data.profile_picture ? '/private-image/' + json.data.profile_picture : null;
                }
            } catch (e) {
                console.error('Gagal memuat profil', e);
            } finally {
                this.isLoadingProfile = false;
            }
        },

        handleFileChange(e) {
            if(e.target.files.length > 0) {
                this.profile.photoFile = e.target.files[0];
            }
        },

        async updateProfile() {
            this.isSaving = true;
            try {
                const formData = new FormData();
                formData.append('name', this.profile.nama);
                formData.append('phone', this.profile.no_hp);
                formData.append('address', this.profile.alamat);
                // Simulasi method patch
                formData.append('_method', 'PATCH');
                if (this.profile.photoFile) {
                    formData.append('profile_picture', this.profile.photoFile);
                }

                // Since we send form-data, we cannot just rely on Proxy converting JSON if it's strictly JSON
                // In Laravel API, we often accept FormData on POST/PATCH. Let's send a POST with _method=PATCH.
                const res = await fetch('/api-proxy/profile', {
                    method: 'POST',
                    body: formData,
                    // Jangan set Content-Type agar browser menset boundary multipart form data secara otomatis
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const json = await res.json();
                if (res.ok && json.success) {
                    this.showMessage('Profil berhasil diperbarui');
                    this.fetchProfile(); // Refresh
                } else {
                    this.showMessage(json.message || 'Gagal memperbarui profil', 'error');
                }
            } catch (e) {
                this.showMessage('Terjadi kesalahan koneksi', 'error');
            } finally {
                this.isSaving = false;
            }
        },

        async changePassword() {
            if (this.passwords.new_password !== this.passwords.new_password_confirmation) {
                this.showMessage('Konfirmasi kata sandi tidak cocok', 'error');
                return;
            }

            this.isChangingPassword = true;
            try {
                const res = await fetch('/api-proxy/change-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: this.passwords.current_password,
                        password: this.passwords.new_password,
                        password_confirmation: this.passwords.new_password_confirmation
                    })
                });

                const json = await res.json();
                if (res.ok && json.success) {
                    this.showMessage('Kata sandi berhasil diperbarui');
                    this.passwords.current_password = '';
                    this.passwords.new_password = '';
                    this.passwords.new_password_confirmation = '';
                    this.showOld = false; this.showNew = false; this.showConfirm = false;
                } else {
                    this.showMessage(json.message || 'Gagal memperbarui kata sandi', 'error');
                }
            } catch (e) {
                this.showMessage('Terjadi kesalahan koneksi', 'error');
            } finally {
                this.isChangingPassword = false;
            }
        }
    }));
});
</script>
@endsection
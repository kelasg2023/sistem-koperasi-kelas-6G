@extends('layouts.app')

@section('content')
<div class="w-full px-4 lg:px-8 py-6 lg:py-8 max-w-5xl mx-auto">
    
    {{-- Header Halaman --}}
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:bg-[#E8F5EC] hover:text-[#2D7A42] hover:border-[#2D7A42]/30 transition-all shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Pengajuan Keanggotaan</h1>
            <p class="text-gray-500 text-sm">Bergabung menjadi bagian dari Koperasi 6G dan nikmati keuntungannya.</p>
        </div>
    </div>

    {{-- Banner Keuntungan (Tema Hijau Koperasi) --}}
    <div class="bg-gradient-to-br from-[#2D7A42] to-[#1A622A] rounded-2xl p-6 lg:p-8 text-white shadow-md mb-8 relative overflow-hidden">
        {{-- Ornamen Latar --}}
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <i class="fa-solid fa-crown absolute -right-4 -bottom-4 text-9xl opacity-10 transform -rotate-12 pointer-events-none text-[#FFD700]"></i>
        
        <div class="relative z-10 max-w-2xl">
            <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-bold inline-block mb-4 border border-white/20">
                <i class="fa-solid fa-star text-[#FFD700] mr-1"></i> Keuntungan Eksklusif
            </span>
            <h2 class="text-2xl lg:text-3xl font-extrabold mb-3 leading-tight">Mengapa Anda Harus Bergabung?</h2>
            <p class="text-sm text-green-50 mb-6 leading-relaxed opacity-90">
                Sebagai anggota penuh Koperasi 6G, Anda bukan sekadar pelanggan, melainkan pemilik yang berhak mendapatkan berbagai fasilitas istimewa dan pembagian keuntungan tiap tahunnya.
            </p>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-black/20 rounded-xl p-3 backdrop-blur-sm border border-white/10">
                    <i class="fa-solid fa-tags text-[#FFD700] mb-2 text-lg"></i>
                    <h4 class="text-xs font-bold mb-1">Harga Member</h4>
                    <p class="text-[10px] text-green-100/80">Potongan harga khusus untuk belanja sembako.</p>
                </div>
                <div class="bg-black/20 rounded-xl p-3 backdrop-blur-sm border border-white/10">
                    <i class="fa-solid fa-hand-holding-dollar text-[#FFD700] mb-2 text-lg"></i>
                    <h4 class="text-xs font-bold mb-1">Bagi Hasil (SHU)</h4>
                    <p class="text-[10px] text-green-100/80">Dapatkan keuntungan tahunan dari aktivitas Koperasi.</p>
                </div>
                <div class="bg-black/20 rounded-xl p-3 backdrop-blur-sm border border-white/10">
                    <i class="fa-solid fa-piggy-bank text-[#FFD700] mb-2 text-lg"></i>
                    <h4 class="text-xs font-bold mb-1">Akses Finansial</h4>
                    <p class="text-[10px] text-green-100/80">Fasilitas simpanan sukarela dan pinjaman bunga rendah.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Layout Utama --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
        
        {{-- Kiri: Form Pengajuan --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Formulir Pendaftaran Identitas</h3>
                <p class="text-xs text-gray-500 mb-6 border-b border-gray-50 pb-4">Harap lengkapi data diri Anda sesuai dengan identitas resmi (KTP).</p>
                
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-5">
                        {{-- NIK KTP --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Induk Kependudukan (NIK)</label>
                            <div class="relative">
                                <i class="fa-solid fa-id-card absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="number" name="nik" placeholder="Masukkan 16 digit NIK" class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white" required>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1.5">Kami menjaga kerahasiaan data pribadi Anda.</p>
                        </div>

                        {{-- Upload KTP & Selfie --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- KTP --}}
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-[#2D7A42] transition-colors bg-gray-50/50 cursor-pointer group relative">
                                <input type="file" name="foto_ktp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100 group-hover:text-[#2D7A42] transition-colors">
                                    <i class="fa-solid fa-address-card text-xl text-gray-400 group-hover:text-[#2D7A42]"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-800 mb-1">Upload Foto KTP</h4>
                                <p class="text-[10px] text-gray-500">Maks. 2MB (JPG, JPEG, PNG)</p>
                            </div>

                            {{-- Selfie dengan KTP --}}
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-[#2D7A42] transition-colors bg-gray-50/50 cursor-pointer group relative">
                                <input type="file" name="foto_selfie" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100 group-hover:text-[#2D7A42] transition-colors">
                                    <i class="fa-solid fa-camera-retro text-xl text-gray-400 group-hover:text-[#2D7A42]"></i>
                                </div>
                                <h4 class="text-sm font-bold text-gray-800 mb-1">Selfie dengan KTP</h4>
                                <p class="text-[10px] text-gray-500">Pastikan wajah & KTP terlihat jelas</p>
                            </div>
                        </div>

                        {{-- Pekerjaan & Penghasilan (Opsional) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pekerjaan</label>
                                <div class="relative">
                                    <i class="fa-solid fa-briefcase absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <select name="pekerjaan" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white appearance-none">
                                        <option value="" disabled selected>Pilih pekerjaan</option>
                                        <option value="Karyawan Swasta">Karyawan Swasta</option>
                                        <option value="PNS">Pegawai Negeri Sipil (PNS)</option>
                                        <option value="Wiraswasta">Wiraswasta / Pengusaha</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ahli Waris (Opsional)</label>
                                <div class="relative">
                                    <i class="fa-solid fa-users absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" name="ahli_waris" placeholder="Nama lengkap ahli waris" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#2D7A42] focus:ring-1 focus:ring-[#2D7A42] transition-all bg-gray-50 focus:bg-white">
                                </div>
                            </div>
                        </div>

                        {{-- Syarat & Ketentuan Checkbox --}}
                        <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100 mt-4">
                            <input type="checkbox" id="syarat" name="syarat" class="mt-1 w-4 h-4 text-[#2D7A42] bg-white border-gray-300 rounded focus:ring-[#2D7A42] cursor-pointer" required>
                            <label for="syarat" class="text-[11px] sm:text-xs text-gray-600 cursor-pointer leading-relaxed">
                                Saya menyatakan bahwa data yang saya masukkan adalah benar. Saya bersedia mematuhi <a href="#" class="font-bold text-[#2D7A42] hover:underline">Anggaran Dasar & Anggaran Rumah Tangga (AD/ART)</a> Koperasi 6G serta menyetujui kewajiban pembayaran simpanan pokok dan wajib.
                            </label>
                        </div>
                    </div>

                    {{-- Tombol Submit Mobile (Tersembunyi di Desktop) --}}
                    <div class="xl:hidden mt-6">
                        <button type="submit" class="w-full px-6 py-3.5 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white font-bold text-sm rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Ajukan & Bayar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kanan: Ringkasan Pembayaran Awal --}}
        <div class="xl:col-span-1">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 lg:p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-50 pb-4">Rincian Pembayaran Awal</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Simpanan Pokok</span>
                        <span class="font-bold text-gray-800">Rp 500.000</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Simpanan Wajib (Bulan ke-1)</span>
                        <span class="font-bold text-gray-800">Rp 100.000</span>
                    </div>
                    <div class="flex justify-between items-center text-sm pb-4 border-b border-gray-100">
                        <span class="text-gray-500 font-medium">Biaya Administrasi</span>
                        <span class="font-bold text-gray-800 text-[#2D7A42]">Gratis</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-gray-900 text-base">Total Bayar</span>
                        <span class="font-extrabold text-2xl text-[#2D7A42]">Rp 600.000</span>
                    </div>
                </div>

                <div class="bg-[#E8F5EC] rounded-xl p-4 mb-6 flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-[#2D7A42] mt-0.5"></i>
                    <p class="text-[11px] text-gray-600 leading-relaxed">
                        Pembayaran awal diperlukan untuk mengaktifkan status keanggotaan Anda. Dana ini akan masuk ke saldo simpanan Anda secara utuh.
                    </p>
                </div>

                {{-- Tombol Submit Desktop --}}
                <button type="button" onclick="document.querySelector('form').submit();" class="hidden xl:flex w-full px-6 py-3.5 bg-[#2D7A42] hover:bg-[#1E5C2F] text-white font-bold text-sm rounded-xl transition-colors shadow-sm items-center justify-center gap-2 group">
                    <span>Ajukan & Bayar Sekarang</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 lg:p-7 flex-1 items-start max-w-4xl mx-auto w-full">
    
    {{-- HEADER --}}
    <div class="mb-6 sm:mb-8 flex items-center gap-3">
        <a href="/admin/kategori" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl lg:text-2xl font-extrabold text-gray-900">Edit Kategori</h2>
            <p class="text-sm text-gray-500 mt-1">Ubah data kategori yang sudah ada.</p>
        </div>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-7 shadow-sm">
        <form action="{{ url('/admin/kategori/1') }}" method="POST" class="flex flex-col gap-5 sm:gap-6">
            @csrf
            @method('PUT')
            
            {{-- Input Nama Kategori --}}
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kategori" value="Sembako" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all">
            </div>

            {{-- Input Ikon/Emoji --}}
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Ikon (Emoji) <span class="text-red-500">*</span></label>
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    {{-- Perhatikan ada atribut value="🌾" di bawah ini --}}
                    <input type="text" name="ikon" value="🌾" maxlength="2" required class="w-20 px-4 py-3 text-center text-xl bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all">
                    
                    {{-- Teks petunjuk untuk Admin --}}
                    <div class="text-xs text-gray-500 bg-blue-50 p-2.5 rounded-lg border border-blue-100 flex-1">
                        <span class="font-bold text-blue-600"><i class="fa-solid fa-circle-info"></i> Tips:</span> 
                        Tekan tombol <kbd class="bg-white border border-gray-200 px-1.5 py-0.5 rounded shadow-sm font-mono text-[10px] font-bold text-gray-700">Windows</kbd> + <kbd class="bg-white border border-gray-200 px-1.5 py-0.5 rounded shadow-sm font-mono text-[10px] font-bold text-gray-700">.</kbd> (titik) di keyboard untuk memunculkan pilihan emoji.
                    </div>
                </div>
            </div>

            {{-- Input Deskripsi Singkat --}}
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all resize-none">Beras, Minyak, Gula</textarea>
            </div>

            <hr class="border-gray-100 my-2">

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-end gap-3">
                <a href="/admin/kategori" class="px-5 py-2.5 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-sm bg-amber-500 text-white hover:bg-amber-600 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 lg:p-7 flex-1 items-start">
    
    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h2 class="text-xl lg:text-2xl font-extrabold text-gray-900">Kelola Kategori</h2>
            <p class="text-sm text-gray-500 mt-1">Atur dan kelola semua kategori produk koperasi.</p>
        </div>
        <a href="/admin/kategori/tambah" class="inline-flex items-center justify-center gap-2 bg-[#2D7A42] text-white font-bold text-sm py-2.5 px-5 rounded-xl hover:bg-[#1E5C2F] transition-colors shadow-sm shrink-0">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </a>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        
        {{-- Pencarian (Opsional untuk UI) --}}
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row gap-3 justify-between items-center bg-gray-50/50">
            <div class="relative w-full sm:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" placeholder="Cari kategori..." class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] transition-all">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="p-4 w-16 text-center">No</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4 text-center">Total Produk</th>
                        <th class="p-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    {{-- DUMMY DATA 1 --}}
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4 text-center font-semibold text-gray-500 text-sm">1</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#E8F5EC] flex items-center justify-center text-lg sm:text-xl shadow-sm group-hover:-translate-y-0.5 transition-transform">
                                    🌾
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm sm:text-[15px]">Sembako</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">Beras, Minyak, Gula</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-md">24 Item</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="/admin/kategori/edit" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                                
                                {{-- Tombol hapus yang sudah di-update menjadi form Backend-Ready --}}
                                <form action="{{ url('/admin/kategori/1') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    {{-- DUMMY DATA 2 --}}
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4 text-center font-semibold text-gray-500 text-sm">2</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#E8F5EC] flex items-center justify-center text-lg sm:text-xl shadow-sm group-hover:-translate-y-0.5 transition-transform">
                                    🥦
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm sm:text-[15px]">Sayuran</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">Sayur Segar Harian</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-md">12 Item</span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="/admin/kategori/edit" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                                
                                {{-- Tombol hapus yang sudah di-update menjadi form Backend-Ready --}}
                                <form action="{{ url('/admin/kategori/2') }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        {{-- PAGINASI (Opsional UI) --}}
        <div class="p-4 border-t border-gray-100 flex items-center justify-between text-xs sm:text-sm">
            <span class="text-gray-500 font-medium">Menampilkan 1-2 dari 8 data</span>
            <div class="flex items-center gap-1">
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-50 disabled:opacity-50" disabled><i class="fa-solid fa-chevron-left"></i></button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2D7A42] text-white font-bold">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

    </div>
</div>
@endsection
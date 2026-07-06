@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 lg:p-7 flex-1 items-start max-w-4xl mx-auto w-full">
    
    {{-- HEADER --}}
    <div class="mb-6 sm:mb-8 flex items-center gap-3">
        <a href="/admin/kategori" class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl lg:text-2xl font-extrabold text-gray-900">Tambah Kategori Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Masukkan detail kategori produk yang ingin ditambahkan.</p>
        </div>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-7 shadow-sm">
        {{-- PERBAIKAN: Menambahkan ID formTambahKategori dan menghapus action --}}
        <form id="formTambahKategori" class="flex flex-col gap-5 sm:gap-6">
            @csrf
            
            {{-- Input Nama Kategori --}}
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kategori" placeholder="Contoh: Sembako, Minuman, dll" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all">
            </div>

            {{-- Input Ikon/Emoji --}}
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Ikon (Emoji) <span class="text-red-500">*</span></label>
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    <input type="text" name="ikon" placeholder="🌾" maxlength="2" required class="w-20 px-4 py-3 text-center text-xl bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all">
                    
                    {{-- Teks petunjuk untuk Admin --}}
                    <div class="text-xs text-gray-500 bg-blue-50 p-2.5 rounded-lg border border-blue-100 flex-1">
                        <span class="font-bold text-blue-600"><i class="fa-solid fa-circle-info"></i> Tips:</span> 
                        Tekan tombol <kbd class="bg-white border border-gray-200 px-1.5 py-0.5 rounded shadow-sm font-mono text-[10px] font-bold text-gray-700">Windows</kbd> + <kbd class="bg-white border border-gray-200 px-1.5 py-0.5 rounded shadow-sm font-mono text-[10px] font-bold text-gray-700">.</kbd> (titik) di keyboard untuk memunculkan pilihan emoji.
                    </div>
                </div>
            </div>

            <div>
    <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
    <input type="text" name="satuan" placeholder="Contoh: kg, pack, pcs" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all">
</div>

            {{-- Input Deskripsi Singkat --}}
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Tambahkan keterangan untuk kategori ini..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all resize-none"></textarea>
            </div>

            <hr class="border-gray-100 my-2">

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-end gap-3">
                <a href="/admin/kategori" class="px-5 py-2.5 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-sm bg-[#2D7A42] text-white hover:bg-[#1E5C2F] transition-colors shadow-sm">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>

</div>

{{-- PERBAIKAN: Script Fetch API untuk mengirim data ke Backend --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formKategori = document.getElementById('formTambahKategori');

    if (formKategori) {
        formKategori.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah reload halaman bawaan form

            // Mengambil semua data dari input form, termasuk token CSRF
            let formData = new FormData(this);

            // Mengubah tombol simpan menjadi loading state (opsional tapi disarankan)
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;

            // Mengirim data ke API menggunakan Fetch
            fetch('/api-proxy/admin/kategori', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(Object.fromEntries(new FormData(formKategori))),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Terjadi kesalahan pada server');
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Kategori berhasil ditambahkan!', showConfirmButton: false, timer: 2000 }).then(() => {
                    window.location.href = '/admin/kategori'; 
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Gagal', 'Gagal menyimpan kategori. Cek koneksi server.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});
</script>
@endsection
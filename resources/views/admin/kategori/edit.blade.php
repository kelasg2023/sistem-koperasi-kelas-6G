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

    {{-- LOADING STATE --}}
    <div id="loadingState" class="bg-white rounded-2xl border border-gray-200 p-10 shadow-sm text-center text-gray-400 text-sm">
        <i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat data kategori...
    </div>

    {{-- ERROR STATE --}}
    <div id="errorState" class="hidden bg-white rounded-2xl border border-gray-200 p-10 shadow-sm text-center text-red-500 text-sm"></div>

    {{-- FORM CARD --}}
    <div id="formCard" class="hidden bg-white rounded-2xl border border-gray-200 p-5 sm:p-7 shadow-sm">
        <form id="kategoriForm" class="flex flex-col gap-5 sm:gap-6">

            {{-- Input Nama Kategori --}}
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" id="nama_kategori" name="nama_kategori" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all">
            </div>

            {{-- Input Satuan --}}
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                <input type="text" id="satuan" name="satuan" required placeholder="contoh: pcs, kg, liter"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] focus:bg-white transition-all">
            </div>

            <div id="formError" class="hidden text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3"></div>

            <hr class="border-gray-100 my-2">

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-end gap-3">
                <a href="/admin/kategori" class="px-5 py-2.5 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-6 py-2.5 rounded-xl font-bold text-sm bg-amber-500 text-white hover:bg-amber-600 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    const KATEGORI_ID = {{ (int) $id }};
    const API_URL_READ  = `/api-proxy/kategori/${KATEGORI_ID}`;       // GET
    const API_URL_WRITE = `/api-proxy/admin/kategori/${KATEGORI_ID}`; // PUT

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    async function loadKategori() {
        try {
            const res = await fetch(API_URL_READ, {
                headers: { 'Accept': 'application/json' }
            });
            const json = await res.json();

            if (!res.ok || !json.success) {
                showError(json.message || 'Kategori tidak ditemukan.');
                return;
            }

            document.getElementById('nama_kategori').value = json.data.nama_kategori ?? '';
            document.getElementById('satuan').value = json.data.satuan ?? '';

            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('formCard').classList.remove('hidden');
        } catch (err) {
            console.error(err);
            showError('Gagal memuat data dari server.');
        }
    }

    function showError(message) {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('formCard').classList.add('hidden');
        const errorState = document.getElementById('errorState');
        errorState.textContent = message;
        errorState.classList.remove('hidden');
    }

    document.getElementById('kategoriForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const formError = document.getElementById('formError');
        formError.classList.add('hidden');

        const payload = {
            nama_kategori: document.getElementById('nama_kategori').value,
            satuan: document.getElementById('satuan').value,
        };

        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';

        try {
            const res = await fetch(API_URL_WRITE, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify(payload),
            });

            const json = await res.json();

            if (!res.ok || !json.success) {
                formError.textContent = json.message || 'Gagal menyimpan perubahan.';
                formError.classList.remove('hidden');
                return;
            }

            window.location.href = '/admin/kategori';
        } catch (err) {
            console.error(err);
            formError.textContent = 'Terjadi kesalahan saat menghubungi server.';
            formError.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Simpan Perubahan';
        }
    });

    document.addEventListener('DOMContentLoaded', loadKategori);
</script>
@endsection
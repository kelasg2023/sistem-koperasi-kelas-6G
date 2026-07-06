@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 lg:p-7 flex-1 items-start">

    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h2 class="text-xl lg:text-2xl font-extrabold text-gray-900">Kelola Kategori</h2>
            <p class="text-sm text-gray-500 mt-1">Atur dan kelola semua kategori produk koperasi.</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="inline-flex items-center justify-center gap-2 bg-[#2D7A42] text-white font-bold text-sm py-2.5 px-5 rounded-xl hover:bg-[#1E5C2F] transition-colors shadow-sm shrink-0">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </a>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">

        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row gap-3 justify-between items-center bg-gray-50/50">
            <div class="relative w-full sm:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" id="searchInput" oninput="renderTable()" placeholder="Cari kategori..." class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/20 focus:border-[#2D7A42] transition-all">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="p-4 w-16 text-center">No</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4 text-center">Satuan</th>
                        <th class="p-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-kategori-body" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- PAGINASI --}}
        <div class="p-4 border-t border-gray-100 flex items-center justify-between text-xs sm:text-sm">
            <span class="text-gray-500 font-medium" id="info-data">Memuat data...</span>
            <div class="flex items-center gap-1" id="paginationButtons"></div>
        </div>
    </div>
</div>

<script>
    // ==================== KONFIGURASI ====================
    const KATEGORI_READ  = '/api-proxy/kategori';       // GET list
    const KATEGORI_WRITE = '/api-proxy/admin/kategori'; // POST, PUT, DELETE
    const ROWS_PER_PAGE = 10;

    let allKategori = [];
    let currentPage = 1;

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    // Menghasilkan avatar huruf inisial sebagai pengganti ikon
    // (kolom 'ikon' tidak tersedia di tabel kategori)
    function getInitialAvatar(nama) {
        const huruf = (nama || '?').trim().charAt(0).toUpperCase();
        return `<div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-[#E8F5EC] flex items-center justify-center text-sm sm:text-base font-bold text-[#2D7A42] shadow-sm">${huruf}</div>`;
    }

    // ==================== FETCH DATA ====================
    async function loadKategori() {
        const tbody = document.getElementById('tabel-kategori-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="p-8 text-center text-gray-500">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat data...
                </td>
            </tr>`;

        try {
            const res = await fetch(KATEGORI_READ, {
                headers: { 'Accept': 'application/json' }
            });
            const json = await res.json();

            // Response API berbentuk { success: true, data: [...] }
            allKategori = json.data || [];
            renderTable();
        } catch (err) {
            console.error('Error:', err);
            tbody.innerHTML = '<tr><td colspan="4" class="p-8 text-center text-red-500">Gagal memuat data dari server.</td></tr>';
            document.getElementById('info-data').innerText = '';
        }
    }

    // ==================== RENDER TABEL ====================
    function renderTable() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const filtered = allKategori.filter(k =>
            (k.nama_kategori || '').toLowerCase().includes(keyword)
        );

        const tbody = document.getElementById('tabel-kategori-body');

        if (filtered.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="p-8 text-center text-gray-500">Belum ada kategori.</td></tr>';
            document.getElementById('info-data').innerText = 'Menampilkan 0 dari 0 data';
            document.getElementById('paginationButtons').innerHTML = '';
            return;
        }

        const totalPages = Math.max(1, Math.ceil(filtered.length / ROWS_PER_PAGE));
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * ROWS_PER_PAGE;
        const pageItems = filtered.slice(start, start + ROWS_PER_PAGE);

        tbody.innerHTML = '';

        pageItems.forEach((item, index) => {
            const row = `
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="p-4 text-center font-semibold text-gray-500 text-sm">${start + index + 1}</td>
                    <td class="p-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            ${getInitialAvatar(item.nama_kategori)}
                            <div>
                                <div class="font-bold text-gray-900 text-sm sm:text-[15px]">${item.nama_kategori}</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-md">${item.satuan ?? '-'}</span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="/admin/kategori/${item.id_kategori}/edit" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-colors">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <button type="button" onclick="deleteKategori(${item.id_kategori})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        renderPagination(filtered.length, totalPages, start);
    }

    function renderPagination(totalItems, totalPages, start) {
        const end = Math.min(start + ROWS_PER_PAGE, totalItems);
        document.getElementById('info-data').innerText =
            `Menampilkan ${start + 1}-${end} dari ${totalItems} data`;

        const wrapper = document.getElementById('paginationButtons');
        wrapper.innerHTML = '';

        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
        prevBtn.className = 'w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-50 disabled:opacity-40';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => { currentPage--; renderTable(); };
        wrapper.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `w-8 h-8 flex items-center justify-center rounded-lg font-bold transition ${
                i === currentPage ? 'bg-[#2D7A42] text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50'
            }`;
            btn.onclick = () => { currentPage = i; renderTable(); };
            wrapper.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
        nextBtn.className = 'w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => { currentPage++; renderTable(); };
        wrapper.appendChild(nextBtn);
    }

    // ==================== HAPUS KATEGORI ====================
    async function deleteKategori(id) {
        const result = await Swal.fire({
            title: 'Hapus Kategori?',
            text: 'Kategori yang memiliki produk tidak dapat dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const res = await fetch(`${KATEGORI_WRITE}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
            });

            const json = await res.json();

            if (!res.ok || !json.success) {
                Swal.fire('Gagal', json.message || 'Gagal menghapus kategori.', 'error');
                return;
            }

            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Kategori berhasil dihapus', showConfirmButton: false, timer: 2000 });
            await loadKategori();
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
        }
    }

    // ==================== INIT ====================
    document.addEventListener('DOMContentLoaded', loadKategori);
</script>
@endsection
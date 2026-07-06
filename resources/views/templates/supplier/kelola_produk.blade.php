@extends('layouts.app')

@section('title', 'Kelola Produk (Supplier)')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Produk (Supplier)</h1>
            <p class="text-sm text-gray-500 mt-1">Lihat dan tambahkan produk baru (Read & Create).</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openKategoriModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
                <i class="fa-solid fa-tags"></i> Tambah Kategori
            </button>
            <button onclick="openModal()" class="bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </button>
        </div>
    </div>

    {{-- Card Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

        {{-- Search --}}
        <div class="relative mb-4">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400 text-sm"></i>
            <input type="text" id="searchInput" oninput="renderTable()" placeholder="Cari produk..."
                class="w-full bg-gray-50 border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42] focus:border-transparent">
        </div>

        {{-- Loading State --}}
        <div id="loadingState" class="text-center py-10 text-gray-400 text-sm">
            <i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat data produk...
        </div>

        {{-- Empty State --}}
        <div id="emptyState" class="hidden text-center py-10 text-gray-400 text-sm">
            Tidak ada produk ditemukan.
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto hidden" id="tableWrapper">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 border-b border-gray-100">
                        <th class="py-3 pr-4 font-semibold">No</th>
                        <th class="py-3 pr-4 font-semibold">Nama Produk</th>
                        <th class="py-3 pr-4 font-semibold">Kategori</th>
                        <th class="py-3 pr-4 font-semibold">Stok</th>
                        <th class="py-3 pr-4 font-semibold">Stok</th>
                        <th class="py-3 pr-4 font-semibold">Harga</th>
                        <th class="py-3 pr-4 font-semibold">Diskon</th>
                        <th class="py-3 pr-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-50"></tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between mt-4 text-sm text-gray-500" id="paginationWrapper">
            <span id="paginationInfo"></span>
            <div class="flex items-center gap-1" id="paginationButtons"></div>
        </div>

    </div>
</div>

{{-- ==================== MODAL TAMBAH PRODUK ==================== --}}
<div id="produkModal" class="fixed inset-0 bg-black/40 z-[200] hidden items-center justify-center px-4">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Tambah Produk</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="produkForm" onsubmit="submitProduk(event)" enctype="multipart/form-data">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Produk</label>
                    <input type="text" id="nama" name="nama" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori</label>
                    <select id="id_kategori" name="id_kategori" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]">
                        <option value="">-- Pilih Kategori --</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Stok</label>
                        <input type="number" id="stok" name="stok" min="0" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Harga (Rp)</label>
                        <input type="number" id="harga" name="harga" min="0" step="0.01" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Diskon (%) <span class="text-gray-400 font-normal">- opsional</span></label>
                    <input type="number" id="diskon_persen" name="diskon_persen" min="0" max="100" step="0.01"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]">
                </div>

                {{-- Input Foto Produk --}}
                <div class="mb-4">
                    <label class="block text-[13px] font-medium text-gray-700 mb-1.5">
                        Foto Produk <span class="text-gray-400 font-normal">- opsional</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="foto_produk" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-[#E8F5EC] hover:border-[#2D7A42] transition-colors group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-7 h-7 mb-2 text-gray-400 group-hover:text-[#2D7A42]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <p class="mb-1 text-xs text-gray-500"><span class="font-semibold text-gray-700 group-hover:text-[#2D7A42]">Klik untuk unggah</span> foto</p>
                                <p class="text-[10px] text-gray-400">PNG, JPG, atau WEBP (Maks. 2MB)</p>
                            </div>
                            <input id="foto_produk" name="foto_produk" type="file" class="hidden" accept="image/png, image/jpeg, image/webp" />
                        </label>
                    </div>
                    <div id="file-name-preview" class="text-xs text-[#2D7A42] font-semibold mt-2 hidden items-center gap-1.5">
                        <i class="fa-solid fa-check-circle"></i> <span id="file-name-text"></span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Deskripsi <span class="text-gray-400 font-normal">- opsional</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42] resize-none"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" id="submitBtn" class="bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-sm font-semibold px-5 py-2 rounded-lg transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL TAMBAH KATEGORI ==================== --}}
<div id="kategoriModal" class="fixed inset-0 bg-black/40 z-[200] hidden items-center justify-center px-4">
    <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-lg">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900">Tambah Kategori</h3>
            <button type="button" onclick="closeKategoriModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="kategoriForm" onsubmit="submitKategori(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Kategori</label>
                    <input type="text" id="nama_kategori" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Satuan (Misal: Pcs, Kg, Box)</label>
                    <input type="text" id="satuan" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D7A42]">
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeKategoriModal()" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50 rounded-lg transition">Batal</button>
                <button type="submit" id="submitKategoriBtn" class="bg-[#2D7A42] hover:bg-[#1E5C2F] text-white text-sm font-semibold px-5 py-2 rounded-lg transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL VIEW DETAIL PRODUK ==================== --}}
<div id="viewModal" class="fixed inset-0 bg-black/40 z-[200] hidden items-center justify-center px-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-lg">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900">Detail Produk</h3>
            <button type="button" onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="flex justify-center mb-4">
            <img id="view_foto" src="" class="w-32 h-32 object-cover rounded-xl border border-gray-200 hidden">
            <div id="view_no_foto" class="w-32 h-32 rounded-xl bg-gray-100 flex flex-col items-center justify-center text-gray-400 hidden">
                <i class="fa-solid fa-image text-2xl mb-1"></i>
                <span class="text-xs">No Image</span>
            </div>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500">Nama Produk:</span>
                <span class="font-semibold text-gray-800" id="view_nama"></span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500">Kategori:</span>
                <span class="font-semibold text-gray-800" id="view_kategori"></span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500">Stok:</span>
                <span class="font-semibold text-gray-800" id="view_stok"></span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500">Harga:</span>
                <span class="font-semibold text-gray-800" id="view_harga"></span>
            </div>
            <div class="flex justify-between border-b border-gray-100 pb-2">
                <span class="text-gray-500">Diskon:</span>
                <span class="font-semibold text-gray-800" id="view_diskon"></span>
            </div>
            <div>
                <span class="text-gray-500 block mb-1">Deskripsi:</span>
                <p class="text-gray-800 bg-gray-50 p-3 rounded-lg text-xs" id="view_deskripsi"></p>
            </div>
        </div>
        <div class="mt-6">
            <button type="button" onclick="closeViewModal()" class="w-full px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ==================== KONFIGURASI ====================
    // Supplier bisa melihat barang dan menambah barang
    const API_BASE_READ  = '/api-proxy/supplier/barang';
    const API_BASE_WRITE = '/api-proxy/supplier/barang';
    const KATEGORI_READ  = '/api-proxy/kategori'; 
    const ROWS_PER_PAGE = 10;

    let allProduk = [];
    let allKategori = [];
    let currentPage = 1;

    // ==================== HELPER ====================
    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    // ==================== FETCH DATA ====================
    async function loadKategori() {
        try {
            const res = await fetch(KATEGORI_READ);
            const json = await res.json();
            allKategori = json.data || [];

            const select = document.getElementById('id_kategori');
            select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            allKategori.forEach(k => {
                const opt = document.createElement('option');
                opt.value = k.id_kategori ?? k.id;
                opt.textContent = k.nama_kategori ?? k.nama;
                select.appendChild(opt);
            });
        } catch (err) {
            console.error('Gagal memuat kategori:', err);
        }
    }

    async function loadProduk() {
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('tableWrapper').classList.add('hidden');
        document.getElementById('emptyState').classList.add('hidden');

        try {
            const res = await fetch(API_BASE_READ);
            const json = await res.json();
            allProduk = json.data?.items || json.data?.data || json.data || [];
            renderTable();
        } catch (err) {
            console.error('Gagal memuat produk:', err);
            document.getElementById('loadingState').innerHTML =
                '<span class="text-red-500">Gagal memuat data. Cek koneksi API.</span>';
            return;
        }

        document.getElementById('loadingState').classList.add('hidden');
    }

    function getKategoriNama(id_kategori) {
        const kategori = allKategori.find(k => (k.id_kategori ?? k.id) == id_kategori);
        return kategori ? (kategori.nama_kategori ?? kategori.nama) : '-';
    }

    // ==================== RENDER TABEL ====================
    function renderTable() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        const filtered = allProduk.filter(p => p.nama.toLowerCase().includes(keyword));

        const totalPages = Math.max(1, Math.ceil(filtered.length / ROWS_PER_PAGE));
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * ROWS_PER_PAGE;
        const pageItems = filtered.slice(start, start + ROWS_PER_PAGE);

        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if (filtered.length === 0) {
            document.getElementById('tableWrapper').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('paginationWrapper').classList.add('hidden');
            return;
        }

        document.getElementById('tableWrapper').classList.remove('hidden');
        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('paginationWrapper').classList.remove('hidden');

        pageItems.forEach((p, index) => {
            const diskon = p.diskon_persen ? `${p.diskon_persen}%` : '-';
            const fotoUrl = p.foto_produk ? `/storage/${p.foto_produk}` : null;
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50/60 transition';
            tr.innerHTML = `
                <td class="py-3 pr-4 text-gray-500">${start + index + 1}</td>
                <td class="py-3 pr-4 font-semibold text-gray-800">
                    <div class="flex items-center gap-3">
                        ${fotoUrl
                            ? `<img src="${fotoUrl}" class="w-9 h-9 rounded-lg object-cover border border-gray-100">`
                            : `<div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-image text-xs"></i></div>`}
                        ${p.nama}
                    </div>
                </td>
                <td class="py-3 pr-4 text-gray-600">${getKategoriNama(p.id_kategori)}</td>
                <td class="py-3 pr-4 text-gray-600">${p.stok}</td>
                <td class="py-3 pr-4 text-gray-600">${formatRupiah(p.harga)}</td>
                <td class="py-3 pr-4 text-gray-600">${diskon}</td>
                <td class="py-3 pr-4 text-right">
                    <button onclick="viewProduk(${p.id || p.barang_id})" class="text-blue-500 hover:text-blue-600" title="Lihat Detail">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        renderPagination(filtered.length, totalPages);
    }

    function renderPagination(totalItems, totalPages) {
        const start = (currentPage - 1) * ROWS_PER_PAGE + 1;
        const end = Math.min(currentPage * ROWS_PER_PAGE, totalItems);

        document.getElementById('paginationInfo').textContent =
            `Menampilkan ${start}-${end} dari ${totalItems} data`;

        const btnWrapper = document.getElementById('paginationButtons');
        btnWrapper.innerHTML = '';

        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
        prevBtn.className = 'w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50 disabled:opacity-40';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => { currentPage--; renderTable(); };
        btnWrapper.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `w-8 h-8 rounded-lg text-sm font-semibold transition ${
                i === currentPage ? 'bg-[#2D7A42] text-white' : 'border border-gray-200 hover:bg-gray-50'
            }`;
            btn.onclick = () => { currentPage = i; renderTable(); };
            btnWrapper.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
        nextBtn.className = 'w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50 disabled:opacity-40';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => { currentPage++; renderTable(); };
        btnWrapper.appendChild(nextBtn);
    }

    // ==================== MODAL ====================
    function openModal() {
        document.getElementById('produkForm').reset();
        document.getElementById('file-name-preview').classList.add('hidden');
        document.getElementById('produkModal').classList.remove('hidden');
        document.getElementById('produkModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('produkModal').classList.add('hidden');
        document.getElementById('produkModal').classList.remove('flex');
    }

    // ==================== MODAL VIEW DETAIL ====================
    function viewProduk(id) {
        const p = allProduk.find(item => (item.id || item.barang_id) == id);
        if (!p) return;

        document.getElementById('view_nama').textContent = p.nama;
        document.getElementById('view_kategori').textContent = getKategoriNama(p.id_kategori);
        document.getElementById('view_stok').textContent = p.stok;
        document.getElementById('view_harga').textContent = formatRupiah(p.harga);
        document.getElementById('view_diskon').textContent = p.diskon_persen ? `${p.diskon_persen}%` : '0%';
        document.getElementById('view_deskripsi').textContent = p.deskripsi || '-';

        const fotoUrl = p.foto_produk ? `/storage/${p.foto_produk}` : null;
        if (fotoUrl) {
            document.getElementById('view_foto').src = fotoUrl;
            document.getElementById('view_foto').classList.remove('hidden');
            document.getElementById('view_no_foto').classList.add('hidden');
        } else {
            document.getElementById('view_foto').classList.add('hidden');
            document.getElementById('view_no_foto').classList.remove('hidden');
            document.getElementById('view_no_foto').classList.add('flex');
        }

        document.getElementById('viewModal').classList.remove('hidden');
        document.getElementById('viewModal').classList.add('flex');
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
        document.getElementById('viewModal').classList.remove('flex');
    }

    // ==================== MODAL KATEGORI ====================
    function openKategoriModal() {
        document.getElementById('kategoriForm').reset();
        document.getElementById('kategoriModal').classList.remove('hidden');
        document.getElementById('kategoriModal').classList.add('flex');
    }

    function closeKategoriModal() {
        document.getElementById('kategoriModal').classList.add('hidden');
        document.getElementById('kategoriModal').classList.remove('flex');
    }

    async function submitKategori(e) {
        e.preventDefault();
        const btn = document.getElementById('submitKategoriBtn');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        try {
            const payload = {
                nama_kategori: document.getElementById('nama_kategori').value,
                satuan: document.getElementById('satuan').value
            };

            const res = await fetch('/api-proxy/supplier/kategori', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify(payload)
            });

            const json = await res.json();
            if (!res.ok || !json.success) {
                Swal.fire('Gagal', json.message || 'Gagal menyimpan kategori.', 'error');
                return;
            }

            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Kategori ditambahkan!', showConfirmButton: false, timer: 2000 });
            closeKategoriModal();
            await loadKategori(); // Refresh dropdown
        } catch (err) {
            Swal.fire('Error', 'Terjadi kesalahan.', 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Simpan';
        }
    }

    // ==================== CRUD ACTIONS (HANYA CREATE BARANG) ====================
    async function submitProduk(e) {
        e.preventDefault();

        const fotoFile = document.getElementById('foto_produk').files[0];
        const submitBtn = document.getElementById('submitBtn');

        const formData = new FormData();
        formData.append('nama', document.getElementById('nama').value);
        formData.append('id_kategori', document.getElementById('id_kategori').value);
        formData.append('stok', document.getElementById('stok').value);
        formData.append('harga', document.getElementById('harga').value);
        formData.append('diskon_persen', document.getElementById('diskon_persen').value || 0);
        formData.append('deskripsi', document.getElementById('deskripsi').value || '');

        if (fotoFile) {
            formData.append('foto_produk', fotoFile);
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';

        try {
            const res = await fetch(API_BASE_WRITE, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: formData,
            });

            const json = await res.json();

            if (!res.ok || !json.success) {
                Swal.fire('Gagal', json.message || 'Gagal menyimpan produk. Cek kembali data yang diisi.', 'error');
                return;
            }

            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Produk ditambahkan!', showConfirmButton: false, timer: 2000 });
            closeModal();
            await loadProduk();
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Simpan';
        }
    }

    // ==================== PREVIEW NAMA FILE FOTO ====================
    function setupFotoPreview() {
        const fotoInput = document.getElementById('foto_produk');
        const fileNamePreview = document.getElementById('file-name-preview');
        const fileNameText = document.getElementById('file-name-text');

        if (!fotoInput) return;

        fotoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                fileNameText.textContent = this.files[0].name;
                fileNamePreview.classList.remove('hidden');
                fileNamePreview.classList.add('flex');
            } else {
                fileNamePreview.classList.add('hidden');
                fileNamePreview.classList.remove('flex');
            }
        });
    }

    // ==================== INIT ====================
    document.addEventListener('DOMContentLoaded', async () => {
        setupFotoPreview();
        await loadKategori();
        await loadProduk();
    });
</script>
@endpush

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="supplierDashboard()">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Supplier</h1>
            <p class="mt-2 text-sm text-gray-600">Kelola stok, kirim pasokan, dan pantau inventaris barang koperasi secara terpadu.</p>
        </div>
    </div>

    <!-- Ringkasan Metrik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-truck text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Jenis Barang</p>
                <p class="text-xl font-bold text-gray-900" x-text="metrics.total_supplied_items || 0"></p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-box text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Kapasitas Gudang</p>
                <p class="text-xl font-bold text-gray-900">Tersedia</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-clock text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Pasokan Menunggu</p>
                <p class="text-xl font-bold text-gray-900" x-text="metrics.pending_orders || 0"></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Scrollable Forms (3 Aksi) -->
        <div class="xl:col-span-1 flex flex-col h-full">
            <h2 class="text-xl font-bold mb-4 text-gray-800"><i class="fa-solid fa-bolt text-yellow-500 mr-2"></i>Aksi Cepat</h2>
            
            <!-- Area Scrollable -->
            <div class="overflow-y-auto pr-2 space-y-6 pb-4" style="max-height: 700px;">
                
                <!-- CARD 1: Form Kirim Pasokan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-800">1. Kirim Pasokan Stok</h3>
                        <p class="text-xs text-gray-500 mt-1">Tambah stok ke barang yang ada.</p>
                    </div>
                    <div class="p-5">
                        <form @submit.prevent="submitPasokan" class="space-y-4">
                            <div>
                                <label for="barang_id" class="block text-xs font-medium text-gray-700 mb-1">Pilih Barang</label>
                                <select id="barang_id" x-model="formPasokan.barang_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 text-sm p-2.5 border" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <template x-for="item in barangList" :key="item.barang_id">
                                        <option :value="item.barang_id" x-text="item.nama + ' (Stok: ' + item.stok + ')'"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label for="nama_merk" class="block text-xs font-medium text-gray-700 mb-1">Nama Merk / Pemasok</label>
                                <input type="text" id="nama_merk" x-model="formPasokan.nama_merk" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 text-sm p-2.5 border" placeholder="Contoh: PT. Sumber Makmur" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="harga_beli" class="block text-xs font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                                    <input type="number" id="harga_beli" x-model="formPasokan.harga_beli" min="1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 text-sm p-2.5 border" required>
                                </div>
                                <div>
                                    <label for="jumlah" class="block text-xs font-medium text-gray-700 mb-1">Jumlah (Pcs)</label>
                                    <input type="number" id="jumlah" x-model="formPasokan.jumlah" min="1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 text-sm p-2.5 border" required>
                                </div>
                            </div>
                            <button type="submit" :disabled="isSubmittingPasokan" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition-colors disabled:opacity-50 text-sm flex justify-center items-center gap-2">
                                <i class="fa-solid fa-paper-plane" x-show="!isSubmittingPasokan"></i>
                                <i class="fa-solid fa-circle-notch fa-spin" x-show="isSubmittingPasokan"></i>
                                <span x-text="isSubmittingPasokan ? 'Mengirim...' : 'Kirim Pasokan'"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- CARD 2: Form Tambah Barang Baru -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-blue-50">
                        <h3 class="font-bold text-blue-800">2. Daftarkan Barang Baru</h3>
                        <p class="text-xs text-blue-600 mt-1">Daftarkan produk yang belum ada di database.</p>
                    </div>
                    <div class="p-5">
                        <form @submit.prevent="submitBarang" class="space-y-4">
                            <div>
                                <label for="nama_barang" class="block text-xs font-medium text-gray-700 mb-1">Nama Barang Baru</label>
                                <input type="text" id="nama_barang" x-model="formBarang.nama" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm p-2.5 border" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="id_kategori" class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                                    <select id="id_kategori" x-model="formBarang.id_kategori" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm p-2.5 border" required>
                                        <option value="">-- Kategori --</option>
                                        <template x-for="kat in kategoriList" :key="kat.id_kategori">
                                            <option :value="kat.id_kategori" x-text="kat.nama_kategori"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label for="harga_jual" class="block text-xs font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                                    <input type="number" id="harga_jual" x-model="formBarang.harga" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm p-2.5 border" required>
                                </div>
                            </div>
                            <button type="submit" :disabled="isSubmittingBarang" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition-colors disabled:opacity-50 text-sm flex justify-center items-center gap-2">
                                <i class="fa-solid fa-plus" x-show="!isSubmittingBarang"></i>
                                <i class="fa-solid fa-circle-notch fa-spin" x-show="isSubmittingBarang"></i>
                                <span x-text="isSubmittingBarang ? 'Mendaftarkan...' : 'Daftarkan Barang'"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- CARD 3: Form Tambah Kategori Baru -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-purple-50">
                        <h3 class="font-bold text-purple-800">3. Buat Kategori Baru</h3>
                        <p class="text-xs text-purple-600 mt-1">Buat klasifikasi untuk jenis barang baru.</p>
                    </div>
                    <div class="p-5">
                        <form @submit.prevent="submitKategori" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="nama_kategori" class="block text-xs font-medium text-gray-700 mb-1">Nama Kategori</label>
                                    <input type="text" id="nama_kategori" x-model="formKategori.nama_kategori" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 text-sm p-2.5 border" required>
                                </div>
                                <div>
                                    <label for="satuan" class="block text-xs font-medium text-gray-700 mb-1">Satuan Unit</label>
                                    <input type="text" id="satuan" x-model="formKategori.satuan" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 text-sm p-2.5 border" required>
                                </div>
                            </div>
                            <button type="submit" :disabled="isSubmittingKategori" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-sm transition-colors disabled:opacity-50 text-sm flex justify-center items-center gap-2">
                                <i class="fa-solid fa-folder-plus" x-show="!isSubmittingKategori"></i>
                                <i class="fa-solid fa-circle-notch fa-spin" x-show="isSubmittingKategori"></i>
                                <span x-text="isSubmittingKategori ? 'Menyimpan...' : 'Buat Kategori'"></span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- Kolom Kanan: Daftar Barang (View Only) -->
        <div class="xl:col-span-2">
            <h2 class="text-xl font-bold mb-4 text-gray-800"><i class="fa-solid fa-table-list text-gray-500 mr-2"></i>Inventaris Barang</h2>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <p class="text-sm text-gray-500">Daftar seluruh barang (Read-Only). Gunakan form di sebelah kiri untuk mengubah data.</p>
                    <button @click="fetchBarangList(); fetchKategoriList();" class="p-2 text-gray-500 hover:bg-gray-200 rounded-lg transition-colors" title="Segarkan Data">
                        <i class="fa-solid fa-sync-alt"></i>
                    </button>
                </div>
                <div class="overflow-x-auto" style="max-height: 640px;">
                    <table class="w-full text-sm text-left relative">
                        <thead class="bg-white text-gray-500 border-b border-gray-100 sticky top-0 shadow-sm z-10">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Nama Barang</th>
                                <th class="px-6 py-4 font-semibold">Kategori</th>
                                <th class="px-6 py-4 font-semibold text-right">Harga Jual</th>
                                <th class="px-6 py-4 font-semibold text-center">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <template x-for="item in barangList" :key="item.barang_id">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800" x-text="item.nama"></div>
                                        <div class="text-xs text-gray-400 mt-0.5">ID: <span x-text="item.barang_id"></span></div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium border border-gray-200" x-text="item.kategori ? item.kategori.nama_kategori : '-'"></span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga)"></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                                              :class="{'bg-red-100 text-red-800': item.stok === 0, 'bg-green-100 text-green-800': item.stok > 0}"
                                              x-text="item.stok"></span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="barangList.length === 0">
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada barang terdaftar.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('supplierDashboard', () => ({
        metrics: {
            total_supplied_items: 0,
            pending_orders: 0
        },
        barangList: [],
        kategoriList: [],
        formPasokan: {
            barang_id: '',
            nama_merk: '',
            harga_beli: '',
            jumlah: ''
        },
        formBarang: {
            nama: '',
            harga: '',
            deskripsi: '',
            id_kategori: ''
        },
        formKategori: {
            nama_kategori: '',
            satuan: ''
        },
        isSubmittingPasokan: false,
        isSubmittingBarang: false,
        isSubmittingKategori: false,
        
        init() {
            this.fetchMetrics();
            this.fetchBarangList();
            this.fetchKategoriList();
        },
        async fetchMetrics() {
            try {
                const res = await fetch('/api-proxy/supplier/dashboard', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.metrics = data.data;
                }
            } catch (e) {
                console.error("Gagal memuat metrik supplier");
            }
        },
        async fetchBarangList() {
            try {
                const res = await fetch('/api-proxy/supplier/barang', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.barangList = data.data;
                }
            } catch (e) {
                console.error("Gagal memuat daftar barang");
            }
        },
        async fetchKategoriList() {
            try {
                const res = await fetch('/api-proxy/kategori', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.kategoriList = data.data;
                }
            } catch (e) {
                console.error("Gagal memuat daftar kategori");
            }
        },
        async submitPasokan() {
            if (!this.formPasokan.barang_id || !this.formPasokan.jumlah || this.formPasokan.jumlah < 1 || !this.formPasokan.nama_merk || !this.formPasokan.harga_beli) {
                Swal.fire('Peringatan', 'Harap isi semua data pasokan dengan benar.', 'warning');
                return;
            }
            
            this.isSubmittingPasokan = true;
            try {
                const uuid = crypto.randomUUID(); // Anti double-submit
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                
                const res = await fetch('/api-proxy/supplier/pasokan', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Idempotency-Key': uuid
                    },
                    body: JSON.stringify({
                        barang_id: this.formPasokan.barang_id,
                        nama_merk: this.formPasokan.nama_merk,
                        harga_beli: parseInt(this.formPasokan.harga_beli),
                        jumlah: parseInt(this.formPasokan.jumlah)
                    })
                });
                const data = await res.json();
                
                if (res.ok && data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');
                    this.formPasokan.barang_id = '';
                    this.formPasokan.nama_merk = '';
                    this.formPasokan.harga_beli = '';
                    this.formPasokan.jumlah = '';
                    this.fetchBarangList(); // Refresh list agar stok terbaru terlihat
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
            } finally {
                this.isSubmittingPasokan = false;
            }
        },
        async submitBarang() {
            if (!this.formBarang.nama || !this.formBarang.harga || !this.formBarang.id_kategori) {
                Swal.fire('Peringatan', 'Harap lengkapi nama, kategori, dan harga.', 'warning');
                return;
            }

            this.isSubmittingBarang = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                
                const res = await fetch('/api-proxy/supplier/barang', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        nama: this.formBarang.nama,
                        harga: parseFloat(this.formBarang.harga),
                        deskripsi: this.formBarang.deskripsi,
                        id_kategori: this.formBarang.id_kategori
                    })
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');
                    this.formBarang.nama = '';
                    this.formBarang.harga = '';
                    this.formBarang.deskripsi = '';
                    this.formBarang.id_kategori = '';
                    this.fetchBarangList(); // Segarkan data barang karena ada yg baru
                } else {
                    let errorMessage = data.message;
                    if (data.data && typeof data.data === 'object') {
                        errorMessage += "\n" + Object.values(data.data).flat().join('\n');
                    }
                    Swal.fire('Gagal', errorMessage || 'Terjadi kesalahan.', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
            } finally {
                this.isSubmittingBarang = false;
            }
        },
        async submitKategori() {
            if (!this.formKategori.nama_kategori || !this.formKategori.satuan) {
                Swal.fire('Peringatan', 'Harap isi nama kategori dan satuannya.', 'warning');
                return;
            }

            this.isSubmittingKategori = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                
                const res = await fetch('/api-proxy/supplier/kategori', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        nama_kategori: this.formKategori.nama_kategori,
                        satuan: this.formKategori.satuan
                    })
                });
                const data = await res.json();

                if (res.ok && data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');
                    this.formKategori.nama_kategori = '';
                    this.formKategori.satuan = '';
                    this.fetchKategoriList(); // Segarkan data kategori agar tab Tambah Barang terupdate
                } else {
                    let errorMessage = data.message;
                    if (data.data && typeof data.data === 'object') {
                        errorMessage += "\n" + Object.values(data.data).flat().join('\n');
                    }
                    Swal.fire('Gagal', errorMessage || 'Terjadi kesalahan.', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
            } finally {
                this.isSubmittingKategori = false;
            }
        }
    }));
});
</script>
@endsection

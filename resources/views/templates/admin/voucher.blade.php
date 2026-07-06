@extends('layouts.app')

@section('title', 'Manajemen Voucher')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="voucherManager()">
    
    <!-- Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Manajemen Voucher ✨</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola kupon diskon untuk pelanggan koperasi Anda.</p>
        </div>
        <button type="button" @click.prevent="openModal()" class="bg-[#2D7A42] hover:bg-[#1E5C2F] text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Voucher
        </button>
    </div>

    <!-- Table Container -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-sm font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-6 py-4">Kode Voucher</th>
                        <th class="px-6 py-4">Produk / Barang</th>
                        <th class="px-6 py-4">Tipe & Diskon</th>
                        <th class="px-6 py-4">Kuota</th>
                        <th class="px-6 py-4">Kedaluwarsa</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <!-- Loading State -->
                    <tr x-show="isLoading" class="animate-pulse">
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                            <i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Memuat data voucher...</p>
                        </td>
                    </tr>
                    <!-- Empty State -->
                    <tr x-show="!isLoading && vouchers.length === 0">
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                            Tidak ada voucher yang tersedia.
                        </td>
                    </tr>
                    <!-- Data Rows -->
                    <template x-for="voucher in vouchers" :key="voucher.id_voucher">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800" x-text="voucher.kode_voucher"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-600" x-text="voucher.barang ? voucher.barang.nama : 'Semua Barang'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="voucher.tipe_voucher === 'langsung' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'"
                                          x-text="voucher.tipe_voucher.toUpperCase()"></span>
                                    <span class="text-[#2D7A42] font-bold" x-text="parseFloat(voucher.potongan_persen) + '%'"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-600 font-medium" x-text="voucher.kuota + ' tersisa'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-500 text-xs" x-text="formatDate(voucher.expired_at)"></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="deleteVoucher(voucher.id_voucher)" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Tambah/Edit -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <form @submit.prevent="saveVoucher">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                    Tambah Voucher Baru
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Voucher</label>
                                        <input type="text" x-model="form.kode_voucher" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/50 focus:border-[#2D7A42]">
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Diskon (%)</label>
                                            <input type="number" step="0.01" min="1" max="100" x-model="form.potongan_persen" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/50 focus:border-[#2D7A42]">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kuota</label>
                                            <input type="number" min="1" x-model="form.kuota" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/50 focus:border-[#2D7A42]">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Untuk Produk</label>
                                        <select x-model="form.barang_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/50 focus:border-[#2D7A42]">
                                            <option value="">Semua Barang (Berlaku untuk semua)</option>
                                            <template x-for="item in products" :key="item.barang_id">
                                                <option :value="item.barang_id" x-text="item.nama"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Voucher</label>
                                        <select x-model="form.tipe_voucher" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/50 focus:border-[#2D7A42]">
                                            <option value="langsung">Langsung (Ketik saat checkout)</option>
                                            <option value="claim">Claim (User harus simpan dulu)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kedaluwarsa</label>
                                        <input type="datetime-local" x-model="form.expired_at" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2D7A42]/50 focus:border-[#2D7A42]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-[#2D7A42] text-base font-medium text-white hover:bg-[#1E5C2F] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors disabled:opacity-50" :disabled="isSaving">
                            <i x-show="isSaving" class="fa-solid fa-spinner fa-spin mr-2"></i> Simpan
                        </button>
                        <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('voucherManager', () => ({
        vouchers: [],
        products: [],
        isLoading: false,
        isSaving: false,
        isModalOpen: false,
        form: {
            kode_voucher: '',
            potongan_persen: '',
            kuota: '',
            barang_id: '',
            tipe_voucher: 'langsung',
            expired_at: ''
        },

        init() {
            this.fetchData();
            this.fetchProducts();
        },

        async fetchData() {
            this.isLoading = true;
            try {
                const res = await fetch('/api-proxy/voucher', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if(data.success) {
                    this.vouchers = data.data;
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Gagal memuat data voucher', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async fetchProducts() {
            try {
                const res = await fetch('/api-proxy/barang', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if(data.success) {
                    this.products = data.data;
                }
            } catch (err) {
                console.error('Failed to load products');
            }
        },

        openModal() {
            this.form = {
                kode_voucher: '',
                potongan_persen: '',
                kuota: '',
                barang_id: '',
                tipe_voucher: 'langsung',
                expired_at: ''
            };
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
        },

        formatDate(datetime) {
            if(!datetime) return '-';
            const d = new Date(datetime);
            return d.toLocaleString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'});
        },

        async saveVoucher() {
            this.isSaving = true;
            try {
                // Konversi tanggal ke format Y-m-d H:i:s
                let formattedDate = this.form.expired_at;
                if (formattedDate && formattedDate.includes('T')) {
                    formattedDate = formattedDate.replace('T', ' ') + ':00';
                }

                const payload = {
                    ...this.form,
                    barang_id: this.form.barang_id === '' ? null : this.form.barang_id,
                    expired_at: formattedDate
                };

                const res = await fetch('/api-proxy/admin/voucher', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                if (data.success) {
                    Swal.fire('Berhasil', 'Voucher ditambahkan', 'success');
                    this.closeModal();
                    this.fetchData();
                } else {
                    Swal.fire('Gagal', data.message || 'Data tidak valid', 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Gagal menyimpan', 'error');
            } finally {
                this.isSaving = false;
            }
        },

        async deleteVoucher(id) {
            const confirmed = await Swal.fire({
                title: 'Hapus Voucher?',
                text: "Voucher yang dihapus tidak bisa dikembalikan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            });

            if (confirmed.isConfirmed) {
                try {
                    const res = await fetch(`/api-proxy/admin/voucher/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await res.json();
                    
                    if (data.success) {
                        Swal.fire('Terhapus!', 'Voucher berhasil dihapus.', 'success');
                        this.fetchData();
                    } else {
                        Swal.fire('Gagal', data.message || 'Voucher gagal dihapus', 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Server terputus', 'error');
                }
            }
        }
    }));
});
</script>
@endpush
@endsection

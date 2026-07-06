@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="p-6" x-data="kelolaUserPage()">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola User</h1>
            <p class="text-sm text-gray-500 mt-1">Atur role pengguna (khusus non-customer).</p>
        </div>
    </div>

    {{-- Card Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

        {{-- Loading State --}}
        <div x-show="isLoading" class="text-center py-10 text-gray-400 text-sm">
            <i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat data user...
        </div>

        {{-- Empty State --}}
        <div x-show="!isLoading && users.length === 0" style="display: none;" class="text-center py-10 text-gray-400 text-sm">
            Tidak ada user (non-customer) yang ditemukan.
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto" x-show="!isLoading && users.length > 0" style="display: none;">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-gray-400 border-b border-gray-100">
                        <th class="py-3 pr-4 font-semibold">Username</th>
                        <th class="py-3 pr-4 font-semibold">Email</th>
                        <th class="py-3 pr-4 font-semibold">Nama</th>
                        <th class="py-3 pr-4 font-semibold">Role saat ini</th>
                        <th class="py-3 pr-4 font-semibold text-right">Aksi (Ubah Role)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="user in users" :key="user.id_users">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 pr-4 text-gray-900 font-medium" x-text="user.username"></td>
                            <td class="py-3 pr-4 text-gray-500" x-text="user.email"></td>
                            <td class="py-3 pr-4 text-gray-900" x-text="user.profile?.name || '-'"></td>
                            <td class="py-3 pr-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold"
                                      :class="{
                                          'bg-blue-100 text-blue-700': user.role === 'admin',
                                          'bg-purple-100 text-purple-700': user.role === 'manager',
                                          'bg-yellow-100 text-yellow-700': user.role === 'supplier',
                                          'bg-green-100 text-green-700': user.role === 'staff'
                                      }"
                                      x-text="user.role.toUpperCase()">
                                </span>
                            </td>
                            <td class="py-3 pr-4 text-right">
                                <select 
                                    x-model="user.new_role"
                                    @change="updateRole(user.id_users, user.new_role)"
                                    class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#2D7A42] focus:border-transparent">
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                    <option value="manager">Manager</option>
                                    <option value="supplier">Supplier</option>
                                </select>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('kelolaUserPage', () => ({
            users: [],
            isLoading: true,

            async init() {
                await this.fetchUsers();
            },

            async fetchUsers() {
                this.isLoading = true;
                try {
                    const res = await fetch('/api-proxy/admin/users', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    
                    if (data.success) {
                        // Filter out customer role, and set new_role to current role
                        this.users = data.data.filter(u => u.role !== 'customer').map(u => ({
                            ...u,
                            new_role: u.role
                        }));
                    }
                } catch (error) {
                    console.error('Error fetching users:', error);
                    Swal.fire('Error', 'Gagal memuat data user.', 'error');
                } finally {
                    this.isLoading = false;
                }
            },

            async updateRole(userId, newRole) {
                // Show loading toast
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang mengubah role user',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    
                    const res = await fetch(`/api-proxy/admin/users/${userId}`, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            role: newRole
                        })
                    });
                    
                    const data = await res.json();
                    
                    if (res.ok && data.success) {
                        Swal.fire({ 
                            toast: true, 
                            position: 'top-end', 
                            icon: 'success', 
                            title: 'Role berhasil diubah!', 
                            showConfirmButton: false, 
                            timer: 2000 
                        });
                        
                        // Update current role display locally
                        const userIndex = this.users.findIndex(u => u.id_users === userId);
                        if (userIndex !== -1) {
                            this.users[userIndex].role = newRole;
                        }
                    } else {
                        throw new Error(data.message || 'Gagal mengubah role');
                    }
                } catch (error) {
                    console.error('Error updating role:', error);
                    Swal.fire('Gagal', error.message || 'Terjadi kesalahan saat menyimpan.', 'error');
                    
                    // Revert select back to original role
                    const userIndex = this.users.findIndex(u => u.id_users === userId);
                    if (userIndex !== -1) {
                        this.users[userIndex].new_role = this.users[userIndex].role;
                    }
                }
            }
        }));
    });
</script>
@endpush
@endsection

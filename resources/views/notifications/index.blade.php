@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4" x-data="{
    notifs: [],
    loading: true,
    async init() {
        try {
            const res = await fetch('/api-proxy/notifications');
            const data = await res.json();
            if(data.success && data.data) {
                this.notifs = data.data.notifications;
            }
        } catch(e) { console.error(e); }
        this.loading = false;
    }
}">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Semua Notifikasi</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <template x-if="loading">
            <div class="p-12 text-center text-gray-400">
                <i class="fa-solid fa-spinner fa-spin text-2xl mb-3"></i>
                <p>Memuat notifikasi...</p>
            </div>
        </template>
        
        <template x-if="!loading && notifs.length === 0">
            <div class="p-12 text-center text-gray-400">
                <i class="fa-regular fa-bell-slash text-4xl mb-3 opacity-50"></i>
                <p>Belum ada notifikasi.</p>
            </div>
        </template>

        <template x-if="!loading && notifs.length > 0">
            <div class="divide-y divide-gray-100">
                <template x-for="notif in notifs" :key="notif.id">
                    <div class="p-5 flex items-start gap-4 hover:bg-gray-50 transition-colors" :class="{'bg-blue-50/20': !notif.read_at}">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" 
                             :class="{
                                'bg-blue-100 text-blue-600': notif.type === 'info' || !notif.type,
                                'bg-red-100 text-red-600': notif.type === 'alert',
                                'bg-green-100 text-green-600': notif.type === 'success'
                             }">
                            <i class="fa-solid fa-circle-info" x-show="notif.type === 'info' || !notif.type"></i>
                            <i class="fa-solid fa-triangle-exclamation" x-show="notif.type === 'alert'"></i>
                            <i class="fa-solid fa-circle-check" x-show="notif.type === 'success'"></i>
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="text-[14.5px] font-bold text-gray-800" x-text="notif.title || notif.data?.title"></h3>
                            <p class="text-[13.5px] text-gray-600 mt-1 leading-relaxed" x-text="notif.message || notif.data?.message"></p>
                            <div class="text-[11.5px] text-gray-400 mt-3 font-medium flex items-center gap-1.5">
                                <i class="fa-regular fa-clock"></i>
                                <span x-text="window.formatDateFromGMT ? window.formatDateFromGMT(notif.created_at) : notif.created_at"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>
@endsection

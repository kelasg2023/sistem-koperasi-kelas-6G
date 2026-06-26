@extends('layouts.app')
@section('title', 'Beranda')

{{-- Breadcrumb opsional --}}
@section('breadcrumb')
    <span>Beranda</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Selamat datang! 👋</h1>
        <p class="mt-1 text-gray-500 dark:text-gray-400">Ini adalah halaman beranda dengan navbar + sidebar.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach ([['Pengguna', '128', 'indigo'], ['Laporan', '34', 'emerald'], ['Peringatan', '5', 'rose']] as [$label, $val, $color])
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
            <p class="mt-1 text-3xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ $val }}</p>
        </div>
        @endforeach
    </div>

</div>
@endsection

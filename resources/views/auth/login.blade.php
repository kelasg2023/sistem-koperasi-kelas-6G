@extends('layouts.app')
@section('title', 'Masuk')

{{-- Dengan mendefinisikan section ini, navbar & sidebar TIDAK akan ditampilkan --}}
@section('no-chrome')
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 to-slate-100 dark:from-gray-950 dark:to-gray-900 p-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 p-8 space-y-6">

        {{-- Brand --}}
        <div class="text-center">
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white text-xl font-bold">
                {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
            </span>
            <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Masuk ke akun Anda</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Masukkan email dan password Anda</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input id="email" type="email" name="email" required autofocus
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700
                              bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                              focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none
                              transition text-sm"
                       placeholder="nama@contoh.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700
                              bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                              focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none
                              transition text-sm"
                       placeholder="••••••••">
            </div>

            <button type="submit"
                    class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                           text-white font-semibold rounded-lg transition-colors duration-150 text-sm">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Daftar</a>
        </p>
    </div>
</div>
@endsection

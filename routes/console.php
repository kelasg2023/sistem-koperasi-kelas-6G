<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal penghapusan riwayat/sampah database secara otomatis
Schedule::command('model:prune')->daily();

// Mengecek dan mengcancel transaksi / topup midtrans expired setiap jam
Schedule::job(new \App\Jobs\CancelExpiredTransactionJob)->hourly();

// Pengecekan otomatis stok kritis setiap jam 08:00 pagi
Schedule::job(new \App\Jobs\StockAlertNotificationJob)->dailyAt('08:00');

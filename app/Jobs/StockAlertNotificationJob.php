<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StockAlertNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $mlUrl = config('app.ml_service_url', 'http://127.0.0.1:5610/api/v1');
            $response = Http::get("{$mlUrl}/stok/alert");

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['kritis']) && count($data['kritis']) > 0) {
                    Log::warning('StockAlertNotificationJob: Terdapat ' . count($data['kritis']) . ' barang dengan stok kritis.');
                    // Di sini Anda bisa menambahkan logika pengiriman email ke supplier / admin
                    // foreach ($data['kritis'] as $item) {
                    //     SendEmailJob::dispatch($adminEmail, 'Stock Kritis: ' . $item['nama']);
                    // }
                } else {
                    Log::info('StockAlertNotificationJob: Stok aman, tidak ada alert.');
                }
            } else {
                Log::error('StockAlertNotificationJob: Gagal mengambil data dari ML Service. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('StockAlertNotificationJob Error: ' . $e->getMessage());
        }
    }
}

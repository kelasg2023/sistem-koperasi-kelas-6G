<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MLService
{
    protected string $baseUrl;

    public function __construct()
    {
        // Secara default menggunakan port 5610 sesuai aplikasi python-starter
        $this->baseUrl = config('services.ml.url', 'http://127.0.0.1:5610/api/v1');
    }

    /**
     * Cek indikasi fraud pada transaksi.
     * Mengembalikan response ML atau null jika gagal.
     */
    public function checkFraud(array $data)
    {
        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/fraud/check", [
                'transaction_id' => $data['transaction_id'],
                'user_id' => $data['user_id'],
                'total_harga' => $data['total_harga'],
                'payment_method' => $data['payment_method'],
                'created_at' => $data['created_at'],
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            
            Log::warning("ML Service Fraud Check gagal dengan status: " . $response->status(), ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error("Koneksi ke ML Service (Fraud Check) gagal: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Dapatkan produk terlaris.
     */
    public function getProdukLaris(string $periode = '7d', int $limit = 5)
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/produk/laris", [
                'periode' => $periode,
                'limit' => $limit,
            ]);

            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error("Koneksi ke ML Service (Produk Laris) gagal: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Dapatkan rekomendasi personal untuk anggota.
     */
    public function getRekomendasiAnggota(int $anggotaId, int $limit = 3)
    {
        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/rekomendasi/anggota/{$anggotaId}", [
                'num_recommendations' => $limit,
            ]);

            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error("Koneksi ke ML Service (Rekomendasi) gagal: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Dapatkan prediksi stok & reorder point.
     */
    public function getStokPrediksi(int $leadTime = 3, float $serviceLevel = 0.95)
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/stok/prediksi", [
                'lead_time' => $leadTime,
                'service_level' => $serviceLevel,
            ]);

            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error("Koneksi ke ML Service (Stok Prediksi) gagal: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Dapatkan peringatan barang kritis / perlu reorder.
     */
    public function getStokAlert(int $leadTime = 3, float $serviceLevel = 0.95)
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/stok/alert", [
                'lead_time' => $leadTime,
                'service_level' => $serviceLevel,
            ]);

            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error("Koneksi ke ML Service (Stok Alert) gagal: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Dapatkan kalkulasi safety stock mendetail untuk 1 produk.
     */
    public function getSafetyStock(int $produkId, int $leadTime = 3, float $serviceLevel = 0.95)
    {
        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/stok/safety/{$produkId}", [
                'lead_time' => $leadTime,
                'service_level' => $serviceLevel,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error("Koneksi ke ML Service (Safety Stock) gagal: " . $e->getMessage());
            return null;
        }
    }
}

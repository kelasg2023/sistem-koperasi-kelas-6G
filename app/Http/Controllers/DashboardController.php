<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    private function apiGet(string $path, array $query = []): array
    {
        $token   = Cookie::get('api_token');
        $baseUrl = env('API_BASE_URL', 'http://localhost:8000/api');

        try {
            $client = Http::withHeaders(['Accept' => 'application/json']);
            if ($token) {
                $client = $client->withToken($token);
            }

            $res = $client->get($baseUrl . $path, $query);
            \Illuminate\Support\Facades\Log::info("API_GET {$path} | Status: " . $res->status() . " | HasToken: " . ($token ? 'YES' : 'NO') . " | Body: " . Str::limit($res->body(), 100));
            return $res->json() ?? [];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("API_GET_ERROR {$path}: " . $e->getMessage());
            return [];
        }
    }

    public function index()
    {
        // -- Profil user dari session (sudah ada saat login) --
        $user    = session('user', []);
        
        if (isset($user['role'])) {
            $redirectMap = [
                'admin'    => 'admin.produk.index',
                'staff'    => 'staff.dashboard',
                'manager'  => 'manager.dashboard',
                'supplier' => 'supplier.dashboard',
            ];
            if (isset($redirectMap[$user['role']])) {
                return redirect()->route($redirectMap[$user['role']]);
            }
        }

        $profile = $user['profile'] ?? [];

        // -- Wallet saldo --
        $walletRes = $this->apiGet('/api-data/wallet');
        $wallet    = $walletRes['data'] ?? null;

        // -- Riwayat transaksi (5 terakhir) --
        $historyRes = $this->apiGet('/transaction/history', ['per_page' => 5]);
        $riwayat    = $historyRes['data']['data'] ?? [];

        // -- Cari transaksi aktif (status proses / shipped) --
        $activeOrder = null;
        foreach ($riwayat as $trx) {
            if (in_array($trx['status'] ?? '', ['proses']) ||
                in_array($trx['status_pengiriman'] ?? '', ['pending', 'dikemas', 'dikirim'])) {
                $activeOrder = $trx;
                break;
            }
        }

        // -- Dashboard summary dari backend --
        $dashRes   = $this->apiGet('/dashboard');
        $dashboard = $dashRes['data'] ?? [];

        // -- Barang rekomendasi (ML) --
        $rekomendasiRes = $this->apiGet('/rekomendasi', ['limit' => 6]);
        $barangs   = $rekomendasiRes['data'] ?? [];


        // -- Kategori --
        $kategoriRes = $this->apiGet('/kategori');
        $kategoris   = $kategoriRes['data'] ?? [];

        // -- Voucher yang tersedia --
        $voucherRes = $this->apiGet('/voucher');
        $vouchers   = $voucherRes['data'] ?? [];

        return view('dashboard_user', compact(
            'user', 'profile', 'wallet', 'riwayat',
            'activeOrder', 'dashboard', 'barangs', 'kategoris', 'vouchers'
        ));
    }

    public function welcome()
    {
        // -- Barang populer dari ML (12 produk) --
        $barangRes = $this->apiGet('/produk/laris', ['limit' => 12]);
        $barangs   = $barangRes['data'] ?? [];

        // -- Kategori --
        $kategoriRes = $this->apiGet('/kategori');
        $kategoris   = $kategoriRes['data'] ?? [];

        // -- Produk promo (yang ada diskon) --
        $promoRes = $this->apiGet('/barang', ['per_page' => 6, 'in_stock' => true]);
        $promos   = array_filter($promoRes['data']['data'] ?? [], fn($b) => ($b['diskon_persen'] ?? 0) > 0);

        return view('welcome', compact('barangs', 'kategoris', 'promos'));
    }
}

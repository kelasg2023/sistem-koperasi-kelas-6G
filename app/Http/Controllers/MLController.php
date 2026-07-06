<?php

namespace App\Http\Controllers;

use App\Services\MLService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class MLController extends Controller
{
    use ApiResponse;

    protected $mlService;

    public function __construct(MLService $mlService)
    {
        $this->mlService = $mlService;
    }

    public function getProdukLaris(Request $request)
    {
        $periode = $request->query('periode', '7d');
        $limit = $request->query('limit', 5);
        $data = $this->mlService->getProdukLaris($periode, $limit);
        
        // Map to Barang models
        $barangIds = collect($data)->pluck('barang_id')->toArray();
        $barangs = \App\Models\Barang::whereIn('barang_id', $barangIds)->get()->keyBy('barang_id');
        
        $result = collect($data)->map(function($item) use ($barangs) {
            if (isset($item['barang_id']) && $barangs->has($item['barang_id'])) {
                $barang = $barangs[$item['barang_id']]->toArray();
                $barang['ml_keterangan'] = $item['keterangan'] ?? null;
                $barang['ml_score'] = $item['terjual'] ?? null;
                return $barang;
            }
            return null;
        })->filter()->values();

        return $this->successResponse($result, 'Berhasil mengambil produk laris dari AI');
    }

    public function getRekomendasi(Request $request)
    {
        $user = $request->user();
        $limit = $request->query('limit', 3);
        $data = $this->mlService->getRekomendasiAnggota($user->id_users, $limit);
        
        // Map to Barang models
        $barangIds = collect($data)->pluck('barang_id')->toArray();
        $barangs = \App\Models\Barang::whereIn('barang_id', $barangIds)->get()->keyBy('barang_id');
        
        $result = collect($data)->map(function($item) use ($barangs) {
            if (isset($item['barang_id']) && $barangs->has($item['barang_id'])) {
                $barang = $barangs[$item['barang_id']]->toArray();
                $barang['ml_keterangan'] = $item['keterangan'] ?? null;
                return $barang;
            }
            return null;
        })->filter()->values();

        return $this->successResponse($result, 'Berhasil mengambil rekomendasi untuk Anda');
    }

    public function getStokPrediksi(Request $request)
    {
        $leadTime = $request->query('lead_time', 3);
        $serviceLevel = $request->query('service_level', 0.95);
        $data = $this->mlService->getStokPrediksi($leadTime, $serviceLevel);
        
        return $this->successResponse($data, 'Berhasil mengambil prediksi stok');
    }

    public function getStokAlert(Request $request)
    {
        $leadTime = $request->query('lead_time', 3);
        $serviceLevel = $request->query('service_level', 0.95);
        $data = $this->mlService->getStokAlert($leadTime, $serviceLevel);
        
        return $this->successResponse($data, 'Berhasil mengambil alert stok kritis');
    }

    public function getSafetyStock(Request $request, $id)
    {
        $leadTime = $request->query('lead_time', 3);
        $serviceLevel = $request->query('service_level', 0.95);
        $data = $this->mlService->getSafetyStock($id, $leadTime, $serviceLevel);
        
        if (!$data) {
            return $this->errorResponse('Gagal mengambil kalkulasi safety stock atau produk tidak ditemukan', 404);
        }
        
        return $this->successResponse($data, 'Berhasil mengambil detail safety stock');
    }
}

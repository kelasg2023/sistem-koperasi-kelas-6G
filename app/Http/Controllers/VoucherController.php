<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class VoucherController extends Controller
{
    /**
     * GET /api/voucher
     * Ambil semua voucher beserta info barang yang terkait.
     */
    public function index(): JsonResponse
    {
        $vouchers = Voucher::with('barang:barang_id,nama')->get();

        return response()->json([
            'success' => true,
            'data'    => $vouchers,
        ]);
    }

    /**
     * GET /api/voucher/{id}
     * Ambil detail satu voucher berdasarkan id_voucher.
     */
    public function show(int $id): JsonResponse
    {
        $voucher = Voucher::with('barang:barang_id,nama')->find($id);

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $voucher,
        ]);
    }

    /**
     * POST /api/voucher
     * Buat voucher baru. Validasi dilakukan di StoreVoucherRequest.
     */
    public function store(StoreVoucherRequest $request): JsonResponse
    {
        $voucher = Voucher::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dibuat.',
            'data'    => $voucher->load('barang:barang_id,nama'),
        ], 201);
    }

    /**
     * PUT /api/voucher/{id}
     * Update data voucher. Hanya field yang dikirim yang akan diupdate.
     */
    public function update(UpdateVoucherRequest $request, int $id): JsonResponse
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan.',
            ], 404);
        }

        $voucher->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diperbarui.',
            'data'    => $voucher->fresh()->load('barang:barang_id,nama'),
        ]);
    }

    /**
     * DELETE /api/voucher/{id}
     * Hapus voucher. Cek dulu apakah voucher pernah dipakai di transaksi.
     */
    public function destroy(int $id): JsonResponse
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan.',
            ], 404);
        }

        // Cegah hapus voucher yang sudah pernah dipakai di transaksi
        if ($voucher->transactionDetails()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak dapat dihapus karena sudah pernah digunakan dalam transaksi.',
            ], 422);
        }

        $voucher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dihapus.',
        ]);
    }

    /**
     * GET /api/voucher/check/{kode}
     * Cek apakah kode voucher valid untuk digunakan:
     *  - Voucher harus ada
     *  - Belum kadaluarsa
     *  - Masih ada kuota
     */
    public function check(string $kode): JsonResponse
    {
        $voucher = Voucher::where('kode_voucher', $kode)
            ->with('barang:barang_id,nama')
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak ditemukan.',
            ], 404);
        }

        // Cek kadaluarsa
        if (Carbon::parse($voucher->expired_at)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah kadaluarsa.',
            ], 422);
        }

        // Cek kuota
        if ($voucher->kuota <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota voucher sudah habis.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Voucher valid dan dapat digunakan.',
            'data'    => $voucher,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClaimVoucherRequest;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Http\Requests\UseVoucherRequest;
use App\Models\Voucher;
use App\Models\VoucherClaim;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    /**
     * GET /api/voucher
     * Ambil semua voucher beserta info barang yang terkait.
     */
    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $vouchers = Voucher::with('barang:barang_id,nama');
        
        if ($request->user()) {
            $userId = $request->user()->id_users;
            $vouchers = $vouchers->with(['claims' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }
        
        $vouchers = $vouchers->get();

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
     * Cek apakah kode voucher valid untuk digunakan.
     * Response menyertakan tipe_voucher dan jumlah klaim yang sudah ada.
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

        if ($voucher->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah kadaluarsa.',
            ], 422);
        }

        if (!$voucher->hasStock()) {
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

    /**
     * POST /api/voucher/claim
     *
     * Digunakan untuk voucher bertipe 'claim'. User mengklaim voucher terlebih dahulu
     * sebelum dapat menggunakannya. Setiap user hanya boleh claim satu kali per voucher.
     *
     * Body: { user_id, kode_voucher }
     */
    public function claim(ClaimVoucherRequest $request): JsonResponse
    {
        $voucher = Voucher::where('kode_voucher', $request->kode_voucher)->first();

        // Pastikan tipe voucher adalah 'claim'
        if ($voucher->tipe_voucher !== 'claim') {
            return response()->json([
                'success' => false,
                'message' => 'Voucher ini bertipe "langsung" dan tidak perlu diklaim terlebih dahulu.',
            ], 422);
        }

        if ($voucher->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah kadaluarsa dan tidak dapat diklaim.',
            ], 422);
        }

        if (!$voucher->hasStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota voucher sudah habis.',
            ], 422);
        }

        // Cek apakah user sudah pernah claim voucher ini
        $alreadyClaimed = VoucherClaim::where('user_id', $request->user_id)
            ->where('id_voucher', $voucher->id_voucher)
            ->exists();

        if ($alreadyClaimed) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah mengklaim voucher ini.',
            ], 422);
        }

        // Simpan klaim dalam transaksi DB
        $claim = DB::transaction(function () use ($request, $voucher) {
            return VoucherClaim::create([
                'user_id'    => $request->user_id,
                'id_voucher' => $voucher->id_voucher,
                'status'     => 'claimed',
                'claimed_at' => Carbon::now(),
                'used_at'    => null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diklaim. Gunakan sebelum tanggal ' . Carbon::parse($voucher->expired_at)->format('d-m-Y H:i') . '.',
            'data'    => [
                'claim'   => $claim,
                'voucher' => $voucher->only(['id_voucher', 'kode_voucher', 'potongan_persen', 'tipe_voucher', 'expired_at']),
            ],
        ], 201);
    }

    /**
     * POST /api/voucher/use
     *
     * Digunakan untuk mengkonsumsi voucher:
     * - Tipe 'langsung': langsung kurangi kuota tanpa perlu klaim.
     * - Tipe 'claim'   : cari record di voucher_claims (status=claimed), update ke 'used',
     *                    isi used_at, dan kurangi kuota voucher.
     *
     * Seluruh operasi dibungkus DB::transaction() + lockForUpdate() untuk konsistensi data.
     *
     * Body: { user_id, kode_voucher }
     */
    public function use(UseVoucherRequest $request): JsonResponse
    {
        $voucher = Voucher::where('kode_voucher', $request->kode_voucher)->first();

        if ($voucher->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah kadaluarsa.',
            ], 422);
        }

        if (!$voucher->hasStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota voucher sudah habis.',
            ], 422);
        }

        // ── Tipe: langsung ───────────────────────────────────────────────────
        if ($voucher->tipe_voucher === 'langsung') {
            DB::transaction(function () use ($voucher) {
                // Lock row untuk menghindari race condition
                Voucher::where('id_voucher', $voucher->id_voucher)->lockForUpdate()->first();
                $voucher->decrement('kuota');
            });

            return response()->json([
                'success' => true,
                'message' => 'Voucher langsung berhasil digunakan.',
                'data'    => [
                    'potongan_persen' => $voucher->potongan_persen,
                    'sisa_kuota'      => $voucher->kuota - 1,
                ],
            ]);
        }

        // ── Tipe: claim ──────────────────────────────────────────────────────
        $claim = VoucherClaim::where('user_id', $request->user_id)
            ->where('id_voucher', $voucher->id_voucher)
            ->where('status', 'claimed')
            ->first();

        if (!$claim) {
            // Cek apakah sudah pernah digunakan sebelumnya
            $usedClaim = VoucherClaim::where('user_id', $request->user_id)
                ->where('id_voucher', $voucher->id_voucher)
                ->where('status', 'used')
                ->exists();

            if ($usedClaim) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher ini sudah pernah Anda gunakan.',
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Anda belum mengklaim voucher ini. Silakan klaim terlebih dahulu.',
            ], 422);
        }

        DB::transaction(function () use ($voucher, $claim) {
            // Lock row voucher dan claim untuk mencegah race condition
            Voucher::where('id_voucher', $voucher->id_voucher)->lockForUpdate()->first();
            VoucherClaim::where('claim_id', $claim->claim_id)->lockForUpdate()->first();

            // Update status claim menjadi 'used' dan isi used_at
            $claim->update([
                'status'  => 'used',
                'used_at' => Carbon::now(),
            ]);

            // Kurangi kuota voucher
            $voucher->decrement('kuota');
        });

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil digunakan.',
            'data'    => [
                'potongan_persen' => $voucher->potongan_persen,
                'used_at'         => $claim->fresh()->used_at,
                'sisa_kuota'      => $voucher->fresh()->kuota,
            ],
        ]);
    }
}

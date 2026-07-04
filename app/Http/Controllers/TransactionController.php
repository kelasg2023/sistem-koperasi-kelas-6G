<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class TransactionController extends Controller
{
    use ApiResponse;

    /**
     * Proses checkout barang (Beli barang).
     */
    public function checkout(Request $request)
    {
        $user = $request->user();

        // Validasi input dasar
        $validator = Validator::make($request->all(), [
            'alamat_pengiriman' => 'required|string',
            'jasa_kurir'        => 'required|string|max:50',
            'payment_method'    => 'required|in:cash,qris,transfer,wallet',
            'items'             => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barang,barang_id',
            'items.*.jumlah'    => 'required|integer|min:1',
            'items.*.kode_voucher' => 'nullable|string|exists:vouchers,kode_voucher',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        try {
            DB::beginTransaction();

            $total_harga_keseluruhan = 0;
            $detailsData = [];

            foreach ($request->items as $item) {
                // Kunci baris barang untuk menghindari race condition (Penting di e-commerce)
                $barang = Barang::where('barang_id', $item['barang_id'])->lockForUpdate()->first();

                if ($barang->stok < $item['jumlah']) {
                    DB::rollBack();
                    return $this->errorResponse("Stok untuk {$barang->nama} tidak mencukupi (Sisa: {$barang->stok})", 400);
                }

                $harga_satuan = $barang->harga;
                // Potong diskon default barang jika ada
                if ($barang->diskon_persen > 0) {
                    $harga_satuan = $harga_satuan - ($harga_satuan * ($barang->diskon_persen / 100));
                }

                $voucher_dipakai_id = null;
                $harga_setelah_voucher = $harga_satuan;

                // Logika penerapan Voucher per barang
                if (isset($item['kode_voucher'])) {
                    $voucher = Voucher::where('kode_voucher', $item['kode_voucher'])
                        ->where('barang_id', $barang->barang_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$voucher) {
                        DB::rollBack();
                        return $this->errorResponse("Voucher {$item['kode_voucher']} tidak valid untuk barang {$barang->nama}", 400);
                    }

                    if (!$voucher->isUsableDirectly()) {
                        DB::rollBack();
                        return $this->errorResponse("Voucher {$item['kode_voucher']} sudah expired atau kuota habis", 400);
                    }

                    // Terapkan diskon voucher
                    $harga_setelah_voucher = $harga_setelah_voucher - ($harga_setelah_voucher * ($voucher->potongan_persen / 100));
                    
                    // Kurangi kuota voucher
                    $voucher->kuota -= 1;
                    $voucher->save();

                    $voucher_dipakai_id = $voucher->id_voucher;
                }

                $subtotal_baris = $harga_setelah_voucher * $item['jumlah'];
                $total_harga_keseluruhan += $subtotal_baris;

                // Kurangi stok barang
                $barang->stok -= $item['jumlah'];
                $barang->save();

                // Kumpulkan data detail
                $detailsData[] = [
                    'barang_id'    => $barang->barang_id,
                    'jumlah'       => $item['jumlah'],
                    'harga_satuan' => $harga_setelah_voucher,
                    'id_voucher'   => $voucher_dipakai_id
                ];
            }

            // Buat Record Transaction
            $transaction = new Transaction();
            $transaction->user_id = $user->id_users;
            $transaction->total_harga = $total_harga_keseluruhan;
            $transaction->status = 'proses'; // default
            $transaction->payment_method = $request->payment_method;
            $transaction->alamat_pengiriman = $request->alamat_pengiriman;
            $transaction->jasa_kurir = $request->jasa_kurir;
            $transaction->status_pengiriman = 'pending';
            $transaction->save();

            // Insert Transaction Details
            foreach ($detailsData as $detail) {
                $transactionDetail = new TransactionDetail();
                $transactionDetail->transaction_id = $transaction->transaction_id;
                $transactionDetail->barang_id = $detail['barang_id'];
                $transactionDetail->jumlah = $detail['jumlah'];
                $transactionDetail->harga_satuan = $detail['harga_satuan'];
                $transactionDetail->id_voucher = $detail['id_voucher'];
                $transactionDetail->save();
            }

            // TODO: Integrasi pemotongan Wallet jika metode pembayarannya wallet

            DB::commit();

            return $this->successResponse($transaction->load('transactionDetails.barang'), 'Checkout berhasil', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Menampilkan riwayat transaksi pengguna yang sedang login.
     */
    public function history(Request $request)
    {
        $user = $request->user();
        
        $transactions = Transaction::with(['transactionDetails.barang'])
            ->where('user_id', $user->id_users)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($transactions, 'Riwayat transaksi berhasil diambil');
    }

    /**
     * Menampilkan status pelacakan spesifik untuk satu transaksi.
     */
    public function track(Request $request, $id)
    {
        $user = $request->user();

        $transaction = Transaction::with(['transactionDetails.barang'])
            ->where('transaction_id', $id)
            ->where('user_id', $user->id_users)
            ->first();

        if (!$transaction) {
            return $this->errorResponse('Transaksi tidak ditemukan atau bukan milik Anda', 404);
        }

        $trackingData = [
            'transaction_id'    => $transaction->transaction_id,
            'status_pesanan'    => $transaction->status,
            'status_pengiriman' => $transaction->status_pengiriman,
            'jasa_kurir'        => $transaction->jasa_kurir,
            'nomor_resi'        => $transaction->nomor_resi ?? 'Belum ada resi',
            'alamat_pengiriman' => $transaction->alamat_pengiriman,
            'tanggal_pesan'     => $transaction->created_at,
        ];

        return $this->successResponse($trackingData, 'Data tracking berhasil diambil');
    }
}

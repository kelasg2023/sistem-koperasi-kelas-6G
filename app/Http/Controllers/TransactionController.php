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
    public function checkout(Request $request, \App\Services\MLService $mlService)
    {
        $user = $request->user();

        // Validasi input dasar
        $validator = Validator::make($request->all(), [
            'alamat_pengiriman' => 'required|string',
            'jasa_kurir'        => 'required|string|max:50',
            'payment_method'    => 'required|in:wallet', // Hanya menerima wallet
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

            // Integrasi pemotongan Wallet (Wajib karena payment_method == 'wallet')
            $wallet = \App\Models\Wallet::where('user_id', $user->id_users)->lockForUpdate()->first();

            if (!$wallet || $wallet->balance < $total_harga_keseluruhan) {
                DB::rollBack();
                return $this->errorResponse('Saldo wallet tidak mencukupi untuk transaksi ini', 400);
            }

            // Potong saldo
            $wallet->balance -= $total_harga_keseluruhan;
            $wallet->save();

            // Catat history
            \App\Models\WalletHistory::create([
                'id_wallet' => $wallet->id_wallet,
                'balance_transaction' => $total_harga_keseluruhan,
                'wt_status_history' => 'terpakai',
            ]);

            // Validasi member otomatis jika total transaksi >= 100.000
            if ($user->role === 'customer') {
                $totalTransaksi = Transaction::where('user_id', $user->id_users)
                    ->whereIn('status', ['proses', 'berhasil'])
                    ->sum('total_harga');

                if ($totalTransaksi >= 100000) {
                    $customer = $user->customer;
                    if ($customer && !$customer->is_member) {
                        $customer->is_member = true;
                        $customer->save();
                    }
                }
            }

            // Deteksi Fraud via ML Service
            $fraudCheckData = [
                'transaction_id' => $transaction->transaction_id,
                'user_id' => $user->id_users,
                'total_harga' => $total_harga_keseluruhan,
                'payment_method' => $request->payment_method,
                'created_at' => $transaction->created_at ? $transaction->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
            ];

            $fraudResult = $mlService->checkFraud($fraudCheckData);
            if ($fraudResult && isset($fraudResult['status']) && $fraudResult['status'] === 'suspicious') {
                DB::rollBack();
                $reason = $fraudResult['reason'] ?? 'Deteksi Anomali';
                return $this->errorResponse("Transaksi ditolak oleh sistem keamanan (Fraud Detected): {$reason}", 400);
            }

            // Rekam jejak pertama (Timeline Tracking)
            \App\Models\TransactionTracking::create([
                'transaction_id' => $transaction->transaction_id,
                'status_pengiriman' => 'pending',
                'keterangan' => 'Pesanan berhasil dibuat dan sedang menunggu konfirmasi.'
            ]);

            DB::commit();

            // Broadcast event untuk update real-time di Frontend (contoh: update dashboard, update stok barang)
            event(new \App\Events\DataUpdated(['type' => 'transaction_created', 'data' => $transaction]));

            return $this->successResponse($transaction->load('transactionDetails.barang'), 'Checkout berhasil', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Membatalkan transaksi oleh pengguna.
     */
    public function cancel(Request $request, $id)
    {
        $user = $request->user();

        try {
            DB::beginTransaction();

            $transaction = Transaction::where('transaction_id', $id)
                ->where('user_id', $user->id_users)
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                return $this->errorResponse('Transaksi tidak ditemukan', 404);
            }

            if (!in_array($transaction->status, ['proses', 'pending'])) {
                return $this->errorResponse('Transaksi tidak dapat dibatalkan pada tahap ini', 400);
            }

            // Ubah status menjadi refund/dibatalkan
            $transaction->status = 'refund';
            $transaction->save();

            // Refund ke Wallet
            $wallet = \App\Models\Wallet::where('user_id', $user->id_users)->lockForUpdate()->first();
            if ($wallet) {
                $wallet->balance += $transaction->total_harga;
                $wallet->save();

                \App\Models\WalletHistory::create([
                    'id_wallet' => $wallet->id_wallet,
                    'balance_transaction' => $transaction->total_harga,
                    'wt_status_history' => 'pengembalian',
                ]);
            }

            // Kembalikan Stok Barang dan Kembalikan Kuota Voucher
            $details = \App\Models\TransactionDetail::where('transaction_id', $id)->get();
            foreach ($details as $detail) {
                // Restore Stock
                $barang = Barang::where('barang_id', $detail->barang_id)->lockForUpdate()->first();
                if ($barang) {
                    $barang->stok += $detail->jumlah;
                    $barang->save();
                }

                // Restore Voucher Quota if any
                if ($detail->id_voucher) {
                    $voucher = Voucher::where('id_voucher', $detail->id_voucher)->lockForUpdate()->first();
                    if ($voucher) {
                        $voucher->kuota += 1;
                        $voucher->save();
                    }
                }
            }

            // Tidak perlu menarik poin karena poin baru diberikan jika transaksi status = berhasil

            DB::commit();

            return $this->successResponse('Pesanan berhasil dibatalkan dan saldo telah dikembalikan', null);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan saat membatalkan transaksi: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Menampilkan semua riwayat transaksi untuk admin & staff.
     */
    public function getAllTransactions(Request $request)
    {
        $transactions = Transaction::with(['user', 'transactionDetails.barang', 'trackingTimeline'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($transactions, 'Semua transaksi berhasil diambil');
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

        $transaction = Transaction::with(['transactionDetails.barang', 'trackingTimeline'])
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
            'timeline'          => $transaction->trackingTimeline
        ];

        return $this->successResponse($trackingData, 'Data tracking dan timeline berhasil diambil');
    }
    /**
     * Update status pengiriman & resi (Khusus Admin/Staff).
     */
    public function updateStatus(Request $request, $id, \App\Services\PointService $pointService)
    {
        $validator = Validator::make($request->all(), [
            'status_pengiriman' => 'required|in:pending,dikemas,dikirim,selesai',
            'nomor_resi' => 'nullable|string',
            'keterangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $transaction = Transaction::find($id);

        if (!$transaction) {
            return $this->errorResponse('Transaksi tidak ditemukan', 404);
        }

        // Update main transaction
        $transaction->status_pengiriman = $request->status_pengiriman;
        if ($request->filled('nomor_resi')) {
            $transaction->nomor_resi = $request->nomor_resi;
        }
        
        // If delivered, we can also update the main status to 'berhasil' if it was 'proses'
        if ($request->status_pengiriman === 'selesai' && $transaction->status === 'proses') {
            $transaction->status = 'berhasil';
            
            // Hitung dan berikan Poin saat transaksi selesai
            $transactionUser = $transaction->user;
            if ($transactionUser && $transactionUser->role === 'customer') {
                $pointAmount = floor($transaction->total_harga / 10000);
                if ($pointAmount > 0) {
                    $pointService->awardPoints(
                        $transactionUser, 
                        $pointAmount, 
                        "Cashback Poin dari Transaksi Selesai #{$transaction->transaction_id}",
                        $transaction->transaction_id
                    );
                }
            }
        }
        $transaction->save();

        // Insert timeline tracking
        $tracking = \App\Models\TransactionTracking::create([
            'transaction_id' => $transaction->transaction_id,
            'status_pengiriman' => $request->status_pengiriman,
            'keterangan' => $request->keterangan
        ]);

        // Broadcast event
        event(new \App\Events\DataUpdated(['type' => 'tracking_updated', 'data' => $tracking]));

        return $this->successResponse($transaction->load('trackingTimeline'), 'Status pengiriman berhasil diperbarui');
    }
}

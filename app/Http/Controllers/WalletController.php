<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Models\WalletTopup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Jobs\ProcessMidtransWebhookJob;

class WalletController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Meminta token topup wallet ke Midtrans
     */
    public function topup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gross_amount' => 'required|numeric|min:10000',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422, $validator->errors());
        }

        $user = $request->user();
        $orderId = 'TOPUP-' . $user->id_users . '-' . time();
        $grossAmount = $request->gross_amount;

        try {
            // Data transaksi yang dikirim ke Midtrans
            $transaction_details = [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ];

            $customer_details = [
                'first_name' => $user->userProfile->name ?? $user->username,
                'email' => $user->email,
                'phone' => $user->userProfile->phone ?? '',
            ];

            $params = [
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
            ];

            // Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan record ke database dengan status pending
            $walletTopup = WalletTopup::create([
                'user_id' => $user->id_users,
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
                'status' => 'pending',
                'snap_token' => $snapToken,
            ]);

            return $this->successResponse([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'wallet_topup' => $walletTopup
            ], 'Token pembayaran berhasil dibuat', 201);

        } catch (\Exception $e) {
            return $this->errorResponse('Terjadi kesalahan saat memproses top-up: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Webhook untuk menerima notifikasi dari Midtrans
     */
    public function webhook(Request $request)
    {
        try {
            $notification = new Notification();

            // Verifikasi Keamanan Signature Key Midtrans
            $signatureKey = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . Config::$serverKey);
            if ($signatureKey !== $notification->signature_key) {
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $grossAmount = $notification->gross_amount;

            // Lempar proses ke Background Job
            ProcessMidtransWebhookJob::dispatch($orderId, $transactionStatus, $grossAmount);

            // Langsung respon 200 OK ke Midtrans agar tidak timeout
            return response()->json(['message' => 'Webhook received and processing in background']);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * Mengecek status transaksi langsung ke Midtrans (Berguna jika webhook gagal di localhost)
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        try {
            // Panggil API Midtrans untuk mendapatkan status
            $statusResponse = \Midtrans\Transaction::status($request->order_id);
            
            // Jalankan logika webhook secara sinkron (langsung saat itu juga)
            ProcessMidtransWebhookJob::dispatchSync(
                $statusResponse->order_id, 
                $statusResponse->transaction_status, 
                $statusResponse->gross_amount
            );

            return $this->successResponse([
                'order_id' => $statusResponse->order_id,
                'transaction_status' => $statusResponse->transaction_status
            ], 'Status checked and processed');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to check status: ' . $e->getMessage(), 500);
        }
    }
}

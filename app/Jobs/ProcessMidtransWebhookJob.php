<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Models\WalletTopup;

class ProcessMidtransWebhookJob implements ShouldQueue
{
    use Queueable;

    protected $orderId;
    protected $transactionStatus;
    protected $grossAmount;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId, $transactionStatus, $grossAmount)
    {
        $this->orderId = $orderId;
        $this->transactionStatus = $transactionStatus;
        $this->grossAmount = $grossAmount;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $walletTopup = WalletTopup::where('order_id', $this->orderId)->first();

            if (!$walletTopup) {
                Log::warning('ProcessMidtransWebhookJob: Order not found - ' . $this->orderId);
                return;
            }

            // Hindari pemrosesan ganda
            if (in_array($walletTopup->status, ['success', 'failed', 'expired'])) {
                Log::info('ProcessMidtransWebhookJob: Transaction already processed - ' . $this->orderId);
                return;
            }

            DB::beginTransaction();

            if ($this->transactionStatus == 'capture' || $this->transactionStatus == 'settlement') {
                $walletTopup->status = 'success';
                
                // Tambahkan saldo ke dompet user
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $walletTopup->user_id],
                    ['balance' => 0]
                );
                
                $wallet->balance += $walletTopup->gross_amount;
                $wallet->save();

                // Catat di wallet history
                WalletHistory::create([
                    'id_wallet' => $wallet->id_wallet,
                    'balance_transaction' => $walletTopup->gross_amount,
                    'wt_status_history' => 'penambahan',
                ]);

            } else if ($this->transactionStatus == 'cancel' || $this->transactionStatus == 'deny') {
                $walletTopup->status = 'failed';
            } else if ($this->transactionStatus == 'expire') {
                $walletTopup->status = 'expired';
            } else if ($this->transactionStatus == 'pending') {
                $walletTopup->status = 'pending';
            }

            $walletTopup->save();
            DB::commit();

            Log::info('ProcessMidtransWebhookJob: Webhook handled successfully for order - ' . $this->orderId);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ProcessMidtransWebhookJob Error: ' . $e->getMessage());
            throw $e; // lempar error agar job bisa diretry
        }
    }
}

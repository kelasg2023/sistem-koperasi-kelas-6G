<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use App\Models\WalletTopup;
use Carbon\Carbon;

class CancelExpiredTransactionJob implements ShouldQueue
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
            $expiredTopups = WalletTopup::where('status', 'pending')
                ->where('created_at', '<', Carbon::now()->subHours(24))
                ->get();

            $count = 0;
            foreach ($expiredTopups as $topup) {
                $topup->status = 'expired';
                $topup->save();
                $count++;
            }

            Log::info("CancelExpiredTransactionJob: Successfully expired {$count} pending wallet topups.");
        } catch (\Exception $e) {
            Log::error("CancelExpiredTransactionJob Error: " . $e->getMessage());
        }
    }
}

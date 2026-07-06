<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\PointLog;
use Illuminate\Support\Facades\DB;

class PointService
{
    /**
     * Award points to a user using an atomic database transaction.
     *
     * @param User $user
     * @param int $amount
     * @param string $description
     * @param int|null $transactionId
     * @return void
     * @throws \Exception
     */
    public function awardPoints(User $user, int $amount, string $description, ?int $transactionId = null): void
    {
        if ($amount <= 0) {
            return;
        }

        DB::transaction(function () use ($user, $amount, $description, $transactionId) {
            // Retrieve customer profile
            $customer = Customer::where('user_id', $user->id_users)->first();
            
            if (!$customer) {
                return;
            }

            // Increment points atomically to prevent race condition
            Customer::where('user_id', $user->id_users)->increment('point', $amount);

            // Log the point transaction
            PointLog::create([
                'user_id' => $user->id_users,
                'transaction_id' => $transactionId,
                'type' => 'earn',
                'amount' => $amount,
                'description' => $description,
            ]);
        });
    }

    /**
     * Redeem points from a user using an atomic database transaction.
     *
     * @param User $user
     * @param int $amount
     * @param string $description
     * @param int|null $transactionId
     * @return void
     * @throws \Exception
     */
    public function redeemPoints(User $user, int $amount, string $description, ?int $transactionId = null): void
    {
        if ($amount <= 0) {
            return;
        }

        DB::transaction(function () use ($user, $amount, $description, $transactionId) {
            // Lock row for update to ensure atomicity
            $customer = Customer::where('user_id', $user->id_users)->lockForUpdate()->first();
            
            if (!$customer || $customer->point < $amount) {
                throw new \Exception('Poin tidak mencukupi');
            }

            // Decrement points atomically
            Customer::where('user_id', $user->id_users)->decrement('point', $amount);

            // Log the point transaction
            PointLog::create([
                'user_id' => $user->id_users,
                'transaction_id' => $transactionId,
                'type' => 'redeem',
                'amount' => $amount,
                'description' => $description,
            ]);
        });
    }
}

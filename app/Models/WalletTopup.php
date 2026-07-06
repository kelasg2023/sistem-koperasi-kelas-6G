<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Builder;

class WalletTopup extends Model
{
    use HasFactory, MassPrunable;

    protected $table = 'wallet_topups';

    protected $fillable = [
        'user_id',
        'order_id',
        'gross_amount',
        'status',
        'snap_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::whereIn('status', ['failed', 'expired'])
                     ->where('created_at', '<', now()->subDays(30));
    }
}

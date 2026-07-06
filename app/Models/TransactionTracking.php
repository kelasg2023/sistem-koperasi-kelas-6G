<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionTracking extends Model
{
    use HasFactory;

    protected $primaryKey = 'tracking_id';

    protected $fillable = [
        'transaction_id',
        'status_pengiriman',
        'keterangan',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}

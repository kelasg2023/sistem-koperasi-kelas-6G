<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'type',
        'amount',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}

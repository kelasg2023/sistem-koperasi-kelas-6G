<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'total_harga', 'status', 'payment_method', 'alamat_pengiriman', 'jasa_kurir', 'nomor_resi', 'status_pengiriman'])]
class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;


    public function user() { return $this->belongsTo(User::class, 'user_id', 'id_users'); }
    public function transactionDetails() { return $this->hasMany(TransactionDetail::class, 'transaction_id', 'transaction_id'); }
    public function audit() { return $this->hasOne(Audit::class, 'transaction_id', 'transaction_id'); }
    public function trackingTimeline() { return $this->hasMany(TransactionTracking::class, 'transaction_id', 'transaction_id')->orderBy('created_at', 'desc'); }

}

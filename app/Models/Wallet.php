<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'balance'])]
class Wallet extends Model
{
    protected $table = 'wallet';
    protected $primaryKey = 'id_wallet';
    public $timestamps = false;


    public function user() { return $this->belongsTo(User::class, 'user_id', 'id_users'); }
    public function walletHistories() { return $this->hasMany(WalletHistory::class, 'id_wallet', 'id_wallet'); }

}

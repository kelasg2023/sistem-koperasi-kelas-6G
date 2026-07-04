<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_wallet', 'balance_transaction', 'wt_status_history'])]
class WalletHistory extends Model
{
    protected $table = 'wallet_history';
    protected $primaryKey = 'id_wt_history';
    public $timestamps = false;


    public function wallet() { return $this->belongsTo(Wallet::class, 'id_wallet', 'id_wallet'); }

}

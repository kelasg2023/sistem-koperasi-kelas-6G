<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['transaction_id', 'barang_id', 'jumlah', 'harga_satuan', 'id_voucher'])]
class TransactionDetail extends Model
{
    protected $table = 'transaction_details';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;


    public function transaction() { return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
    public function voucher() { return $this->belongsTo(Voucher::class, 'id_voucher', 'id_voucher'); }

}

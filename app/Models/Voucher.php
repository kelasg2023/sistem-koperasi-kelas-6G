<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode_voucher', 'potongan_persen', 'kuota', 'barang_id', 'expired_at'])]
class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'id_voucher';
    public $timestamps = false;


    public function barang() { return $this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
    public function transactionDetails() { return $this->hasMany(TransactionDetail::class, 'id_voucher', 'id_voucher'); }

}

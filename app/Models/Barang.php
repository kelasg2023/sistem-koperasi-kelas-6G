<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['nama', 'stok', 'harga', 'diskon_persen', 'deskripsi', 'id_kategori'])]
class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';

    use SoftDeletes;

    public $timestamps = false;


    public function kategori() { return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori'); }
    public function merks() { return $this->hasMany(Merk::class, 'barang_id', 'barang_id'); }
    public function suppliers() { return $this->hasMany(Supplier::class, 'barang_id', 'barang_id'); }
    public function stokHistories() { return $this->hasMany(StokHistory::class, 'barang_id', 'barang_id'); }
    public function vouchers() { return $this->hasMany(Voucher::class, 'barang_id', 'barang_id'); }
    public function transactionDetails() { return $this->hasMany(TransactionDetail::class, 'barang_id', 'barang_id'); }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['merk_id', 'barang_id', 'harga_beli', 'jumlah', 'status'])]
class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;


    public function merk() { return $this->belongsTo(Merk::class, 'merk_id', 'merk_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
    public function stokHistories() { return $this->hasMany(StokHistory::class, 'supplier_id', 'supplier_id'); }

}

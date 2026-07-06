<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['supplier_id', 'barang_id', 'jumlah', 'stok_awal', 'stok_akhir', 'keterangan', 'stok_mutasi'])]
class StokHistory extends Model
{
    protected $table = 'stok_history';
    protected $primaryKey = 'stok_history_id';
    public $timestamps = false;


    public function supplier() { return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id'); }
    public function barang() { return $this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }

}

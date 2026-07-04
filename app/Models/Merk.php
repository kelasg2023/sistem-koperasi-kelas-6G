<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_merk', 'barang_id'])]
class Merk extends Model
{
    protected $table = 'merk';
    protected $primaryKey = 'merk_id';
    public $timestamps = false;


    public function barang() { return $this->belongsTo(Barang::class, 'barang_id', 'barang_id'); }
    public function suppliers() { return $this->hasMany(Supplier::class, 'merk_id', 'merk_id'); }

}

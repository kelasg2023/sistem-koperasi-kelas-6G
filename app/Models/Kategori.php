<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_kategori', 'satuan'])]
class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;


    public function barangs() { return $this->hasMany(Barang::class, 'id_kategori', 'id_kategori'); }

}

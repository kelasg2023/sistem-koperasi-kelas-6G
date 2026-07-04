<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'point'])]
class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customers_id';
    public $timestamps = false;


    public function user() { return $this->belongsTo(User::class, 'user_id', 'id_users'); }

}

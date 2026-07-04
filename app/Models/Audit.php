<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['transaction_id', 'status_audit', 'info_audit_lama', 'info_audit_baru'])]
class Audit extends Model
{
    protected $table = 'audit';
    protected $primaryKey = 'audit_id';
    public $timestamps = false;


    public function transaction() { return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id'); }

}

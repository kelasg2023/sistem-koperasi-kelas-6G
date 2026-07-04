<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherClaim extends Model
{
    protected $table      = 'voucher_claims';
    protected $primaryKey = 'claim_id';
    public    $timestamps = false;

    protected $fillable = [
        'user_id',
        'id_voucher',
        'status',
        'claimed_at',
        'used_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** Claim ini milik user tertentu */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }

    /** Claim ini terkait dengan voucher tertentu */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'id_voucher', 'id_voucher');
    }
}

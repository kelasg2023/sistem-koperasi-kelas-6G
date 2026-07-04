<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $table      = 'vouchers';
    protected $primaryKey = 'id_voucher';
    public    $timestamps = false;

    protected $fillable = [
        'kode_voucher',
        'potongan_persen',
        'kuota',
        'barang_id',
        'tipe_voucher',
        'expired_at',
    ];

    protected $casts = [
        'expired_at'      => 'datetime',
        'potongan_persen' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'id_voucher', 'id_voucher');
    }

    public function claims()
    {
        return $this->hasMany(VoucherClaim::class, 'id_voucher', 'id_voucher');
    }

    // ─── Helper Methods ───────────────────────────────────────────────────────

    /** Apakah voucher sudah melewati tanggal kadaluarsa? */
    public function isExpired(): bool
    {
        return Carbon::parse($this->expired_at)->isPast();
    }

    /** Apakah kuota voucher masih tersedia? */
    public function hasStock(): bool
    {
        return $this->kuota > 0;
    }

    /** Apakah voucher tipe 'langsung' dan siap digunakan langsung? */
    public function isUsableDirectly(): bool
    {
        return $this->tipe_voucher === 'langsung'
            && ! $this->isExpired()
            && $this->hasStock();
    }
}

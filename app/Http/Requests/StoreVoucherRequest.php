<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    /**
     * Semua user yang authenticated boleh membuat voucher.
     * TODO: Batasi hanya role admin/manager setelah Sanctum diinstall.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules untuk membuat voucher baru.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_voucher'    => 'required|string|max:50|unique:vouchers,kode_voucher',
            'potongan_persen' => 'required|numeric|min:0|max:100',
            'kuota'           => 'required|integer|min:0',
            'barang_id'       => 'required|integer|exists:barang,barang_id',
            'tipe_voucher'    => 'required|in:langsung,claim',
            'expired_at'      => 'required|date|after:now',
        ];
    }

    /**
     * Pesan error custom (bahasa Indonesia).
     */
    public function messages(): array
    {
        return [
            'kode_voucher.required'    => 'Kode voucher wajib diisi.',
            'kode_voucher.unique'      => 'Kode voucher sudah digunakan.',
            'kode_voucher.max'         => 'Kode voucher maksimal 50 karakter.',
            'potongan_persen.required' => 'Potongan persen wajib diisi.',
            'potongan_persen.min'      => 'Potongan persen tidak boleh negatif.',
            'potongan_persen.max'      => 'Potongan persen tidak boleh melebihi 100%.',
            'kuota.required'           => 'Kuota wajib diisi.',
            'kuota.min'                => 'Kuota tidak boleh negatif.',
            'barang_id.required'       => 'Barang wajib dipilih.',
            'barang_id.exists'         => 'Barang tidak ditemukan.',
            'expired_at.required'      => 'Tanggal kadaluarsa wajib diisi.',
            'expired_at.after'         => 'Tanggal kadaluarsa harus di masa depan.',
        ];
    }
}

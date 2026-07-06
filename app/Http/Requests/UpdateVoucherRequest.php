<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVoucherRequest extends FormRequest
{
    /**
     * Semua user yang authenticated boleh update voucher.
     * TODO: Batasi hanya role admin/manager setelah Sanctum diinstall.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules untuk update voucher.
     * Semua field pakai 'sometimes' → hanya divalidasi kalau dikirim.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Ambil ID voucher dari route parameter
        $voucherId = $this->route('id');

        return [
            'kode_voucher'    => [
                'sometimes',
                'string',
                'max:50',
                // Unique tapi abaikan record voucher yang sedang diedit
                Rule::unique('vouchers', 'kode_voucher')->ignore($voucherId, 'id_voucher'),
            ],
            'potongan_persen' => 'sometimes|numeric|min:0|max:100',
            'kuota'           => 'sometimes|integer|min:0',
            'barang_id'       => 'nullable|integer|exists:barang,barang_id',
            'tipe_voucher'    => 'sometimes|in:langsung,claim',
            'expired_at'      => 'sometimes|date|after:now',
        ];
    }

    /**
     * Pesan error custom (bahasa Indonesia).
     */
    public function messages(): array
    {
        return [
            'kode_voucher.unique'      => 'Kode voucher sudah digunakan oleh voucher lain.',
            'kode_voucher.max'         => 'Kode voucher maksimal 50 karakter.',
            'potongan_persen.min'      => 'Potongan persen tidak boleh negatif.',
            'potongan_persen.max'      => 'Potongan persen tidak boleh melebihi 100%.',
            'kuota.min'                => 'Kuota tidak boleh negatif.',
            'barang_id.exists'         => 'Barang tidak ditemukan.',
            'tipe_voucher.in'          => 'Tipe voucher harus langsung atau claim.',
            'expired_at.after'         => 'Tanggal kadaluarsa harus di masa depan.',
        ];
    }
}

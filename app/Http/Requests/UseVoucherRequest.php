<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UseVoucherRequest extends FormRequest
{
    /**
     * TODO: Batasi hanya user yang authenticated setelah Sanctum diinstall.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi request penggunaan voucher.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'      => 'required|integer|exists:users,id_users',
            'kode_voucher' => 'required|string|max:50|exists:vouchers,kode_voucher',
        ];
    }

    /**
     * Pesan error custom (bahasa Indonesia).
     */
    public function messages(): array
    {
        return [
            'user_id.required'      => 'User wajib diisi.',
            'user_id.exists'        => 'User tidak ditemukan.',
            'kode_voucher.required' => 'Kode voucher wajib diisi.',
            'kode_voucher.exists'   => 'Kode voucher tidak ditemukan.',
        ];
    }
}

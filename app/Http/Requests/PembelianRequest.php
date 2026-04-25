<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembelianRequest extends FormRequest
{

    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'anggaran' => 'required',
            'vendor' => 'required',
            'no_inv' => 'required',
            'tgl' => 'required',
            'status' => 'required',
            'terbayar' => 'required',
            'deskripsi.*' => 'required',
            'harga.*' => 'required',
            'qty.*' => 'required',
            'jumlah.*' => 'required',
        ];
    }
    function messages()
    {
        return [
            'anggaran.required' => 'Anggaran harus diisi',
            'vendor.required' => 'Vendor harus diisi',
            'no_inv.required' => 'Nomor invoice harus diisi',
            'tgl.required' => 'Tanggal harus diisi',
            'status.required' => 'Status harus diisi',
            'terbayar.required' => 'Jumlah terbayar harus diisi',
            'deskripsi.*.required' => 'Deskripsi harus diisi',
            'harga.*.required' => 'Harga harus diisi',
            'qty.*.required' => 'Quantity harus diisi',
            'jumlah.*.required' => 'Jumlah harus diisi',
        ];
    }
}

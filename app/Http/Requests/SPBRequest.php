<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SPBRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'namaSpb' => 'required',
            'customer' => 'required|min:2',
            'perusahaan' => 'required|min:2',
            'no_telp' => 'required|regex:/^[0-9\+\s-]+$/|min:2',
            'barang_id[]' => 'required',
            'deskripsi_item[]' => 'required',
            'satuan[]' => 'required',
            'qty[]' => 'required',
        ];
    }
    public function messages()
    {
        return [

            'namaSpb.required' => 'Nama SPB harus diisi.',
            'customer.required' => 'Nama pelanggan harus diisi.',
            'customer.min' => 'Nama pelanggan minimal terdiri dari 2 karakter.',
            'perusahaan.required' => 'Nama perusahaan harus diisi.',
            'perusahaan.min' => 'Nama perusahaan minimal terdiri dari 2 karakter.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.regex' => 'Nomor telepon tidak valid.',
            'no_telp.min' => 'Nomor telepon minimal terdiri dari 2 karakter.',
            'barang_id[].required' => 'Barang harus dipilih.',
            'deskripsi_item[].required' => 'Deskripsi item harus diisi.',
            'satuan[].required' => 'Satuan harus diisi.',
            'qty[].required' => 'Kuantitas harus diisi.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
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
            'kategori_id' => 'required',
            'jenis_barang' => 'required',
            'kode_barang' => 'required',
            'stok' => 'required',
            'harga_modal' => 'required',
            'harga_jual' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'kategori_id.required' => 'Kategori ID harus diisi.',
            'jenis_barang.required' => 'Jenis barang harus diisi.',
            'kode_barang.required' => 'Kode barang harus diisi.',
            'stok.required' => 'Stok harus diisi.',
            'harga_modal.required' => 'Harga modal harus diisi.',
            'harga_jual.required' => 'Harga jual harus diisi.',
        ];
    }
}

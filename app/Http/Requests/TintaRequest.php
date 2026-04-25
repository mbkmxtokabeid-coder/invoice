<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TintaRequest extends FormRequest
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
            'nama_tinta' => 'required',
            'kode_tinta' => 'required',
            'stok' => 'required',
            'tgl_masuk' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'kategori_id.required' => 'Kategori ID harus diisi.',
            'nama_tinta.required' => 'Jenis barang harus diisi.',
            // 'jenis_barang.required' => 'Jenis barang harus diisi.',
            'kode_tinta.required' => 'Kode barang harus diisi.',
            // 'kode_barang.required' => 'Kode barang harus diisi.',
            'stok.required' => 'Stok harus diisi.',
            'tgl_masuk.required' => 'Tanggal Masuk harus diisi.',

        ];
    }
}

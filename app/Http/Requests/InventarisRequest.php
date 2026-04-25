<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventarisRequest extends FormRequest
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
            'jenis_inventaris' => 'required',
            'kode_inventaris' => 'required',
            'stok' => 'required',
            

        ];
    }
    public function messages()
    {
        return [
            'kategori_id.required' => 'Kategori ID harus diisi.',
            'jenis_inventaris.required' => 'Jenis inventaris harus diisi.',
            'kode_inventaris.required' => 'Kode inventaris harus diisi.',
            'stok.required' => 'Stok harus diisi.',
            
        ];
    }
}

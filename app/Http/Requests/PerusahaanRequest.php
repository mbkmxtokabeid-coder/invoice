<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerusahaanRequest extends FormRequest
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
        'nama_perusahaan' => 'required|min:3',
          'alamat_perusahaan' => 'required|min:10',
          'logo' => 'image|mimes:png,jpg,jpeg|max:2048',
          'no_hp' => 'required|numeric'
        ];
    }
    function messages()  {
        return[
            'nama_perusahaan.required' => 'Nama perusahaan harus diisi',
          
          'alamat_perusahaan.required' => 'alamat harus diisi',
          'no_hp.required'=>'Nomor Handphone harus diisi',
          'nama_perusahaan.min' => 'Judul minimal 3 karakter',
          'no_hp.numeric' => 'Nomor telepon harus angka',
          'alamat_perusahaan.min' => 'Alamat minimal 10 karakter',
          'Logo.mimes' => 'Format Gambar harus png/jpg/jpeg',
          'Logo.image' => 'Harus foto/gambar',
          'Logo.max' => 'Ukuran maksimal 2Mb',
        ];
        
    }
}

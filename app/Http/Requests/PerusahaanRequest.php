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
        return true;
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
          'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
          'no_hp' => 'required|numeric'
        ];
    }
    function messages()  {
        return[
          'nama_perusahaan.required' => 'Nama perusahaan harus diisi',
          'alamat_perusahaan.required' => 'Alamat harus diisi',
          'no_hp.required'=>'Nomor Handphone harus diisi',
          'nama_perusahaan.min' => 'Judul minimal 3 karakter',
          'no_hp.numeric' => 'Nomor telepon harus angka',
          'alamat_perusahaan.min' => 'Alamat minimal 10 karakter',
          'logo.mimes' => 'Format Gambar harus png/jpg/jpeg/webp/svg',
          'logo.image' => 'Harus foto/gambar',
          'logo.max' => 'Ukuran maksimal 2Mb',
        ];
        
    }
}

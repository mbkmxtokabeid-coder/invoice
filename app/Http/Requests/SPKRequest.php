<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SPKRequest extends FormRequest
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
            'pekerjaan' => 'required',
            'tgl_buat' => 'required',
            'invoice' => 'required|min:5',
            'customer' => 'required|min:2',
            'jumlah' => 'required',
            'satuan' => 'required',
            'jenis_bahan' => 'required|min:3',
            'ketebalan' => 'required',
            'ukuran' => 'required',
            'lain' => 'required',
            'express' => 'required',
            'tgl_selesai' => 'required',
            'contoh_design' => 'file|max:1536|mimes:jpeg,jpg,png,svg,pdf,webp',
        ];
    }
    public function messages()
    {
        return [

            'pekerjaan.required' => 'Pekerjaan harus diisi.',
            'tgl_buat.required' => 'Tanggal harus diisi.',
            'invoice.required' => 'Invoice harus diisi.',
            'invoice.min' => 'Invoice minimal terdiri dari 5 karakter.',
            'customer.required' => 'Nama pelanggan harus diisi.',
            'customer.min' => 'Nama pelanggan minimal terdiri dari 2 karakter.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'satuan.required' => 'Satuan harus diisi.',
            'jenis_bahan.required' => 'Jenis Bahan harus diisi.',
            'jenis_bahan.min' => 'Jenis Bahan minimal terdiri dari 3 kaarakter.',
            'ketebalan.required' => 'Ketebalan harus diisi.',
            'ukuran.required' => 'Ukuran harus diisi.',
            'lain.required' => 'Lainnya harus diisi.',
            'express.required' => 'Express mohon dipilih.',
            'tgl_selesai.required' => 'Tanggal selesai harus diisi.',
            'contoh_design.mimes' => 'Format tidak sesuai.',
            'contoh_design.max' => 'Ukuran melebihi 1,5 MB.',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spk extends Model
{
    use HasFactory;
    protected $table = 'spk';
    protected $fillable = [
        'pekerjaan',
        'tgl_mulai',
        'target_selesai',
        'nomor_invoice',
        'customer',
        'jumlah',
        'satuan',
        'jenis_bahan',
        'ketebalan',
        'ukuran',
        'lain',
        'express',
        'timeline',
        'status_spk',
        'status_kerja',
        'gambar',
    ];
}

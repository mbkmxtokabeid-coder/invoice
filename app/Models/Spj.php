<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    protected $table = 'spj';
    protected $fillable = [
       'nomor_spj',
       'perusahaan',
       'nama_pemberi_tugas',
       'nama_kurir',
       'tanggal_tugas',
       'waktu_berangkat',
       'tujuan',
    ];
    use HasFactory;
}

<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pembelian extends Model
{
    use HasFactory, UUIDAsPrimaryKey;
    protected $table = 'pembelian';
    protected $fillable = [
        // 'id',
        'anggaran',
        'id_vendor',
        'nomor_inv',
        'tanggal',
        'tgl_jto',
        'status',
        'terbayar',
        'sisa',
        'jumlah_harga',
        'token',

    ];

    function anggaran()
    {
        return $this->belongsTo(Budget::class, 'anggaran');
    }
    public function pembelian_barang()
    {
        return $this->hasMany(PembelianBarang::class, 'pembelian_id');
    }
    function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }
}

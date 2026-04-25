<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUIDAsPrimaryKey;

class PembelianBarang extends Model
{
    use HasFactory, UUIDAsPrimaryKey;
    protected $table = 'pembelian_barang';
    protected $fillable = [
        'pembelian_id',
        'deskripsi',
        'qty',
        'satuan',
        'harga_barang',
        'total',

    ];
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}

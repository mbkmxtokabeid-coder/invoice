<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenjualanBarang extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = "penjualan_barang";
    protected $guarded = [];
    protected $fillable = [
        'barang_id',
        'penjualan_id',
        'deskripsi_item',
        'qty',
        'satuan',
        'hargaBarang',
        'jumlah_harga',
        'material_id', 
        'material_qty',
        'material_panjang', 
        'material_lebar',  
    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangSpb extends Model
{
    use HasFactory;
    protected $table = 'barang_spb';
    protected $fillable = [
        'spb',
        'barang_id',
        'deskripsi',
        'satuan',
        'qty',
        'keterangan',
    ];
    public function spb()
    {
        return $this->belongsTo(Spb::class, 'spb');
    }
    public function kategori_barang()
    {
        return $this->belongsTo(KategoriBarang::class, 'barang_id');
    }
}

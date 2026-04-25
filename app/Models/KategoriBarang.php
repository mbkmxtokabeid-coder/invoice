<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;
    protected $table = "kategori_barang";
    protected $guarded = [];
    protected $fillable = [
        'nama_kategori'
    ];
    public function barang()
    {
        return $this->hasMany(Barang::class, 'barang_id');
    }
    public function barang_spb()
    {
        return $this->hasMany(BarangSpb::class, 'kategori_id');
    }
}

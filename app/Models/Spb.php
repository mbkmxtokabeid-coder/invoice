<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spb extends Model
{
    use HasFactory;
    protected $table = 'spb';
    protected $fillable = [
        'nama_spb',
        'customer',
        'perusahaan',
        'nomor_telepon',
        'jumlah_item',
        'status',
    ];
    public function barangSpb()
    {
        return $this->hasMany(BarangSpb::class, 'barang_id');
    }
    public function perusahaan_spb()
    {
        return $this->belongsTo(Perusahaan::class, 'nama_spb');
        // return $this->belongsTo(Perusahaan::class, 'nama_spb', 'id');
    }
}

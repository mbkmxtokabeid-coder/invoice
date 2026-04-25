<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $table = "material";
    protected $guarded = [];
    protected $fillable = [
        'kode_material',
        'jenis_material',
        'stok',
        'satuan',
        'harga_modal',
        'harga_jual',
        
    ];
   
}

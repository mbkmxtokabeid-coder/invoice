<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    
    use SoftDeletes;
    use HasFactory;
    protected $table = 'perusahaan';

    protected $fillable = [
        'nama_perusahaan',
        'alamat_perusahaan',
        'no_hp',
        'logo',
        'deleted_at',
    ];
    public function perusahaan()
    {
        return $this->hasMany(Invoice::class, 'perusahaan_id');
    }
    public function spbs()
    {
        return $this->hasMany(Spb::class, 'nama_spb');
        // return $this->hasMany(Spb::class, 'nama_spb', 'id');
    }
}

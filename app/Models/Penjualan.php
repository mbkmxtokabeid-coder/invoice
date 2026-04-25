<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Penjualan extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = "penjualan";
    protected $guarded = [];
    protected $fillable = [
        'invoice',
        'nomor_invoice',
        'tgl_penjualan',
        'customer',
        'perusahaan',
        'no_telepon',
        'admin',
        'order_by',
        'nama_sales',
        'tgl_selesai',
        'jumlah_item',
        'dp',
        'potongan',
        'jenis_pembayaran',
        'no_rek',
        'total_harga',
        'status',
        'approval',
        'diskon',
        'ppn',
        'total_pembayaran',
        'sisa_pembayaran',

    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'admin');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_by');
    }
    public function penjualanBarang()
    {
        return $this->hasMany(PenjualanBarang::class, 'penjualan_id');
    }
    public function InvoiceUpdateLog()
{
    return $this->hasMany(InvoiceUpdateLog::class, 'invoice_id');
}
}

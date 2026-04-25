<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceUpdateLog extends Model
{
    use HasFactory;
    public $timestamps = true;

    const UPDATED_AT = null; 
    protected $table = "invoice_update_logs";
    protected $guarded = [];
    protected $fillable = [
        'invoice_id',
        
    ];



      public function invoice()
      {
        return $this->belongsTo(Penjualan::class, 'invoice_id');
      }
}

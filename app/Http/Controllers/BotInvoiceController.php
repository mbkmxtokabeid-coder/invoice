<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Penjualan;


class BotInvoiceController extends Controller
{
    
    public function getInvoices($token, $filter = 'monthly')
{
    // cek token
    if ($token !== env('BOT_SECRET_KEY')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $query = DB::table('penjualan')
        ->select('id', 'nomor_invoice', 'tgl_penjualan', 'customer', 'total_harga', 'status')
        ->where('status', 'Lunas')
        ->orderBy('tgl_penjualan', 'desc');

    // === FILTERING ===
    if ($filter === 'daily') {
        // Hari ini (08:00 - 23:59 WIB sesuai timezone Laravel)
        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay   = Carbon::today()->endOfDay();
        $query->whereBetween('tgl_penjualan', [$startOfDay, $endOfDay]);

    } elseif ($filter === 'weekly') {
        // Senin sampai Sabtu minggu ini
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SATURDAY)->endOfDay();
        $query->whereBetween('tgl_penjualan', [$startOfWeek, $endOfWeek]);

    } elseif ($filter === 'monthly') {
        // Bulan berjalan
        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay();
        $endOfMonth   = Carbon::now()->endOfMonth()->endOfDay();
        $query->whereBetween('tgl_penjualan', [$startOfMonth, $endOfMonth]);
    }

    // Ambil data 5 per halaman
    $invoices = $query->paginate(5);

    return response()->json($invoices);
}



        
}

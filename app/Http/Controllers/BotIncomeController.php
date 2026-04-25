<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class BotIncomeController extends Controller
{
    // function income yearly
    public function getIncome($token)
    {
        // Validasi token sederhana
        if ($token !== "Ibeka123") {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $results = Penjualan::select(
                    DB::raw("DATE_FORMAT(tgl_penjualan, '%Y-%m') as bulan_penjualan"),
                    DB::raw("SUM(total_pembayaran) as total_bulanan")
                )
                ->where('jenis_pembayaran', '<>', 'PO')
                ->where('status','Lunas')
                ->whereNull('deleted_at')
                ->whereYear('tgl_penjualan', now()->year)
                ->groupBy(DB::raw("DATE_FORMAT(tgl_penjualan, '%Y-%m')"))
                ->orderBy('bulan_penjualan', 'desc')
                ->get();

            return response()->json([
                'bulanPenjualan' => $results->pluck('bulan_penjualan'),
                'totalBulanan'   => $results->pluck('total_bulanan'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPerHari($token)
    {
    if ($token !== "Ibeka123") {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $results = Penjualan::selectRaw('DATE(tgl_penjualan) as tanggal, SUM(total_pembayaran) as total_harian')
        ->whereYear('tgl_penjualan', now()->year)
        ->whereMonth('tgl_penjualan', now()->month)
        ->where('jenis_pembayaran', '<>', 'PO')
        ->where('status', 'Lunas')
        ->whereNull('deleted_at')
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'ASC')
        ->get();

    $tanggal = [];
    $total = [];

    foreach ($results as $row) {
        $tanggal[] = $row->tanggal;
        $total[] = $row->total_harian;
    }

    return response()->json([
        'tanggalPenjualan' => $tanggal,
        'totalHarian' => $total,
    ]);
    }
    
    public function getPerJamHarian($token)
{
    if ($token !== "Ibeka123") {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    try {
        $results = Penjualan::selectRaw("
                HOUR(tgl_penjualan) as jam,
                SUM(total_pembayaran) as total_per_jam
            ")
            ->whereDate('tgl_penjualan', now()) // hanya untuk hari ini
            ->where('jenis_pembayaran', '<>', 'PO')
            ->where('status', 'Lunas')
            ->whereNull('deleted_at')
            ->groupBy(DB::raw("HOUR(tgl_penjualan)"))
            ->orderBy(DB::raw("jam"), 'ASC')
            ->get();

        // bikin array jam 00–23 biar konsisten meski kosong
        $data = [];
        for ($i = 0; $i < 24; $i++) {
            $data[$i] = 0;
        }

        foreach ($results as $row) {
            $data[(int)$row->jam] = (int)$row->total_per_jam;
        }

        // Format jam jadi "00:00", "01:00", dst
        $labels = [];
        $values = [];
        foreach ($data as $jam => $total) {
            $labels[] = str_pad($jam, 2, '0', STR_PAD_LEFT) . ":00";
            $values[] = $total;
        }

        return response()->json([
            'income_per_hour' => collect($labels)->map(function($jam, $i) use ($values) {
                return [
                    'jam' => $jam,
                    'total' => $values[$i]
                ];
            }),
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}

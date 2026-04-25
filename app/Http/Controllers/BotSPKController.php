<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Spk;

class BotSPKController extends Controller
{
    public function getSPK($token, $filter = 'daily')
    {
        if ($token !== env('BOT_SECRET_KEY')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $query = DB::table('spk')
            ->select('id', 'nomor_invoice','customer','pekerjaan','jumlah', 'satuan','jenis_bahan','ketebalan','ukuran','lain','status_spk','status_kerja','created_at')->where('status_spk', 'Belum Selesai');

        // === FILTERING ===
        if ($filter === 'daily') {
            $today = Carbon::today();
            $query->whereDate('created_at', $today);
        } elseif ($filter === 'weekly') {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        } elseif ($filter === 'monthly') {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        }

        // === PAGINATION (10 per page) ===
        $spk = $query->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json($spk);
    }
}

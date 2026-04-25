<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Options;
use App\Models\User;
use App\Models\Barang;
use App\Models\Invoice;
use App\Models\Perusahaan;
use Barryvdh\DomPDF\PDF;
use App\Models\Penjualan;
use App\Models\Vendor;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use App\Models\PenjualanBarang;

use Illuminate\Support\Facades\DB;
use App\Exports\LaporanBarangExport;
use App\Models\PenjualanTokabe;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class LaporanPembelianController extends Controller
{

    public function index()
    {
      Carbon::setLocale('id');
        // $kategoriInv = Penjualan::orderByDesc('created_at')->get();
        // $perusahaan = Penjualan::pluck('perusahaan');
        $katVen = Vendor::pluck('nama_vendor', 'id');
        $distinctPembelian = Pembelian::distinct()->pluck('tanggal');
        $namaBulan = [];
        $daftarTahun = [];

        for ($i = 1; $i <= 12; $i++) {
            $tanggal = Carbon::create(null, $i, null);
            $namaBulan[] = $tanggal->monthName;
        }

        foreach ($distinctPembelian as $tglPembelian) {
            $tanggal = Carbon::parse($tglPembelian);

            // Menambahkan tahun jika belum ada di array $daftarTahun
            if (!in_array($tanggal->year, $daftarTahun)) {
                $daftarTahun[] = $tanggal->year;
            }
        }
      return view('pages.laporanPembelian.daftar-laporanpembelian',compact('katVen','namaBulan','daftarTahun'));
    }
    public function exportByVendor(Request $request)
{
    $status = $request->status;
    $vendorId = $request->vendor; 
    $vendor = Vendor::find($vendorId); 
    $namaVendor = $vendor ? $vendor->nama_vendor : 'Semua Vendor';
    $namaPerusahaan = Perusahaan::where('id', 1)->first()->nama_perusahaan;

    if ($vendorId == 'semua') {
        $pembelians = Pembelian::with('vendor')
            ->when(in_array($status, ['Lunas', 'Belum Lunas']), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->get();
        if (!in_array($status, ['Lunas', 'Belum Lunas'])) {
            $status = 'Seluruh Status';
        }
    } else {
        $pembelians = Pembelian::with('vendor')
            ->where('id_vendor', $vendorId)
            ->when(in_array($status, ['Lunas', 'Belum Lunas']), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->get();
        if (!in_array($status, ['Lunas', 'Belum Lunas'])) {
            $status = 'Seluruh Status';
        }
    }

    if ($pembelians->isEmpty()) {
        Alert::error('Data Pembelian tidak ditemukan');
        return redirect('daftar-laporanPembelian');
    }

    $grandTotal = $pembelians->sum('jumlah_harga');
    $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
    $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', '.');

    foreach ($pembelians as $pem) {
        $pem->formatted_tgl_pembelian = Carbon::parse($pem->tanggal)->locale('id')->isoFormat('DD MMMM YYYY');
        $pem->formatted_tgl_jto = Carbon::parse($pem->tgl_jto)->locale('id')->isoFormat('DD MMMM YYYY');
        $pem->formatted_total_transaksi = 'Rp.' . number_format($pem->jumlah_harga, 0, ',', '.');
        $pem->formatted_total_terbayar = 'Rp.' . number_format($pem->terbayar, 0, ',', '.');
        $pem->formatted_total_sisa = 'Rp.' . number_format($pem->sisa, 0, ',', '.');
    }

    if ($request->export_type == 'excel') {
        return Excel::download(new LaporanExport($pembelians), "Laporan {$namaVendor} {$status} {$tanggal}.xlsx");
    } elseif ($request->export_type == 'pdf') {
        return view('pages.laporanPembelian.pembelianpdf', compact('pembelians', 'namaVendor', 'status', 'formatGrandTotal', 'tanggal', 'namaPerusahaan'));
    }
    }
    
    
    
    public function exportByDate(Request $request)
{
    Carbon::setLocale('id');
    $tgl_custom = $request->tgl_custom;
    $vendor = vendor::find($request->vendor); // Mengambil data invoice berdasarkan ID
    $namaPerusahaan = Perusahaan::where('id', 1)->first()->nama_perusahaan;
    $namaVendor = $vendor ? $vendor->nama_vendor : 'Semua Invoice';
    $tglMulai = null;
    $tglAkhir = null;
    $tglAkhirFormat = null;
    if (strpos($tgl_custom, ' to ') !== false) {
        // Jika terdapat kata "to" dalam string
        $tglArray = explode(' to ', $tgl_custom);

        $tglMulai = Carbon::createFromFormat('d M, Y', trim($tglArray[0]))->toDateString();
        $tglAkhir = Carbon::createFromFormat('d M, Y', trim($tglArray[1]))->toDateString();
    } else {
        // Jika tidak terdapat kata "to" dalam string
        $tglMulai = date('Y-m-d', strtotime(trim($tgl_custom)));
    }

    if ($request->vendor == 'semua') {
        // dd($tglMulai, $tglAkhir);
        if (is_null($tglAkhir)) {
            $pembelians = Pembelian::whereDate('tanggal', $tglMulai)->where('status','<>','batal')->orderby('tanggal','asc')->get();
            $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');

            $grandTotal = $pembelians->sum('total_pembayaran');
        } else {
            $pembelians = Pembelian::whereBetween('tanggal', [$tglMulai, $tglAkhir])->where('status','<>','batal')->orderby('tanggal','asc')->get();
            $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
            $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');

            $grandTotal = $pembelians->sum('jumlah_harga');
        }
    } else {
        if (is_null($tglAkhir)) {
            $pembelians = Pembelian::whereDate('tanggal', $tglMulai)->where('id_vendor', $request->vendor)->where('status','<>','batal')->get();
            $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');

            $grandTotal = $pembelians->sum('jumlah_harga');
        } else {
            $pembelians = Pembelian::whereBetween('tanggal', [$tglMulai, $tglAkhir])->where('id_vendor', $request->vendor)->where('status','<>','batal')->get();
            $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
            $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');

            $grandTotal = $pembelians->sum('jumlah_harga');
        }
    }

    $periode = 'Tanggal ' . $tglAkhirFormat != null ? $tglMulaiFormat . ' - ' . $tglAkhirFormat : $tglMulaiFormat;

    if ($pembelians->isEmpty()) {
        Alert::error('Data pada tanggal tersebut tidak tersedia');
        return redirect('daftar-laporanPembelian');
    } else {
        $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');

        if ($request->export_type == 'excel') {
            return Excel::download(new LaporanExport($penjualans), 'Laporan dari tanggal ' . $tglMulai . ' Hingga tanggal ' . $tglAkhir . ' '  . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } elseif ($request->export_type == 'pdf') {
            Carbon::setLocale('id');
            $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
            // Render tampilan PDF
            foreach ($pembelians as $pem) {
                $pem->formatted_tgl_pembelian = Carbon::parse($pem->tanggal)->locale('id')->isoFormat('DD MMMM YYYY');
                $pem->formatted_tgl_jto = Carbon::parse($pem->tgl_jto)->locale('id')->isoFormat('DD MMMM YYYY');
                $pem->formatted_total_transaksi = 'Rp.' . number_format($pem->jumlah_harga, 0, ',', '.');
                $pem->formatted_total_terbayar = 'Rp.' . number_format($pem->terbayar, 0, ',', '.');
                $pem->formatted_total_sisa = 'Rp.' . number_format($pem->sisa, 0, ',', '.');
                
            }
            return view('pages.laporanPembelian.pembelianpdf-non-status', compact('pembelians', 'namaVendor', 'formatGrandTotal', 'tanggal', 'periode','namaPerusahaan'));
        }
    }
    // dd($penjualans);

}
    
    public function exportByMonth(Request $request)
    {

        $bulanIndonesia = $request->bulan;
        $periode = 'Bulan ' . $request->bulan;
        $vendor = Vendor::find($request->vendor); // Mengambil data invoice berdasarkan ID
          $namaPerusahaan = Perusahaan::where('id', 1)->first()->nama_perusahaan;
        $namaVendor = $vendor ? $vendor->nama_vendor : 'Semua Vendor';
        // Mengonversi nama bulan dari bahasa Indonesia ke bahasa Inggris
        $bulanInggris = '';
        switch ($bulanIndonesia) {
            case 'Januari':
                $bulanInggris = 'January';
                break;
            case 'Februari':
                $bulanInggris = 'February';
                break;
            case 'Maret':
                $bulanInggris = 'March';
                break;
            case 'April':
                $bulanInggris = 'April';
                break;
            case 'Mei':
                $bulanInggris = 'May';
                break;
            case 'Juni':
                $bulanInggris = 'June';
                break;
            case 'Juli':
                $bulanInggris = 'July';
                break;
            case 'Agustus':
                $bulanInggris = 'August';
                break;
            case 'September':
                $bulanInggris = 'September';
                break;
            case 'Oktober':
                $bulanInggris = 'October';
                break;
            case 'November':
                $bulanInggris = 'November';
                break;
            case 'Desember':
                $bulanInggris = 'December';
                break;
            default:
                // Jika nama bulan tidak valid, lakukan penanganan kesalahan di sini
                break;
        }

        // Mengonversi nama bulan dalam bahasa Inggris menjadi format angka bulan (misalnya, 'May' menjadi '05')
        $bulan = date('m', strtotime($bulanInggris));
        $tahun = date('Y');
        if ($request->vendor == 'semua') {
            $pembelians = Pembelian::whereMonth('tanggal', $bulan)
                ->where('status', '<>', 'Batal')
                ->whereYear('tanggal', $tahun)->get();
            $grandTotal = $pembelians->sum('jumlah_harga');
        } else {
            $pembelians = Pembelian::where('id_vendor', $request->vendor)
            ->where('status', '<>', 'Batal')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)->get();
            $grandTotal = $pembelians->sum('jumlah_harga');
        }

        // dd($request->bulan, $bulan, $tahun);
        if ($pembelians->isEmpty()) {
            Alert::error('Data Laporan Pada Bulan ' . $request->bulan . ' Tahun ' . $tahun . ' tidak tersedia');
            return redirect('daftar-laporanPembelian');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            if ($request->export_type == 'excel') {
                return Excel::download(new LaporanExport($penjualans), 'Laporan ' . $namaInvoice . ' Bulan ' . $request->bulan . ' tahun ' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($request->export_type == 'pdf') {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                // Render tampilan PDF
                foreach ($pembelians as $pem) {
                    $pem->formatted_tgl_pembelian = Carbon::parse($pem->tanggal)->locale('id')->isoFormat('DD MMMM YYYY');
                    $pem->formatted_tgl_jto = Carbon::parse($pem->tgl_jto)->locale('id')->isoFormat('DD MMMM YYYY');
                    $pem->formatted_total_transaksi = 'Rp.' . number_format($pem->jumlah_harga, 0, ',', '.');
                    $pem->formatted_total_terbayar = 'Rp.' . number_format($pem->terbayar, 0, ',', '.');
                    $pem->formatted_total_sisa = 'Rp.' . number_format($pem->sisa, 0, ',', '.');
                    
                }
                return view('pages.laporanPembelian.pembelianpdf-non-status', compact('pembelians', 'namaVendor', 'formatGrandTotal', 'tanggal', 'periode','namaPerusahaan'));
            }
        }
    }
    
     public function exportByYear(Request $request)
    {
        $periode = 'Tahun ' . $request->tahun;
        $vendor = Vendor::find($request->vendor); // Mengambil data invoice berdasarkan ID
        $namaVendor = $vendor ? $vendor->nama_vendor : 'Semua Vendor';
        $tahun = $request->tahun;
         $namaPerusahaan = Perusahaan::where('id', 1)->first()->nama_perusahaan;
        
        if ($request->vendor == 'semua') {
            $pembelians = Pembelian::whereYear('tanggal', $tahun)
            ->where('status', '<>', 'Batal')->get();
            $grandTotal = $pembelians->sum('jumlah_harga');
        } else {
            $pembelians = Pembelian::where('id_vendor', $request->vendor)
            ->whereYear('tanggal', $tahun)
            ->where('status', '<>', 'Batal')->get();
            $grandTotal = $pembelians->sum('jumlah_harga');
        }

        

        if ($pembelians->isEmpty()) {
            Alert::error('Data Laporan ' . $namaVendor . ' Tahun ' . $tahun . ' tidak tersedia');
            return redirect('daftar-laporanPembelian');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            if ($request->export_type == 'excel') {
                return Excel::download(new LaporanExport($pembelians), 'Laporan ' . $namaVendor . ' tahun ' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($request->export_type == 'pdf') {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                // Render tampilan PDF
                foreach ($pembelians as $pem) {
                    $pem->formatted_tgl_pembelian = Carbon::parse($pem->tanggal)->locale('id')->isoFormat('DD MMMM YYYY');
                    $pem->formatted_tgl_jto = Carbon::parse($pem->tgl_jto)->locale('id')->isoFormat('DD MMMM YYYY');
                    $pem->formatted_total_transaksi = 'Rp.' . number_format($pem->jumlah_harga, 0, ',', '.');
                    $pem->formatted_total_terbayar = 'Rp.' . number_format($pem->terbayar, 0, ',', '.');
                    $pem->formatted_total_sisa = 'Rp.' . number_format($pem->sisa, 0, ',', '.');
                    
                }
                return view('pages.laporanPembelian.pembelianpdf-non-status', compact('pembelians', 'namaVendor', 'formatGrandTotal', 'tanggal', 'periode','namaPerusahaan'));
            }
        }
    }


}
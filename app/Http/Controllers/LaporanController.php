<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Exports\LaporanBarangExport;
use App\Models\Invoice;
use App\Models\Penjualan;
use App\Models\PenjualanTokabe;
use App\Models\PenjualanBarang;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Options;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class LaporanController extends Controller
{

    public function index()
    {


        Carbon::setLocale('id');
        $katInv = Invoice::pluck('nama_invoice', 'id');
        $perusahaan = Perusahaan::pluck('nama_perusahaan', 'id');
        $distinctPenjualan = Penjualan::distinct()->pluck('tgl_penjualan');
        $namaBulan = [];
        $daftarTahun = [];

        for ($i = 1; $i <= 12; $i++) {
            $tanggal = Carbon::create(null, $i, null);
            $namaBulan[] = $tanggal->monthName;
        }

        foreach ($distinctPenjualan as $tglPenjualan) {
            $tanggal = Carbon::parse($tglPenjualan);

            // Menambahkan tahun jika belum ada di array $daftarTahun
            if (!in_array($tanggal->year, $daftarTahun)) {
                $daftarTahun[] = $tanggal->year;
            }
        }

        return view('pages.laporan.daftar-laporan', compact('katInv', 'namaBulan', 'daftarTahun','perusahaan'));
    }
    // Export ByDate (Custom Date)
    public function exportByDate(Request $request)
    {
        Carbon::setLocale('id');
        $tgl_custom = $request->tgl_custom;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';
        $tglMulai = null;
        $tglAkhir = null;
        $tglAkhirFormat = null;
        if (is_null($request->perusahaan)) {
            Alert::error('Mohon isi Nama Perusahaan terlebih dahulu');
            return redirect('/daftar-laporan');
        }
        $namaPerusahaan = Perusahaan::where('id', $request->perusahaan)->first()->nama_perusahaan;
        if (strpos($tgl_custom, ' to ') !== false) {
            // Jika terdapat kata "to" dalam string
            $tglArray = explode(' to ', $tgl_custom);
            $tglMulai = Carbon::createFromFormat('d M, Y', trim($tglArray[0]))->toDateString();
            $tglAkhir = Carbon::createFromFormat('d M, Y', trim($tglArray[1]))->toDateString();
        } else {
            // Jika tidak terdapat kata "to" dalam string
            $tglMulai = date('Y-m-d', strtotime(trim($tgl_custom)));
        }


        if ($namaPerusahaan == 'Total Karya Berkah') {
            if ($request->invoice == 'semua') {
                // dd($tglMulai, $tglAkhir);
                if (is_null($tglAkhir)) {
                    $penjualans = PenjualanTokabe::whereDate('tgl_penjualan', $tglMulai)->get();
                    $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');

                    $grandTotal = $penjualans->sum('total_pembayaran');
                } else {
                    $penjualans = PenjualanTokabe::whereBetween('tgl_penjualan', [$tglMulai, $tglAkhir])->get();
                    $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
                    $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');

                    $grandTotal = $penjualans->sum('total_pembayaran');
                }
            } else {
                if (is_null($tglAkhir)) {
                    $penjualans = PenjualanTokabe::whereDate('tgl_penjualan', $tglMulai)->where('invoice', $request->invoice)->get();
                    $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');

                    $grandTotal = $penjualans->sum('total_pembayaran');
                } else {
                    $penjualans = PenjualanTokabe::whereBetween('tgl_penjualan', [$tglMulai, $tglAkhir])->where('invoice', $request->invoice)->get();
                    $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
                    $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');

                    $grandTotal = $penjualans->sum('total_pembayaran');
                }
            }
        } else {
            if ($request->invoice == 'semua') {
                // dd($tglMulai, $tglAkhir);
                if (is_null($tglAkhir)) {
                    $penjualans = Penjualan::whereDate('tgl_penjualan', $tglMulai)->get();
                    $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');

                    $grandTotal = $penjualans->sum('total_pembayaran');
                } else {
                    $penjualans = Penjualan::whereBetween('tgl_penjualan', [$tglMulai, $tglAkhir])->get();
                    $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
                    $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');

                    $grandTotal = $penjualans->sum('total_pembayaran');
                }
            } else {
                if (is_null($tglAkhir)) {
                    $penjualans = Penjualan::whereDate('tgl_penjualan', $tglMulai)->where('invoice', $request->invoice)->get();
                    $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');

                    $grandTotal = $penjualans->sum('total_pembayaran');
                } else {
                    $penjualans = Penjualan::whereBetween('tgl_penjualan', [$tglMulai, $tglAkhir])->where('invoice', $request->invoice)->get();
                    $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
                    $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');

                    $grandTotal = $penjualans->sum('total_pembayaran');
                }
            }
        }


        $periode = 'Tanggal ' . $tglAkhirFormat != null ? $tglMulaiFormat . ' - ' . $tglAkhirFormat : $tglMulaiFormat;

        if ($penjualans->isEmpty()) {
            Alert::error('Data pada tanggal tersebut tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');

            if ($request->export_type == 'excel') {
                return Excel::download(new LaporanExport($penjualans), 'Laporan dari tanggal ' . $tglMulai . ' Hingga tanggal ' . $tglAkhir . ' '  . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($request->export_type == 'pdf') {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                // Render tampilan PDF
                foreach ($penjualans as $inv) {
                    $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->locale('id')->isoFormat('DD MMMM YYYY');
                    $inv->formatted_total_pembayaran = 'Rp.' . number_format($inv->total_pembayaran, 0, ',', ',');

                    $inv->formatted_total_harga = 'Rp.' . number_format($inv->total_harga, 0, ',', ',');

                    if ($inv->diskon || $inv->potongan || $inv->ppn) {
                        if ($inv->diskon) {
                            $inv->lain = 'dsc ' . $inv->diskon . '%';
                        } elseif ($inv->ppn) {
                            $inv->lain = 'ppn ' . $inv->ppn . '%';
                        } else {
                            $inv->lain = 'pot Rp.' . number_format($inv->potongan, 0, ',', '.');
                        }
                    } else {
                        $inv->lain = '-';
                    }

                    // --- INJEKSI KODE RELASI ITEM ---
                    if (isset($inv->id)) {
                        $isTokabe = $inv instanceof \App\Models\PenjualanTokabe;
                        $tableRelasi = $isTokabe ? 'penjualan_tokabe_barang' : 'penjualan_barang';
                        $fkColumn = $isTokabe ? 'penjualan_tokabe_id' : 'penjualan_id';

                        try {
                            $inv->items = DB::table($tableRelasi)
                                ->join('barang', $tableRelasi . '.barang_id', '=', 'barang.id')
                                ->where($tableRelasi . '.' . $fkColumn, $inv->id)
                                ->whereNull($tableRelasi . '.deleted_at')
                                ->select('barang.jenis_barang', $tableRelasi . '.deskripsi_item', $tableRelasi . '.qty', $tableRelasi . '.satuan')
                                ->get();
                        } catch (\Exception $e) {
                            $inv->items = collect([]);
                        }
                    } else {
                        $inv->items = collect([]);
                    }
                }
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
        // dd($penjualans);

    }

    // Export ByInvoice
    public function exportByInvoice(Request $request)
    {
        // $nama_invoice = '';
        $status_invoice = $request->status;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';


        if ($request->invoice == 'semua') {
            if ($status_invoice == 'Lunas') {
                $penjualans = Penjualan::orderByDesc('created_at')->where('status', $status_invoice)->get(); // Mengambil koleksi objek penjualan
                $grandTotal = $penjualans->sum('total_pembayaran');
                // dd($grandTotal);
            } elseif ($status_invoice == 'Belum Lunas') {
                $penjualans = Penjualan::orderByDesc('created_at')->where('status', $status_invoice)->get(); // Mengambil koleksi objek
                $status_invoice = 'Belum Lunas';
                $grandTotal = $penjualans->sum('total_pembayaran');
            } elseif ($status_invoice == 'Batal') {
                $penjualans = Penjualan::orderByDesc('created_at')->where('status', $status_invoice)->get(); // Mengambil koleksi objek
                $status_invoice = 'Batal';
                $grandTotal = $penjualans->sum('total_pembayaran');
            } else {
                $penjualans = Penjualan::orderByDesc('created_at')->get(); // Mengambil koleksi objek penjualan
                $status_invoice = 'Seluruh Status';
                $grandTotal = $penjualans->sum('total_pembayaran');
            }
        } else {
            if ($status_invoice == 'Lunas') {
                $penjualans = Penjualan::orderByDesc('created_at')->where('invoice', $request->invoice)->where('status', $status_invoice)->get(); // Mengambil koleksi objek penjualan
                $grandTotal = $penjualans->sum('total_pembayaran');
            } elseif ($status_invoice == 'Belum Lunas') {
                $penjualans = Penjualan::orderByDesc('created_at')->where('invoice', $request->invoice)->where('status', $status_invoice)->get(); // Mengambil koleksi objek
                $grandTotal = $penjualans->sum('total_pembayaran');
            } elseif ($status_invoice == 'Batal') {
                $penjualans = Penjualan::orderByDesc('created_at')->where('invoice', $request->invoice)->where('status', $status_invoice)->get(); // Mengambil koleksi objek
                $grandTotal = $penjualans->sum('total_pembayaran');
            } else {
                $penjualans = Penjualan::orderByDesc('created_at')->where('invoice', $request->invoice)->get();
                $status_invoice = 'Seluruh Status';
                $grandTotal = $penjualans->sum('total_pembayaran');
            }
        }
        if ($penjualans->isEmpty()) {
            Alert::error('Data Invoice tersebut tidak tersedia');
            return redirect('daftar-laporan');
            // session()->forget('Alert');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            // $export = new LaporanExport($penjualans);
            if ($request->export_type == 'excel') {
                // var_dump($penjualans);die;
                return Excel::download(new LaporanExport($penjualans), 'Laporan ' . $namaInvoice . ' ' . $status_invoice . ' ' . $tanggal . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($request->export_type == 'pdf') {

                Carbon::setLocale('id');

                // format grand total
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                // Render tampilan PDF
                foreach ($penjualans as $inv) {
                    $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->locale('id')->isoFormat('DD MMMM YYYY');
                    $inv->formatted_total_pembayaran = 'Rp.' . number_format($inv->total_pembayaran, 0, ',', ',');

                    $inv->formatted_total_harga = 'Rp.' . number_format($inv->total_harga, 0, ',', ',');

                    if ($inv->diskon || $inv->potongan || $inv->ppn) {
                        if ($inv->diskon) {
                            $inv->lain = 'dsc ' . $inv->diskon . '%';
                        } elseif ($inv->ppn) {
                            $inv->lain = 'ppn ' . $inv->ppn . '%';
                        } else {
                            $inv->lain = 'pot Rp.' . number_format($inv->potongan, 0, ',', '.');
                        }
                    } else {
                        $inv->lain = '-';
                    }

                    // --- INJEKSI KODE RELASI ITEM ---
                    if (isset($inv->id)) {
                        $isTokabe = $inv instanceof \App\Models\PenjualanTokabe;
                        $tableRelasi = $isTokabe ? 'penjualan_tokabe_barang' : 'penjualan_barang';
                        $fkColumn = $isTokabe ? 'penjualan_tokabe_id' : 'penjualan_id';

                        try {
                            $inv->items = DB::table($tableRelasi)
                                ->join('barang', $tableRelasi . '.barang_id', '=', 'barang.id')
                                ->where($tableRelasi . '.' . $fkColumn, $inv->id)
                                ->whereNull($tableRelasi . '.deleted_at')
                                ->select('barang.jenis_barang', $tableRelasi . '.deskripsi_item', $tableRelasi . '.qty', $tableRelasi . '.satuan')
                                ->get();
                        } catch (\Exception $e) {
                            $inv->items = collect([]);
                        }
                    } else {
                        $inv->items = collect([]);
                    }
                }
                return view('pages.laporan.pdf', compact('penjualans', 'namaInvoice', 'status_invoice', 'formatGrandTotal', 'tanggal'));
                
            }
        }
    }
    // Export berdasarkan bulan pada tahun sekarang
    public function exportByMonth(Request $request)
    {

        $bulanIndonesia = $request->bulan;
        $periode = 'Bulan ' . $request->bulan;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';
        if (is_null($request->perusahaan)) {
            Alert::error('Mohon isi Nama Perusahaan terlebih dahulu');
            return redirect('daftar-laporan');
        }
        $namaPerusahaan = Perusahaan::where('id', $request->perusahaan)->first()->nama_perusahaan;

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
        if ($namaPerusahaan == 'Total Karya Berkah') {
            if ($request->invoice == 'semua') {
                $penjualans = PenjualanTokabe::whereMonth('tgl_penjualan', $bulan)
                    ->whereYear('tgl_penjualan', $tahun)->get();
                $grandTotal = $penjualans->sum('total_pembayaran');
            } else {
                $penjualans = PenjualanTokabe::whereMonth('tgl_penjualan', $bulan)
                    ->where('invoice', $request->invoice)
                    ->whereYear('tgl_penjualan', $tahun)->get();
                $grandTotal = $penjualans->sum('total_pembayaran');
            }
        } else {
            if ($request->invoice == 'semua') {
                $penjualans = Penjualan::whereMonth('tgl_penjualan', $bulan)
                    ->whereYear('tgl_penjualan', $tahun)->where('status', '<>', 'Batal')->get();
                $grandTotal = $penjualans->sum('total_pembayaran');
            } else {
                $penjualans = Penjualan::whereMonth('tgl_penjualan', $bulan)
                    ->where('invoice', $request->invoice)
                    ->where('status', '<>', 'Batal')
                    ->whereYear('tgl_penjualan', $tahun)->get();
                $grandTotal = $penjualans->sum('total_pembayaran');
            }
        }

        // dd($request->bulan, $bulan, $tahun);
        if ($penjualans->isEmpty()) {
            Alert::error('Data Laporan Pada Bulan ' . $request->bulan . ' Tahun ' . $tahun . ' tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            if ($request->export_type == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan ' . $namaInvoice . ' Bulan ' . $request->bulan . ' tahun ' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($request->export_type == 'pdf') {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                // Render tampilan PDF
                foreach ($penjualans as $inv) {
                    $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->locale('id')->isoFormat('DD MMMM YYYY');
                    $inv->formatted_total_pembayaran = 'Rp.' . number_format($inv->total_pembayaran, 0, ',', ',');

                    $inv->formatted_total_harga = 'Rp.' . number_format($inv->total_harga, 0, ',', ',');

                    if ($inv->diskon || $inv->potongan || $inv->ppn) {
                        if ($inv->diskon) {
                            $inv->lain = 'dsc ' . $inv->diskon . '%';
                        } elseif ($inv->ppn) {
                            $inv->lain = 'ppn ' . $inv->ppn . '%';
                        } else {
                            $inv->lain = 'pot Rp.' . number_format($inv->potongan, 0, ',', '.');
                        }
                    } else {
                        $inv->lain = '-';
                    }

                    // --- INJEKSI KODE RELASI ITEM ---
                    if (isset($inv->id)) {
                        $isTokabe = $inv instanceof \App\Models\PenjualanTokabe;
                        $tableRelasi = $isTokabe ? 'penjualan_tokabe_barang' : 'penjualan_barang';
                        $fkColumn = $isTokabe ? 'penjualan_tokabe_id' : 'penjualan_id';

                        try {
                            $inv->items = DB::table($tableRelasi)
                                ->join('barang', $tableRelasi . '.barang_id', '=', 'barang.id')
                                ->where($tableRelasi . '.' . $fkColumn, $inv->id)
                                ->whereNull($tableRelasi . '.deleted_at')
                                ->select('barang.jenis_barang', $tableRelasi . '.deskripsi_item', $tableRelasi . '.qty', $tableRelasi . '.satuan')
                                ->get();
                        } catch (\Exception $e) {
                            $inv->items = collect([]);
                        }
                    } else {
                        $inv->items = collect([]);
                    }
                }
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
    }


    // Export Laporan Berdasarkan Tahun
   public function exportByYear(Request $request)
    {
        $periode = 'Tahun ' . $request->tahun;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';
        // $tahun = date('Y', strtotime($request->tahun));
        $tahun = $request->tahun;
        if (is_null($request->perusahaan)) {
            Alert::error('Mohon isi Nama Perusahaan terlebih dahulu');
            return redirect('daftar-laporan');
        }
        $namaPerusahaan = Perusahaan::where('id', $request->perusahaan)->first()->nama_perusahaan;

        // dd($tahun);

        if ($namaPerusahaan == 'Total Karya Berkah') {
            if ($request->invoice == 'semua') {
                $penjualans = PenjualanTokabe::whereYear('tgl_penjualan', $tahun)->get();
                $grandTotal = $penjualans->sum('total_pembayaran');
            } else {
                $penjualans = PenjualanTokabe::whereYear('tgl_penjualan', $tahun)->get()->where('invoice', $request->invoice);
                $grandTotal = $penjualans->sum('total_pembayaran');
            }
        } else {
            if ($request->invoice == 'semua') {
                $penjualans = Penjualan::whereYear('tgl_penjualan', $tahun)
                    ->where('status', '<>', 'Batal')->get();
                $grandTotal = $penjualans->sum('total_pembayaran');
            } else {
                $penjualans = Penjualan::whereYear('tgl_penjualan', $tahun)->where('invoice', $request->invoice)
                    ->where('status', '<>', 'Batal')->get();

                $grandTotal = $penjualans->sum('total_pembayaran');
            }
        }

        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';

        if ($penjualans->isEmpty()) {
            Alert::error('Data Laporan ' . $namaInvoice . ' Tahun ' . $tahun . ' tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            if ($request->export_type == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan ' . $namaInvoice . ' tahun ' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($request->export_type == 'pdf') {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                // Render tampilan PDF
                foreach ($penjualans as $inv) {
                    $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->locale('id')->isoFormat('DD MMMM YYYY');
                    $inv->formatted_total_pembayaran = 'Rp.' . number_format($inv->total_pembayaran, 0, ',', ',');

                    $inv->formatted_total_harga = 'Rp.' . number_format($inv->total_harga, 0, ',', ',');

                    if ($inv->diskon || $inv->potongan || $inv->ppn) {
                        if ($inv->diskon) {
                            $inv->lain = 'dsc ' . $inv->diskon . '%';
                        } elseif ($inv->ppn) {
                            $inv->lain = 'ppn ' . $inv->ppn . '%';
                        } else {
                            $inv->lain = 'pot Rp.' . number_format($inv->potongan, 0, ',', '.');
                        }
                    } else {
                        $inv->lain = '-';
                    }

                    // --- INJEKSI KODE RELASI ITEM ---
                    if (isset($inv->id)) {
                        $isTokabe = $inv instanceof \App\Models\PenjualanTokabe;
                        $tableRelasi = $isTokabe ? 'penjualan_tokabe_barang' : 'penjualan_barang';
                        $fkColumn = $isTokabe ? 'penjualan_tokabe_id' : 'penjualan_id';

                        try {
                            $inv->items = DB::table($tableRelasi)
                                ->join('barang', $tableRelasi . '.barang_id', '=', 'barang.id')
                                ->where($tableRelasi . '.' . $fkColumn, $inv->id)
                                ->whereNull($tableRelasi . '.deleted_at')
                                ->select('barang.jenis_barang', $tableRelasi . '.deskripsi_item', $tableRelasi . '.qty', $tableRelasi . '.satuan')
                                ->get();
                        } catch (\Exception $e) {
                            $inv->items = collect([]);
                        }
                    } else {
                        $inv->items = collect([]);
                    }
                }
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
    }
    
   public function exportByBarang(Request $request)
    {
        Carbon::setLocale('id');
        $tgl_custom = $request->tgl_custom;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';
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

        if ($request->invoice == 'semua') {
            
            if (is_null($tglAkhir)) {

                $penjualans = DB::table('penjualan')
                    ->whereDate('tgl_penjualan', $tglMulai)
                    ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
                    ->join('barang', 'penjualan_barang.barang_id', '=', 'barang.id')
                    ->select('penjualan.nomor_invoice', 'penjualan.tgl_penjualan', 'penjualan.customer', 'penjualan.perusahaan', 'penjualan.status', 'penjualan_barang.deskripsi_item', 'penjualan_barang.qty', 'penjualan_barang.hargaBarang', 'penjualan_barang.jumlah_harga', 'barang.jenis_barang')
                    ->whereNull('penjualan_barang.deleted_at')
                    // ->where('deleted_at','<>','Null')
                    ->where('status', '<>', 'Batal')
                    ->orderBy('penjualan.tgl_penjualan', 'asc')
                    ->get();

                $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
                
                $grandTotal = $penjualans->sum('jumlah_harga');

                
            } else {
                
                $penjualans = DB::table('penjualan')
                    // ->where('penjualan.deleted_at',null)
                    ->whereBetween('tgl_penjualan', [$tglMulai, $tglAkhir])
                    ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
                    ->join('barang', 'penjualan_barang.barang_id', '=', 'barang.id')
                    ->select('penjualan.nomor_invoice', 'penjualan.tgl_penjualan', 'penjualan.customer', 'penjualan.perusahaan', 'penjualan.status', 'penjualan_barang.deskripsi_item', 'penjualan_barang.qty', 'penjualan_barang.hargaBarang', 'penjualan_barang.jumlah_harga', 'barang.jenis_barang')
                    // ->whereNull('penjualan_barang.deleted_at')
                    ->whereNull('penjualan_barang.deleted_at')
                    ->where('status', '<>', 'Batal')
                    ->orderBy('penjualan.tgl_penjualan', 'asc')
                    ->get();

                $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
                $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');

                $grandTotal = $penjualans->sum('jumlah_harga');
                

            }
        } else {
            if (is_null($tglAkhir)) {
                // $penjualans = Penjualan::whereDate('tgl_penjualan', $tglMulai)->where('invoice', $request->invoice)->get();
                $penjualans = DB::table('penjualan')
                    ->whereDate('tgl_penjualan', $tglMulai)
                    ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
                    ->join('barang', 'penjualan_barang.barang_id', '=', 'barang.id')
                    ->select('penjualan.nomor_invoice', 'penjualan.tgl_penjualan', 'penjualan.customer', 'penjualan.perusahaan', 'penjualan.status', 'penjualan_barang.deskripsi_item', 'penjualan_barang.qty', 'penjualan_barang.hargaBarang', 'penjualan_barang.jumlah_harga', 'barang.jenis_barang')
                    ->where('invoice', $request->invoice)
                    ->whereNull('penjualan_barang.deleted_at')
                    ->where('status', '<>', 'Batal')
                    ->orderBy('penjualan.tgl_penjualan', 'asc')
                    ->get();
                $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');

                $grandTotal = $penjualans->sum('jumlah_harga');
            } else {
                // $penjualans = Penjualan::whereBetween('tgl_penjualan', [$tglMulai, $tglAkhir])->where('invoice', $request->invoice)->get();
                $penjualans = DB::table('penjualan')
                    ->whereBetween('tgl_penjualan', [$tglMulai, $tglAkhir])
                    ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
                    ->join('barang', 'penjualan_barang.barang_id', '=', 'barang.id')
                    ->select('penjualan.nomor_invoice', 'penjualan.tgl_penjualan', 'penjualan.customer', 'penjualan.perusahaan', 'penjualan.status', 'penjualan_barang.deskripsi_item', 'penjualan_barang.qty', 'penjualan_barang.hargaBarang', 'penjualan_barang.jumlah_harga', 'barang.jenis_barang')
                    ->where('invoice', $request->invoice)
                    ->whereNull('penjualan_barang.deleted_at')
                    ->where('status', '<>', 'Batal')
                    ->orderBy('penjualan.tgl_penjualan', 'asc')
                    ->get();
                    
                    
                    
                $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
                $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');

                $grandTotal = $penjualans->sum('jumlah_harga');
            }
        }

        $periode = 'Tanggal ' . $tglAkhirFormat != null ? $tglMulaiFormat . ' - ' . $tglAkhirFormat : $tglMulaiFormat;

        if ($penjualans->isEmpty()) {
            Alert::error('Data pada tanggal tersebut tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');

            if ($request->export_type == 'excel') {
                return Excel::download(new LaporanBarangExport($penjualans), 'Laporan dari tanggal ' . $tglMulai . ' Hingga tanggal ' . $tglAkhir . ' '  . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } elseif ($request->export_type == 'pdf') {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                // Render tampilan PDF
                foreach ($penjualans as $inv) {
                    $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->locale('id')->isoFormat('DD MMMM YYYY');
                    $inv->formatted_harga_barang = 'Rp.' . number_format($inv->hargaBarang, 0, ',', ',');

                    $inv->formatted_total_harga = 'Rp.' . number_format($inv->jumlah_harga, 0, ',', ',');
                }
                return view('pages.laporan.pdf-laporan-barang', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode'));
            }
        }
        
    }
}
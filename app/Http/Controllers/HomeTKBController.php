<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanTokabe;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\POController;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;

class HomeTKBController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    
    function index() {

        Carbon::setLocale('id');

        // $penjualan = Penjualan::orderByDesc('tgl_penjualan')->take(10)->get();
        $yearNow = Carbon::now()->year;
        $monthNow = Carbon::now()->month;

        $dataPerjam = $this->getPenjualan();
        $dataPenjualan = $this->getDataPenjualan();
        $dataPenjualanLastYear = $this->getDataPenjualanLastYear();

        $dataPerminggu = $this->getPerminggu();
        $dataPerbulan = $this->getPerbulan();
        $dataPerbulanLastYear = $this->getPerbulanLastYear();

        $totalPerminggu = $this->getTotalPerMinggu();
        $totalPerbulan = $this->getTotalPerBulan();
        $totalPertahun = $this->getTotalPerTahun();
       


        $hourArray = $dataPerjam['jamPenjualan'];
        $dateArray = $dataPenjualan['tanggalPenjualan'];
        $dateArrayLastYear = $dataPenjualanLastYear['tanggalPenjualanLastYear'];

        $weekArray = $dataPerminggu['tanggalPenjualan'];
        $monthArray = $dataPerbulan['bulanPenjualan'];
        $monthArrayLastYear = $dataPerbulanLastYear['bulanPenjualanLastYear'];

        $totalJamArray = $dataPerjam['totalPerjam'];
        $totalHarianArray = $dataPenjualan['totalHarian'];
        $totalHarianArrayLastYear = $dataPenjualanLastYear['totalHarianLastYear'];

        $totalMingguanArray = $dataPerminggu['totalHarian'];
        $totalBulananArray = $dataPerbulan['totalBulanan'];
        $totalBulananArrayLastYear = $dataPerbulanLastYear['totalBulananLastYear'];

        $jumlahPerHari = number_format(array_sum($dataPerjam['totalPerjam']), 0, ',', ',');
        $jumlahPerMinggu = number_format(array_sum($dataPerminggu['totalHarian']), 0, ',', ',');

        // Week For Donut Chart
        $datasInWeek = $totalPerminggu['weekDatas'];
        $percentageInWeek = $totalPerminggu['weekIn'];
        $percentageNotPaidWeek = $totalPerminggu['weekNotPaid'];
        $percentageCanceledWeek = $totalPerminggu['weekCanceled'];
        // Month For Donut Chart
        $datasInMonth = $totalPerbulan['monthDatas'];
        $percentageInMonth = $totalPerbulan['monthIn'];
        $percentageNotPaidMonth = $totalPerbulan['monthNotPaid'];
        $percentageCanceledMonth = $totalPerbulan['monthCanceled'];
        // Year For Donut Chart
        $datasInYear = $totalPertahun['yearDatas'];
        $percentageInYear = $totalPertahun['yearIn'];
        $percentageNotPaidYear = $totalPertahun['yearNotPaid'];
        $percentageCanceledYear = $totalPertahun['yearCanceled'];

        //data for vendorchart
       


        $jumlahPerBulan = number_format(PenjualanTokabe::where('jenis_pembayaran', '<>', 'PO')->whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran'), 0, ',', ',');
        $jumlahPerTahun = number_format(PenjualanTokabe::where('jenis_pembayaran', '<>', 'PO')->whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran'), 0, ',', ',');

        $jumlahBBPerTahun = number_format(PenjualanTokabe::where('jenis_pembayaran', '<>', 'PO')->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->sum('sisa_pembayaran'), 0, ',', ',');
        $jumlahBBPerBulan = number_format(PenjualanTokabe::where('jenis_pembayaran', '<>', 'PO')->whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->sum('sisa_pembayaran'), 0, ',', ',');

        // foreach ($penjualan as $jual) {
        //     $jual->formatted_date = Carbon::parse($jual->tgl_penjualan)->isoFormat('DD MMMM YYYY');
        //     $jual->formatted_sisa = number_format($jual->sisa_pembayaran);
        // }


        // Data PO
        $dataPerjamPO = $this->getPenjualanPO();
        $dataPenjualanPO = $this->getDataPenjualanPO();
        $dataPermingguPO = $this->getPermingguPO();
        $dataPerbulanPO = $this->getPerbulanPO();



        $hourArrayPO = $dataPerjamPO['jamPenjualan'];
        $dateArrayPO = $dataPenjualanPO['tanggalPenjualan'];
        $weekArrayPO = $dataPermingguPO['tanggalPenjualan'];
        $monthArrayPO = $dataPerbulanPO['bulanPenjualan'];
        $totalJamArrayPO = $dataPerjamPO['totalPerjam'];
        $totalHarianArrayPO = $dataPenjualanPO['totalHarian'];
        $totalMingguanArrayPO = $dataPermingguPO['totalHarian'];
        $totalBulananArrayPO = $dataPerbulanPO['totalBulanan'];
        $jumlahPerHariPO = number_format(array_sum($dataPerjamPO['totalPerjam']), 0, ',', ',');
        $jumlahPerMingguPO = number_format(array_sum($dataPermingguPO['totalHarian']), 0, ',', ',');

        $jumlahPerBulanPO = number_format(PenjualanTokabe::where('jenis_pembayaran', 'PO')->whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran'), 0, ',', ',');
        $jumlahPerTahunPO = number_format(PenjualanTokabe::where('jenis_pembayaran', 'PO')->whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran'), 0, ',', ',');

        $jumlahBBPerTahunPO = number_format(PenjualanTokabe::where('jenis_pembayaran', 'PO')->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->sum('sisa_pembayaran'), 0, ',', ',');
        $jumlahBBPerBulanPO = number_format(PenjualanTokabe::where('jenis_pembayaran', 'PO')->whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->sum('sisa_pembayaran'), 0, ',', ',');


        $invoicePO = DB::select("SELECT customer, tgl_penjualan, status
        FROM penjualan_tokabe
        WHERE jenis_pembayaran = 'PO'
            AND deleted_at IS NULL
        ORDER BY created_at DESC
        LIMIT 6");
        foreach ($invoicePO as $inv) {
            $inv->formatted_tgl = Carbon::parse($inv->tgl_penjualan)->format('d M Y');
        }

        // dd($vendorList);
        return view('pages.welcomeTKB', compact('jumlahPerBulan', 'jumlahPerTahun', 'dateArray','dateArrayLastYear', 'monthArray','monthArrayLastYear', 'totalHarianArray','totalHarianArrayLastYear', 'totalBulananArray','totalBulananArrayLastYear', 'hourArray', 'totalJamArray', 'weekArray', 'totalMingguanArray', 'jumlahPerHari', 'jumlahPerMinggu', 'datasInWeek', 'percentageInWeek', 'percentageNotPaidWeek', 'percentageCanceledWeek', 'datasInMonth', 'percentageInMonth', 'percentageNotPaidMonth', 'percentageCanceledMonth', 'datasInYear', 'percentageInYear', 'percentageNotPaidYear', 'percentageCanceledYear', 'jumlahBBPerBulan', 'jumlahBBPerTahun','jumlahPerBulanPO', 'jumlahPerTahunPO', 'dateArrayPO', 'monthArrayPO', 'totalHarianArrayPO', 'totalBulananArrayPO', 'hourArrayPO', 'totalJamArrayPO', 'weekArrayPO', 'totalMingguanArrayPO', 'jumlahPerHariPO', 'jumlahPerMingguPO', 'jumlahBBPerBulanPO', 'jumlahBBPerTahunPO', 'invoicePO'));
    }



    public function getPenjualan()
    {
        try {
            $sql = "SELECT
            DATE_FORMAT(tgl_penjualan, '%Y-%m-%d%H:%i') AS jam_penjualan,
            -- DATE_FORMAT(tgl_penjualan, '%Y-%m-%d %H') AS jam_penjualan,
            SUM(total_pembayaran) AS total_perjam

            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
            AND status <> 'Batal'
            AND deleted_at IS NULL
            AND DATE(tgl_penjualan) = CURDATE()
            GROUP BY jam_penjualan
            ORDER BY jam_penjualan DESC";

            $results = DB::select($sql);

            if (!empty($results)) {
                $jam_penjualan = [];
                $total_perjam = [];
                foreach ($results as $row) {
                    $datetime = date('H:i', strtotime($row->jam_penjualan));
                    $datetime_with_offset = date('Y-m-d H:i', strtotime($datetime . ' +7 hours'));
                    $jam_penjualan[] = $datetime_with_offset;
                    $total_perjam[] = $row->total_perjam;
                }
                return [
                    'jamPenjualan' => $jam_penjualan,
                    'totalPerjam' => $total_perjam,
                ];
            } else {
                return [
                    'jamPenjualan' => [],
                    'totalPerjam' => [],
                ];
            }
        } catch (\PDOException $e) {
            dd($e->getMessage());
            return 'Error';
        }
    }
        
    public function getDataPenjualan()
    {
        try {
            $sql = "SELECT
            tanggal_penjualan,
            bulan_penjualan,
            SUM(total_harian) AS total_harian,
            SUM(total_bulanan) AS total_bulanan
            FROM (
                SELECT
                    DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal_penjualan,
                    DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS bulan_penjualan,
                    total_pembayaran AS total_harian,
                    CASE WHEN MONTH(tgl_penjualan) AND YEAR(tgl_penjualan) = YEAR(NOW()) THEN total_pembayaran ELSE 0 END AS total_bulanan
                FROM penjualan_tokabe
                WHERE jenis_pembayaran <> 'PO'
                AND status <> 'Batal'
                AND deleted_at IS NULL
                AND MONTH(tgl_penjualan) = MONTH(NOW())
            ) AS subquery
            GROUP BY tanggal_penjualan, bulan_penjualan
            ORDER BY tanggal_penjualan DESC";


            $results = DB::select($sql);

            if (!empty($results)) {
                $bulan_penjualan = [];
                $tanggal_penjualan = [];
                $total_harian = [];
                $total_bulanan = [];

                foreach ($results as $row) {

                    $tanggal_penjualan[] = $row->bulan_penjualan;
                    $total_harian[] = $row->total_harian;
                    $total_bulanan[] = $row->total_bulanan;
                }

                return [
                    'bulanPenjualan' => $bulan_penjualan,
                    'tanggalPenjualan' => $tanggal_penjualan,
                    'totalHarian' => $total_harian,
                    'totalBulanan' => $total_bulanan,
                ];
            } else {
                return [
                    'bulanPenjualan' => [],
                    'tanggalPenjualan' => [],
                    'totalHarian' => [],
                    'totalBulanan' => [],
                ];
            }
            // dd($sql);
        } catch (\PDOException $e) {
            // dd($e->getMessage());
            return 'Error';
        }
    }
    public function getDataPenjualanLastYear()
{
    try {
        $sql = "SELECT
            tanggal_penjualan,
            bulan_penjualan,
            SUM(total_harian) AS total_harian,
            SUM(total_bulanan) AS total_bulanan
        FROM (
            SELECT
                DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal_penjualan,
                DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS bulan_penjualan,
                total_pembayaran AS total_harian,
                CASE WHEN MONTH(tgl_penjualan) AND YEAR(tgl_penjualan) = YEAR(NOW()) - 1 THEN total_pembayaran ELSE 0 END AS total_bulanan
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
            AND status <> 'Batal'
            AND deleted_at IS NULL
            AND MONTH(tgl_penjualan) <= MONTH(NOW())
            AND YEAR(tgl_penjualan) = YEAR(NOW()) - 1
        ) AS subquery
        GROUP BY tanggal_penjualan, bulan_penjualan
        ORDER BY tanggal_penjualan DESC";

        $results = DB::select($sql);

        if (!empty($results)) {
            $tanggal_penjualan = [];
            $total_harian = [];
            $total_bulanan = [];

            foreach ($results as $row) {
                $tanggal_penjualan[] = $row->bulan_penjualan;
                $total_harian[] = $row->total_harian;
                $total_bulanan[] = $row->total_bulanan;
            }

            return [
                'tanggalPenjualanLastYear' => $tanggal_penjualan,
                'totalHarianLastYear' => $total_harian,
                'totalBulananLastYear' => $total_bulanan,
            ];
        } else {
            return [
                'tanggalPenjualanLastYear' => [],
                'totalHarianLastYear' => [],
                'totalBulananLastYear' => [],
            ];
        }
    } catch (\PDOException $e) {
        return 'Error';
    }
}

    public function getPerminggu()
    {
        try {
            $sql = "SELECT
                tanggal_penjualan,
                SUM(total_harian) AS total_harian
                FROM (
                    SELECT
                        DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal_penjualan,
                        total_pembayaran AS total_harian
                    FROM penjualan_tokabe
                    WHERE jenis_pembayaran <> 'PO'
                    AND status <> 'Batal'
                    AND deleted_at IS NULL
                    AND tgl_penjualan >= DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY) -- Start of current week (Monday)
                    AND tgl_penjualan <= DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 8) DAY) -- End of current week (Saturday)
                ) AS subquery
                GROUP BY tanggal_penjualan
                ORDER BY tanggal_penjualan DESC";

            $results = DB::select($sql);

            if (!empty($results)) {
                $tanggal_penjualan = [];
                $total_harian = [];

                foreach ($results as $row) {
                    $tanggal_penjualan[] = $row->tanggal_penjualan;
                    $total_harian[] = $row->total_harian;
                }

                return [
                    'tanggalPenjualan' => $tanggal_penjualan,
                    'totalHarian' => $total_harian,
                ];
            } else {
                return [
                    'tanggalPenjualan' => [],
                    'totalHarian' => [],

                ];
            }
        } catch (\PDOException $e) {
            // dd($e->getMessage());
            return 'Error';
        }
    }



    function getPerbulan()
    {
        try {
            $sql = "SELECT
            DATE_FORMAT(tgl_penjualan, '%Y-%m') AS bulan_penjualan,
            SUM(total_pembayaran) AS total_bulanan
        FROM (
            SELECT DISTINCT nomor_invoice, tgl_penjualan, total_pembayaran
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
            AND status <> 'Batal'
            AND deleted_at IS NULL
                AND YEAR(tgl_penjualan) = YEAR(NOW())
        ) AS subquery
        GROUP BY DATE_FORMAT(tgl_penjualan, '%Y-%m')
        ORDER BY bulan_penjualan DESC";

            $results = DB::select($sql);

            if (!empty($results)) {
                $bulan_penjualan = [];
                $total_bulanan = [];

                foreach ($results as $row) {
                    $bulan_penjualan[] = $row->bulan_penjualan;
                    $total_bulanan[] = $row->total_bulanan;
                }

                return [
                    'bulanPenjualan' => $bulan_penjualan,
                    'totalBulanan' => $total_bulanan,
                ];
            } else {
                return [
                    'bulanPenjualan' => [],
                    'totalBulanan' => [],
                ];
            }
        } catch (\PDOException $e) {
            dd($e->getMessage());
            return 'Error';
        }
    }
    
    function getPerbulanLastYear()
{
    try {
        $sql = "SELECT
            DATE_FORMAT(tgl_penjualan, '%Y-%m') AS bulan_penjualan,
            SUM(total_pembayaran) AS total_bulanan
        FROM (
            SELECT DISTINCT nomor_invoice, tgl_penjualan, total_pembayaran
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <>'PO' AND
            status <> 'Batal'
            AND deleted_at IS NULL
                AND YEAR(tgl_penjualan) = YEAR(NOW()) - 1
        ) AS subquery
        GROUP BY DATE_FORMAT(tgl_penjualan, '%Y-%m')
        ORDER BY bulan_penjualan ASC";

        $results = DB::select($sql);

        if (!empty($results)) {
            $bulan_penjualan_last_year = [];
            $total_bulanan_last_year = [];

            foreach ($results as $row) {
                $bulan_penjualan_last_year[] = $row->bulan_penjualan;
                $total_bulanan_last_year[] = $row->total_bulanan;
            }

            return [
                'bulanPenjualanLastYear' => $bulan_penjualan_last_year,
                'totalBulananLastYear' => $total_bulanan_last_year,
            ];
        } else {
            return [
                'bulanPenjualanLastYear' => [],
                'totalBulananLastYear' => [],
            ];
        }
    } catch (\PDOException $e) {
        dd($e->getMessage());
        return 'Error';
    }
}


    function getTotalPerMinggu()
    {
        Carbon::setLocale('id');
        $today = Carbon::now();
        $startDate = $today->startOfWeek()->isoFormat('YYYY-MM-DD');
        $endDate = $today->endOfWeek()->isoFormat('YYYY-MM-DD');
        $previousWeekStart = $today->subWeek()->startOfWeek()->isoFormat('YYYY-MM-DD');
        $previousWeekEnd = $today->endOfWeek()->isoFormat('YYYY-MM-DD');

        $invoice = PenjualanTokabe::whereBetween('tgl_penjualan', [$startDate, $endDate])->get();
        $previousInv = PenjualanTokabe::whereBetween('tgl_penjualan', [$previousWeekStart, $previousWeekEnd])->get();
        $penjualanInvoice = $invoice->sum('total_pembayaran');
        $bayar = [];
        $blmBayar = [];
        $btlBayar = [];
        $preBayar = [];
        $preBlmBayar = [];
        $preBtlBayar = [];
        foreach ($invoice as $inv) {

            if ($inv->status == 'Belum Lunas') {
                $bayar[] = $inv->dp;
                $blmBayar[] = $inv->total_pembayaran - $inv->dp;
            } elseif ($inv->status == 'Lunas') {
                $bayar[] = $inv->total_pembayaran;
            } else {
                $btlBayar[] = $inv->total_pembayaran;
            }
        }
        foreach ($previousInv as $preinv) {
            if ($preinv->status == 'Belum Lunas') {
                $preBayar[] = $preinv->dp;
                $preBlmBayar[] = $preinv->total_pembayaran - $preinv->dp;
            } elseif ($preinv->status == 'Lunas') {
                $preBayar[] = $preinv->total_pembayaran;
            } else {
                $preBtlBayar[] = $preinv->total_pembayaran;
            }
        }
        $moneyIn = array_sum($bayar);
        $remainNotPaid = array_sum($blmBayar);
        $canceledPaid = array_sum($btlBayar);
        $preMoneyIn = array_sum($preBayar);
        $preRemainNotPaid = array_sum($preBlmBayar);
        $preCanceledPaid = array_sum($preBtlBayar);

        $datas = [$moneyIn, $remainNotPaid, $canceledPaid];

        $moneyInIncreasePercentage = number_format((($preMoneyIn != 0) ? (($moneyIn - $preMoneyIn) / $preMoneyIn) * 100 : 0), 2);
        $remainNotPaidDecreasePercentage = number_format((($preRemainNotPaid != 0) ? (($preRemainNotPaid - $remainNotPaid) / $preRemainNotPaid) * 100 : 0), 2);
        $canceledPaidIncreasePercentage = ($preCanceledPaid != 0) ? (($canceledPaid - $preCanceledPaid) / $preCanceledPaid) * 100 : 0;
        // return [$canceledPaid, $preCanceledPaid, $moneyInIncreasePercentage, $canceledPaidIncreasePercentage, $remainNotPaidDecreasePercentage];
        // return [$moneyIn, $remainNotPaid, $canceledPaid];
        return [
            'weekDatas' => $datas,
            'weekIn' => $moneyInIncreasePercentage,
            'weekNotPaid' => $remainNotPaidDecreasePercentage,
            'weekCanceled' => $canceledPaidIncreasePercentage,
        ];
    }

    function getTotalPerBulan()
    {
        Carbon::setLocale('id');
        $today = Carbon::now()->addHours(7);

        $startDate = $today->startOfMonth()->isoFormat('YYYY-MM-DD');
        $endDate = $today->endOfMonth()->isoFormat('YYYY-MM-DD');
        $previousMonthStart = $today->subMonth()->startOfMonth()->isoFormat('YYYY-MM-DD');
        $previousMonthEnd = $today->endOfMonth()->isoFormat('YYYY-MM-DD');
        $invoice = PenjualanTokabe::whereBetween('tgl_penjualan', [$startDate, $endDate])->get();

        $invoicetest = PenjualanTokabe::where('tgl_penjualan', $endDate)->get();
        // dd($invoicetest);

        $previousInv = PenjualanTokabe::whereBetween('tgl_penjualan', [$previousMonthStart, $previousMonthEnd])->get();
        // $penjualanInvoice = $invoice->sum('total_pembayaran');
        $bayar = [];
        $blmBayar = [];
        $btlBayar = [];
        $preBayar = [];
        $preBlmBayar = [];
        $preBtlBayar = [];

        foreach ($invoice as $inv) {
            if ($inv->status == 'Belum Lunas') {
                $bayar[] = $inv->dp;
                $blmBayar[] = $inv->total_pembayaran - $inv->dp;
            } elseif ($inv->status == 'Lunas') {
                $bayar[] = $inv->total_pembayaran;
            } else {
                $btlBayar[] = $inv->total_pembayaran;
            }
        }

        foreach ($previousInv as $preinv) {
            if ($preinv->status == 'Belum Lunas') {
                $preBayar[] = $preinv->dp;
                $preBlmBayar[] = $preinv->total_pembayaran - $preinv->dp;
            } elseif ($preinv->status == 'Lunas') {
                $preBayar[] = $preinv->total_pembayaran;
            } else {
                $preBtlBayar[] = $preinv->total_pembayaran;
            }
        }


        $moneyIn = array_sum($bayar);
        $remainNotPaid = array_sum($blmBayar);
        $canceledPaid = array_sum($btlBayar);
        $preMoneyIn = array_sum($preBayar);
        $preRemainNotPaid = array_sum($preBlmBayar);
        $preCanceledPaid = array_sum($preBtlBayar);

        $datas = [$moneyIn, $remainNotPaid, $canceledPaid];

        $moneyInIncreasePercentage = number_format((($preMoneyIn != 0) ? (($moneyIn - $preMoneyIn) / $preMoneyIn) * 100 : 0), 2);
        $remainNotPaidDecreasePercentage = number_format((($preRemainNotPaid != 0) ? (($preRemainNotPaid - $remainNotPaid) / $preRemainNotPaid) * 100 : 0), 2);
        $canceledPaidIncreasePercentage = number_format((($preCanceledPaid != 0) ? (($canceledPaid - $preCanceledPaid) / $preCanceledPaid) * 100 : 0), 2);

        return [
            'monthDatas' => $datas,
            'monthIn' => $moneyInIncreasePercentage,
            'monthNotPaid' => $remainNotPaidDecreasePercentage,
            'monthCanceled' => $canceledPaidIncreasePercentage,
        ];
    }

    function getTotalPerTahun()
    {
        Carbon::setLocale('id');
        $today = Carbon::now();
        $startDate = $today->startOfYear()->isoFormat('YYYY-MM-DD');
        $endDate = $today->endOfYear()->isoFormat('YYYY-MM-DD');
        $previousYearStart = $today->subYear()->startOfYear()->isoFormat('YYYY-MM-DD');
        $previousYearEnd = $today->endOfYear()->isoFormat('YYYY-MM-DD');

        $invoice = PenjualanTokabe::whereBetween('tgl_penjualan', [$startDate, $endDate])->get();
        $previousInv = PenjualanTokabe::whereBetween('tgl_penjualan', [$previousYearStart, $previousYearEnd])->get();
        $penjualanInvoice = $invoice->sum('total_pembayaran');
        $bayar = [];
        $blmBayar = [];
        $btlBayar = [];
        $preBayar = [];
        $preBlmBayar = [];
        $preBtlBayar = [];

        foreach ($invoice as $inv) {
            if ($inv->status == 'Belum Lunas') {
                $bayar[] = $inv->dp;
                $blmBayar[] = $inv->total_pembayaran - $inv->dp;
            } elseif ($inv->status == 'Lunas') {
                $bayar[] = $inv->total_pembayaran;
            } else {
                $btlBayar[] = $inv->total_pembayaran;
            }
        }

        foreach ($previousInv as $preinv) {
            if ($preinv->status == 'Belum Lunas') {
                $preBayar[] = $preinv->dp;
                $preBlmBayar[] = $preinv->total_pembayaran - $preinv->dp;
            } elseif ($preinv->status == 'Lunas') {
                $preBayar[] = $preinv->total_pembayaran;
            } else {
                $preBtlBayar[] = $preinv->total_pembayaran;
            }
        }

        $moneyIn = array_sum($bayar);
        $remainNotPaid = array_sum($blmBayar);
        $canceledPaid = array_sum($btlBayar);
        $preMoneyIn = array_sum($preBayar);
        $preRemainNotPaid = array_sum($preBlmBayar);
        $preCanceledPaid = array_sum($preBtlBayar);

        $datas = [$moneyIn, $remainNotPaid, $canceledPaid];

        $moneyInIncreasePercentage = number_format((($preMoneyIn != 0) ? (($moneyIn - $preMoneyIn) / $preMoneyIn) * 100 : 0), 2);
        $remainNotPaidDecreasePercentage = number_format((($preRemainNotPaid != 0) ? (($preRemainNotPaid - $remainNotPaid) / $preRemainNotPaid) * 100 : 0), 2);
        $canceledPaidIncreasePercentage = number_format((($preCanceledPaid != 0) ? (($canceledPaid - $preCanceledPaid) / $preCanceledPaid) * 100 : 0), 2);
        // dd($canceledPaid, $preBtlBayar);
        return [
            'yearDatas' => $datas,
            'yearIn' => $moneyInIncreasePercentage,
            'yearNotPaid' => $remainNotPaidDecreasePercentage,
            'yearCanceled' => $canceledPaidIncreasePercentage,
        ];
    }



    public function getPenjualanPO()
    {
      try {
        $sql = "SELECT
            DATE_FORMAT(tgl_penjualan, '%Y-%m-%d%H:%i') AS jam_penjualan,
            -- DATE_FORMAT(tgl_penjualan, '%Y-%m-%d %H') AS jam_penjualan,
            SUM(total_pembayaran) AS total_perjam
  
            FROM penjualan_tokabe
            WHERE jenis_pembayaran = 'PO'
            AND status <> 'Batal'
            AND deleted_at IS NULL
            AND DATE(tgl_penjualan) = CURDATE()
            GROUP BY jam_penjualan
            ORDER BY jam_penjualan DESC";
  
        $results = DB::select($sql);
  
        if (!empty($results)) {
          $jam_penjualan = [];
          $total_perjam = [];
          foreach ($results as $row) {
            $datetime = date('H:i', strtotime($row->jam_penjualan));
            $datetime_with_offset = date('Y-m-d H:i', strtotime($datetime . ' +7 hours'));
            $jam_penjualan[] = $datetime_with_offset;
            $total_perjam[] = $row->total_perjam;
          }
          return [
            'jamPenjualan' => $jam_penjualan,
            'totalPerjam' => $total_perjam,
          ];
        } else {
          return [
            'jamPenjualan' => [],
            'totalPerjam' => [],
          ];
        }
      } catch (\PDOException $e) {
        dd($e->getMessage());
        return 'Error';
      }
    }
  
    public function getDataPenjualanPO()
    {
      try {
        $sql = "SELECT
            tanggal_penjualan,
            bulan_penjualan,
            SUM(total_harian) AS total_harian,
            SUM(total_bulanan) AS total_bulanan
            FROM (
                SELECT
                    DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal_penjualan,
                    DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS bulan_penjualan,
                    total_pembayaran AS total_harian,
                    CASE WHEN MONTH(tgl_penjualan) AND YEAR(tgl_penjualan) = YEAR(NOW()) THEN total_pembayaran ELSE 0 END AS total_bulanan
                FROM penjualan_tokabe
                WHERE jenis_pembayaran = 'PO'
                AND status <> 'Batal'
                AND deleted_at IS NULL
                -- AND MONTH(tgl_penjualan) = MONTH(NOW())
            ) AS subquery
            GROUP BY tanggal_penjualan, bulan_penjualan
            ORDER BY tanggal_penjualan DESC";
  
  
        $results = DB::select($sql);
  
        if (!empty($results)) {
          $bulan_penjualan = [];
          $tanggal_penjualan = [];
          $total_harian = [];
          $total_bulanan = [];
  
          foreach ($results as $row) {
  
            $tanggal_penjualan[] = $row->bulan_penjualan;
            $total_harian[] = $row->total_harian;
            $total_bulanan[] = $row->total_bulanan;
          }
  
          return [
            'bulanPenjualan' => $bulan_penjualan,
            'tanggalPenjualan' => $tanggal_penjualan,
            'totalHarian' => $total_harian,
            'totalBulanan' => $total_bulanan,
          ];
        } else {
          return [
            'bulanPenjualan' => [],
            'tanggalPenjualan' => [],
            'totalHarian' => [],
            'totalBulanan' => [],
          ];
        }
        // dd($sql);
      } catch (\PDOException $e) {
        // dd($e->getMessage());
        return 'Error';
      }
    }
  
    public function getPermingguPO()
    {
      try {
        $sql = "SELECT
                tanggal_penjualan,
                SUM(total_harian) AS total_harian
                FROM (
                    SELECT
                        DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal_penjualan,
                        total_pembayaran AS total_harian
                    FROM penjualan_tokabe
                    WHERE jenis_pembayaran = 'PO'
                    AND status <> 'Batal'
                    AND deleted_at IS NULL
                    AND tgl_penjualan >= DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY) -- Start of current week (Monday)
                    AND tgl_penjualan <= DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 8) DAY) -- End of current week (Saturday)
                ) AS subquery
                GROUP BY tanggal_penjualan
                ORDER BY tanggal_penjualan DESC";
  
        $results = DB::select($sql);
  
        if (!empty($results)) {
          $tanggal_penjualan = [];
          $total_harian = [];
  
          foreach ($results as $row) {
            $tanggal_penjualan[] = $row->tanggal_penjualan;
            $total_harian[] = (int) $row->total_harian;
          }
  
          return [
            'tanggalPenjualan' => $tanggal_penjualan,
            'totalHarian' => $total_harian,
          ];
        } else {
          return [
            'tanggalPenjualan' => [],
            'totalHarian' => [],
  
          ];
        }
      } catch (\PDOException $e) {
        // dd($e->getMessage());
        return 'Error';
      }
    }
  
  
  
    function getPerbulanPO()
    {
      try {
        $sql = "SELECT
            DATE_FORMAT(tgl_penjualan, '%Y-%m') AS bulan_penjualan,
            SUM(total_pembayaran) AS total_bulanan
        FROM (
            SELECT DISTINCT nomor_invoice, tgl_penjualan, total_pembayaran
            FROM penjualan_tokabe
            WHERE jenis_pembayaran = 'PO'
            AND status <> 'Batal'
            AND deleted_at IS NULL
                AND YEAR(tgl_penjualan) = YEAR(NOW())
        ) AS subquery
        GROUP BY DATE_FORMAT(tgl_penjualan, '%Y-%m')
        ORDER BY bulan_penjualan DESC";
  
        $results = DB::select($sql);
  
        if (!empty($results)) {
          $bulan_penjualan = [];
          $total_bulanan = [];
  
          foreach ($results as $row) {
            $bulan_penjualan[] = $row->bulan_penjualan;
            $total_bulanan[] = $row->total_bulanan;
          }
  
          return [
            'bulanPenjualan' => $bulan_penjualan,
            'totalBulanan' => $total_bulanan,
          ];
        } else {
          return [
            'bulanPenjualan' => [],
            'totalBulanan' => [],
          ];
        }
      } catch (\PDOException $e) {
        dd($e->getMessage());
        return 'Error';
      }
    }
}

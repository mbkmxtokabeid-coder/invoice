<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class POController extends Controller
{

  public function getPenjualanPO()
  {
    try {
      $sql = "SELECT
          DATE_FORMAT(tgl_penjualan, '%Y-%m-%d%H:%i') AS jam_penjualan,
          -- DATE_FORMAT(tgl_penjualan, '%Y-%m-%d %H') AS jam_penjualan,
          SUM(total_pembayaran) AS total_perjam

          FROM penjualan
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
              FROM penjualan
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
                  FROM penjualan
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



  function getPerbulanPO()
  {
    try {
      $sql = "SELECT
          DATE_FORMAT(tgl_penjualan, '%Y-%m') AS bulan_penjualan,
          SUM(total_pembayaran) AS total_bulanan
      FROM (
          SELECT DISTINCT nomor_invoice, tgl_penjualan, total_pembayaran
          FROM penjualan
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

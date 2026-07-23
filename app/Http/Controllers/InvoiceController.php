<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Material;
use Carbon\Carbon;
// use DateTimeZone;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
// use App\Models\KategoriBarang;
// use App\Models\Perusahaan;
use App\Models\Order;
use App\Models\Penjualan;
use App\Models\InvoiceUpdateLog;
use App\Models\PenjualanBarang;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class InvoiceController extends Controller
{
    public function loadListInvoice()
    {
        Carbon::setLocale('id');
        
         DB::table('penjualan')
        ->where('approval', 'Unlock')
        ->whereNotNull('approved_at')
        ->where('approved_at', '<', now()->subHours(6))
        ->update([
            'approval' => 'Lock', // status lock
            'approved_at' => null
        ]);
        
        $yearNow = Carbon::now()->year;
        $monthNow = Carbon::now()->month;
        $totalPerBulan = Penjualan::whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran') / 1000000;
        if ($totalPerBulan >= 1000) {
            $jumlahPerBulan = intval($totalPerBulan);
        } else {
            $jumlahPerBulan = number_format($totalPerBulan, 2);
        }
        $totalPerTahun = Penjualan::whereYear('tgl_penjualan', $yearNow)->where('jenis_pembayaran', '<>', 'PO')->sum('total_pembayaran') / 1000000;
        if ($totalPerTahun >= 1000) {
            $jumlahPerTahun = intval($totalPerTahun);
        } else {
            $jumlahPerTahun = number_format($totalPerTahun, 2);
        }
        $jumlahBBPerTahun = number_format((Penjualan::whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->where('jenis_pembayaran', '<>', 'PO')->sum('sisa_pembayaran') / 1000000), 2);
        $jumlahBBPerBulan = number_format((Penjualan::whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->where('jenis_pembayaran', '<>', 'PO')->sum('sisa_pembayaran') / 1000000), 2);



        $penjualan = Penjualan::with('user')->orderByDesc('tgl_penjualan')->get();
        // Modify
        
        $jlhInvoice = Penjualan::whereYear('tgl_penjualan', $yearNow)->where('jenis_pembayaran', '<>', 'PO')->count();
        $dataLunas = Penjualan::where('status', 'Lunas')->where('jenis_pembayaran', '<>', 'PO')->whereYear('tgl_penjualan', $yearNow);
        $jumlahInvoice = number_format((Penjualan::sum('total_pembayaran') / 1000000), 2);
        $dataBlmLunas = Penjualan::where('status', 'Belum Lunas')->where('jenis_pembayaran', '<>', 'PO')->whereYear('tgl_penjualan', $yearNow);
        $totDpTahun = $dataBlmLunas->sum('dp') / 1000000;

        $dataBatal = Penjualan::where('status', 'Batal')->whereYear('tgl_penjualan', $yearNow);
        $lunas = $dataLunas->count();
        $belumLunas = $dataBlmLunas->count();
        $belumLunasMonth = Penjualan::where('status', 'Belum Lunas')->whereMonth('tgl_penjualan', $monthNow)->count();
        $batal = $dataBatal->count();
        $totalLunas = $dataLunas->sum('total_pembayaran') / 1000000;

        if ($totalLunas >= 1000 || $totDpTahun >= 1000) {
            $jumlahLunas = intval($totalLunas);
            $dpTahun = intval($totDpTahun);
        } else {
            $jumlahLunas = number_format($totalLunas, 2);
            $dpTahun = number_format($totDpTahun);
        }
        $jumlahBlmLunas = number_format(($dataBlmLunas->sum('total_pembayaran') / 1000000), 2);
        $jumlahBatal = number_format(($dataBatal->sum('total_pembayaran') / 1000000), 2);
        $totalLunasThn = $jumlahLunas + $dpTahun;
        foreach ($penjualan as $inv) {
            $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->format('d M Y');
            $inv->formatted_tgl_update = Carbon::parse($inv->updated_at)->addHour(7)->format('d M Y H:i');
            $inv->formatted_total_pembayaran = number_format($inv->total_pembayaran, 0, ',', ',');
            $inv->formatted_dp = number_format($inv->dp, 0, ',', ',');
            if ($inv->dp == null) {
                $inv->dp = 0;
            }
        }
        //Data For PO

        // PO Card
        $jumlahBBPerTahunPO = number_format((Penjualan::whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->where('jenis_pembayaran', 'PO')->sum('sisa_pembayaran') / 1000000), 2);
        $jumlahBBPerBulanPO = number_format((Penjualan::whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->where('jenis_pembayaran', 'PO')->sum('sisa_pembayaran') / 1000000), 2);


        $dataBlmLunasPO = Penjualan::where('status', 'Belum Lunas')->where('jenis_pembayaran', 'PO')->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
        $blmLunasPO = $dataBlmLunasPO->count();
        $totalLunasPOThn = $dataBlmLunasPO->sum('total_pembayaran') / 1000000;

        if ($totalLunasPOThn >= 1000) {
            $jumlahLunasPOThn = intval($totalLunasPOThn);
        } else {
            $jumlahLunasPOThn = number_format($totalLunasPOThn, 2);
        }

        $jumlahPO = number_format((Penjualan::where('jenis_pembayaran', 'PO')->where('status','<>','Batal')->sum('total_pembayaran') / 1000000), 2);
        $jumlahPOLunas = number_format((Penjualan::whereYear('tgl_penjualan', $yearNow)->where('jenis_pembayaran', '=', 'PO')->where('status',  'Lunas')->sum('total_pembayaran') / 1000000), 2);
        
        $jumlahPOBlmLunas = number_format((Penjualan::whereYear('tgl_penjualan', $yearNow)->where('jenis_pembayaran', '=', 'PO')->where('status', 'Belum Lunas')->sum('total_pembayaran') / 1000000), 2);
        
        $countAllInvPO = Penjualan::where('jenis_pembayaran', 'PO')->where('status','<>','Batal')->count();
        $countAllOutstandingPO = Penjualan::whereYear('tgl_penjualan', $yearNow)->where('jenis_pembayaran', 'PO')->where('status', 'Belum Lunas')->count();
        $countAllSettledPO = Penjualan::whereYear('tgl_penjualan', $yearNow)->where('jenis_pembayaran', 'PO')->where('status', 'Lunas')->count();
        return  view('pages.invoices.daftar-invoice', compact('penjualan', 'jlhInvoice', 'lunas', 'belumLunas', 'batal', 'totalLunasThn',  'jumlahBatal', 'jumlahInvoice', 'jumlahBBPerTahun', 'jumlahBBPerBulan', 'jumlahPerBulan', 'jumlahPerTahun', 'belumLunasMonth', 'jumlahPO', 'jumlahPOLunas', 'jumlahPOBlmLunas', 'countAllInvPO', 'blmLunasPO', 'countAllOutstandingPO', 'countAllSettledPO', 'jumlahBBPerBulanPO'));
    }

    // Selected Invoice Code
    public function getInvoiceByOption($selectedOption)
    {
        // Retrieve the invoice based on the selected option
        $invoice = Invoice::where('nama_invoice', $selectedOption)->first();

        // Return the invoice or appropriate response
        if ($invoice) {
            return response()->json(['kode_invoice' => $invoice->kode_invoice]);
        } else {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
    }

    public function editInvoice($id)
    {
        $inv = Penjualan::find($id);
        $invoice = Invoice::find($inv->invoice);
        $admin_now = User::find($inv->admin);
        $admin = User::where('role', 'Admin')->get();
        $order = Order::all();
        $penjualan_barang = PenjualanBarang::where('penjualan_id', $id)->get();
        $jenisBarang = Barang::all();
        $materials = Material::all();

        $jam = substr($inv->tgl_penjualan, 11, 5);
        $nomor_unik = preg_replace("/[^0-9]/", '', $inv->nomor_invoice);
        $kode = preg_replace("/[^A-Z]/", '', $inv->nomor_invoice);
        $perusahaan = $inv->perusahaan;
        $nama_adm = $admin_now->nama;
        $sales = $inv->nama_sales;
        if (!$perusahaan) {
            $perusahaan  = '-';
        }
        if (!$sales) {
            $sales = '-';
        }

        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
        }

        return view('pages.invoices.edit-invoices', compact('inv', 'jam', 'nomor_unik', 'perusahaan', 'admin', 'order', 'sales', 'materials', 'jenisBarang', 'nama_adm', 'barang', 'invoice', 'kode', 'penjualan_barang'));
    }

    public function updateInvoice(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);
        // $tot_barang = $request->input('count');
        $nmr_inv = $request->kode . $request->kodeUnik;

        $status = ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') ? 'Lunas' : 'Belum Lunas';
        $tanggalPenjualan = $request->tgl_jual . ":" . $request->jam;
        $item = (!empty($request->barang_id)) ? count($request->barang_id) : 0;

        $tot_harga = intval(str_replace(',', '', $request->tot_harga));
        $tot_pem = intval(str_replace(',', '', $request->tot_pem));
        $dp = intval(str_replace(',', '', $request->dp));
        // $potongan = intval(str_replace(',', '', $request->ptg));
        
        $sisa_pemb = ceil(floatval(str_replace(',', '', $request->sisa_pem)));

        if ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') {
            $sisa_pemb =  0;
        }
       
        if(!$request->norek){
             $norek = 'BNI';
        } else {
            $norek = $request->norek;
        }
        
         // 1. KEMBALIKAN (RESTORE) STOK BARANG & MATERIAL SEBELUMNYA
         $stokSebelumnya = [];
        foreach ($penjualan->penjualanBarang as $items) {
            $stokSebelumnya[] = [
                'barang_id' => $items->barang_id,
                'stok' => $items->qty,
                'material_id' => $items->material_id, // Simpan ID Material sebelumnya
                'material_qty' => $items->material_qty // Simpan Qty Material sebelumnya
            ];
        }
        
        foreach ($stokSebelumnya as $barangSebelumnya) {
            // Restore Stok Barang
            $barang = Barang::find($barangSebelumnya['barang_id']);
            if($barang) {
                $barang->stok += $barangSebelumnya['stok'];
                $barang->save();
            }

            // Restore Stok Material (Support Multi-Material / JSON)
            if (!empty($barangSebelumnya['material_id']) && !empty($barangSebelumnya['material_qty'])) {
                $matIds = json_decode($barangSebelumnya['material_id'], true);
                $matQtys = json_decode($barangSebelumnya['material_qty'], true);

                if (is_array($matIds)) {
                    foreach ($matIds as $index => $matId) {
                        $materialObj = Material::find($matId);
                        if ($materialObj) {
                            $materialObj->stok += (float) ($matQtys[$index] ?? 0);
                            $materialObj->save();
                        }
                    }
                } else {
                    // Fallback jika datanya masih string ID lama (sebelum multi-material)
                    $materialObj = Material::find($barangSebelumnya['material_id']);
                    if ($materialObj) {
                        $materialObj->stok += (float) $barangSebelumnya['material_qty'];
                        $materialObj->save();
                    }
                }
            }
        }
       
        $penjualan->invoice = $request->inv;
        $penjualan->nomor_invoice = $nmr_inv;
        $penjualan->tgl_penjualan = $tanggalPenjualan;
        $penjualan->customer = $request->pelanggan;
        $penjualan->perusahaan = $request->perusahaan;
        $penjualan->no_telepon = $request->tlp;
        $penjualan->admin = $request->adm;
        $penjualan->order_by = $request->order;
        $penjualan->nama_sales = $request->sales;
        $penjualan->tgl_selesai = $request->tgl_selesai;
        $penjualan->jumlah_item = $item;
        $penjualan->dp = $dp;
        $penjualan->jenis_pembayaran = $request->jns_pem;
        $penjualan->no_rek = $norek;
        $penjualan->total_harga = $tot_harga;
        $penjualan->status = $status;
        
        if ($request->select_potongan == 'PPN') {
            $penjualan->ppn = $request->biaya_lain;
            $penjualan->diskon = null;
            $penjualan->potongan = null;
        } else if ($request->select_potongan == 'Diskon') {
            $penjualan->ppn = null;
            $penjualan->diskon = $request->biaya_lain;
            $penjualan->potongan = null;
        } else if ($request->select_potongan == 'Potongan') {
            $penjualan->ppn = null;
            $penjualan->diskon = null;
            $penjualan->potongan = intval(str_replace(',', '', $request->biaya_lain));
        } else {
            $penjualan->ppn = null;
            $penjualan->diskon = null;
            $penjualan->potongan = null;
        }
        
        $penjualan->total_pembayaran = $tot_pem;
        $penjualan->sisa_pembayaran = $sisa_pemb;
        $penjualan->save();
        
        InvoiceUpdateLog::create([
        'invoice_id' => $penjualan->id,
        
        ]);

        PenjualanBarang::where('penjualan_id', $id)->delete();

        // 2. SIMPAN ITEM BARU DAN POTONG STOK (BARANG & MATERIAL)
        foreach ($request->barang_id as $key => $value) {
            $hrg = $request->hrg[$key];
            if (empty($hrg)) {
                $hrg = 0;
            }
            if (empty($value) || empty($request->deskripsi_item[$key]) || empty($request->qty[$key]) || empty($request->satuan[$key])) {
                Alert::error('Error', 'Harap lengkapi semua kolom item.');
                return redirect()->back();
               
            }

            // Bersihkan array material dari elemen kosong untuk Mencegah Array to String Conversion
            $matIds = isset($request->material_id[$key]) && is_array($request->material_id[$key]) ? array_filter($request->material_id[$key]) : [];
            $matQtys = isset($request->material_qty[$key]) && is_array($request->material_qty[$key]) ? array_filter($request->material_qty[$key], 'strlen') : [];
            $matPanjangs = isset($request->material_panjang[$key]) && is_array($request->material_panjang[$key]) ? array_filter($request->material_panjang[$key], 'strlen') : [];
            $matLebars = isset($request->material_lebar[$key]) && is_array($request->material_lebar[$key]) ? array_filter($request->material_lebar[$key], 'strlen') : [];

            $item = new PenjualanBarang();
            $item->barang_id = $value;
            $item->penjualan_id = $penjualan->id;
            $item->deskripsi_item = $request->deskripsi_item[$key];
            $item->qty = $request->qty[$key];
            $item->satuan = $request->satuan[$key];
            $item->hargaBarang = intval(str_replace(',', '', $request->hrg[$key]));
            $item->jumlah_harga = intval(str_replace(',', '', $request->jlh_hrg[$key]));
            
            // Simpan Material, Qty, P x L sebagai JSON String
            $item->material_id = !empty($matIds) ? json_encode(array_values($matIds)) : null;
            $item->material_qty = !empty($matQtys) ? json_encode(array_values($matQtys)) : null;
            $item->material_panjang = !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null;
            $item->material_lebar = !empty($matLebars) ? json_encode(array_values($matLebars)) : null;

            $item->save();

            // Kurangi Stok Barang
            $barang = Barang::find($item->barang_id);
            if ($barang) {
                $barang->stok -= $item->qty;
                $barang->save();
            }

            // Kurangi Stok Material
            if (!empty($matIds)) {
                foreach (array_values($matIds) as $mIndex => $mId) {
                    $material = Material::find($mId);
                    $mQty = array_values($matQtys)[$mIndex] ?? 0;
                    if ($material) {
                        $material->stok -= $mQty;
                        $material->save();
                    }
                }
            }
        }
        Alert::success('Data Invoice Berhasil diUpdate');
        return redirect('list-invoice');
    }


    public function cetakInvoice($id)
    {
        $penjualan = Penjualan::where('id', $id)->first();
        $invoice = Invoice::where('id', $penjualan->invoice)->first();
        $penjualan_barang = PenjualanBarang::where('penjualan_id', $id)->get();
        $admin_now = User::where('id', $penjualan->admin)->first();
        // $jenisBarang = Barang::all();
        $barang = [];
        $hargaMod = [];
        $jumlahHarga = [];
        $totHargaMod = number_format($penjualan->total_harga, 0, ',', '.');
        $sisaBayarMod = $penjualan->status === 'Lunas' ? '0' : number_format($penjualan->sisa_pembayaran, 0, ',', '.');
        $admin = $admin_now->nama;
        $tanggal =  $penjualan->tgl_penjualan;
        Carbon::setLocale('id');


        // Ubah format tanggal menjadi tanggal string
        $tanggalString = Carbon::parse($tanggal)->toDateString();

        // Format tanggal yang diinginkan dengan bulan dalam bahasa Indonesia
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');


        if ($penjualan->no_rek == "BNI")
            $norek = "BNI | A/N : Oky Irawan | No. Rek : 816029999";
        else {
            $norek = "BNI | A/N : Oky Irawan | No. Rek : 816029999";
        }

        if ($penjualan->dp == null) {
            $dp = 0;
        } else {
            $dp = number_format($penjualan->dp, 0, ',', '.');
        }

        if ($penjualan->diskon != null || $penjualan->potongan != null || $penjualan->ppn != null) {
            if ($penjualan->diskon) {
                // $biayaLain = number_format($penjualan->diskon ? $penjualan->diskon : $penjualan->ppn, 0, ',', '.') . '%';
                $biayaLain = number_format($penjualan->diskon, 0, ',', '.') . '%';
            }else if( $penjualan->ppn){
                $biayaLain = number_format($penjualan->ppn / 100 * $penjualan->total_harga);
                
            }else {
                $biayaLain = 'Rp.' . number_format($penjualan->potongan, 0, ',', '.');
            }
        } else {
            $biayaLain = 0;
        }
        
        // SELEKSI NAMA PERUSAHAAN
        if (strpos($penjualan->nomor_invoice, "T") === 0) {
            $toko = "Total Karya Berkah";
        } else {
            $toko = "Ibekami.id";
        }
        

        foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
            $hargaMod[] = number_format($item->hargaBarang, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }

        return view('pages.invoices.cetak', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin','toko'));
    }



    public function viewDownloadInvoice($id)
    {
        $penjualan = Penjualan::where('id', $id)->first();
        $invoice = Invoice::where('id', $penjualan->invoice)->first();
        $namaInv = $penjualan->nomor_invoice;
        $penjualan_barang = PenjualanBarang::where('penjualan_id', $id)->get();
        $admin_now = User::where('id', $penjualan->admin)->first();
        // $jenisBarang = Barang::all();
        $barang = [];
        $hargaMod = [];
        $jumlahHarga = [];
        $totHargaMod = number_format($penjualan->total_pembayaran, 0, ',', '.');
        $totalHarga = number_format($penjualan->total_harga, 0, ',', '.');
        $sisaBayarMod = $penjualan->status === 'Lunas' ? '0' : number_format($penjualan->sisa_pembayaran, 0, ',', '.');
        
        $admin = $admin_now->nama;
        $tanggal =  $penjualan->tgl_penjualan;
        Carbon::setLocale('id');
        $persen = '';
        $dp = null;

        // Ubah format tanggal menjadi tanggal string
        $tanggalString = Carbon::parse($tanggal)->toDateString();

        // Format tanggal yang diinginkan dengan bulan dalam bahasa Indonesia
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');


        if ($penjualan->no_rek == "BNI")
            $norek = "BNI | A/N : Oky Irawan | No. Rek : 816029999";
        else {
            $norek = '';
        }

        if ($penjualan->dp != null) {

            $dp = number_format($penjualan->dp, 0, ',', '.');
        }

        if ($penjualan->diskon != null || $penjualan->potongan != null || $penjualan->ppn != null) {
            if ($penjualan->diskon || $penjualan->ppn) {
                $potongan = ($penjualan->diskon ? $penjualan->diskon : $penjualan->ppn) / 100 * $penjualan->total_harga;
                $biayaLain = 'Rp.' . number_format($potongan, 0, ',', '.');
            } else {
                $biayaLain = 'Rp.' . number_format($penjualan->potongan, 0, ',', '.');
            }
        } else {
            $biayaLain = 0;
        }

        if ($penjualan->diskon) {
            $persen = number_format($penjualan->diskon, 0, ',', '.') . '%';
        } else if ($penjualan->ppn) {
            $persen = number_format($penjualan->ppn, 0, ',', '.') . '%';
        }
        foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
            $hargaMod[] = number_format($item->hargaBarang, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }
        // SELEKSI NAMA PERUSAHAAN
        if (strpos($penjualan->nomor_invoice, "T") === 0) {
            $toko = "Total Karya Berkah";
        } else {
            $toko = "Ikhtiar Berkah";
        }
        
       
       if ($penjualan->invoice === 5) {
            return view('pages.invoices.download-tokabe', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen','namaInv','toko'));
        }
        return view('pages.invoices.download', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod','totalHarga', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen','namaInv','toko'));
    }
    
     public function publicDownload(Request $request, $id)
    {
        // 1. CEK KEAMANAN LINK (Signature)
        // Ini memastikan link valid dan belum expired (30 hari)
        if (! $request->hasValidSignature()) {
            abort(403, 'Maaf, Link download ini sudah kadaluarsa atau tidak valid.');
        }

        // 2. LOGIKA DATA (Sama persis dengan viewDownloadInvoice Anda)
        $penjualan = Penjualan::where('id', $id)->first();

        // Cek jika data tidak ditemukan agar tidak error
        if (!$penjualan) {
            abort(404);
        }

        $invoice = Invoice::where('id', $penjualan->invoice)->first();
        $namaInv = $penjualan->nomor_invoice;
        $penjualan_barang = PenjualanBarang::where('penjualan_id', $id)->get();
        $admin_now = User::where('id', $penjualan->admin)->first();
        
        $barang = [];
        $hargaMod = [];
        $jumlahHarga = [];
        $totHargaMod = number_format($penjualan->total_pembayaran, 0, ',', '.');
        $totalHarga = number_format($penjualan->total_harga, 0, ',', '.');
        $sisaBayarMod = $penjualan->status === 'Lunas' ? '0' : number_format($penjualan->sisa_pembayaran, 0, ',', '.');
        
        $admin = $admin_now ? $admin_now->nama : 'Admin'; // Antisipasi jika user admin terhapus
        $tanggal =  $penjualan->tgl_penjualan;
        Carbon::setLocale('id');
        $persen = '';
        $dp = null;

        // Ubah format tanggal menjadi tanggal string
        $tanggalString = Carbon::parse($tanggal)->toDateString();

        // Format tanggal yang diinginkan dengan bulan dalam bahasa Indonesia
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');


        if ($penjualan->no_rek == "BNI")
            $norek = "BNI | A/N : Oky Irawan | No. Rek : 816029999";
        else {
            $norek = '';
        }

        if ($penjualan->dp != null) {
            $dp = number_format($penjualan->dp, 0, ',', '.');
        }

        if ($penjualan->diskon != null || $penjualan->potongan != null || $penjualan->ppn != null) {
            if ($penjualan->diskon || $penjualan->ppn) {
                $potongan = ($penjualan->diskon ? $penjualan->diskon : $penjualan->ppn) / 100 * $penjualan->total_harga;
                $biayaLain = 'Rp.' . number_format($potongan, 0, ',', '.');
            } else {
                $biayaLain = 'Rp.' . number_format($penjualan->potongan, 0, ',', '.');
            }
        } else {
            $biayaLain = 0;
        }

        if ($penjualan->diskon) {
            $persen = number_format($penjualan->diskon, 0, ',', '.') . '%';
        } else if ($penjualan->ppn) {
            $persen = number_format($penjualan->ppn, 0, ',', '.') . '%';
        }
        foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
            $hargaMod[] = number_format($item->hargaBarang, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }
        
        // SELEKSI NAMA PERUSAHAAN
        if (strpos($penjualan->nomor_invoice, "T") === 0) {
            $toko = "Total Karya Berkah";
        } else {
            $toko = "Ikhtiar Berkah";
        }
        
        // 3. RETURN VIEW (Sesuai kode asli Anda)
        if ($penjualan->invoice === 5) {
            return view('pages.invoices.download-tokabe', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen','namaInv','toko'));
        }
        return view('pages.invoices.public-download-view', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod','totalHarga', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen','namaInv','toko'));
    }
    
    
    public function cetakInvoiceBaru($id)
    {
        $penjualan = Penjualan::where('id', $id)->first();
        $invoice = Invoice::where('id', $penjualan->invoice)->first();
        $namaInv = $penjualan->nomor_invoice;
        $penjualan_barang = PenjualanBarang::where('penjualan_id', $id)->get();
        $admin_now = User::where('id', $penjualan->admin)->first();
        // $jenisBarang = Barang::all();
        $barang = [];
        $hargaMod = [];
        $jumlahHarga = [];
        $totHargaMod = number_format($penjualan->total_pembayaran, 0, ',', '.');
        $totalHarga = number_format($penjualan->total_harga, 0, ',', '.');
        $sisaBayarMod = $penjualan->status === 'Lunas' ? '0' : number_format($penjualan->sisa_pembayaran, 0, ',', '.');
        
        $admin = $admin_now->nama;
        $tanggal =  $penjualan->tgl_penjualan;
        Carbon::setLocale('id');
        $persen = '';
        $dp = null;

        // Ubah format tanggal menjadi tanggal string
        $tanggalString = Carbon::parse($tanggal)->toDateString();

        // Format tanggal yang diinginkan dengan bulan dalam bahasa Indonesia
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');


        if ($penjualan->no_rek == "BNI")
            $norek = "BNI | A/N : Oky Irawan | No. Rek : 816029999";
        else {
            $norek = '';
        }

        if ($penjualan->dp != null) {

            $dp = number_format($penjualan->dp, 0, ',', '.');
        }

        if ($penjualan->diskon != null || $penjualan->potongan != null || $penjualan->ppn != null) {
            if ($penjualan->diskon || $penjualan->ppn) {
                $potongan = ($penjualan->diskon ? $penjualan->diskon : $penjualan->ppn) / 100 * $penjualan->total_harga;
                $biayaLain = 'Rp.' . number_format($potongan, 0, ',', '.');
            } else {
                $biayaLain = 'Rp.' . number_format($penjualan->potongan, 0, ',', '.');
            }
        } else {
            $biayaLain = 0;
        }

        if ($penjualan->diskon) {
            $persen = number_format($penjualan->diskon, 0, ',', '.') . '%';
        } else if ($penjualan->ppn) {
            $persen = number_format($penjualan->ppn, 0, ',', '.') . '%';
        }
        foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
            $hargaMod[] = number_format($item->hargaBarang, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }
        // SELEKSI NAMA PERUSAHAAN
        if (strpos($penjualan->nomor_invoice, "T") === 0) {
            $toko = "Total Karya Berkah";
        } else {
            $toko = "Ikhtiar Berkah";
        }
        
       
       if ($penjualan->invoice === 5) {
            return view('pages.invoices.download-tokabe', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen','namaInv','toko'));
        }
        return view('pages.invoices.cetakTerbaru', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod','totalHarga', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen','namaInv','toko'));
    }

    public function downloadInvoice()
    {
        $pdf = PDF::loadview('pages.invoices.pdf');
        return $pdf->download('invoice-test.pdf');
        // return view('pages.invoices.pdf');
    }

    function statusBatal($id)
    {
        $inv = Penjualan::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->status = 'Batal';
        $inv->save();
         InvoiceUpdateLog::create([
        'invoice_id' => $inv->id,
        
        ]);
        Alert::warning('Invoice Dibatalkan');
        return redirect()->back();
    }
    function statusLunas($id)
    {
        $inv = Penjualan::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->status = 'Lunas';
        $inv->sisa_pembayaran =0;
        $inv->save();
         InvoiceUpdateLog::create([
        'invoice_id' => $inv->id,
        
        ]);
        Alert::success('Invoice Sudah Lunas');
        return redirect()->back();
    }
    
    function unlock($id)
    {
        $inv = Penjualan::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->approval = 'Unlock';
        $inv->approved_at = now();
        $inv->save();

        $inv->save();
        
        Alert::success('Invoice Sudah di-Unlock');
        return redirect()->route('daftar_invoice');
    }
    
     function lock($id)
    {
        $inv = Penjualan::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->approval = 'Lock';
        

        $inv->save();
        
        Alert::success('Invoice Sudah di-Lock');
        return redirect()->back();
    }
 
    public function approvalPage($id)
{
    $invoice = Penjualan::where('id', $id)->firstOrFail();
    $penjualan_barang = PenjualanBarang::where('penjualan_id', $id)->get();
    
    $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', ',');
    $barang = [];
    $hargaMod = [];
    $jumlahHarga = [];
     foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
            $hargaMod[] = number_format($item->hargaBarang, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }



    return view('pages.invoices.approval', compact('invoice','penjualan_barang','barang','hargaMod','jumlahHarga'));
}
    public function pelunasanPage($id)
{
    $invoice = Penjualan::where('id', $id)->firstOrFail();
    $penjualan_barang = PenjualanBarang::where('penjualan_id', $id)->get();
   $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', ',');
    $barang = [];
    $hargaMod = [];
    $jumlahHarga = [];
     foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
            $hargaMod[] = number_format($item->hargaBarang, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }


    return view('pages.invoices.pelunasan', compact('invoice','penjualan_barang','barang','hargaMod','jumlahHarga'));
}

        public function storeStatusBatal (Request $request, $id)
        {
            $invoice = Penjualan::findOrFail($id);
            $invoice->status = 'Batal';
            $invoice->alasan_batal = $request->alasan_batal; // Simpan alasan dari SweetAlert
            $invoice->save();
        
            return redirect()->back()->with('success', 'Invoice berhasil dibatalkan.');
        }
    
     public function getUpdateLog($id)
{
    $penjualan = Penjualan::with('InvoiceUpdateLog')->findOrFail($id);

    return response()->json([
        'created_at' => $penjualan->created_at->format('d-m-Y H:i'), 
        'logs' => $penjualan->InvoiceUpdateLog->map(function($log) {
            return [
                'tanggal' => $log->created_at->format('d-m-Y H:i'),
                'keterangan' => $log->keterangan,
            ];
        })
    ]);
}
    
    // ====================================================BATAS UNTUK INVOICE TOKABE======================================================
    
    
}
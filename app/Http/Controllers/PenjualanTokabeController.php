<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use App\Models\PenjualanTokabe;
use App\Models\PenjualanBarang;
use App\Models\Barang;
use App\Models\PenjualanJasaTokabe;
use App\Models\Perusahaan;
use App\Models\Material;
use App\Models\Spk;
use Carbon\Carbon;
use DateTimezone;

use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PenjualanTokabeController extends Controller
{

    public function listInvoiceTokabe()
    {
        DB::table('penjualan_tokabe')
            ->where('approval', 'Unlock')
            ->whereNotNull('approved_at')
            ->where('approved_at', '<', now()->subHours(6))
            ->update(['approval' => 'Lock', 'approved_at' => null]);

        $perusahaan = Perusahaan::where('nama_perusahaan', 'Total Karya Berkah')->first();
        $invoices = Invoice::where('perusahaan_id', $perusahaan->id)->get();

        Carbon::setLocale('id');
        $yearNow = Carbon::now()->year;
        $monthNow = Carbon::now()->month;

        $totalPerBulan = PenjualanTokabe::whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran') / 1000000;
        if ($totalPerBulan >= 1000) {
            $jumlahPerBulan = intval($totalPerBulan);
        } else {
            $jumlahPerBulan = number_format($totalPerBulan, 2);
        }
        $totalPerTahun = PenjualanTokabe::whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran') / 1000000;
        if ($totalPerTahun >= 1000) {
            $jumlahPerTahun = intval($totalPerTahun);
        } else {
            $jumlahPerTahun = number_format($totalPerTahun, 2);
        }
        $jumlahBBPerTahun = number_format((PenjualanTokabe::whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->sum('sisa_pembayaran') / 1000000), 2);

        $jumlahBBPerBulan = number_format((PenjualanTokabe::whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->sum('sisa_pembayaran') / 1000000), 2);

        $penjualan = PenjualanTokabe::with('user')->orderByDesc('tgl_penjualan')->get();
        $jlhInvoice = PenjualanTokabe::whereYear('tgl_penjualan', $yearNow)->count();
        $jumlahInvoice = number_format((PenjualanTokabe::sum('total_pembayaran') / 1000000), 2);
        $dataLunas = PenjualanTokabe::where('status', 'Lunas')->whereYear('tgl_penjualan', $yearNow);
        $dataBlmLunas = PenjualanTokabe::where('status', 'Belum Lunas')->whereYear('tgl_penjualan', $yearNow);
        $totDpTahun = $dataBlmLunas->sum('dp') / 1000000;

        $dataBatal = PenjualanTokabe::where('status', 'Batal')->whereYear('tgl_penjualan', $yearNow);
        $lunas = $dataLunas->count();
        $belumLunas = $dataBlmLunas->count();
        $belumLunasMonth = PenjualanTokabe::where('status', 'Belum Lunas')->whereMonth('tgl_penjualan', $monthNow)->count();
        $batal = $dataBatal->count();
        $totalLunas = $dataLunas->sum('total_pembayaran') / 1000000;

        if ($totalLunas >= 1000 || $totDpTahun >= 1000) {
            $jumlahLunas = intval($totalLunas);
            $dpTahun = intval($totDpTahun);
        } else {
            $jumlahLunas = number_format($totalLunas, 2);
            $dpTahun = number_format($totDpTahun);
        }
        
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

        return  view('pages.invoices.tokabe.daftar-invoiceTokabe', compact('invoices', 'perusahaan', 'penjualan', 'jlhInvoice', 'lunas', 'belumLunas', 'batal', 'totalLunasThn',  'jumlahBatal', 'jumlahInvoice', 'jumlahBBPerTahun', 'jumlahBBPerBulan', 'jumlahPerBulan', 'jumlahPerTahun', 'belumLunasMonth'));
    }

    public function addInvoice()
    {
        $no_invoice = $this->generateNoInvoice();
        $invoice = Invoice::where('perusahaan_id', 2)->get();
        $admin = User::where('role', 'AdminTKB')->where('status', 'Aktif')->get();
        $order = Order::all();
        $materials = Material::where('stok', '>', 0)->get();
        $tanggal = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $year = $tanggal->format('Y'); 
        $month = $tanggal->format('m'); 
        $day = $tanggal->format('d');
        $formatJam = $tanggal->format('H:i'); 
        $hariIni = sprintf('%s-%s-%s', $year, $month, $day);
        $jenisBarang = Barang::where('is_active', 1)->get();

        $kontak = PenjualanTokabe::select('customer', 'no_telepon', 'perusahaan')->get();
        return view('pages.invoices.tokabe.addInvoice', compact('invoice', 'admin', 'order', 'no_invoice', 'hariIni', 'formatJam', 'kontak', 'jenisBarang', 'materials'));
    }

    public function generateNoInvoice($tanggal = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $year = $tanggal->format('Y'); // Tahun
        $month = $tanggal->format('m'); // Bulan dalam angka

        // Konversi bulan ke angka Romawi
        $romawiBulan = $this->convertToRoman((int)$month);

        // Ambil invoice terakhir di bulan dan tahun yang sama
        $lastInvoice = PenjualanTokabe::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderByRaw("CAST(SUBSTRING_INDEX(nomor_invoice, '/', 1) AS UNSIGNED) DESC")
            ->pluck('nomor_invoice')
            ->first();

        $last_number = $lastInvoice ? ((int) explode('/', $lastInvoice)[0]) : 0;

        // Nomor urut berikutnya
        $next_unique_number = sprintf('%03d', $last_number + 1);

        // Format nomor invoice baru
        return "{$next_unique_number}/TKB/{$romawiBulan}/{$year}";
    }

    private function convertToRoman($number)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$number] ?? '';
    }

    public function getUpdatedInvoiceNumber(Request $request)
    {
        $tanggal = $request->input('tanggal'); // Tanggal yang dipilih user
        $newInvoice = $this->generateNoInvoice($tanggal);

        return response()->json(['no_invoice' => $newInvoice]);
    }

    public function storeInvoiceTokabe(Request $request)
{
    // --- 1. VALIDASI STOK MULTI-MATERIAL ---
    if (!empty($request->barang_id)) {
        foreach ($request->barang_id as $key => $value) {
            if (isset($request->material_id[$key]) && is_array($request->material_id[$key])) {
                foreach ($request->material_id[$key] as $matIndex => $matId) {
                    if (!empty($matId)) {
                        $material = Material::find($matId);
                        $qtyMat = $request->material_qty[$key][$matIndex] ?? 0;
                        
                        if ($material && $material->stok < $qtyMat) {
                            return response()->json([
                                'status' => 'error', 
                                'message' => "Stok material '{$material->jenis_material}' tidak mencukupi. Sisa: {$material->stok}"
                            ], 400);
                        }
                    }
                }
            }
        }
    }
    // --- END VALIDASI ---

    $invoice = Invoice::where('nama_invoice', $request->inv)->first();
    $nmr_inv = $this->generateNoInvoice();

    $status = ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') ? 'Lunas' : 'Belum Lunas';
    $tanggalPenjualan = $request->tgl_jual . ":" . $request->jam;
    $item = (!empty($request->barang_id)) ? count($request->barang_id) : 0;
    $sisa = ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') ? 0 : $request->sisa_pem;
    
    // PEMBERSIHAN ANGKA MENGGUNAKAN PREG_REPLACE (Lebih kuat dari str_replace)
    $tot_harga = intval(preg_replace('/[^0-9]/', '', $request->tot_harga ?? '0'));
    $tot_pem = intval(preg_replace('/[^0-9]/', '', $request->tot_pem ?? '0'));
    $sisa_pem = intval(preg_replace('/[^0-9]/', '', $sisa ?? '0'));
    $dp = intval(preg_replace('/[^0-9]/', '', $request->dp ?? '0'));

    $norek = $request->norek ?: 'TKB';

    $existingPenjualan = PenjualanTokabe::where('nomor_invoice', $nmr_inv)
        ->where('customer', $request->pelanggan)
        ->first();

    // Logika Pengambilan Potongan
    $jenisPotongan = $request->select_potongan ?? $request->input('select-potongan');
    $rawPotongan = $request->ptg ?? ($request->biaya_lain ?? $request->input('ptg'));
    $nilai_potongan = intval(preg_replace('/[^0-9]/', '', $rawPotongan ?? '0'));

    if ($existingPenjualan) {
        // Update Data
        $existingPenjualan->tgl_penjualan = $tanggalPenjualan;
        $existingPenjualan->perusahaan = $request->perusahaan;
        $existingPenjualan->no_telepon = $request->tlp;
        $existingPenjualan->admin = $request->adm;
        $existingPenjualan->order_by = $request->order;
        $existingPenjualan->nama_sales = $request->sales;
        $existingPenjualan->tgl_selesai = $request->tgl_selesai;
        $existingPenjualan->jumlah_item = $item;
        $existingPenjualan->dp = $dp;
        $existingPenjualan->jenis_pembayaran = $request->jns_pem;
        $existingPenjualan->no_rek = $norek;
        $existingPenjualan->total_harga = $tot_harga;
        $existingPenjualan->status = $status;
        
        // Reset semua field potongan
        $existingPenjualan->ppn = null;
        $existingPenjualan->pph = null;
        $existingPenjualan->diskon = null;
        $existingPenjualan->potongan = null;

        if ($jenisPotongan == 'PPN') {
            $existingPenjualan->ppn = $nilai_potongan;
        } else if ($jenisPotongan == 'PPH') {
            $existingPenjualan->pph = $nilai_potongan;
        } else if ($jenisPotongan == 'Diskon') {
            $existingPenjualan->diskon = $nilai_potongan;
        } else if ($jenisPotongan == 'Potongan') {
            $existingPenjualan->potongan = $nilai_potongan;
        }

        $existingPenjualan->total_pembayaran = $tot_pem;
        $existingPenjualan->sisa_pembayaran = $sisa_pem;
        $existingPenjualan->save();

        foreach ($request->barang_id as $key => $value) {
            $barang = Barang::find($value);
            if ($barang != null) {
                $existingPenjualanBarang = PenjualanJasaTokabe::where('penjualan_id', $existingPenjualan->id)
                    ->where('barang_id', $barang->id)
                    ->first();

                $matIds = isset($request->material_id[$key]) && is_array($request->material_id[$key]) ? array_filter($request->material_id[$key]) : [];
                $matQtys = isset($request->material_qty[$key]) && is_array($request->material_qty[$key]) ? array_filter($request->material_qty[$key], 'strlen') : [];
                $matPanjangs = isset($request->material_panjang[$key]) && is_array($request->material_panjang[$key]) ? array_filter($request->material_panjang[$key], 'strlen') : [];
                $matLebars = isset($request->material_lebar[$key]) && is_array($request->material_lebar[$key]) ? array_filter($request->material_lebar[$key], 'strlen') : [];

                $data_brg = [
                    'deskripsi_item' => $request->deskripsi_item[$key] ?? '',
                    'qty' => $request->qty[$key] ?? 0,
                    'satuan' => !empty($request->satuan[$key]) ? $request->satuan[$key] : 'Pcs',
                    'harga' => intval(preg_replace('/[^0-9]/', '', $request->hrg[$key] ?? '0')),
                    'jumlah_harga' => intval(preg_replace('/[^0-9]/', '', $request->jlh_hrg[$key] ?? '0')),
                    'material_id' => !empty($matIds) ? json_encode(array_values($matIds)) : null,
                    'material_qty' => !empty($matQtys) ? json_encode(array_values($matQtys)) : null,
                    'material_panjang' => !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null,
                    'material_lebar' => !empty($matLebars) ? json_encode(array_values($matLebars)) : null,
                ];

                if ($existingPenjualanBarang) {
                    $existingPenjualanBarang->update($data_brg);
                } else {
                    $data_brg['barang_id'] = $barang->id;
                    $data_brg['penjualan_id'] = $existingPenjualan->id;
                    PenjualanJasaTokabe::create($data_brg);
                }

                if (!$existingPenjualanBarang) {
                    $barang->decrement('stok', $request->qty[$key] ?? 0);
                    if (!empty($matIds)) {
                        foreach (array_values($matIds) as $mIndex => $mId) {
                            $material = Material::find($mId);
                            $mQty = array_values($matQtys)[$mIndex] ?? 0;
                            if ($material) $material->decrement('stok', $mQty);
                        }
                    }
                }
            }
        }
    } else {
        // Create Baru
        $penjualan = new PenjualanTokabe();
        $penjualan->invoice = $invoice->id;
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

        if ($jenisPotongan == 'PPN') {
            $penjualan->ppn = $nilai_potongan;
        } else if ($jenisPotongan == 'PPH') {
            $penjualan->pph = $nilai_potongan;
        } else if ($jenisPotongan == 'Diskon') {
            $penjualan->diskon = $nilai_potongan;
        } else if ($jenisPotongan == 'Potongan') {
            $penjualan->potongan = $nilai_potongan;
        }

        $penjualan->total_pembayaran = $tot_pem;
        $penjualan->sisa_pembayaran = $sisa_pem;
        $penjualan->save();

        foreach ($request->barang_id as $key => $value) {
            $barang = Barang::find($value);
            if ($barang != null) {
                $matIds = isset($request->material_id[$key]) && is_array($request->material_id[$key]) ? array_filter($request->material_id[$key]) : [];
                $matQtys = isset($request->material_qty[$key]) && is_array($request->material_qty[$key]) ? array_filter($request->material_qty[$key], 'strlen') : [];
                $matPanjangs = isset($request->material_panjang[$key]) && is_array($request->material_panjang[$key]) ? array_filter($request->material_panjang[$key], 'strlen') : [];
                $matLebars = isset($request->material_lebar[$key]) && is_array($request->material_lebar[$key]) ? array_filter($request->material_lebar[$key], 'strlen') : [];
                
                $penjualan_brg = [
                    'barang_id' => $barang->id,
                    'penjualan_id' => $penjualan->id,
                    'deskripsi_item' => $request->deskripsi_item[$key] ?? '',
                    'qty' => $request->qty[$key] ?? 0,
                    'satuan' => !empty($request->satuan[$key]) ? $request->satuan[$key] : 'Pcs',
                    'harga' => intval(preg_replace('/[^0-9]/', '', $request->hrg[$key] ?? '0')),
                    'jumlah_harga' => intval(preg_replace('/[^0-9]/', '', $request->jlh_hrg[$key] ?? '0')),
                    'material_id' => !empty($matIds) ? json_encode(array_values($matIds)) : null,
                    'material_qty' => !empty($matQtys) ? json_encode(array_values($matQtys)) : null,
                    'material_panjang' => !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null,
                    'material_lebar' => !empty($matLebars) ? json_encode(array_values($matLebars)) : null,
                ];
                PenjualanJasaTokabe::create($penjualan_brg);
                $barang->decrement('stok', $request->qty[$key] ?? 0);
            }
        }
    }

    return response()->json(['status' => 'success', 'message' => 'Data Sudah Berhasil Disimpan']);
}

    public function delete($id)
    {
        $penjualan = PenjualanTokabe::find($id);
        
        if (!$penjualan) {
            Alert::error('Data tidak ditemukan');
            return redirect('list-invoice/tokabe');
        }

        $pjBarang = PenjualanJasaTokabe::where('penjualan_id', $penjualan->id)->get();

        // Mengembalikan stok barang dan material yang terkait
        foreach ($pjBarang as $barang) {
            $barangObj = Barang::find($barang->barang_id);
            if ($barangObj) {
                $barangObj->stok += $barang->qty;
                $barangObj->save();
            }

            // Restore Stok Material (Support Multi-Material / JSON)
            if ($barang->material_id && $barang->material_qty) {
                $matIds = json_decode($barang->material_id, true);
                $matQtys = json_decode($barang->material_qty, true);

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
                    $materialObj = Material::find($barang->material_id);
                    if ($materialObj) {
                        $materialObj->stok += (float) $barang->material_qty;
                        $materialObj->save();
                    }
                }
            }
        }

        // Menghapus penjualan dan barang penjualan terkait
        $penjualan->penjualanBarang()->delete();
        $penjualan->delete();
        Alert::success('Data Berhasil dihapus');
        return redirect('list-invoice/tokabe');
    }

    public function editInvoice($id)
    {
        $inv = PenjualanTokabe::find($id);
        $invoice = Invoice::find($inv->invoice);
        $admin_now = User::find($inv->admin);
        $admin = User::where('role', 'AdminTKB')->get();
        $order = Order::all();
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $jenisBarang = Barang::all();
        $jam = substr($inv->tgl_penjualan, 11, 5);
        $nomor_unik = $inv->nomor_invoice;
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
            
            // Jembatan ALIAS: Kita mengalihkan variabel `harga` menjadi `hargaBarang`.
            // Hal ini memastikan Form Edit yang menggunakan {{$jual->hargaBarang}} tetap dapat terisi nilainya
            // meskipun di database Tokabe nama kolom sebenarnya adalah `harga`.
            $item->hargaBarang = $item->harga;
        }

        // Material untuk form edit
        $materials = Material::all(); 

        return view('pages.invoices.tokabe.edit-invoicesTokabe', compact('inv', 'jam', 'nomor_unik', 'perusahaan', 'admin', 'order', 'sales', 'jenisBarang', 'nama_adm', 'barang', 'invoice', 'penjualan_barang', 'materials'));
    }

    public function updateInvoice(Request $request, $id)
    {
        // 1. VALIDASI INPUT TERLEBIH DAHULU (Penting agar data tidak hilang!)
        if (!empty($request->barang_id)) {
            foreach ($request->barang_id as $key => $value) {
                // Mengambil nilai string harga, jika kosong kita set menjadi string kosong ''
                $hrg = $request->hrg[$key] ?? '';
                // Validasi basic: barang, qty, satuan, dan harga harus ada.
                if (empty($value) || empty($request->qty[$key]) || empty($request->satuan[$key]) || $hrg === '') {
                    Alert::error('Error', 'Harap lengkapi semua kolom wajib pada item (Barang, Qty, Satuan, Harga).');
                    return redirect()->back()->withInput();
                }
            }
        }
        
        $penjualan = PenjualanTokabe::find($id);
        $nmr_inv = $request->kode . $request->kodeUnik;

        $status = ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') ? 'Lunas' : 'Belum Lunas';
        $tanggalPenjualan = $request->tgl_jual . ":" . $request->jam;
        $item = (!empty($request->barang_id)) ? count($request->barang_id) : 0;

        $tot_harga = intval(str_replace(',', '', $request->tot_harga));
        $tot_pem = intval(str_replace(',', '', $request->tot_pem));
        $dp = intval(str_replace(',', '', $request->dp));
        
        $sisa_pemb = $request->sisa_pem;

        if ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') {
            $sisa_pemb =  0;
        }
        $sisa_pem = intval(str_replace(',', '', $sisa_pemb));

        if(!$request->norek){
            $norek = 'TKB';
        } else {
            $norek = $request->norek;
        }

        // 2. KEMBALIKAN (RESTORE) STOK BARANG & MATERIAL SEBELUMNYA
        $pjBarang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();

        foreach ($pjBarang as $barang) {
            $barangObj = Barang::find($barang->barang_id);
            if ($barangObj) {
                $barangObj->stok += $barang->qty;
                $barangObj->save();
            }

            // Restore Stok Material JSON
            if ($barang->material_id && $barang->material_qty) {
                $matIds = is_string($barang->material_id) ? json_decode($barang->material_id, true) : $barang->material_id;
                $matQtys = is_string($barang->material_qty) ? json_decode($barang->material_qty, true) : $barang->material_qty;

                if (is_array($matIds)) {
                    foreach ($matIds as $index => $matId) {
                        $materialObj = Material::find($matId);
                        if ($materialObj) {
                            $materialObj->stok += (float) ($matQtys[$index] ?? 0);
                            $materialObj->save();
                        }
                    }
                } else {
                    $materialObj = Material::find($barang->material_id);
                    if ($materialObj) {
                        $materialObj->stok += (float)$barang->material_qty;
                        $materialObj->save();
                    }
                }
            }
        }
        
        // HAPUS ITEM LAMA (Sekarang aman karena sudah divalidasi di atas)
        $penjualan->penjualanBarang()->delete();

        // Mencegah error salah ketik nama select_potongan
        $jenisPotongan = $request->select_potongan ?? $request->input('select-potongan');
        $rawPotongan = $request->biaya_lain ?? ($request->ptg ?? $request->input('biaya_lain'));
        $nilai_potongan = intval(preg_replace('/[^0-9]/', '', $rawPotongan ?? '0'));

        // 3. SIMPAN PENJUALAN BARU
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
        
        if ($jenisPotongan == 'PPN') {
            $penjualan->ppn = $nilai_potongan;
            $penjualan->pph = null;
            $penjualan->diskon = null;
            $penjualan->potongan = null;
        } else if ($jenisPotongan == 'PPH') {
            $penjualan->ppn = null;
            $penjualan->pph = $nilai_potongan;
            $penjualan->diskon = null;
            $penjualan->potongan = null;
        } else if ($jenisPotongan == 'Diskon') {
            $penjualan->ppn = null;
            $penjualan->pph = null;
            $penjualan->diskon = $nilai_potongan;
            $penjualan->potongan = null;
        } else if ($jenisPotongan == 'Potongan') {
            $penjualan->ppn = null;
            $penjualan->pph = null;
            $penjualan->diskon = null;
            $penjualan->potongan = intval(str_replace(',', '', $nilai_potongan));
        } else {
            $penjualan->ppn = null;
            $penjualan->pph = null;
            $penjualan->diskon = null;
            $penjualan->potongan = null;
        }
        
        $penjualan->total_pembayaran = $tot_pem;
        $penjualan->sisa_pembayaran = $sisa_pem;
        $penjualan->save();
        
        // 4. TAMBAHKAN ITEM BARU & POTONG STOK
        if (!empty($request->barang_id)) {
            foreach ($request->barang_id as $key => $value) {
                $hrg = $request->hrg[$key] ?? 0;
                
                $matIds = isset($request->material_id[$key]) && is_array($request->material_id[$key]) ? array_filter($request->material_id[$key]) : [];
                $matQtys = isset($request->material_qty[$key]) && is_array($request->material_qty[$key]) ? array_filter($request->material_qty[$key], 'strlen') : [];
                $matPanjangs = isset($request->material_panjang[$key]) && is_array($request->material_panjang[$key]) ? array_filter($request->material_panjang[$key], 'strlen') : [];
                $matLebars = isset($request->material_lebar[$key]) && is_array($request->material_lebar[$key]) ? array_filter($request->material_lebar[$key], 'strlen') : [];
                
                $itemData = new PenjualanJasaTokabe();
                $itemData->barang_id = $value;
                $itemData->penjualan_id = $penjualan->id;
                $itemData->deskripsi_item = $request->deskripsi_item[$key] ?? null;
                $itemData->qty = $request->qty[$key];
                $itemData->satuan = $request->satuan[$key];
                $itemData->harga = intval(str_replace(',', '', $hrg));
                $itemData->jumlah_harga = intval(str_replace(',', '', $request->jlh_hrg[$key] ?? '0'));
                
                $itemData->material_id = !empty($matIds) ? json_encode(array_values($matIds)) : null;
                $itemData->material_qty = !empty($matQtys) ? json_encode(array_values($matQtys)) : null;
                $itemData->material_panjang = !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null;
                $itemData->material_lebar = !empty($matLebars) ? json_encode(array_values($matLebars)) : null;

                $itemData->save();

                // Potong Stok Barang Biasa
                $barang = Barang::find($itemData->barang_id);
                if ($barang) {
                    $barang->stok -= $itemData->qty;
                    $barang->save();
                }
                
                // Potong Stok Material JSON
                if (!empty($matIds)) {
                    foreach (array_values($matIds) as $mIndex => $mId) {
                        $material = Material::find($mId);
                        $mQty = array_values($matQtys)[$mIndex] ?? 0;
                        if ($material) {
                            $material->decrement('stok', $mQty);
                        }
                    }
                }
            }
        }
        
        $penjualan->approval = 'Lock';
        $penjualan->approved_at = null;
        $penjualan->save();
        
        Alert::success('Data Invoice Berhasil diUpdate');
        return redirect('list-invoice/tokabe');
    }
    
    function ubahStatus($status, $id)
    {
        $invoice = PenjualanTokabe::find($id);
        if (!$invoice) {
            return back()->with(['error' => 'Data tidak ditemukan!']);
        }
        if ($status === 'batal') {
            $penjBarang = PenjualanJasaTokabe::where('penjualan_id', $invoice->id)->get();

            // Mengembalikan stok barang dan material yang terkait JSON
            foreach ($penjBarang as $barang) {
                $barangObj = Barang::find($barang->barang_id);
                if ($barangObj) {
                    $barangObj->stok += $barang->qty;
                    $barangObj->save();
                }

                if ($barang->material_id && $barang->material_qty) {
                    $matIds = json_decode($barang->material_id, true);
                    $matQtys = json_decode($barang->material_qty, true);

                    if (is_array($matIds)) {
                        foreach ($matIds as $index => $matId) {
                            $materialObj = Material::find($matId);
                            if ($materialObj) {
                                $materialObj->stok += (float) ($matQtys[$index] ?? 0);
                                $materialObj->save();
                            }
                        }
                    } else {
                        // Fallback Format Lama
                        $materialObj = Material::find($barang->material_id);
                        if ($materialObj) {
                            $materialObj->stok += (float) $barang->material_qty;
                            $materialObj->save();
                        }
                    }
                }
            }
            $invoice->status = 'Batal';
            $invoice->save();

            return back()->with(['batal' => 'Invoice telah dibatalkan']);
        } else {
            $invoice->status = 'Lunas';
            $invoice->sisa_pembayaran = 0;
            $invoice->save();
            return back()->with(['lunas' => 'Invoice sudah Lunas']);
        }
    }
    
    public function unlock($id)
    {
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->approval = 'Unlock';
        $inv->approved_at = now();
        $inv->save();
        Alert::success('Invoice Tokabe berhasil di-Unlock');
        return redirect()->route('list_invoice_tokabe');
    }

    public function lock($id)
    {
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->approval = 'Lock';
        $inv->approved_at = null;
        $inv->save();
        Alert::success('Invoice Tokabe berhasil di-Lock');
        return redirect()->back();
    }

    public function approvalPage($id)
    {
        $invoice = PenjualanTokabe::findOrFail($id);
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', '.');
        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barang[] = Barang::where('id', $item->barang_id)->get();
        }
        return view('pages.invoices.tokabe.approval', compact('invoice', 'penjualan_barang', 'barang'));
    }

    public function pelunasanPage($id)
    {
        $invoice = PenjualanTokabe::findOrFail($id);
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', '.');
        $invoice->formatted_sisa_pembayaran = number_format($invoice->sisa_pembayaran, 0, ',', '.');
        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barang[] = Barang::where('id', $item->barang_id)->get();
        }
        return view('pages.invoices.tokabe.pelunasan', compact('invoice', 'penjualan_barang', 'barang'));
    }

    public function statusLunas($id)
    {
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->status = 'Lunas';
        $inv->sisa_pembayaran = 0;
        $inv->save();
        Alert::success('Invoice Tokabe Sudah Lunas');
        return redirect()->route('list_invoice_tokabe');
    }

    public function storeStatusBatal(Request $request, $id)
    {
        $invoice = PenjualanTokabe::findOrFail($id);
        $invoice->status = 'Batal';
        $invoice->alasan_batal = $request->alasan_batal;
        $invoice->save();
        return redirect()->back()->with('batal', 'Invoice Tokabe berhasil dibatalkan.');
    }

    public function cetakInvoiceTKB($id)
    {
        $penjualan = PenjualanTokabe::where('id', $id)->first();
        $invoice = Invoice::where('id', $penjualan->invoice)->first();
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $admin_now = User::where('id', $penjualan->admin)->first();
        $barang = [];
        $hargaMod = [];
        $jumlahHarga = [];
        $totHargaMod = number_format($penjualan->total_pembayaran, 0, ',', '.');
        
        if ($penjualan->status == 'Lunas' && $penjualan->jenis_pembayaran != 'Cash Lunas' && $penjualan->jenis_pembayaran != 'Transfer Lunas') {
            $sisaBayarMod = 0 . '(Lunas)';
        } else {
            $sisaBayarMod = number_format($penjualan->sisa_pembayaran, 0, ',', '.');
        }
        $admin = $admin_now->nama;
        $tanggal =  $penjualan->tgl_penjualan;
        Carbon::setLocale('id');

        $tanggalString = Carbon::parse($tanggal)->toDateString();
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');

        $toko="Total Karya Berkah";
        
        if ($penjualan->no_rek == "BNI"){
            $norek = "BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999";
        }elseif ($penjualan->no_rek == "TKBBNI"){
            $norek = "BNI | A/N : PT. Total Karya Berkah | No. Rek : 3528289999";
        }else{
            $norek="BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999";
        }
        
        if ($penjualan->dp == null) {
            $dp = 0;
        } else {
            $dp = number_format($penjualan->dp, 0, ',', '.');
        }

        if ($penjualan->diskon != null || $penjualan->potongan != null || $penjualan->ppn != null || $penjualan->pph != null) {
            if ($penjualan->diskon || $penjualan->ppn || $penjualan->pph) {
                $pengali = $penjualan->diskon ?: ($penjualan->ppn ?: $penjualan->pph);
                
                // Jika Diskon, nilainya minus di tampilan harga
                if ($penjualan->diskon) {
                    $biayaLain = number_format($pengali * -$penjualan->total_harga / 100, 0, ',', '.');
                } else {
                    $biayaLain = number_format($pengali * $penjualan->total_harga / 100, 0, ',', '.');
                }
                $persen = $pengali;
            } else {
                $biayaLain = 'Rp.' . number_format($penjualan->potongan, 0, ',', '.');
            }
        } else {
            $biayaLain = 0;
            $persen = null;
        }

        foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
            $hargaMod[] = number_format($item->harga, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }

        return view('pages.invoices.tokabe.downloadTKB', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'toko', 'totHargaMod', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen'));
    }

    public function viewDownloadInvoiceTKB($id)
    {
        $penjualan = PenjualanTokabe::find($id);
        $invoice = Invoice::where('id', $penjualan->invoice)->first();
        $namaInvoice = $invoice->nama_invoice;
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $admin_now = User::where('id', $penjualan->admin)->first();
        $barang = [];
        $hargaMod = [];
        $jumlahHarga = [];
        $totHargaMod = number_format($penjualan->total_pembayaran, 0, ',', '.');
        if ($penjualan->status == 'Lunas' && $penjualan->jenis_pembayaran != 'Cash Lunas' && $penjualan->jenis_pembayaran != 'Transfer Lunas') {
            $sisaBayarMod = 0 . '(Lunas)';
        } else {
            $sisaBayarMod = number_format($penjualan->sisa_pembayaran, 0, ',', '.');
        }
        $admin = $admin_now->nama;
        $tanggal =  $penjualan->tgl_penjualan;
        Carbon::setLocale('id');
        $persen = '';
        
        $tanggalString = Carbon::parse($tanggal)->toDateString();
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');

         if ($penjualan->no_rek == "BNI"){
            $norek = "BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999";
        }elseif ($penjualan->no_rek == "TKBBNI"){
            $norek = "BNI | A/N : PT. Total Karya Berkah | No. Rek : 3528289999";
        }else{
            $norek="BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999";
        }
        
        if ($penjualan->dp == null) {
            $dp = 0;
        } else {
            $dp = number_format($penjualan->dp, 0, ',', '.');
        }

        if ($penjualan->diskon != null || $penjualan->potongan != 0 || $penjualan->ppn != null || $penjualan->pph != null) {
            if ($penjualan->diskon || $penjualan->ppn || $penjualan->pph) {
                $pengali = $penjualan->diskon ?: ($penjualan->ppn ?: $penjualan->pph);
                $potongan = $pengali / 100 * $penjualan->total_harga;
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
        } else if ($penjualan->pph) {
            $persen = number_format($penjualan->pph, 0, ',', '.') . '%';
        }
        
        foreach ($penjualan_barang as $item) {
            $barangItem = Barang::where('id', $item->barang_id)->get();
            $barang[] = $barangItem;
            $hargaMod[] = number_format($item->harga, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }
        return view('pages.invoices.tokabe.downloadTKB', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen', 'namaInvoice'));
    }

    public function storeDariInvoiceTokabe(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required',
            'express'    => 'required'
        ]);

        $penjualan = PenjualanTokabe::with(['penjualanBarang'])->findOrFail($request->invoice_id);
        
        $existingSpk = Spk::where('nomor_invoice', $penjualan->nomor_invoice)->first();
        if ($existingSpk) {
            Alert::error('Gagal', 'SPK untuk Invoice ' . $penjualan->nomor_invoice . ' sudah dibuat sebelumnya.');
            return redirect()->back();
        }

        // 1. Ambil Nama Barang/Pekerjaan
        $pekerjaanRil = $penjualan->penjualanBarang->map(function($item) {
            $barang = Barang::find($item->barang_id);
            $nama = $barang ? $barang->jenis_barang : 'Item Tidak Diketahui';
            return $item->deskripsi_item ? $nama . " (" . $item->deskripsi_item . ")" : $nama;
        })->unique()->filter()->implode(', ');

        // 2. Ambil Jenis Bahan dari Material (SANGAT AMAN: Mendukung JSON Array Multi-Material)
        $bahanRil = $penjualan->penjualanBarang->map(function($item) {
            if (!empty($item->material_id)) {
                $matIds = is_string($item->material_id) && is_array(json_decode($item->material_id, true))
                          ? json_decode($item->material_id, true)
                          : [$item->material_id];

                $namaMaterials = [];
                foreach ($matIds as $mId) {
                    if ($mId) {
                        $mat = Material::find($mId);
                        if ($mat) {
                            $namaMaterials[] = $mat->jenis_material;
                        }
                    }
                }
                return !empty($namaMaterials) ? implode(', ', $namaMaterials) : null;
            }
            return null;
        })->filter()->unique()->implode(', ');

        // 3. Ambil Ukuran (SANGAT AMAN: Mendukung JSON Array Multi-Material)
        $ukuranRil = $penjualan->penjualanBarang->map(function($item) {
            if (!empty($item->material_panjang) && !empty($item->material_lebar)) {
                $pArr = is_string($item->material_panjang) && is_array(json_decode($item->material_panjang, true)) ? json_decode($item->material_panjang, true) : [$item->material_panjang];
                $lArr = is_string($item->material_lebar) && is_array(json_decode($item->material_lebar, true)) ? json_decode($item->material_lebar, true) : [$item->material_lebar];
                $qArr = is_string($item->material_qty) && is_array(json_decode($item->material_qty, true)) ? json_decode($item->material_qty, true) : [$item->material_qty];
                $idArr = is_string($item->material_id) && is_array(json_decode($item->material_id, true)) ? json_decode($item->material_id, true) : [$item->material_id];

                $ukurans = [];
                foreach ($pArr as $idx => $p) {
                    $l = $lArr[$idx] ?? 0;
                    $q = $qArr[$idx] ?? 0;
                    $mId = $idArr[$idx] ?? null;

                    $satuanMaterial = '';
                    if ($mId) {
                        $mat = Material::find($mId);
                        $satuanMaterial = $mat ? ' ' . $mat->satuan : '';
                    }

                    if ($p && $l) {
                        $ukurans[] = $p . " x " . $l . " (" . $q . $satuanMaterial . ")";
                    }
                }
                return !empty($ukurans) ? implode(' | ', $ukurans) : null;
            }
            return null;
        })->filter()->unique()->implode(' | ');

        if (empty($pekerjaanRil)) { $pekerjaanRil = 'Pekerjaan Invoice #' . $penjualan->nomor_invoice; }
        if (empty($bahanRil)) { $bahanRil = '-'; }
        if (empty($ukuranRil)) { $ukuranRil = '-'; }

        $now = Carbon::now()->setTimezone('Asia/Jakarta');

        $spk = Spk::create([
            'pekerjaan'      => $pekerjaanRil,
            'tgl_mulai'      => $now->format('Y-m-d H:i'),
            'target_selesai' => $now->copy()->addDays(3)->format('Y-m-d H:i'),
            'nomor_invoice'  => $penjualan->nomor_invoice,
            'customer'       => $penjualan->customer,
            'jumlah'         => $penjualan->penjualanBarang->sum('qty') ?: 1,
            'satuan'         => 'Pcs',
            'jenis_bahan'    => $bahanRil,
            'ketebalan'      => '-',
            'ukuran'         => $ukuranRil,
            'lain'           => $request->lainnya,
            'express'        => $request->express,
            'timeline'       => 'On progress',
            'status_spk'     => 'Belum Selesai',
            'status_kerja'   => 'Belum Diproses',
            'gambar'         => 'noImage.jpg',
        ]);

        Alert::success('Berhasil', 'SPK untuk Invoice ' . $penjualan->nomor_invoice . ' berhasil dibuat!');
        return redirect()->back();
    }
    
    
    public function publicDownloadTokabe(Request $request, $id)
{
    // 1. CEK KEAMANAN LINK (Signature)
    if (! $request->hasValidSignature()) {
        abort(403, 'Maaf, Link download ini sudah kadaluarsa atau tidak valid.');
    }

    // 2. LOGIKA DATA
    $penjualan = PenjualanTokabe::find($id);
    
    if (!$penjualan) {
        abort(404);
    }

    $invoice = Invoice::where('id', $penjualan->invoice)->first();
    $namaInvoice = $invoice->nama_invoice;
    $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
    $admin_now = User::where('id', $penjualan->admin)->first();
    
    $barang = [];
    $hargaMod = [];
    $jumlahHarga = [];
    
    // --- LOGIKA BIAYA LAIN & TOTAL HARGA ---
    $nominalBiayaLain = 0;
    $persen = '';

    if ($penjualan->diskon || $penjualan->ppn || $penjualan->pph) {
        $pengali = $penjualan->diskon ?: ($penjualan->ppn ?: $penjualan->pph);
        $nominalBiayaLain = ($pengali / 100) * $penjualan->total_harga;
        $biayaLain = 'Rp.' . number_format($nominalBiayaLain, 0, ',', '.');
        $persen = number_format($pengali, 0, ',', '.') . '%';
    } else if ($penjualan->potongan && $penjualan->potongan != 0) {
        $nominalBiayaLain = $penjualan->potongan;
        $biayaLain = 'Rp.' . number_format($nominalBiayaLain, 0, ',', '.');
    } else {
        $biayaLain = 0;
    }

    // Update total harga dengan menambahkan biaya lain
    $totalPlusBiaya = $penjualan->total_harga + $nominalBiayaLain;
    $totHargaMod = number_format($totalPlusBiaya, 0, ',', '.');
    // ---------------------------------------

    if ($penjualan->status == 'Lunas' && !in_array($penjualan->jenis_pembayaran, ['Cash Lunas', 'Transfer Lunas'])) {
        $sisaBayarMod = 0 . '(Lunas)';
    } else {
        $sisaBayarMod = number_format($penjualan->sisa_pembayaran, 0, ',', '.');
    }

    $admin = $admin_now ? $admin_now->nama : 'Admin';
    $tanggal = $penjualan->tgl_penjualan;
    Carbon::setLocale('id');
    
    $tanggalString = Carbon::parse($tanggal)->toDateString();
    $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');

    if ($penjualan->no_rek == "BNI"){
        $norek = "BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999";
    } elseif ($penjualan->no_rek == "TKBBNI"){
        $norek = "BNI | A/N : PT. Total Karya Berkah | No. Rek : 3528289999";
    } else {
        $norek = "BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999";
    }
    
    $dp = ($penjualan->dp == null) ? 0 : number_format($penjualan->dp, 0, ',', '.');

    foreach ($penjualan_barang as $item) {
        $barangItem = Barang::where('id', $item->barang_id)->get();
        $barang[] = $barangItem;
        $hargaMod[] = number_format($item->harga, 0, ',', '.');
        $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
    }
    
    return view('pages.invoices.tokabe.public-download-tkb', compact(
        'invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 
        'jumlahHarga', 'norek', 'totHargaMod', 'dp', 'biayaLain', 
        'sisaBayarMod', 'formatTanggal', 'admin', 'persen', 'namaInvoice'
    ));
}
}
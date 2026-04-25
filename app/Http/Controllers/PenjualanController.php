<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Barang;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Penjualan;
use App\Models\User;
use App\Models\KategoriBarang;
use App\Models\PenjualanBarang;
use App\Models\Material;
use App\Models\Spk; // Ditambahkan agar bisa mengakses tabel SPK untuk penghapusan otomatis
use Carbon\Carbon;
use DateTimezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class PenjualanController extends Controller
{
    public $barang_id;

    public function loadInvoice()
    {
        $materials = \App\Models\Material::where('stok', '>', 0)->get();

        $no_invoice = $this->generateNoInvoice();
        $invoice = Invoice::all();
        $admin = User::where('role', 'Admin')->where('status', 'Aktif')->get();
        $order = Order::all();
        
        $tanggal = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $year = $tanggal->format('Y'); 
        $month = $tanggal->format('m'); 
        $day = $tanggal->format('d');
        $formatJam = $tanggal->format('H:i'); 
        $hariIni = sprintf('%s-%s-%s', $year, $month, $day);
        $kontak = Penjualan::select('customer', 'no_telepon', 'perusahaan')->get();
         
        $jenisBarang = Barang::where('is_active', 1)->get();
        return view('pages.invoices.invoice', compact('invoice', 'admin', 'order', 'no_invoice', 'jenisBarang', 'hariIni', 'formatJam','kontak','materials'));
    }

    public function generateNoInvoice()
    {
        // Generate the unique code
        $invoice = preg_replace('/\D/', '', Penjualan::pluck('nomor_invoice')->toArray());
        
        // Memastikan tidak error jika $invoice kosong (database awal)
        $urutan = empty($invoice) ? 1 : intval(max($invoice)) + 1;

        $uniqueCode = sprintf('%05d', $urutan);

        return $uniqueCode;
    }

    public function tambahPenjualan(Request $request)
    {
        // --- 1. VALIDASI STOK MATERIAL (MULTI MATERIAL) ---
        if (!empty($request->barang_id)) {
            foreach ($request->barang_id as $key => $value) {
                // Cek apakah data material dikirim dalam bentuk array (Multi-Material)
                if (isset($request->material_id[$key]) && is_array($request->material_id[$key])) {
                    foreach ($request->material_id[$key] as $matIndex => $matId) {
                        if (!empty($matId)) {
                            $material = Material::find($matId);
                            $qtyMat = $request->material_qty[$key][$matIndex] ?? 0;
                            
                            if ($material && $material->stok < $qtyMat) {
                                return response()->json([
                                    'status' => 'error', 
                                    'message' => "Stok material '{$material->jenis_material}' tidak mencukupi. Sisa: {$material->stok}"
                                ], 400); // Set response code ke 400 Bad Request
                            }
                        }
                    }
                }
            }
        }
        // --- END VALIDASI ---

        $invoice = Invoice::where('nama_invoice', $request->inv)->first();
        $nmr_inv = $request->kode . $request->kodeUnik;

        $status = ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') ? 'Lunas' : 'Belum Lunas';
        $tanggalPenjualan = $request->tgl_jual . ":" . $request->jam;
        $item = (!empty($request->barang_id)) ? count($request->barang_id) : 0;

        $tot_harga = intval(str_replace(',', '', $request->tot_harga));
        $tot_pem = ceil(floatval(str_replace(',', '', $request->tot_pem)));
        $sisa_pem = ceil(floatval(str_replace(',', '', $request->sisa_pem)));

        $dp = intval(str_replace(',', '', $request->dp));
        $potongan = intval(str_replace(',', '', $request->ptg));
        
        $norek = '';
        if (!$request->norek) {
            $norek = 'BNI';
        } else {
            $norek = $request->norek;
        }
        
        $existingPenjualan = Penjualan::where('nomor_invoice', $nmr_inv)
            ->where('customer', $request->pelanggan)
            ->first();

        if ($existingPenjualan) {
            // Data sudah ada, lakukan pembaruan
            $existingPenjualan->tgl_penjualan = $tanggalPenjualan;
            $existingPenjualan->perusahaan = $request->perusahaan;
            $existingPenjualan->no_telepon = $request->tlp;
            $existingPenjualan->admin = $request->adm;
            $existingPenjualan->order_by = $request->order;
            $existingPenjualan->nama_sales = $request->sales;
            $existingPenjualan->tgl_selesai = $request->tgl_selesai;
            $existingPenjualan->jumlah_item = $item;
            $existingPenjualan->dp = $dp;
            $existingPenjualan->potongan = $potongan;
            $existingPenjualan->jenis_pembayaran = $request->jns_pem;
            $existingPenjualan->no_rek = $norek;
            $existingPenjualan->total_harga = $tot_harga;
            $existingPenjualan->status = $status;
            $existingPenjualan->diskon = $request->dskn;
            $existingPenjualan->ppn = $request->ppn;
            $existingPenjualan->total_pembayaran = $tot_pem;
            $existingPenjualan->sisa_pembayaran = $sisa_pem;

            $existingPenjualan->save();

            foreach ($request->barang_id as $key => $value) {
                $barang = Barang::find($value);
                if ($barang != null || $request->deskripsi_item[$key] != null || $request->qty[$key] != null || $request->satuan[$key] != null || $request->hrg[$key] != null || $request->jlh_hrg[$key] != null) {
                    $existingPenjualanBarang = PenjualanBarang::where('penjualan_id', $existingPenjualan->id)
                        ->where('barang_id', $barang->id)
                        ->first();

                    // Bersihkan array material dari elemen kosong untuk Mencegah Array to String Conversion
                    $matIds = isset($request->material_id[$key]) && is_array($request->material_id[$key]) ? array_filter($request->material_id[$key]) : [];
                    $matQtys = isset($request->material_qty[$key]) && is_array($request->material_qty[$key]) ? array_filter($request->material_qty[$key], 'strlen') : [];
                    $matPanjangs = isset($request->material_panjang[$key]) && is_array($request->material_panjang[$key]) ? array_filter($request->material_panjang[$key], 'strlen') : [];
                    $matLebars = isset($request->material_lebar[$key]) && is_array($request->material_lebar[$key]) ? array_filter($request->material_lebar[$key], 'strlen') : [];

                    if ($existingPenjualanBarang) {
                        // Jika sudah ada, perbarui data barang tersebut
                        $existingPenjualanBarang->deskripsi_item = $request->deskripsi_item[$key];
                        $existingPenjualanBarang->qty = $request->qty[$key];
                        $existingPenjualanBarang->satuan = $request->satuan[$key];
                        $existingPenjualanBarang->hargaBarang = intval(str_replace(',', '', $request->hrg[$key]));
                        $existingPenjualanBarang->jumlah_harga = intval(str_replace(',', '', $request->jlh_hrg[$key]));
                        
                        // Update data material menggunakan JSON
                        $existingPenjualanBarang->material_id = !empty($matIds) ? json_encode(array_values($matIds)) : null;
                        $existingPenjualanBarang->material_qty = !empty($matQtys) ? json_encode(array_values($matQtys)) : null;
                        $existingPenjualanBarang->material_panjang = !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null;
                        $existingPenjualanBarang->material_lebar = !empty($matLebars) ? json_encode(array_values($matLebars)) : null;

                        $existingPenjualanBarang->save();
                    } else {
                        // Jika belum ada, buat data baru untuk penjualan_barang
                        $penjualan_brg = [
                            'barang_id' => $barang->id,
                            'penjualan_id' => $existingPenjualan->id,
                            'deskripsi_item' => $request->deskripsi_item[$key],
                            'qty' => $request->qty[$key],
                            'satuan' => $request->satuan[$key],
                            'hargaBarang' => intval(str_replace(',', '', $request->hrg[$key])),
                            'jumlah_harga' => intval(str_replace(',', '', $request->jlh_hrg[$key])),
                            
                            // Simpan sebagai JSON
                            'material_id' => !empty($matIds) ? json_encode(array_values($matIds)) : null,
                            'material_qty' => !empty($matQtys) ? json_encode(array_values($matQtys)) : null,
                            'material_panjang' => !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null,
                            'material_lebar' => !empty($matLebars) ? json_encode(array_values($matLebars)) : null,
                        ];
                        PenjualanBarang::create($penjualan_brg);
                    }

                    if (!$existingPenjualanBarang) {
                        Barang::where('id', $barang->id)->update([
                            'stok' => $barang->stok - $request->qty[$key]
                        ]);

                        // Potong stok multi-material
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
            }
        } else {
            // Data belum ada, buat data baru
            $penjualan = Penjualan::create([
                'invoice' => $invoice->id,
                'nomor_invoice' => $nmr_inv,
                'tgl_penjualan' => $tanggalPenjualan,
                'customer' => $request->pelanggan,
                'perusahaan' => $request->perusahaan,
                'no_telepon' => $request->tlp,
                'admin' => $request->adm,
                'order_by' => $request->order,
                'nama_sales' => $request->sales,
                'tgl_selesai' => $request->tgl_selesai,
                'jumlah_item' => $item,
                'dp' => $dp,
                'potongan' => $potongan,
                'jenis_pembayaran' => $request->jns_pem,
                'no_rek' => $norek,
                'total_harga' => $tot_harga,
                'status' => $status,
                'diskon' => $request->dskn,
                'ppn' => $request->ppn,
                'total_pembayaran' => $tot_pem,
                'sisa_pembayaran' => $sisa_pem,
            ]);

            foreach ($request->barang_id as $key => $value) {
                $barang = Barang::find($value);
                if ($barang != null || $request->deskripsi_item[$key] != null || $request->qty[$key] || $request->satuan[$key] || $request->hrg[$key] || $request->jlh_hrg[$key]) {
                    
                    // Bersihkan array material dari elemen kosong
                    $matIds = isset($request->material_id[$key]) && is_array($request->material_id[$key]) ? array_filter($request->material_id[$key]) : [];
                    $matQtys = isset($request->material_qty[$key]) && is_array($request->material_qty[$key]) ? array_filter($request->material_qty[$key], 'strlen') : [];
                    $matPanjangs = isset($request->material_panjang[$key]) && is_array($request->material_panjang[$key]) ? array_filter($request->material_panjang[$key], 'strlen') : [];
                    $matLebars = isset($request->material_lebar[$key]) && is_array($request->material_lebar[$key]) ? array_filter($request->material_lebar[$key], 'strlen') : [];

                    $penjualan_brg = [
                        'barang_id' => $barang->id,
                        'penjualan_id' => $penjualan->id,
                        'deskripsi_item' => $request->deskripsi_item[$key],
                        'qty' => $request->qty[$key],
                        'satuan' => $request->satuan[$key],
                        'hargaBarang' => intval(str_replace(',', '', $request->hrg[$key])),
                        'jumlah_harga' => intval(str_replace(',', '', $request->jlh_hrg[$key])),
                        
                        // Simpan sebagai JSON
                        'material_id' => !empty($matIds) ? json_encode(array_values($matIds)) : null,
                        'material_qty' => !empty($matQtys) ? json_encode(array_values($matQtys)) : null,
                        'material_panjang' => !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null,
                        'material_lebar' => !empty($matLebars) ? json_encode(array_values($matLebars)) : null,
                    ];
                    PenjualanBarang::create($penjualan_brg);

                    // Update stok barang
                    Barang::where('id', $barang->id)->update([
                        'stok' => $barang->stok - $request->qty[$key]
                    ]);

                    // Update stok multi-material
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
        }

        return response()->json(['status' => 'success', 'message' => 'Data Sudah Berhasil Disimpan']);
    }

    public function getBarangByOption($selectedOption)
    {
        $kataTerakhir = explode(' ', $selectedOption);
        $kataTerakhir = end($kataTerakhir);
        $jenisBarang = collect();

        if ($kataTerakhir != 'Berkah' && $kataTerakhir != 'Pajak') {
            $jenisBarang = Barang::join('kategori_barang', 'barang.kategori_id', '=', 'kategori_barang.id')
                ->select('barang.jenis_barang')
                ->where('kategori_barang.nama_kategori', $kataTerakhir)
                ->pluck('jenis_barang');
        } else {
            $jenisBarang = Barang::pluck('jenis_barang');
        }

        return response()->json($jenisBarang);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        if (!$penjualan) {
            Alert::error('Data tidak ditemukan');
            return redirect('list-invoice');
        }

        // --- 1. PROSES HAPUS SPK TERKAIT ---
        // Mencari data SPK berdasarkan nomor_invoice
        $spk = Spk::where('nomor_invoice', $penjualan->nomor_invoice)->first();
        if ($spk) {
            // Hapus file gambar SPK jika ada dan bukan gambar default
            if ($spk->gambar && $spk->gambar != 'noImage.jpg') {
                $imagePath = public_path('images/spk/') . $spk->gambar;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            // Hapus data SPK dari database
            $spk->delete();
        }
        // -----------------------------------
        
        $pjBarang = PenjualanBarang::where('penjualan_id', $penjualan->id)->get();

        // Mengembalikan stok barang dan material yang terkait
        foreach ($pjBarang as $barang) {
            $barangObj = Barang::find($barang->barang_id);
            if ($barangObj) {
                $barangObj->stok += $barang->qty;
                $barangObj->save();
            }
            
            // Mengembalikan stok multi-material
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
        return redirect('list-invoice');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Budget;
// use App\Models\KategoriBarang;
use App\Models\Pembelian;
use App\Models\PembelianBarang;
use App\Models\Vendor;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class PembelianController extends Controller
{
    function index($id)
    {
        $pembelianVendors = Pembelian::where('id_vendor', $id);
        $pembelianVendor = Pembelian::where('id_vendor', $id);
        $vendor = Vendor::find($id);

        if (empty($pembelianVendors)) {
            Alert::error('Data tidak ada');
            return redirect(route('vendor.list'));
        }
        Carbon::setLocale('id');
        $yearNow = Carbon::now()->year;
        $monthNow = Carbon::now()->month;

        $jumlahOutStndAll = number_format(($pembelianVendors->sum('sisa') / 1000000), 2);

        $jumlahOutStndYear = number_format(($pembelianVendors->whereYear('tanggal', $yearNow)->sum('sisa') / 1000000), 2);

        $jumlahOutStndMonth = number_format(($pembelianVendors->whereMonth('tanggal', $monthNow)->whereYear('tanggal', $yearNow)->sum('sisa') / 1000000), 2);
        $pembelianAll = $pembelianVendor->count();
        // dd($jumlahOutStndMonth);
        $pembelianYear = $pembelianVendor->whereYear('tanggal', $yearNow)->count();

        $pembelianMonth = $pembelianVendor->whereYear('tanggal', $yearNow)->whereMonth('tanggal', $monthNow)->count();
        $pembelians = Pembelian::where('id_vendor', $id)->orderByDesc('created_at')->get();
        foreach ($pembelians as $pembelian) {
            $pembelian->formattedHarga = number_format($pembelian->jumlah_harga);
            $pembelian->formattedSisa = number_format($pembelian->sisa);
            $pembelian->formatted_tgl = Carbon::parse($pembelian->tanggal)->translatedFormat('d M Y');
            $pembelian->formatted_tgl_jto = Carbon::parse($pembelian->tgl_jto)->translatedFormat('d M Y');
        }



        return view('pages.pembelian.daftar-pembelian', compact('pembelians', 'jumlahOutStndAll', 'jumlahOutStndYear', 'jumlahOutStndMonth', 'pembelianAll', 'pembelianYear', 'pembelianMonth', 'vendor'));
    }

    function addPembelian($id)
    {
        $uniqueToken = uniqid();
        $anggaran = Budget::select('id', 'nama_budget', 'anggaran')->get();
        $vendors = Vendor::find($id);
        // dd($anggaran);
        return view('pages.pembelian.tambah-pembelian', compact('anggaran', 'vendors', 'uniqueToken'));
    }

     function store(Request $request)
    {
        $token = $request->token;
        $existToken = Pembelian::where('token', $token)->first();
        if ($existToken) {
            Alert::error('Data Sudah diSubmit');
            return redirect(route('pembelian.list', ['uid' => $request->vendor]));
        }

        $terbayar = intval(str_replace(',', '', $request->terbayar));
        $sisa = intval(str_replace(',', '', $request->sisa));
        $jumlahTot = intval(str_replace(',', '', $request->jumlahTotal));
        if ($request->status === 'Lunas') {
            $terbayar = $jumlahTot;
            $sisa = 0;
        }
        $pembelian = Pembelian::create([
            'id' => Uuid::uuid4()->toString(),
            'anggaran' => $request->anggaran,
            'id_vendor' => $request->vendor,
            'nomor_inv' => $request->no_inv,
            'tanggal' => $request->tgl,
            'tgl_jto' => $request->tgl_jto,
            'status' => $request->status,
            'terbayar' => $terbayar,
            'sisa' => $sisa,
            'jumlah_harga' => $jumlahTot,
            'token' => $token,
        ]);
        $budget = Budget::find($request->anggaran);
        Budget::where('id', $request->anggaran)->update([
            'anggaran' => $budget->anggaran - $terbayar
        ]);
        $vendor = Vendor::find($request->vendor);
        Vendor::where('id', $request->vendor)->update([
            'pembelian_terbayar' => $vendor->pembelian_terbayar + $terbayar,
            'pembelian_sisa' => $vendor->pembelian_sisa + $sisa,
            'total_pembelian' => $vendor->total_pembelian + $jumlahTot,
        ]);
        $errors = [];
        foreach ($request->deskripsi as $key => $deskripsi) {

            if (empty($deskripsi) || empty($request->qty[$key]) ||empty($request->satuan[$key]) || empty($request->harga[$key])) {
                Alert::error('error', 'Barang Harus diisi');
                return redirect(route('pembelian.add'))->withInput();
            } else {
                $harga_barang = intval(str_replace(',', '', $request->harga[$key]));
                $qty = intval($request->qty[$key]);
                $satuan = $request->satuan[$key];
                $total = $harga_barang * $qty;

                // $total = $request->jumlah[$key];
                PembelianBarang::create([
                    'id' => Uuid::uuid4()->toString(),
                    'pembelian_id' => $pembelian->id,
                    'deskripsi' => $deskripsi,
                    'satuan' => $request->satuan[$key],
                    'harga_barang' => $harga_barang,
                    'qty' => $qty,
                    'total' => $total,
                ]);
            }
        }
        if (count($errors) > 0) {
            // Ada kesalahan, tampilkan pesan kesalahan dan kembali ke halaman pembelian.add
            Alert::error('error', $errors);
            return redirect(route('pembelian.add'))->withInput();
        }

        // Jika tidak ada kesalahan, tampilkan pesan sukses dan arahkan ke halaman pembelian.list
        Alert::success('Pembelian Berhasil ditambahkan');
        return redirect(route('pembelian.list', ['uid' => $request->vendor]));
    }
    
    function edit($id)
    {
        $pembelian = Pembelian::find($id);
        $vendor = Vendor::where('id', $pembelian->id_vendor)->select('nama_vendor')->first();
        $anggaran = Budget::all();
        $anggaranNow = $anggaran->where('id', $pembelian->anggaran)->first();
        $pembelianBrg = PembelianBarang::where('pembelian_id', $id)->get();
        // $barang = [];
        // foreach ($pembelianBrg as $item) {
        //     $barangItem = $item;
        //     $barang[] = $barangItem;
        // }
        

        return view('pages.pembelian.edit-pembelian', compact('pembelian', 'anggaran', 'pembelianBrg', 'anggaranNow', 'vendor'));
    }

    function update(Request $request, $id)
    {
        $pembelian = Pembelian::find($id);
        $terbayar = intval(str_replace(',', '', $request->terbayar));
        $sisa = intval(str_replace(',', '', $request->sisa));
        $jumlahTot = intval(str_replace(',', '', $request->jumlahTotal));
        if ($request->status === 'Lunas') {
            $terbayar = $jumlahTot;
            $sisa = 0;
        }
        if ($pembelian->anggaran !== $request->anggaran) {
            $budget = Budget::find($pembelian->anggaran);
            Budget::where('id', $pembelian->anggaran)->update([
                'anggaran' => $budget->anggaran + $pembelian->terbayar
            ]);
        }

        $selisih_bayar = $terbayar - $pembelian->terbayar;
        $selisih_sisa = $sisa - $pembelian->sisa;
        $selisih_total = $jumlahTot - $pembelian->jumlah_harga;
        
        $pembelian->update([
            'anggaran' => $request->anggaran,
            'id_vendor' => $pembelian->id_vendor,
            'nomor_inv' => $request->no_inv,
            'tanggal' => $request->tgl,
            'tgl_jto' => $request->tgl_jto,
            'status' => $request->status,
            'terbayar' => $terbayar,
            'sisa' => $sisa,
            'jumlah_harga' => $jumlahTot,
        ]);
        PembelianBarang::where('pembelian_id', $id)->delete();

        $budgets = Budget::find($request->anggaran);
        Budget::where('id', $request->anggaran)->update([
            'anggaran' => $budgets->anggaran - $terbayar
        ]);

        $vendor = Vendor::find($pembelian->id_vendor);
        
        Vendor::where('id', $pembelian->id_vendor)->update([
            'pembelian_terbayar' => $vendor->pembelian_terbayar + $selisih_bayar,
            'pembelian_sisa' => $vendor->pembelian_sisa + $selisih_sisa,
            'total_pembelian' => $vendor->total_pembelian + $selisih_total,
        ]);


        foreach ($request->deskripsi as $key => $deskripsi) {
            if (empty($deskripsi) || empty($request->qty[$key]) || empty($request->satuan[$key]) || empty($request->harga[$key])) {
                Alert::error('error', 'Barang Harus diisi');
                return redirect()->back()->withInput();
            } else {
                $harga_barang = intval(str_replace(',', '', $request->harga[$key]));
                $qty = intval($request->qty[$key]);
                $satuan = $request->satuan[$key];
                $total = $harga_barang * $qty;

                // $total = $request->jumlah[$key];
                PembelianBarang::create([
                    'id' => Uuid::uuid4()->toString(),
                    'pembelian_id' => $pembelian->id,
                    'deskripsi' => $deskripsi,
                    'satuan' => $request->satuan[$key],
                    'harga_barang' => $harga_barang,
                    'qty' => $qty,
                    'total' => $total,
                ]);
            }
        }
        Alert::success('Berhasil', 'Data Pembelian Berhasil diupdate');
        return redirect(route('pembelian.list', ['uid' => $pembelian->id_vendor]));
    }

    function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        PembelianBarang::where('pembelian_id', $id)->delete();
        $budget = Budget::find($pembelian->anggaran);
        Budget::where('id', $pembelian->anggaran)->update([
            'anggaran' => $budget->anggaran + $pembelian->terbayar
        ]);
        $vendor = Vendor::find($pembelian->id_vendor);
        Vendor::where('id', $pembelian->id_vendor)->update([
            'pembelian_terbayar' => $vendor->pembelian_terbayar - $pembelian->terbayar,
            'pembelian_sisa' => $vendor->pembelian_sisa - $pembelian->sisa,
            'total_pembelian' => $vendor->total_pembelian - $pembelian->jumlah_harga,
        ]);
        $pembelian->delete();
        Alert::success('Pembelian Berhasil dihapus');
        return redirect(route('pembelian.list', ['uid' => $pembelian->id_vendor]));
    }

    function lunas($uid)
    {
        $pembelian = Pembelian::find($uid);
        if (!$pembelian) {
            Alert::error('Data pembelian tidak ditemuan');
        } else {
            $pembelian->status = 'Lunas';
            $budget = Budget::find($pembelian->anggaran);
            Budget::where('id', $pembelian->anggaran)->update([
                'anggaran' => $budget->anggaran - $pembelian->sisa
            ]);
            Pembelian::where('id', $pembelian->id)->update([
                'terbayar' => $pembelian->terbayar + $pembelian->sisa
            ]);
            $vendor = Vendor::find($pembelian->id_vendor);
            Vendor::where('id', $pembelian->id_vendor)->update([
                'pembelian_sisa' => $vendor->pembelian_sisa - $pembelian->sisa,
                'pembelian_terbayar' => $vendor->pembelian_terbayar + $pembelian->sisa,
            ]);
            $pembelian->sisa = 0;
            $pembelian->save();
            Alert::success('Pembelian Sudah Lunas');
            return redirect()->back();
        }
    }
}

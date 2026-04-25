<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangSpb;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Perusahaan;
use App\Models\Spb;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class SpbController extends Controller
{
    public function indexSPB()
    {
        $spb = Spb::orderByDesc('created_at')->get();
        // $spb = Spb::with('perusahaan_spb')
        // ->where('nama_spb', $id)
        // ->orderByDesc('created_at')
        // ->get();
        $jumlahspb = Spb::count();

        $jumlahSelesai = Spb::where('status', 'Sudah diantar')->count();
        $jumlahBelumSelesai = Spb::where('status', 'Belum diantar')->count();


        Carbon::setLocale('id');

        $formatTanggal = $spb->map(function ($item) {
            return $item->created_at->isoFormat('DD MMMM YYYY');
        });

        return view('pages.SPB.daftar-spb', compact('spb', 'jumlahspb', 'jumlahSelesai', 'jumlahBelumSelesai', 'formatTanggal'));
    }
    public function tambahSPB()
    {
        $namaPerusahaan = Perusahaan::all();
        $customer = Penjualan::pluck('customer');
        $perusahaan = Penjualan::pluck('perusahaan');
        $barang = KategoriBarang::all();
        return view('pages.SPB.tambah-spb', compact('customer', 'perusahaan', 'barang','namaPerusahaan'));
    }

    public function storeSPB(Request $request)
    {

        $item = (!empty($request->barang_id)) ? count($request->barang_id) : 0;
        $spb = Spb::create([
            'nama_spb' => $request->namaSpb,
            'customer' => $request->customer,
            'perusahaan' => $request->perusahaan,
            'nomor_telepon' => $request->no_telp,
            'jumlah_item' => $item,
            'status' => 'Belum diantar',
        ]);

        $request->validate([
            'barang_id' => 'required|array',
            'barang_id.*' => 'required',
        ]);

        if (empty($request->barang_id)) {
            Alert::error('error', 'SPB gagal ditambahkan');
            return redirect('tambah-spb');
            // return redirect('tambah-spb')->with('error', 'SPB gagal ditambahkan');
        }

        foreach ($request->barang_id as $key => $value) {
            $kategori_brg = KategoriBarang::find($value);
            $keterangan = (!empty($request->keterangan[$key])) ? $request->keterangan[$key] : '-------------------';




            $brg_spb = [
                'barang_id' => $kategori_brg->id,
                'spb' => $spb->id,
                'deskripsi' => $request->deskripsi_item[$key],
                'satuan' => $request->satuan[$key],
                'qty' => $request->qty[$key],
                'keterangan' => $keterangan,
            ];
            BarangSpb::create($brg_spb);
        }

        Alert::success('Berhasil', 'SPB berhasil ditambahkan');
        return redirect('daftar-spb')->with('success', 'SPB Berhasil Ditambahkan');
    }


    public function cetakSPB($id)
    {
        $spb = Spb::where('id', $id)->first();
        $brg_spb = BarangSpb::where('spb', $id)->get();
        $tanggal = $spb->created_at;
        $barangs = [];
        Carbon::setLocale('id');
        // $deskripsi = [];
        // $satuan = [];
        // $qty = [];
        // $keterangan = [];

        $tanggalString = Carbon::parse($tanggal)->toDateString();

        // Format tanggal yang diinginkan dengan bulan dalam bahasa Indonesia
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');

        foreach ($brg_spb as $barang) {
            $kategoriBrg = KategoriBarang::where('id', $barang->barang_id)->get();
            $barangs[] = $kategoriBrg;
        }
        return view('pages.SPB.cetak', compact('spb', 'brg_spb', 'barangs', 'formatTanggal'));
    }

    public function editSPB($id)
    {
        $namaPerusahaan = Perusahaan::all();
        $spb = Spb::where('id', $id)->first();
        $customer = Penjualan::pluck('customer');
        $perusahaan = Penjualan::pluck('perusahaan');
        $kategori_brg = KategoriBarang::all();
        $brg_spb = BarangSpb::where('spb', $id)->get();
        $barangs = [];

        foreach ($brg_spb as $barang) {
            $kategoriItem = KategoriBarang::where('id', $barang->barang_id)->get();
            $barangs[] = $kategoriItem;
        }
        // dd($brg_spb);
        return view('pages.SPB.edit-spb', compact('spb', 'customer', 'perusahaan', 'barangs', 'kategori_brg', 'brg_spb','namaPerusahaan'));
    }

    public function updateSPB(Request $request, $id)
    {
        $spb = Spb::find($id);
        $item = (!empty($request->barang_id)) ? count($request->barang_id) : 0;

        $spb->nama_spb = $request->namaSpb;
        $spb->customer = $request->customer;
        $spb->perusahaan = $request->perusahaan;
        $spb->nomor_telepon = $request->no_telp;
        $spb->jumlah_item = $item;
        $spb->save();
        BarangSpb::where('spb', $id)->delete();


        $request->validate([
            'barang_id' => 'required|array',
            'barang_id.*' => 'required',
        ]);

        if (empty($request->barang_id)) {
            Alert::error('error', 'SPB gagal diupdate');
            return redirect('edit-spb');
            // return redirect('tambah-spb')->with('error', 'SPB gagal ditambahkan');
        }

        foreach ($request->barang_id as $key => $value) {
            $barangs = new BarangSpb();
            $keterangan = (!empty($request->keterangan[$key])) ? $request->keterangan[$key] : '-------------------';


            $barangs->barang_id = $value;
            $barangs->spb = $spb->id;
            $barangs->deskripsi = $request->deskripsi_item[$key];
            $barangs->satuan = $request->satuan[$key];
            $barangs->qty = $request->qty[$key];
            $barangs->keterangan = $keterangan;
            $barangs->save();
        }

        Alert::success('Berhasil', 'SPB berhasil diperbaharui');
        return redirect('daftar-spb');
    }

    public function destroySPB($id)
    {
        Spb::where('id', $id)->delete();
        $barang_spb = BarangSpb::where('spb', $id);

        $barang_spb->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus');
        return redirect('daftar-spb');
    }

    public function ubahStatus($id)
    {
        $spb = Spb::find($id);

        if (!$spb) {
            Alert::error('Gagal, SPB tidak ditemukan');
            return redirect()->back();
        } else {

            $spb->status = 'Sudah diantar';
            $spb->save();
            Alert::success('Berhasil, Barang sudah diantar');
            return redirect()->back();
        }
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianBarang;
use App\Models\Vendor;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class VendorController extends Controller
{
    function index()
    {
        $vendors = Vendor::orderByDesc('nama_vendor')->get();
        $nama_vendor = [];
        $total_sisa = [];
        $OutstndLunas = number_format((Pembelian::sum('terbayar') / 1000000), 2);
        $OutstndBlmLunas=number_format((Pembelian::sum('sisa') / 1000000), 2);
        $totalOutstnd=number_format((Pembelian::sum('jumlah_harga') / 1000000), 2);
        
      
        foreach ($vendors as $vendor) {
            $vendor->formattedTotal = number_format($vendor->total_pembelian, 0, ',', ',');
            $vendor->formattedTerbayar = number_format($vendor->pembelian_terbayar, 0, ',', ',');
            $vendor->formattedSisa = number_format($vendor->pembelian_sisa, 0, ',', ',');
            $nama_vendor[] = $vendor->nama_vendor;
            $total_sisa[] = number_format($vendor->pembelian_sisa, 0, ',',',');
        }

        return view('pages.vendorPgs.daftar-vendor', compact('vendors', 'nama_vendor', 'total_sisa', 'OutstndLunas','OutstndBlmLunas','totalOutstnd'));
    }

    function add()
    {
        return view('pages.vendorPgs.tambah-vendor');
    }

    function store(Request $request)
    {

        Vendor::create([
            'nama_vendor' => $request->nama_vendor,
        ]);
        Alert::success('Berhasil', 'Vendor Ditambahkan');
        return redirect(route('vendor.list'));
    }

    function edit($id)
    {
        $vendor = Vendor::find($id);
        return view('pages.vendorPgs.edit-vendor', compact('vendor'));
    }

    function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        $vendor->update([
            'nama_vendor' => $request->vendor,
            'total_pembelian' => $vendor->total_pembelian,
            'pembelian_sisa' => $vendor->pembelian_sisa,
            'pembelian_terbayar' => $vendor->pembelian_terbayar,
        ]);
        Alert::success('Berhasil', 'Nama Vendor Berhasil Diubah');
        return redirect(route('vendor.list'));
    }

    function destroy($id)
    {
        $vendor = Vendor::find($id);
        $pembelianDB = Pembelian::where('id_vendor', $id)->first();
        // dd($pembelianDB->id);
        if ($pembelianDB) {
            $pembelian = new PembelianController();
            $pembelian->destroy($pembelianDB->id);
            $vendor->delete();
            Alert::success('Vendor Berhasil dihapus');
            return redirect(route('vendor.list'));
        } else {
            $vendor->delete();
            Alert::success('Vendor Berhasil dihapus');
            return redirect(route('vendor.list'));
        }
    }
}

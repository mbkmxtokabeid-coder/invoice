<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\DB;


class kategoryController extends Controller
{
    public function daftarKategori(){   
        $invoices = DB::table('invoice')
        ->join('perusahaan', 'invoice.perusahaan_id', '=', 'perusahaan.id')
        ->select('perusahaan.nama_perusahaan', 'invoice.*')
        ->get();

        return view('pages.kategori.daftar-kategori', compact('invoices'));
    }

    public function tambahKategori(){   
        $perusahaans = Perusahaan::all();
        return view('pages.kategori.tambah-kategori', compact('perusahaans'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_invoice' => 'required',
            'kode_invoice' => 'required',
            'perusahaan_id' => 'required',
        ]);

        Invoice::create([
            'nama_invoice' => $request->nama_invoice,
            'kode_invoice' => $request->kode_invoice,
            'perusahaan_id' => $request->perusahaan_id,
        ]);
        
        return redirect('/daftar-kategori')->with('success', 'Berhasil menambahkan kategori!');

    }

    public function deleteKategori($id){
        $kategori = Invoice::find($id);
		$kategori->delete();

		return redirect('/daftar-kategori')->with('delete', 'Berhasil menghapus kategorinya!');
    }

    public function editKategori($id){
        $kategori = Invoice::find($id);
        $perusahaans = Perusahaan::all();

        return view('pages.kategori.edit-kategori', compact('kategori', 'perusahaans'));
    }

    public function updateKategori(Request $request, $id){
        $kategori = Invoice::find($id);
        $kategori->update([
            'nama_invoice' => $request->nama_invoice,
            'kode_invoice' => $request->kode_invoice,
            'perusahaan_id' => $request->perusahaan_id,
        ]);
        
        return redirect('/daftar-kategori')->with('update', 'Berhasil meng-update kategori!');
    }
}

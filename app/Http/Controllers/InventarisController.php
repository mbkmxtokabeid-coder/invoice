<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;




class InventarisController extends Controller
{
  public function index()
  {
    $inventaris = Inventaris::orderByDesc('created_at')
            ->select('id', 'jenis_inventaris', 'kode_inventaris', 'stok', 'tgl_beli', 'created_at', 'updated_at')
            ->get()
            ->map(function ($item) {
                $item->stok = max(0, $item->stok);
                $item->updated_at = Carbon::parse($item->updated_at)->addHours(7)->format('d F Y H:i:s');
                return $item;
            });
        foreach ($inventaris as $inv) {
           
            $inv->formattedTglBeli = Carbon::parse($inv->tgl_beli)->format('d M Y');

        }
        


        $inventaris = $inventaris->sortBy(function ($inventaris) {
            return $inventaris->jenis_material;
        });

        
        // dd($kategori_brg);
     
      return view('pages.inventaris.daftar-inventaris',[
      'inventaris'=>$inventaris,]);
        
  }

  public function create()
    {
       
        return view('pages.inventaris.tambah-inventaris');
    }

  public function store(Request $request)
    {

        
        $stok = $request->stok;
        $noComaStok = str_replace(',', '', $stok);
        Inventaris::create([
            'kode_inventaris' => $request->kode_inventaris,
            'jenis_inventaris' => $request->jenis_inventaris,
            'stok' => $noComaStok,
            'tgl_beli'=>$request->tgl_beli,
        ]);
        Alert::success('Inventaris Berhasil ditambahkan');
        return redirect('listInventaris');
    }

    public function destroy($id)
    {
        $inventaris = Inventaris::find($id);
        $inventaris->delete();
        Alert::success('Barang berhasil dihapus');
        return redirect('listInventaris');
    }
    
    
    function edit($id)
    {
        $inventaris = Inventaris::find($id);
       
        // dd($kategori);
        if ($inventaris->stok < 0) {
            $inventaris->stok = 0;
        }
        return view('pages.inventaris.update-stokInventaris', compact('inventaris'));
    }

    function update(Request $request, $id)
    {
        $inventaris = Inventaris::find($id);
        if ($request->stok < 0) {
            Alert::error('Stok tidak boleh dibawah 0');
            return redirect()->back();
        }
        $stok = intval(str_replace(',', '', $request->stok));
        $inventaris->jenis_inventaris = $request->jenis;
        $inventaris->stok = $stok;
        $inventaris->tgl_beli = $request->tgl_beli;
        $inventaris->update();

        Alert::success('Stok Berhasil di Update');
        return redirect('listInventaris');
    }




}

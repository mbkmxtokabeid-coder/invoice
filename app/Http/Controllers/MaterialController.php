<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index()
    {
        // Menambahkan 'satuan' ke dalam select
        $material = Material::orderByDesc('created_at')
            ->select('id', 'jenis_material', 'kode_material', 'stok', 'satuan', 'harga_modal', 'harga_jual', 'created_at', 'updated_at')
            ->get()
            ->map(function ($item) {
                $item->stok = max(0, $item->stok);
                $item->updated_at = Carbon::parse($item->updated_at)->addHours(7)->format('d F Y H:i:s');
                return $item;
            });

        foreach ($material as $mtr) {
            $mtr->formattedModal = number_format(intval($mtr->harga_modal), 0, ',', ',');
            $mtr->formattedJual = number_format(intval($mtr->harga_jual), 0, ',', ',');
        }

        $material = $material->sortBy(function ($material) {
            return $material->nama_material;
        });

        // Kembali ke view awal
        return view('pages.material.daftar-material', [
            'material' => $material,
        ]);
    }

    public function create()
    {
        // Kembali ke view awal
        return view('pages.material.tambah-material');
    }

    public function store(Request $request)
    {
        $hargaJual = $request->harga_jual;
        $hargaModal = $request->harga_modal;
        $stok = $request->stok;
        
        $noComaJual = str_replace(',', '', $hargaJual);
        $noComaModal = str_replace(',', '', $hargaModal);
        $noComaStok = str_replace(',', '', $stok);

        Material::create([
            'kode_material' => $request->kode_material,
            'jenis_material' => $request->jenis_material,
            'stok' => $noComaStok,
            'satuan' => $request->satuan, // Logika satuan tetap ada
            'harga_modal' => $noComaModal,
            'harga_jual' => $noComaJual,
        ]);

        Alert::success('Material Berhasil ditambahkan');
        return redirect('listMaterial');
    }

    public function destroy($id)
    {
        $material = Material::find($id);
        $material->delete();
        Alert::success('Barang berhasil dihapus');
        return redirect('listMaterial');
    }

    public function edit($id)
    {
        $material = Material::find($id);

        if ($material->stok < 0) {
            $material->stok = 0;
        }
        
        // Kembali ke view awal
        return view('pages.material.update-stokMaterial', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::find($id);
        
        if ($request->stok < 0) {
            Alert::error('Stok tidak boleh dibawah 0');
            return redirect()->back();
        }

        $stok = intval(str_replace(',', '', $request->stok));
        $hargaModal = intval(str_replace(',', '', $request->harga_modal));
        $hargaJual = intval(str_replace(',', '', $request->harga_jual));

        $material->jenis_material = $request->jenis;
        $material->stok = $stok;
        $material->satuan = $request->satuan; // Logika update satuan tetap ada
        $material->harga_modal = $hargaModal;
        $material->harga_jual = $hargaJual;
        
        $material->update();

        Alert::success('Stok Berhasil di Update');
        return redirect('listMaterial');
    }
}
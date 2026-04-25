<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class BarangController extends Controller
{
    public function index()
    {
        // Menambahkan filter is_active = 1
        $barang = Barang::where('is_active', 1)
            ->select('id', 'jenis_barang', 'kode_barang', 'kategori_id', 'stok', 'harga_modal', 'harga_jual', 'created_at', 'updated_at')
            ->get()
            ->map(function ($item) {
                $item->stok = max(0, $item->stok);
                $item->updated_at = Carbon::parse($item->updated_at)->addHours(7)->format('d F Y H:i:s');
                return $item;
            });

        foreach ($barang as $brg) {
            $brg->formattedModal = number_format(intval($brg->harga_modal), 0, ',', ',');
            $brg->formattedJual = number_format(intval($brg->harga_jual), 0, ',', ',');
        }

        $kategori = Barang::join('kategori_barang', 'barang.kategori_id', '=', 'kategori_barang.id')
            ->pluck('kategori_barang.nama_kategori', 'barang.kategori_id');
        
        $kategori_brg = KategoriBarang::whereNotIn('id', [7, 5])->get();

        $barang = $barang->sortBy(function ($barang) {
            return $barang->kategori_item->nama_kategori ?? '';
        });

        return view('pages.barang.daftar-barang', [
            'barang' => $barang,
            'kategori' => $kategori,
            'kategoriBrg' => $kategori_brg,
        ]);
    }

    public function create()
    {
        $kategori = KategoriBarang::all();
        return view('pages.barang.tambah-barang', compact('kategori'));
    }

    public function store(Request $request)
    {
        $hargaJual = $request->harga_jual;
        $hargaModal = $request->harga_modal;
        $noComaJual = str_replace(',', '', $hargaJual);
        $noComaModal = str_replace(',', '', $hargaModal);
        $noComaStok = str_replace(',', '', $request->stok);

        Barang::create([
            'kategori_id' => $request->kategori_id,
            'jenis_barang' => $request->jenis_barang,
            'kode_barang' => $request->kode_barang,
            'stok' => $noComaStok,
            'harga_modal' => $noComaModal,
            'harga_jual' => $noComaJual,
            'is_active' => 1, // Default aktif saat dibuat
        ]);

        Alert::success('Barang Berhasil ditambahkan');
        return redirect('listBarang');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        $barang->delete();
        Alert::success('Barang Berhasil dihapus');
        return redirect('listBarang');
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        $kategori = KategoriBarang::where('id', $barang->kategori_id)->value('nama_kategori');
        
        if ($barang->stok < 0) {
            $barang->stok = 0;
        }
        return view('pages.barang.update-stok', compact('barang', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);
        if ($request->stok < 0) {
            Alert::error('Stok tidak boleh dibawah 0');
            return redirect()->back();
        }

        $stok = intval(str_replace(',', '', $request->stok));
        $hargaModal = intval(str_replace(',', '', $request->harga_modal));
        $hargaJual = intval(str_replace(',', '', $request->harga_jual));

        $barang->jenis_barang = $request->jenis;
        $barang->stok = $stok;
        $barang->harga_modal = $hargaModal;
        $barang->harga_jual = $hargaJual;
        $barang->update();

        Alert::success('Stok Berhasil di Update');
        return redirect('listBarang');
    }
    
    public function daftarTinta()
    {
        // Menambahkan filter is_active = 1
        $tinta = Barang::where('is_active', 1)
            ->where('kategori_id', 7)
            ->orderByDesc('created_at')
            ->get();

        foreach ($tinta as $ink) {
            $ink->formatted_stock = number_format($ink->stok, 0, ',', ',');
        }
        return view('pages.barang.daftar-tinta', compact('tinta'));
    }

    public function addTinta()
    {
        return view('pages.barang.tambah-tinta');
    }

    public function storeTinta(Request $request)
    {
        $stok = $request->stok;
        $noComaStok = str_replace(',', '', $stok);

        Barang::create([
            'kategori_id' => $request->kategori,
            'jenis_barang' => $request->nama_tinta,
            'kode_barang' => $request->kode_tinta,
            'stok' => $noComaStok,
            'harga_modal' => $request->harga_modal,
            'harga_jual' => $request->harga_jual,
            'tgl_masuk' => Carbon::createFromFormat('d/m/Y', $request->tgl_masuk)->format('Y-m-d'),
            'is_active' => 1,
        ]);
        Alert::success('Tinta Berhasil ditambahkan');
        return redirect(route('stokTinta'));
    }

    public function editStokTinta($id)
    {
        $tinta = Barang::find($id);
        if ($tinta->stok < 0) {
            $tinta->stok = 0;
        }
        $format_tgl = Carbon::createFromFormat('Y-m-d H:i:s', $tinta->tgl_masuk)->format('d/m/Y');
        return view('pages.barang.edit-tinta', compact('tinta', 'format_tgl'));
    }

    public function updateTinta(Request $request, $id)
    {
        $tinta = Barang::find($id);
        if ($request->stok < 0) {
            Alert::error('Stok tidak boleh di bawah 0');
        }
        $stok = $request->stok;
        $noComaStok = str_replace(',', '', $stok);

        $tinta->update([
            'kategori_id' => $request->kategori,
            'jenis_barang' => $request->nama_tinta,
            'kode_barang' => $request->kode_tinta,
            'stok' => $noComaStok,
            'harga_modal' => $request->harga_modal,
            'harga_jual' => $request->harga_jual,
            'tgl_masuk' => Carbon::createFromFormat('d/m/Y', $request->tgl_masuk)->format('Y-m-d'),
        ]);
        Alert::success('Tinta Berhasil diUpdate');
        return redirect(route('stokTinta'));
    }

    public function destroyTinta($id)
    {
        $barang = Barang::find($id);
        $barang->delete();
        Alert::success('Tinta berhasil dihapus');
        return redirect(route('stokTinta'));
    }
    
    public function dataBarang($kategori_id)
    {
        // Menambahkan filter is_active = 1
        $totalPotensiPenjualan = Barang::where('is_active', 1)
            ->where('kategori_id', $kategori_id)
            ->selectRaw('SUM(CASE WHEN stok < 0 THEN 0 ELSE stok END * harga_jual) as total_potensi_penjualan')
            ->selectRaw('SUM(CASE WHEN stok < 0 THEN 0 ELSE stok END) as total_stok')
            ->first();

        if ($totalPotensiPenjualan) {
            $totalPotensiPenjualan->total_potensi_penjualan /= 1000000;
        }
        return response()->json($totalPotensiPenjualan);
    }

    public function potensiProfit($kategori_id)
    {
        // Menambahkan filter is_active = 1
        $totalPotensiProfit = Barang::where('is_active', 1)
            ->where('kategori_id', $kategori_id)
            ->selectRaw('SUM(CASE WHEN stok < 0 THEN 0 ELSE stok END * (harga_jual - harga_modal)) as total_profit')
            ->first();

        if ($totalPotensiProfit) {
            $totalPotensiProfit->total_profit /= 1000000;
        }
        return response()->json($totalPotensiProfit);
    }
}
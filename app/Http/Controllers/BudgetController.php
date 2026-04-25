<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetController extends Controller
{
    function index()
    {
        $budgets = Budget::orderByDesc('updated_at')->get();
        foreach ($budgets as $budget) {
            $budget->formattedAnggaran = number_format($budget->anggaran, 0, ',', ',');
        }
        return view('pages.anggaran.daftar-anggaran', compact('budgets'));
    }

    function tambah()
    {
        $kategori = KategoriBarang::pluck('nama_kategori', 'id');
        return view('pages.anggaran.tambah-anggaran', compact('kategori'));
    }
    function store(Request $request)
    {
        $anggaran = intval(str_replace(',', '', $request->anggaran));
        Budget::create([
            'nama_budget' => $request->nama_anggaran,
            'kategori_id' => $request->kategori,
            'anggaran' => $anggaran,
            'tanggal' => $request->tanggal,
        ]);
        Alert::success('Anggaran Berhasil Ditambah');
        return redirect(route('budget.list'));
    }

    function edit($id)
    {
        $budget = Budget::find($id);
        $kategori = KategoriBarang::pluck('nama_kategori', 'id');
        return view('pages.anggaran.edit-anggaran', compact('budget', 'kategori'));
    }
    function update(Request $request, $id)
    {
        $data = Budget::find($id);
        $anggaran = intval(str_replace(',', '', $request->anggaran));
        $data->update([
            'nama_budget' => $request->nama_anggaran,
            'kategori_id' => $request->kategori,
            'anggaran' => $anggaran,
            'tanggal' => $request->tanggal,
        ]);
        Alert::success('Anggaran Berhasil Diubah');
        return redirect(route('budget.list'));
    }
    function delete($id)
    {
        $budget = Budget::find($id);
        $budget->delete();
        Alert::success('Budget Berhasil dihapus');
        return redirect(route('budget.list'));
    }
}

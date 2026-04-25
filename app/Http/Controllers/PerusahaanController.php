<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
class PerusahaanController extends Controller
{
  function index()
  {
      $company = Perusahaan::orderByDesc('created_at')->get();
      return view('pages.perusahaan.daftar-perusahaan', compact('company'));
  }
  function tambah()
  {
     
      return view('pages.perusahaan.tambah-perusahaan');
  }
  

      public function store(Request $request)
      {
          // Validasi data masukan, jika perlu
        
        // dd($request);
        

      $perusahaan = new Perusahaan;
      $perusahaan->nama_perusahaan = $request->nama_perusahaan;
      $perusahaan->alamat_perusahaan = $request->alamat_perusahaan;
      $perusahaan->no_hp = $request->no_hp;


      if ($request->hasFile('logo')) {
          $file = $request->file('logo');
          $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

          // Simpan file ke storage/app/public/image_hero
          $file->storeAs('public/images/perusahaan', $fileName);

          // Simpan nama file di database
          $perusahaan->logo = $fileName;
      }
      else{
        $perusahaan->logo= 'default.png';
      }

      $perusahaan->save();

          // Tambahkan pesan sukses
          Alert::success('Perusahaan Berhasil Ditambah');
          return redirect()->route('perusahaan.list');
      }
      
      function delete($id)
      {
          $perusahaan = Perusahaan::find($id);
          $perusahaan->delete();
          Alert::success('Perusahaan Berhasil dihapus');
          return redirect(route('perusahaan.list'));
      }

      function edit($id)
    {
        $perusahaan = Perusahaan::find($id);
        return view('pages.perusahaan.edit-perusahaan', compact('perusahaan'));
    }
    function update(Request $request, $id)
    {
        $perusahaan = Perusahaan::find($id);


       

        $perusahaan->nama_perusahaan = $request->nama_perusahaan;
        $perusahaan->alamat_perusahaan = $request->alamat_perusahaan;
        $perusahaan->no_hp= $request->no_hp;

        // if ($request->hasFile('logo')) {
        //     $file = $request->file('logo');
        //     $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        //     // Hapus gambar lama jika ada
        //     if ($perusahaan->logo) {
        //         Storage::disk('public')->delete('images/perusahaan/' . $perusahaan->logo);
               
        //     }
        //     $file->storeAs('public/images/perusahaan/', $fileName);

        //     // Simpan nama file di database
        //     $perusahaan->logo = $fileName;
        
        // }
        // else{
        //     $perusahaan->logo= 'default.png';
        //   }
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            
            // Hapus gambar lama jika ada
            if ($perusahaan->logo && $perusahaan->logo !== 'default.png') {
                Storage::disk('public')->delete('images/perusahaan/' . $perusahaan->logo);
            }
            
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images/perusahaan/', $fileName);
        
            // Simpan nama file di database
            $perusahaan->logo = $fileName;
        } else {
            // Jika tidak ada file baru diunggah, periksa apakah gambar sebelumnya adalah gambar default
            // Jika bukan gambar default, hapus gambar lama
            if ($request->logo && $perusahaan->logo !== 'default.png') {
                Storage::disk('public')->delete('images/perusahaan/' . $perusahaan->logo);
            }
            
            $perusahaan->logo = 'default.png';
        }
        

        $perusahaan->save();
        Alert::success('Perusahaan Berhasil Diubah');
        return redirect()->route('perusahaan.list');
    }
    

}


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

          // Simpan ke semua lokasi target agar kompatibel dengan lokal maupun berbagai konfigurasi hosting
          $targetDirs = array_unique([
              public_path('images/perusahaan'),
              public_path('storage/images/perusahaan'),
              base_path('public/images/perusahaan'),
              base_path('public/storage/images/perusahaan'),
              storage_path('app/public/images/perusahaan'),
          ]);

          $primaryDir = array_shift($targetDirs);
          if (!file_exists($primaryDir)) {
              mkdir($primaryDir, 0755, true);
          }
          $file->move($primaryDir, $fileName);

          // Salin ke lokasi target lainnya
          foreach ($targetDirs as $dir) {
              if (!file_exists($dir)) {
                  @mkdir($dir, 0755, true);
              }
              @copy($primaryDir . '/' . $fileName, $dir . '/' . $fileName);
          }

          // Simpan nama file di database
          $perusahaan->logo = $fileName;
      } else {
          $perusahaan->logo = 'default.png';
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

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            // Simpan ke semua lokasi target agar kompatibel dengan lokal maupun berbagai konfigurasi hosting
            $targetDirs = array_unique([
                public_path('images/perusahaan'),
                public_path('storage/images/perusahaan'),
                base_path('public/images/perusahaan'),
                base_path('public/storage/images/perusahaan'),
                storage_path('app/public/images/perusahaan'),
            ]);

            // Hapus gambar lama di semua lokasi jika bukan default
            if ($perusahaan->logo && $perusahaan->logo !== 'default.png') {
                foreach ($targetDirs as $dir) {
                    $oldFile = $dir . '/' . $perusahaan->logo;
                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                    }
                }
            }

            $primaryDir = array_shift($targetDirs);
            if (!file_exists($primaryDir)) {
                mkdir($primaryDir, 0755, true);
            }
            $file->move($primaryDir, $fileName);

            // Salin ke lokasi target lainnya
            foreach ($targetDirs as $dir) {
                if (!file_exists($dir)) {
                    @mkdir($dir, 0755, true);
                }
                @copy($primaryDir . '/' . $fileName, $dir . '/' . $fileName);
            }

            // Simpan nama file di database
            $perusahaan->logo = $fileName;
        }

        $perusahaan->save();
        Alert::success('Perusahaan Berhasil Diubah');
        return redirect()->route('perusahaan.list');
    }
    

}


<?php

namespace App\Http\Controllers;

use App\Events\SPKCreatedProcessed;
use App\Models\Penjualan;
use App\Models\Spk;
use App\Models\PenjualanBarang;
use App\Models\Material;
use App\Models\Barang; // Ditambahkan untuk mengambil data nama barang
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTimeZone;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Notifications\NewSPK;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

class SpkController extends Controller
{
    public function indexSPK()
    {
        Carbon::setLocale('id');
        
        $dataPerMinggu = $this->spkChartPerWeek();
        $dataPerbulan = $this->spkChartPerMonth();

        $noInvMonth = $dataPerbulan['noInv'];
        $tlDesgnMonth = $dataPerbulan['tlDesign'];
        $tlProdMonth = $dataPerbulan['tlProd'];
        $tlMonth = $dataPerbulan['timeline'];

        $noInvWeek = $dataPerMinggu['noInv'];
        $tlDesgnWeek = $dataPerMinggu['tlDesign'];
        $tlProdWeek = $dataPerMinggu['tlProd'];
        $tlWeek = $dataPerMinggu['timeline'];
        
        $spk = Spk::orderByDesc('created_at')->get();
        $belum_selesai = Spk::where('status_spk', 'Belum Selesai')->count();
        $belum_proses = Spk::where('status_kerja', 'Belum Diproses')->count();

        foreach ($spk as $item) {
            if ($item->status_spk == 'Belum Selesai') {
                if ($item->status_kerja != 'Selesai') {
                    $tglSekarang = Carbon::now('Asia/Jakarta');
                    $tglSekarang->addHours(7);
                    $target = Carbon::parse($item->target_selesai);

                    $diff = $tglSekarang->diff($target);
                    $item->timeline = '';

                    if ($diff->days > 0) {
                        $item->timeline .= $diff->days . ' hari ';
                    }
                    if ($diff->h >= 0) {
                        $item->timeline .= $diff->h . ' jam ';
                    }
                    if ($diff->i > 0) {
                        $item->timeline .= $diff->i . ' menit';
                    }

                    if ($tglSekarang > $target) {
                        $item->timeline = 'Lewat ' . $item->timeline;
                        $item->color = 'red';
                    } elseif ($diff->days < 1 && $diff->h <= 1) {
                        $item->color = 'red';
                    } else {
                        if ($item->status_kerja == 'Proses Design' || $item->status_kerja == 'Proses Produksi') {
                            $item->color = 'purple';
                        } else {
                            $item->color = 'default';
                        }
                    }
                } else {
                    $item->color = 'green';
                }
            }
        }

        return view('pages.SPK.daftar-spk', compact('spk', 'belum_selesai', 'belum_proses','noInvMonth', 'tlDesgnMonth', 'tlProdMonth', 'tlMonth', 'noInvWeek', 'tlDesgnWeek', 'tlProdWeek', 'tlWeek'));
    }

    function prosesSPK($id)
    {
        Carbon::setLocale('id');
        $waktuNow = Carbon::now('Asia/Jakarta');
        $waktuNow->addHour(7);
        $spk = Spk::find($id);
        $tanggalMulai = Carbon::parse($spk->tgl_mulai);

        if (!$spk) {
            Alert::error('Gagal, SPK tidak ditemukan');
            return redirect()->back();
        } else {
            if ($spk->status_kerja == 'Belum Diproses') {
                $spk->status_kerja = 'Proses Design';
                $waktuNow->subHour(7);
                $spk->timeline_design = $waktuNow;
                $spk->save();
                Alert::success('SPK Didesign');
                return redirect()->back();
            } elseif ($spk->status_kerja == 'Proses Design') {
                $spk->status_kerja = 'Proses Produksi';
                $tgl_design = Carbon::parse($spk->timeline_design);
                $selisihWaktu = $tgl_design->diff($waktuNow);
                $hari = $selisihWaktu->days;
                $jam = $selisihWaktu->h;
                $menit = $selisihWaktu->i;
                $timeline = "Design Selesai ";
                if ($hari > 0) { $timeline .= $hari . " Hari, "; }
                if ($jam >= 0) { $timeline .= $jam . " Jam, "; }
                if ($menit > 0) { $timeline .= $menit . " Menit"; }
                $spk->timeline_design = $timeline;
                $waktuNow->subHour(7);
                $spk->timeline_produksi = $waktuNow;
                $spk->save();
                Alert::success('SPK diproduksi');
                return redirect()->back();
            } elseif ($spk->status_kerja == 'Proses Produksi') {
                $spk->status_kerja = 'Selesai';
                $tgl_produksi = Carbon::parse($spk->timeline_produksi);
                $selisihProduksi = $tgl_produksi->diff($waktuNow);
                $hariProduksi = $selisihProduksi->days;
                $jamProduksi = $selisihProduksi->h;
                $menitProduksi = $selisihProduksi->i;
                $timelineProd = "Produksi Selesai ";
                if ($hariProduksi > 0) { $timelineProd .= $hariProduksi . " Hari, "; }
                if ($jamProduksi >= 0) { $timelineProd .= $jamProduksi . " Jam, "; }
                if ($menitProduksi > 0) { $timelineProd .= $menitProduksi . " Menit"; }
                $spk->timeline_produksi = $timelineProd;
                $selisihWaktu = $tanggalMulai->diff($waktuNow);
                $hari = $selisihWaktu->days;
                $jam = $selisihWaktu->h;
                $menit = $selisihWaktu->i;
                $timeline = "Selesai ";
                if ($hari > 0) { $timeline .= $hari . " Hari, "; }
                if ($jam >= 0) { $timeline .= $jam . " Jam, "; }
                if ($menit > 0) { $timeline .= $menit . " Menit"; }
                $spk->timeline = $timeline;
                $spk->save();
                Alert::success('Barang Telah Selesai');
                return redirect()->back();
            }
        }
    }

    function statusSPK($id)
    {
        Carbon::setLocale('id');
        $waktuNow = Carbon::now('Asia/Jakarta');
        $waktuNow->addHour(7);
        $spk = Spk::find($id);
        $tanggalMulai = Carbon::parse($spk->tgl_mulai);

        if (!$spk) {
            Alert::error('Gagal, SPK tidak ditemukan');
            return redirect()->back();
        } else {
            if ($spk->status_kerja != 'Selesai') {
                $spk->status_spk = 'Selesai';
                $spk->status_kerja = 'Selesai';
                $selisihWaktu = $tanggalMulai->diff($waktuNow);
                $hari = $selisihWaktu->days;
                $jam = $selisihWaktu->h;
                $menit = $selisihWaktu->i;
                $timeline = "Selesai ";
                if ($hari > 0) { $timeline .= $hari . " Hari, "; }
                if ($jam >= 0) { $timeline .= $jam . " Jam, "; }
                if ($menit > 0) { $timeline .= $menit . " Menit"; }
                $spk->timeline = $timeline;
                $spk->save();
                Alert::success('Berhasil, SPK sudah Selesai');
                return redirect()->back();
            } else {
                $spk->status_spk = 'Selesai';
                $spk->save();
                Alert::success('Berhasil, SPK sudah Selesai');
                return redirect()->back();
            }
        }
    }

    public function addSPK()
    {
        $tanggal = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $today = $tanggal->format('d/m/Y');
        $invoice = Penjualan::pluck('nomor_invoice');
        $customer = Penjualan::pluck('customer');
        return view('pages.SPK.tambah-spk', compact('today', 'invoice', 'customer'));
    }

    public function storeSPK(Request $request)
    {
        $existingSpk = Spk::where('nomor_invoice', $request->invoice)->first();
        if ($existingSpk) {
            Alert::error('Gagal', 'SPK untuk Nomor Invoice ini sudah ada.');
            return redirect()->back();
        }

        $now = Carbon::now()->setTimezone('Asia/Jakarta');
        $jam = $now->format('H');
        $menit = $now->format('i');

        $alamat_image = 'noImage.jpg';
        if ($request->hasFile('contoh_design')) {
            $contohDesign = $request->file('contoh_design');
            $imageName = uniqid() . '.' . $contohDesign->extension();
            $contohDesign->move(public_path('images/spk'), $imageName);
            $alamat_image = $imageName;
        }

        $waktu_buat = $request->tgl_buat . ' ' . $jam . ':' . $menit;

        $spk = Spk::create([
            'pekerjaan' => $request->pekerjaan,
            'tgl_mulai' => Carbon::createFromFormat('d/m/Y H:i', $waktu_buat)->format('Y-m-d H:i'),
            'target_selesai' => Carbon::createFromFormat('d/m/Y H:i', $request->tgl_selesai)->format('Y-m-d H:i'),
            'nomor_invoice' => $request->invoice,
            'customer' => $request->customer,
            'jumlah' => intval(str_replace(',', '', $request->jumlah)),
            'satuan' => $request->satuan,
            'jenis_bahan' => $request->jenis_bahan,
            'ketebalan' => $request->ketebalan,
            'ukuran' => $request->ukuran,
            'lain' => $request->lain,
            'express' => $request->express,
            'timeline' => 'On progress',
            'status_spk' => 'Belum Selesai',
            'status_kerja' => 'Belum Diproses',
            'gambar' => $alamat_image,
        ]);

        event(new SPKCreatedProcessed($request->invoice));
        $produksi = User::where('role', 'Produksi')->get();
        Notification::send($produksi, new NewSPK($spk));
        
        $firebaseAuthToken = $this->getFirebaseAuthToken();
        foreach ($produksi as $prod) {
            if ($prod->device_token) {
                $this->pushNotif($prod->device_token, 'SPK Baru Dibuat', 'SPK dengan nomor invoice ' . $request->invoice . ' telah dibuat.', Storage::url('images/ikhtiarberkah.png'), 'https://ikhtiarberkah.com/invoice/lihat-spk/' . $spk->id, $firebaseAuthToken);
            }
        }
        
        Alert::success('Spk berhasil ditambahkan');
        return redirect('/daftar-spk');
    }

    public function storeDariInvoice(Request $request)
{
    $request->validate([
        'invoice_id' => 'required',
        'express'    => 'required'
    ]);

    // Mengambil data Penjualan beserta item penjualan barangnya
    $penjualan = Penjualan::with(['penjualanBarang'])->findOrFail($request->invoice_id);
    
    $existingSpk = Spk::where('nomor_invoice', $penjualan->nomor_invoice)->first();
    if ($existingSpk) {
        Alert::error('Gagal', 'SPK untuk Invoice ' . $penjualan->nomor_invoice . ' sudah dibuat sebelumnya.');
        return redirect()->back();
    }

    // 1. Ambil Nama Barang/Pekerjaan Secara Akurat dari Barang melalui barang_id
    $pekerjaanRil = $penjualan->penjualanBarang->map(function($item) {
        $barang = Barang::find($item->barang_id);
        $nama = $barang ? $barang->jenis_barang : 'Item Tidak Diketahui';
        return $item->deskripsi_item ? $nama . " (" . $item->deskripsi_item . ")" : $nama;
    })->unique()->filter()->implode(', ');

    // 2. Ambil Jenis Bahan dari Material (SANGAT AMAN: Mendukung Integer Biasa maupun format JSON Array)
    $bahanRil = $penjualan->penjualanBarang->map(function($item) {
        if (!empty($item->material_id)) {
            // Deteksi jika material_id tersimpan sebagai Array JSON (karena form Tokabe bisa multi-material)
            $matIds = is_string($item->material_id) && is_array(json_decode($item->material_id, true))
                      ? json_decode($item->material_id, true)
                      : [$item->material_id]; // Jadikan array meskipun cuma 1 ID

            $namaMaterials = [];
            foreach ($matIds as $mId) {
                if ($mId) {
                    $mat = Material::find($mId);
                    if ($mat) {
                        $namaMaterials[] = $mat->jenis_material;
                    }
                }
            }
            return !empty($namaMaterials) ? implode(', ', $namaMaterials) : null;
        }
        return null;
    })->filter()->unique()->implode(', ');

    // 3. Ambil Ukuran (SANGAT AMAN: Mendukung Integer Biasa maupun format JSON Array)
    $ukuranRil = $penjualan->penjualanBarang->map(function($item) {
        if (!empty($item->material_panjang) && !empty($item->material_lebar)) {
            // Deteksi format JSON jika multi material
            $pArr = is_string($item->material_panjang) && is_array(json_decode($item->material_panjang, true)) ? json_decode($item->material_panjang, true) : [$item->material_panjang];
            $lArr = is_string($item->material_lebar) && is_array(json_decode($item->material_lebar, true)) ? json_decode($item->material_lebar, true) : [$item->material_lebar];
            $qArr = is_string($item->material_qty) && is_array(json_decode($item->material_qty, true)) ? json_decode($item->material_qty, true) : [$item->material_qty];
            $idArr = is_string($item->material_id) && is_array(json_decode($item->material_id, true)) ? json_decode($item->material_id, true) : [$item->material_id];

            $ukurans = [];
            foreach ($pArr as $idx => $p) {
                $l = $lArr[$idx] ?? 0;
                $q = $qArr[$idx] ?? 0;
                $mId = $idArr[$idx] ?? null;

                $satuanMaterial = '';
                if ($mId) {
                    $mat = Material::find($mId);
                    $satuanMaterial = $mat ? ' ' . $mat->satuan : '';
                }

                if ($p && $l) {
                    $ukurans[] = $p . " x " . $l . " (" . $q . $satuanMaterial . ")";
                }
            }
            return !empty($ukurans) ? implode(' | ', $ukurans) : null;
        }
        return null;
    })->filter()->unique()->implode(' | ');

    // Fallback jika data kosong
    if (empty($pekerjaanRil)) { $pekerjaanRil = 'Pekerjaan Invoice #' . $penjualan->nomor_invoice; }
    if (empty($bahanRil)) { $bahanRil = '-'; }
    if (empty($ukuranRil)) { $ukuranRil = '-'; }

    $now = Carbon::now()->setTimezone('Asia/Jakarta');

    $spk = Spk::create([
        'pekerjaan'      => $pekerjaanRil,
        'tgl_mulai'      => $now->format('Y-m-d H:i'),
        'target_selesai' => $now->copy()->addDays(3)->format('Y-m-d H:i'),
        'nomor_invoice'  => $penjualan->nomor_invoice,
        'customer'       => $penjualan->customer,
        'jumlah'         => $penjualan->penjualanBarang->sum('qty') ?: 1,
        'satuan'         => 'Pcs',
        'jenis_bahan'    => $bahanRil,
        'ketebalan'      => '-',
        'ukuran'         => $ukuranRil,
        'lain'           => $request->lainnya,
        'express'        => $request->express,
        'timeline'       => 'On progress',
        'status_spk'     => 'Belum Selesai',
        'status_kerja'   => 'Belum Diproses',
        'gambar'         => 'noImage.jpg',
    ]);

    event(new SPKCreatedProcessed($penjualan->nomor_invoice));
    $produksi = User::where('role', 'Produksi')->get();
    Notification::send($produksi, new NewSPK($spk));
    
    $firebaseAuthToken = $this->getFirebaseAuthToken();
    foreach ($produksi as $prod) {
        if ($prod->device_token) {
            $this->pushNotif($prod->device_token, 'SPK Baru Dibuat', 'SPK nomor ' . $penjualan->nomor_invoice . ' telah dibuat dari Invoice.', Storage::url('images/ikhtiarberkah.png'), 'https://ikhtiarberkah.com/invoice/lihat-spk/' . $spk->id, $firebaseAuthToken);
        }
    }

    Alert::success('Berhasil', 'SPK untuk Invoice ' . $penjualan->nomor_invoice . ' berhasil dibuat!');
    return redirect()->back();
}

    public function editSPK($id)
    {
        $spk = Spk::find($id);
        $today = Carbon::parse($spk->tgl_mulai)->format('d/m/Y');
        $waktu = Carbon::parse($spk->tgl_mulai)->format('H:i');
        $tgl_selesai = Carbon::parse($spk->target_selesai)->format('d/m/Y H:i');
        $invoice = Penjualan::pluck('nomor_invoice');
        $customer = Penjualan::pluck('customer');
        return view('pages.SPK.edit-spk', compact('spk', 'today', 'invoice', 'customer', 'tgl_selesai', 'waktu'));
    }

    public function updateSPK(Request $request, $id)
    {
        $spk = Spk::find($id);
        if ($request->hasFile('contoh_design')) {
            $contohDesign = $request->file('contoh_design');
            $imageName = uniqid() . '.' . $contohDesign->extension();
            $contohDesign->move(public_path('images/spk'), $imageName);
            if ($spk->gambar && $spk->gambar != 'noImage.jpg') {
                $oldPath = public_path('images/spk') . '/' . $spk->gambar;
                if (file_exists($oldPath)) { unlink($oldPath); }
            }
            $spk->gambar = $imageName;
        }

        $waktu_buat = $request->tgl_buat . ' ' . $request->jam_buat;
        $spk->pekerjaan = $request->pekerjaan;
        $spk->tgl_mulai = Carbon::createFromFormat('d/m/Y H:i', $waktu_buat)->format('Y-m-d H:i');
        $spk->target_selesai = Carbon::createFromFormat('d/m/Y H:i', $request->tgl_selesai)->format('Y-m-d H:i');
        $spk->nomor_invoice = $request->invoice;
        $spk->customer = $request->customer;
        $spk->jumlah = intval(str_replace(',', '', $request->jumlah));
        $spk->satuan = $request->satuan;
        $spk->jenis_bahan = $request->jenis_bahan;
        $spk->ketebalan = $request->ketebalan;
        $spk->ukuran = $request->ukuran;
        $spk->lain = $request->lain;
        $spk->express = $request->express;
        $spk->save();

        Alert::success('Spk berhasil diupdate');
        return redirect('/daftar-spk');
    }

    public function destroySPK($id)
    {
        $spk = Spk::find($id);
        if ($spk->gambar && $spk->gambar != 'noImage.jpg') {
            $oldPath = public_path('images/spk') . '/' . $spk->gambar;
            if (file_exists($oldPath)) { unlink($oldPath); }
        }
        $spk->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus');
        return redirect('daftar-spk');
    }

    public function markAsRead()
    {
        Auth::user()->unreadNotification->markAsRead();
        return redirect()->back();
    }

    public function cetakSPK($id)
    {
        $spk = Spk::find($id);
        Carbon::setLocale('id');
        $tanggal = Carbon::parse($spk->tgl_mulai)->format('d/m/Y');
        $target = Carbon::parse($spk->target_selesai)->isoFormat('DD MMMM YYYY');
        return view('pages.SPK.cetak', compact('spk', 'tanggal', 'target'));
    }

    public function lihatSPK($id)
    {
        $spk = Spk::find($id);
        Carbon::setLocale('id');
        $tanggal = Carbon::parse($spk->tgl_mulai)->format('d/m/Y');
        $target = Carbon::parse($spk->target_selesai)->isoFormat('DD MMMM YYYY');
        return view('pages.SPK.lihat', compact('spk', 'tanggal', 'target'));
    }
    
    // Fungsi bantuan untuk Firebase
    private function getFirebaseAuthToken()
    {
        require_once base_path('vendor/autoload.php');
        $credential = new ServiceAccountCredentials(
            "https://www.googleapis.com/auth/firebase.messaging",
            json_decode(file_get_contents(base_path("ntfk.json")), true)
        );
        $token = $credential->fetchAuthToken(HttpHandlerFactory::build());
        return $token['access_token'];
    }

    function pushNotif($tokens, $judul, $pesan, $urlGbr = null, $urlTjn = null, $accessToken = null)
    {
        if (!$accessToken) { $accessToken = $this->getFirebaseAuthToken(); }
        $ch = curl_init('https://fcm.googleapis.com/v1/projects/notifdev-b86ea/messages:send');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);
        $image = Storage::url('images/ikhtiarberkah.png');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "message" => [
                "token" => $tokens,
                "notification" => [
                    "title" => $judul,
                    "body" => $pesan,
                    "image" => $image
                ],
                "webpush" => [
                    "fcm_options" => ["link" => $urlTjn]
                ]
            ]
        ]));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'post');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    // Chart Methods (Per Month & Per Week)
    function spkChartPerMonth() {
        $datas = Spk::select('nomor_invoice', 'timeline_design', 'timeline_produksi', 'timeline')
            ->whereBetween('tgl_mulai', [now()->startOfMonth(), now()->endOfMonth()])->get();
        return $this->parseChartData($datas);
    }

    function spkChartPerWeek() {
        $datas = Spk::select('nomor_invoice', 'timeline_design', 'timeline_produksi', 'timeline')
            ->whereBetween('tgl_mulai', [now()->startOfWeek(), now()->endOfWeek()])->get();
        return $this->parseChartData($datas);
    }

    private function parseChartData($datas) {
        $inv = []; $design = []; $prod = []; $total = [];
        foreach ($datas as $data) {
            $inv[] = $data->nomor_invoice;
            $design[] = $this->parseHours($data->timeline_design);
            $prod[] = $this->parseHours($data->timeline_produksi);
            $total[] = $this->parseHours($data->timeline);
        }
        return ['noInv' => $inv, 'tlDesign' => $design, 'tlProd' => $prod, 'timeline' => $total];
    }

    private function parseHours($string) {
        if (!$string) return 0;
        $d = 0; $h = 0; $m = 0;
        if (preg_match('/(\d+)\s*Hari/', $string, $match)) $d = (int)$match[1];
        if (preg_match('/(\d+)\s*Jam/', $string, $match)) $h = (int)$match[1];
        if (preg_match('/(\d+)\s*Menit/', $string, $match)) $m = (int)$match[1];
        return ($d * 24) + $h + ($m / 60);
    }
}
@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" /> --}}

  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<style>
    /* Atur SweetAlert menjadi lebih besar dan muncul di tengah */
    .swal2-popup {
        width: 500px;
        background-color: rgba(0, 0, 0, 0.7); /* Atur latar belakang gelap dengan alpha (transparansi) 0.7 */
        color: white;
    }
    
    /* SOLUSI TERBAIK: Beri minimal tinggi pada pembungkus tabel SAJA agar dropdown 1 baris tidak terpotong */
    .table-responsive, .dataTables_scrollBody, .invoice-table-body {
        min-height: 350px; 
    }
    
    /* Memastikan dropdown z-index berada paling atas layar */
    .dropdown-menu {
        z-index: 1050 !important;
    }
</style>
@endsection
@extends('layout.template')
@section('content')
<div class="page-content">
  <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Invoice</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice</a></li>
                          <li class="breadcrumb-item active">Daftar Invoice</li>
                      </ol>
                  </div>

              </div>
          </div>
      </div>
      <!-- end page title -->

       <div class="row pb-4 gy-3">
          <div class="col-sm-4">
              <a href="/addInvoice" class="btn btn-primary addMembers-modal"><i class="las la-plus me-1"></i>Tambah Invoices</a>
          </div>

      </div>

          <div class="row">
            @if(Auth::user()->role === 'Pemilik')
    
          <div class="col-xl-3 col-md-6">
              <!-- card -->
              <div class="card">
                  <div class="card-body bg-primary">
                      <div class="d-flex align-items-center">
                          <div class="flex-grow-1">
                              <h4 class="fs-22 fw-semibold ff-secondary mb-2">Rp<span class="counter-value" data-target="{{$jumlahPerTahun}}">0</span>M</h4>
                              <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah Seluruh Non PO Invoice Tahun <script>document.write(new Date().getFullYear())</script>
                              </p>
                          </div>
                          <div class="avatar-sm flex-shrink-0">
                              <span class="avatar-title bg-light rounded-circle fs-3">
                                  {{-- <i class="las la-file-alt fs-24 text-primary"></i> --}}
                                  <i class="las la-check-square fs-24 text-primary"></i>
                              </span>
                          </div>
                      </div>
                      <div class="d-flex align-items-end justify-content-between mt-4">
                          <div>
                              <span class="badge bg-success me-1">{{$jlhInvoice}}</span> <span class="text-white">Invoice Dibuat Tahun <script>document.write(new Date().getFullYear())</script></span>
                          </div>
                      </div>
                  </div><!-- end card body -->
              </div><!-- end card -->
          </div><!-- end col -->
    
          <div class="col-xl-3 col-md-6">
              <div class="card">
                  <div class="card-body bg-primary">
                      <div class="d-flex align-items-center">
                          <div class="flex-grow-1">
                              <h4 class="fs-22 fw-semibold ff-secondary mb-2">Rp<span class="counter-value" data-target="{{$totalLunasThn}}">0</span>M</h4>
                              <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah Non PO Terbayar Tahun <script>document.write(new Date().getFullYear())</script></p>
    
                          </div>
                          <div class="avatar-sm flex-shrink-0">
                              <span class="avatar-title bg-light rounded-circle fs-3">
                                  <i class="las la-check-square fs-24 text-primary"></i>
                              </span>
                          </div>
                      </div>
                      <div class="d-flex align-items-end justify-content-between mt-4">
                          <div>
                              <span class="badge bg-success me-1">{{$lunas}}</span> <span class="text-white">Invoice Lunas Tahun <script>document.write(new Date().getFullYear())</script></span>
                          </div>
                      </div>
                  </div><!-- end card body -->
              </div><!-- end card -->
          </div><!-- end col -->
          @endif
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$jumlahBBPerTahun}}">0</span>M</h4>
                                <p class="text-uppercase fw-medium fs-14 text-white-50 mb-0">Jumlah Non PO Belum Terbayar Tahun <script>document.write(new Date().getFullYear())</script>
                                </p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-light rounded-circle fs-3">
                                    <i class="las la-clock fs-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-success me-1">{{$belumLunas}}</span> <span class="text-white">Invoice Non PO Belum Lunas</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
    
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$jumlahBBPerBulan}}">0</span>M</h4>
                                <p class="text-uppercase fw-medium fs-14 text-white-50 mb-0">Jumlah Non PO Belum Terbayar Bulan <script>
                                  var bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                                  var tanggal = new Date();
                                  var namaBulan = bulan[tanggal.getMonth()];
                                  document.write(namaBulan);
                              </script></p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-light rounded-circle fs-3">
                                    <i class="las la-clock fs-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-success me-1">{{$belumLunasMonth}}</span> <span class="text-white">Invoice Belum Lunas</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
          @if(Auth::user()->role === 'Pemilik')
    
            {{-- Batas Card PO --}}
            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$jumlahPO}}">0</span>M</h4>
                                <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah Seluruh <br> PO</p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded-circle fs-3">
                                    <i class="las la-money-check-alt fs-24 text-primary"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-primary me-1">{{$countAllInvPO}}</span> <span class="text-white">Invoice</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card">
                    <div class="card-body bg-primary">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$jumlahPOLunas}}">0</span>M</h4>
                                <p class="text-uppercase fw-medium fs-14 text-white mb-0"> Jumlah PO Lunas Tahun <br> <script>document.write(new Date().getFullYear())</script>
                                </p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded-circle fs-3">
                                    <i class="las la-money-bill-alt fs-24 text-primary"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-success me-1">{{$countAllSettledPO}}</span> <span class="text-white">Invoice</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card">
                    <div class="card-body bg-danger">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$jumlahPOBlmLunas}}">0</span>M</h4>
                                <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah PO Belum Lunas Tahun <script>document.write(new Date().getFullYear())</script>
                                </p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded-circle fs-3">
                                    <i class="las la-money-bill fs-24 text-primary"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-primary me-1">{{$countAllOutstandingPO}}</span> <span class="text-white">Invoice</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card">
                    <div class="card-body bg-danger">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$jumlahBBPerBulanPO}}">0</span>M</h4>
                                <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah PO Belum Lunas Bulan <script>
                                    var bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                                    var tanggal = new Date();
                                    var namaBulan = bulan[tanggal.getMonth()];
                                    document.write(namaBulan);
                                </script>
                                </p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded-circle fs-3">
                                    <i class="las la-money-bill fs-24 text-primary"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-primary me-1">{{$blmLunasPO}}</span> <span class="text-white">Invoice</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
    {{-- BATAS PO --}}
            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card">
                    <div class="card-body bg-success">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$jumlahBatal}}">0</span>M</h4>
                                <p class="text-uppercase fw-medium fs-14 text-white mb-0"> Invoice Dibatalkan
                                </p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded-circle fs-3">
                                    <i class="las la-times-circle fs-24 text-primary"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-primary me-1">{{$batal}}</span> <span class="text-white">Invoice Dibatalkan</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
    
            @endif
        </div>

      <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Invoice</h5>
                  </div>
                      <!-- KELAS invoice-table-body DITAMBAHKAN DI SINI -->
                      <div class="card-body invoice-table-body">
                          <table id="scroll-horizontal" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                              <thead>
                                  <tr class="text-muted text-uppercase">
                                      <th scope="col" class="text-center">No</th>
                                      <th scope="col">Invoice ID</th>
                                      <th scope="col">Nama Pelanggan & Perusahaan</th>
                                      <th scope="col" style="width: 13%;">No. Telepon</th>
                                      <th scope="col">Tanggal Penjualan</th>
                                      <th scope="col" style="width: 10%;">Status</th>
                                      <th scope="col" style="width: 10%;">Deskripsi</th>
                                      <th scope="col" style="width: 15%;">Bahan Material</th>
                                      <th scope="col">Alasan Batal</th>
                                       <th scope="col" style="width: 10%;">Approval</th>
                                      <th scope="col">Panjar</th>
                                      <th scope="col">Total</th>
                                      <th scope="col">Tipe Pembayaran</th>
                                      <th scope="col">Order By</th>
                                      <th scope="col">Admin</th>
                                      <th scope="col" style="width: 5%;">Aksi</th>
                                  </tr>
                              </thead>

                              <tbody>
                                @foreach ($penjualan as $inv)

                                  <tr>
                                    <td><div class="text-center">{{$loop->iteration}}</div></td>
                                      <td><p class="fw-medium mb-0">{{$inv->nomor_invoice}}</p></td>
                                      <td>{{$inv->customer. ' / '. $inv->perusahaan}}</td>
                                      <td>{{$inv->no_telepon}}</td>
                                      <td>{{$inv->formatted_tgl_penjualan}}
                                        @if ($inv->updated_at != $inv->created_at)
                                            <br> Last Update: {{ $inv->formatted_tgl_update }}
                                        @endif</td>
                                      <td>@if ($inv->status == 'Lunas')<span class="badge badge-soft-primary p-2">{{$inv->status}}</span>
                                          @elseif($inv->status == 'Belum Lunas')
                                      <span class="badge badge-soft-danger p-2">{{$inv->status}}</span>
                                      @else
                                      <span class="badge badge-soft-success p-2">{{$inv->status}}</span>
                                          @endif
                                      </td>
                                       <td>
                                          <ul class="list-unstyled mb-0">
                                              @foreach($inv->penjualanBarang as $pb)
                                                  <li class="mb-1">- {{ $pb->deskripsi_item }}</li>
                                              @endforeach
                                          </ul>
                                      </td>
                                       <td>
                                          @php $hasMaterial = false; @endphp
                                          @if($inv->penjualanBarang)
                                              <ul class="list-unstyled mb-0">
                                              @foreach($inv->penjualanBarang as $pb)
                                                  @if($pb->material_id)
                                                      @php 
                                                          $hasMaterial = true; 
                                                          // Mengambil data material berdasarkan ID yang tersimpan di PenjualanBarang
                                                          $mat = \App\Models\Material::find($pb->material_id);
                                                      @endphp
                                                      @if($mat)
                                                          <li class="mb-2" style="border-bottom: 1px dashed #e9ebec; padding-bottom: 4px;">
                                                              <span class="fw-semibold text-primary">{{ $mat->jenis_material }}</span><br>
                                                              <span class="text-muted" style="font-size: 0.8rem;">Dipakai: <b>{{ $pb->material_qty }} {{ $mat->satuan }}</b></span>
                                                              @if($pb->material_panjang && $pb->material_lebar)
                                                                  <br><span class="text-muted" style="font-size: 0.75rem;">(Ukuran: {{ $pb->material_panjang }} &times; {{ $pb->material_lebar }})</span>
                                                              @endif
                                                          </li>
                                                      @endif
                                                  @endif
                                              @endforeach
                                              </ul>
                                          @endif
                                          
                                          @if(!$hasMaterial)
                                              <span class="text-muted" style="font-size: 0.8rem;"><i>Tidak ada material yang dipakai</i></span>
                                          @endif
                                      </td>
                                      <td>{{$inv->alasan_batal}}</td>
                                      <td>@if ($inv->approval == 'Unlock')<span class="badge badge-soft-success p-2">{{$inv->approval}}</span>
                                          @elseif($inv->approval == 'Lock')
                                      <span class="badge badge-soft-danger p-2">{{$inv->approval}}</span>
                                      @else
                                      <span class="badge badge-soft-primary p-2">{{$inv->approval}}</span>
                                          @endif
                                      </td>
                                      <td>Rp.{{$inv->formatted_dp}}</td>
                                      <td>Rp.{{$inv->formatted_total_pembayaran}}</td>
                                    <td>
                                        @if ($inv->jenis_pembayaran == 'Cash Lunas' || $inv->jenis_pembayaran == 'Transfer Lunas')
                                            <span class="badge rounded-pill badge-soft-primary p-2">{{$inv->jenis_pembayaran}}</span>
                                        @else
                                            <span class="badge rounded-pill badge-soft-danger p-2">{{ $inv->jenis_pembayaran == 'Cash Belum Lunas' ? 'Cash Outstanding' : $inv->jenis_pembayaran }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $inv->order_by}}</td>
                                     <td>{{ $inv->user ? $inv->user->nama : 'Tidak Diketahui' }}</td> <!-- Menampilkan nama user -->
                                      <td>
                                      
                                          <div class="dropdown d-inline-block">
                                              <!-- PENAMBAHAN data-bs-boundary="window" DAN data-bs-popper-config AGAR DROPDOWN MUNCUL MELAYANG DI LUAR TABEL -->
                                              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="window" data-bs-popper-config='{"strategy":"fixed"}'>
                                                  <i class="las la-ellipsis-h align-middle fs-18"></i>
                                              </button>
                                              <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                                                  <li>
                                                      <a class="dropdown-item" href="/cetak/{{ $inv->id }}">
                                                          <i class="las la-print  fs-18 align-middle me-2 text-muted"></i>Cetak Invoice
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item" href="/view/download/{{ $inv->id }}" target="_blank">
                                                          <i class="las la-file-download fs-18 align-middle me-2 text-muted"></i>Download
                                                      </a>
                                                  </li>
    
    {{-- === BUTTON KIRIM WA CUSTOMER === --}}
    @php
        // 1. Format Nomor HP
        $no_hp_cust = $inv->no_telepon ?? ''; 
        $no_hp_cust = preg_replace('/[^0-9]/', '', $no_hp_cust);
        if(substr($no_hp_cust, 0, 1) == '0'){
            $no_hp_cust = '62' . substr($no_hp_cust, 1);
        }

        // 2. Generate Signed URL (Expired 7 Hari)
        $link_khusus_customer = URL::temporarySignedRoute(
            'invoice.public_download', 
            now()->addDays(7),
            ['id' => $inv->id]   
        );

        // 3. Susun Pesan Lengkap sesuai format baru
        $pesan_cust = "Invoice : " . $inv->nomor_invoice . "\n";
        $pesan_cust .= "Link Download Invoice (Berlaku 7 Hari):\n";
        $pesan_cust .= $link_khusus_customer . "\n\n";
        $pesan_cust .= "Total Rp " . number_format($inv->total_harga, 0, ',', '.') . "\n\n";

        // Cek Sisa Pembayaran (Jika > 0 berarti Belum Lunas)
        if($inv->sisa_pembayaran > 0) {
             $pesan_cust .= "Mohon transfer ke:\n";
             $pesan_cust .= "BSI | A/N : Yusni Kurniasih | No. Rek : 2845999999\n";
             $pesan_cust .= "**\n\n";
             $pesan_cust .= "Apabila sudah melakukan transaksi pembayaran mohon dikirim bukti transfernya";
        } else {
             $pesan_cust .= "Status : Lunas\n";
             $pesan_cust .= "**\n\n";
             $pesan_cust .= "Terima kasih,\n";
             $pesan_cust .= "ibeka.id";
        }
    @endphp
    <li>
         <a class="dropdown-item" href="https://wa.me/{{ $no_hp_cust }}?text={{ urlencode($pesan_cust) }}" target="_blank">
            <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Kirim Invoice (WA)
        </a>
    </li>

    {{-- === LOGIKA USER === --}}
    @php
        $role     = Auth::user()->role;
        $isOwner  = $role === 'Pemilik';
        $isAdmin  = $role === 'Admin';
        $waNumber = '628116029999'; // Ganti nomor WA Owner
        $waNumber2 = '628116179999';
        $waNumberLunas1 = '628116179999';
        $waNumberLunas2 = '628116029999';

        $msg = urlencode("Halo, saya ingin meminta approval untuk membuka akses invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman approval:\n" . url("/invoice-approval/{$inv->id}"));
        $msgLunas = urlencode("Halo, saya ingin Request perubahan status pelunasan untuk invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman ubah status pelunasan:\n" . url("/invoice-pelunasan/{$inv->id}"));

        $waLink = "https://wa.me/{$waNumber}?text={$msg}";
        $waLink2 = "https://wa.me/{$waNumber2}?text={$msg}";
        $waLinkLunas1 = "https://wa.me/{$waNumberLunas1}?text={$msgLunas}";
        $waLinkLunas2 = "https://wa.me/{$waNumberLunas2}?text={$msgLunas}";

    @endphp

    {{-- === APPROVAL (KHUSUS OWNER) === --}}
    @if($isOwner)
        @if($inv->approval === 'Unlock')
            <li>
                <a class="dropdown-item" href="/ubah-status-lock/{{ $inv->id }}">
                    <i class="las la-check-square fs-18 align-middle me-2 text-muted"></i>Lock
                </a>
            </li>
        @endif
    @endif

    {{-- === REQUEST APPROVAL (KHUSUS ADMIN) === --}}
    @if($isAdmin && $inv->approval !== 'Unlock')
        <li>
            <a class="dropdown-item text-warning fw-semibold" href="{{ $waLink }}" target="_blank">
                <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Request Unlock (Owner 1)
            </a>
        </li>
    @endif
    
     @if($isAdmin && $inv->approval !== 'Unlock')
        <li>
            <a class="dropdown-item text-warning fw-semibold" href="{{ $waLink2 }}" target="_blank">
                <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Request Unlock (Owner 2)
            </a>
        </li>
    @endif
    
    {{-- === BUTTON BUAT SPK === --}}
        <li>
            <a class="dropdown-item btn-buat-spk" href="#" data-id="{{ $inv->id }}" data-bs-toggle="modal" data-bs-target="#buatSpkModal">
                <i class="las la-clipboard-list fs-18 align-middle me-2 text-primary"></i>Buat SPK
            </a>
        </li>
    
    
    {{-- === REQUEST LUNAS (KHUSUS ADMIN) === --}}
    @if($isAdmin && $inv->status !== 'Lunas')
        <li>
            <a class="dropdown-item text-warning fw-semibold" href="{{ $waLinkLunas1 }}" target="_blank">
                <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Request Pelunasan (Owner 1)
            </a>
        </li>
        <li>
            <a class="dropdown-item text-warning fw-semibold" href="{{ $waLinkLunas2 }}" target="_blank">
                <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Request Pelunasan (Owner 2)
            </a>
        </li>
    @endif

    {{-- === Tombol lain: Pemilik selalu bisa, Admin hanya jika sudah approved === --}}
    @php($canManage = !$isAdmin || $inv->approval === 'Unlock')

    @if($canManage)
        <li>
            <a class="dropdown-item" href="/editInvoice/{{ $inv->id }}/edit">
                <i class="las la-pen fs-18 align-middle me-2 text-muted"></i>Edit
            </a>
        </li>

        <li>
            <a class="dropdown-item btn-update-log"
               href="#" data-id="{{ $inv->id }}"
               data-bs-toggle="modal" data-bs-target="#updateLogModal">
                <i class="las la-paste fs-18 align-middle me-2 text-muted"></i>Update Log
            </a>
        </li>


        @if($inv->status !== 'Batal')
           {{-- Menjadi tombol yang memicu fungsi JavaScript --}}
<button type="button" class="dropdown-item" 
        onclick="confirmBatal('{{ $inv->id }}', '{{ $inv->nomor_invoice }}')">
    <i class="las la-folder-minus fs-18 align-middle me-2 text-muted"></i>Invoice Batal
</button>

{{-- Form tersembunyi untuk mengirim data --}}
<form id="form-batal-{{ $inv->id }}" action="/invoice/ubah-status-batal/{{ $inv->id }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="alasan_batal" id="input-alasan-{{ $inv->id }}">
</form>
        @endif
    @endif

    {{-- === DELETE – tampil untuk semua === --}}
    <li class="dropdown-divider"></li>
    <li>
        <form action="{{ route('delete.invoice', $inv->id) }}" method="POST" id="deleteInv{{ $inv->id }}">
            @csrf @method('delete')
            <button class="dropdown-item" type="button"
                    onclick="showConfirmation('{{ $inv->nomor_invoice }}','{{ $inv->id }}')">
                <i class="las la-trash-alt fs-18 align-middle me-2 text-muted"></i>Delete
            </button>
        </form>
    </li>
</ul>

                                          </div>
                                      </td>
                                  </tr>
                                  @endforeach

                              </tbody><!-- end tbody -->
                          </table><!-- end table -->
                      </div><!-- end table responsive -->
                  </div>
              </div>
          </div>
    
    <!-- Scrollable Modal Update Log -->
<div class="modal fade" id="updateLogModal" tabindex="-1" aria-labelledby="updateLogModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateLogModalLabel">Riwayat Update Invoice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <ul id="updateLogList" class="list-group" >
          <!-- Data akan diisi oleh AJAX -->
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Buat SPK -->
<div class="modal fade" id="buatSpkModal" tabindex="-1" aria-labelledby="buatSpkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buatSpkModalLabel">Buat SPK Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="{{ route('spk.store_dari_invoice') }}" method="POST">
        @csrf
        <div class="modal-body">
          <input type="hidden" name="invoice_id" id="spk_invoice_id">
          
          <div class="mb-3">
            <label for="lainnya" class="form-label">Keterangan / Lainnya</label>
            <textarea class="form-control" id="lainnya" name="lainnya" rows="3" placeholder="Masukkan keterangan tambahan jika ada..."></textarea>
          </div>
          
          <div class="mb-3">
            <label for="express" class="form-label">Express</label>
            <select class="form-select" id="express" name="express" required>
              <option value="">-- Pilih Status Pengerjaan --</option>
              <option value="Y">Ya</option>
              <option value="N">Tidak</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Buat SPK Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>

  </div>
  <!-- container-fluid -->
</div>

@endsection

@section('plugins')
{{-- Datatable --}}

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
{{-- <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> --}}

<script>
    const loggedInUser = "{{ auth()->user()->nama }}";
</script>

<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('js/pages/datatables.init.js')}}"></script>
<script src="{{asset('js/halaman/daftar-invoice.js')}}"></script>
<script>
    function showConfirmation(invoice, invId) {
        // console.log('invoice:', invoice)
        // console.log('id:', invId)
        Swal.fire({
            html: '<lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#eee966,secondary:#c71f16" state="hover-2" style="width:200px;height:200px"></lord-icon>' +
                  '<p>Apakah Anda ingin menghapus invoice ' + invoice + '?</p>',
            showCancelButton: true,
            confirmButtonColor: '#c71f16',
            cancelButtonColor: '#eee966',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus',
            customClass: {
                popup: 'swal2-popup' // Menggunakan gaya khusus untuk SweetAlert
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form with the specified ID
                document.getElementById('deleteInv' + invId).submit();
            }
        });
    }
    function confirmBatal(id, nomorInvoice) {
    Swal.fire({
        title: 'Pembatalan Invoice',
        text: "Masukkan alasan pembatalan untuk invoice " + nomorInvoice,
        input: 'textarea',
        inputPlaceholder: 'Tulis alasan batal di sini...',
        inputAttributes: {
            'aria-label': 'Tulis alasan batal di sini'
        },
        showCancelButton: true,
        confirmButtonText: 'Proses Batal',
        cancelButtonText: 'Kembali',
        confirmButtonColor: '#d33',
        inputValidator: (value) => {
            if (!value) {
                return 'Alasan batal harus diisi!'
            }
        },
        customClass: {
            popup: 'swal2-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Masukkan alasan ke input hidden
            document.getElementById('input-alasan-' + id).value = result.value;
            // Submit form
            document.getElementById('form-batal-' + id).submit();
        }
    });
}
</script>
<script>
    $(document).on('click', '.btn-update-log', function () {
        var invoiceId = $(this).data('id');
        $('#updateLogList').html('<li class="list-group-item">Loading...</li>');

        $.ajax({
            url: '/invoice/invoice/update-log/' + invoiceId,
            method: 'GET',
            success: function (data) {
                let html = '';

                // Tampilkan first created selalu di awal
                html += `<li class="list-group-item ps-3">${data.created_at} - <strong>First Created</strong></li>`;

                // Tambahkan update log jika ada
                if (data.logs.length > 0) {
                    data.logs.forEach(function (log) {
                        html += `<li class="list-group-item ps-3">${log.tanggal} - Invoice diperbarui oleh ${loggedInUser}</li>`;
                    });
                }

                $('#updateLogList').html(html);
            },
            error: function () {
                $('#updateLogList').html('<li class="list-group-item">Gagal memuat data log.</li>');
            }
        });
    });

    // Skrip untuk menangkap ID invoice ke dalam modal SPK
    $(document).on('click', '.btn-buat-spk', function () {
        var invoiceId = $(this).data('id');
        $('#spk_invoice_id').val(invoiceId);
    });
</script>
@endsection
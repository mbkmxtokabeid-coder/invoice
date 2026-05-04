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
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
    }
    
    /* SOLUSI TERBAIK: Beri minimal tinggi pada pembungkus tabel SAJA agar dropdown 1 baris tidak terpotong */
    /* Hapus .card-body dari sini agar card di bagian atas tidak ikut memanjang */
    .table-responsive, .dataTables_scrollBody {
        min-height: 350px; 
    }
    
    /* Memastikan dropdown z-index berada paling atas */
    .dropdown-menu {
        z-index: 1050 !important;
    }
</style>
@endsection
@extends('layout.template')
@section('content')
@if (session()->has('batal'))
        <script>
             Swal.fire({
            icon: 'success',
            title: 'Invoice Dibatalkan',
            text: '{{ session('batal') }}',
            confirmButtonText: 'OK'
        });
        </script>
    @elseif(session()->has('error'))
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
        </script>
    @elseif(session()->has('lunas'))
        <script>
            Swal.fire({
            icon: 'success',
            title: 'Invoice Lunas',
            text: '{{ session('lunas') }}',
            confirmButtonText: 'OK'
        });
        </script>
    @endif
<div class="page-content">
  <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Invoice Tokabe</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice Tokabe</a></li>
                          <li class="breadcrumb-item active">Daftar Invoice Tokabe</li>
                      </ol>
                  </div>

              </div>
          </div>
      </div>
      <!-- end page title -->
      </div>

       <div class="row pb-4 ">
          <div class="col-sm-6">
            
                <a href="{{ route('add.inv.tkb') }}" class="btn btn-primary"><i class="las la-plus me-1"></i>Tambah Invoice</a>
          </div>
      </div>
      <div class="row">
        @if (Auth::user()->role === 'Pemilik')

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card">
                <div class="card-body bg-primary">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2">Rp<span class="counter-value" data-target="{{$jumlahPerTahun}}">0</span>M</h4>
                            <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah Seluruh Invoice Tahun <script>document.write(new Date().getFullYear())</script>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-3">
                                <i class="las la-check-square fs-24 text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <span class="badge bg-success me-1">{{$jlhInvoice}}</span> <span class="text-white">Invoice Tokabe Dibuat Tahun <script>document.write(new Date().getFullYear())</script></span>
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
                            <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah Terbayar Tahun <br> <script>document.write(new Date().getFullYear())</script></p>

                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-3">
                                <i class="las la-check-square fs-24 text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <span class="badge bg-success me-1">{{$lunas}}</span> <span class="text-white">Invoice Tokabe Lunas Tahun <script>document.write(new Date().getFullYear())</script></span>
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
                              <p class="text-uppercase fw-medium fs-14 text-white-50 mb-0">Jumlah Belum Terbayar Tahun <script>document.write(new Date().getFullYear())</script>
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
                              <span class="badge bg-success me-1">{{$belumLunas}}</span> <span class="text-white">Invoice Tokabe Belum Lunas</span>
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
                              <p class="text-uppercase fw-medium fs-14 text-white-50 mb-0">Jumlah Belum Terbayar Bulan <script>
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
                              <span class="badge bg-success me-1">{{$belumLunasMonth}}</span> <span class="text-white">Invoice Tokabe Belum Lunas</span>
                          </div>
                      </div>
                  </div><!-- end card body -->
              </div><!-- end card -->
          </div><!-- end col -->
          @if (Auth::user()->role === 'Pemilik')

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
                              <span class="badge bg-primary me-1">{{$batal}}</span> <span class="text-white">Invoice Tokabe Dibatalkan</span>
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
                    <h5 class="card-title mb-0">Daftar Invoice Tokabe</h5>
                  </div>
                      <div class="card-body">
                          <table id="scroll-horizontal" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                              <thead>
                                  <tr class="text-muted text-uppercase">
                                      <th scope="col" class="text-center">No</th>
                                      <th scope="col">Invoice ID</th>
                                      <th scope="col">Nama Pelanggan & Perusahaan</th>
                                      <th scope="col" style="width: 13%;">No. Telepon</th>
                                      <th scope="col">Tanggal Penjualan</th>
                                      <th scope="col" style="width: 10%;">Status</th>
                                      <th scope="col">Approval</th>
                                       <th scope="col">Deskripsi</th>
                                      <th scope="col" style="width: 15%;">Bahan Material</th>
                                      <th scope="col">Panjar</th>
                                      <th scope="col">Total</th>
                                      <th scope="col">Tipe Pembayaran</th>
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
                                          @if($inv->approval == 'Unlock')
                                              <span class="badge badge-soft-success p-2">Unlock</span>
                                          @else
                                              <span class="badge badge-soft-warning p-2">Lock</span>
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
                                      <td>Rp.{{$inv->formatted_dp}}</td>
                                      <td>Rp.{{$inv->formatted_total_pembayaran}}</td>
                                      <td>
                                        @if ($inv->jenis_pembayaran == 'Cash Lunas' || $inv->jenis_pembayaran == 'Transfer Lunas')<span class="badge rounded-pill badge-soft-primary p-2">{{$inv->jenis_pembayaran}}</span>
                                          @else
                                          <span class="badge rounded-pill badge-soft-danger p-2">{{$inv->jenis_pembayaran}}</span>
                                          @endif</td>
                                      <td>{{ $inv->user ? $inv->user->nama : 'Tidak Diketahui' }}</td> <!-- Menampilkan nama user -->
                                      <td>
                                          
                                          @php
                                              $roleTkb    = Auth::user()->role;
                                              $isOwnerTkb = $roleTkb === 'Pemilik';
                                              $isAdminTkb = $roleTkb === 'AdminTKB' || $roleTkb === 'Admin';

                                              // Nomor WA Owner Tokabe
                                              $waOwner1Tkb = '628116029999';
                                              $waOwner2Tkb = '628116179999';

                                              // Pesan Request Unlock
                                              $msgUnlockTkb = urlencode("Halo, saya ingin meminta approval untuk membuka akses invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman approval:\n" . url("/tokabe-approval/{$inv->id}"));

                                              // Pesan Request Pelunasan
                                              $msgLunasTkb = urlencode("Halo, saya ingin Request perubahan status pelunasan untuk invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman ubah status pelunasan:\n" . url("/tokabe-pelunasan/{$inv->id}"));

                                              // Format Nomor HP Customer
                                              $no_hp_cust = $inv->no_telepon ?? '';
                                              $no_hp_cust = preg_replace('/[^0-9]/', '', $no_hp_cust);
                                              if(substr($no_hp_cust, 0, 1) == '0'){
                                                  $no_hp_cust = '62' . substr($no_hp_cust, 1);
                                              }

                                              // Pesan WA Customer
                                              $link_khusus_customer = url('/view/download/invoiceTKB/' . $inv->id);
                                              $pesan_cust = "Invoice : " . $inv->nomor_invoice . "\n";
                                              $pesan_cust .= "Link Download Invoice:\n";
                                              $pesan_cust .= $link_khusus_customer . "\n\n";
                                              $pesan_cust .= "Total Rp " . number_format($inv->total_harga, 0, ',', '.') . "\n";
                                              if($inv->ppn > 0) {
                                                  $nominal_ppn = $inv->total_harga * $inv->ppn / 100;
                                                  $pesan_cust .= "PPN (" . $inv->ppn . "%) Rp " . number_format($nominal_ppn, 0, ',', '.') . "\n";
                                                  $pesan_cust .= "-------------------------------------------\n";
                                                  $pesan_cust .= "*Total Pembayaran Rp " . number_format($inv->total_pembayaran, 0, ',', '.') . "*\n\n";
                                              } else {
                                                  $pesan_cust .= "\n";
                                              }
                                              if($inv->sisa_pembayaran > 0) {
                                                  $pesan_cust .= "Mohon transfer ke:\n";
                                                  if($inv->no_rek == "BNI") {
                                                      $pesan_cust .= "BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999\n";
                                                  } elseif($inv->no_rek == "TKBBNI") {
                                                      $pesan_cust .= "BNI | A/N : PT. Total Karya Berkah | No. Rek : 3528289999\n";
                                                  } else {
                                                      $pesan_cust .= "Mandiri | A/N : PT. Total Karya Berkah | No. Rek : 1050009589999\n";
                                                  }
                                                  $pesan_cust .= "**\n\nApabila sudah melakukan transaksi pembayaran mohon dikirim bukti transfernya";
                                              } else {
                                                  $pesan_cust .= "Status : Lunas\n**\n\nTerima kasih,\ntokabe.id";
                                              }

                                              $canManageTkb = !$isAdminTkb || $inv->approval === 'Unlock';
                                          @endphp

                                          <div class="dropdown d-inline-block">
                                              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="window" data-bs-popper-config='{"strategy":"fixed"}'>
                                                  <i class="las la-ellipsis-h align-middle fs-18"></i>
                                              </button>
                                              <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                                                  {{-- CETAK --}}
                                                  <li>
                                                      <a class="dropdown-item" href="{{route('cetak.inv.tkb',['id' => $inv->id])}}" target="_blank">
                                                          <i class="las la-print fs-18 align-middle me-2 text-muted"></i>Cetak Invoice
                                                      </a>
                                                  </li>

                                                  {{-- DOWNLOAD --}}
                                                  <li>
                                                      <a class="dropdown-item" href="{{route('download.inv.tkb',['id' => $inv->id])}}" target="_blank">
                                                          <i class="las la-file-download fs-18 align-middle me-2 text-muted"></i>Download
                                                      </a>
                                                  </li>

                                                  {{-- KIRIM WA CUSTOMER --}}
                                                  <li>
                                                      <a class="dropdown-item" href="https://wa.me/{{ $no_hp_cust }}?text={{ urlencode($pesan_cust) }}" target="_blank">
                                                          <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Kirim Invoice (WA)
                                                      </a>
                                                  </li>

                                                  {{-- === OWNER: Lock langsung jika sudah Unlock === --}}
                                                  @if($isOwnerTkb && $inv->approval === 'Unlock')
                                                  <li>
                                                      <a class="dropdown-item" href="{{ route('tokabe.lock', $inv->id) }}">
                                                          <i class="las la-lock fs-18 align-middle me-2 text-warning"></i>Lock
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === ADMIN: Request Unlock via WA jika masih Lock === --}}
                                                  @if($isAdminTkb && $inv->approval !== 'Unlock')
                                                  <li>
                                                      <a class="dropdown-item text-warning fw-semibold" href="https://wa.me/{{ $waOwner1Tkb }}?text={{ $msgUnlockTkb }}" target="_blank">
                                                          <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Request Unlock (Owner 1)
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item text-warning fw-semibold" href="https://wa.me/{{ $waOwner2Tkb }}?text={{ $msgUnlockTkb }}" target="_blank">
                                                          <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Request Unlock (Owner 2)
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- BUAT SPK --}}
                                                  <li>
                                                      <a class="dropdown-item btn-buat-spk" href="#" data-id="{{ $inv->id }}" data-bs-toggle="modal" data-bs-target="#buatSpkModal">
                                                          <i class="las la-clipboard-list fs-18 align-middle me-2 text-primary"></i>Buat SPK
                                                      </a>
                                                  </li>

                                                  {{-- === ADMIN: Request Pelunasan via WA === --}}
                                                  @if($isAdminTkb && $inv->status !== 'Lunas')
                                                  <li>
                                                      <a class="dropdown-item text-warning fw-semibold" href="https://wa.me/{{ $waOwner1Tkb }}?text={{ $msgLunasTkb }}" target="_blank">
                                                          <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Request Pelunasan (Owner 1)
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item text-warning fw-semibold" href="https://wa.me/{{ $waOwner2Tkb }}?text={{ $msgLunasTkb }}" target="_blank">
                                                          <i class="lab la-whatsapp fs-18 align-middle me-2 text-success"></i>Request Pelunasan (Owner 2)
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === OWNER: Pelunasan langsung === --}}
                                                  @if($isOwnerTkb && $inv->status == 'Belum Lunas')
                                                  <li>
                                                      <a class="dropdown-item" href="{{ route('tokabe.pelunasan.page', $inv->id) }}">
                                                          <i class="las la-money-bill fs-18 align-middle me-2 text-success"></i>Pelunasan
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === EDIT & BATAL: hanya jika Pemilik, atau Admin dengan approval Unlock === --}}
                                                  @if($canManageTkb)
                                                  <li>
                                                      <a class="dropdown-item" href="{{ route('edit.inv.tkb', $inv->id) }}">
                                                          <i class="las la-pen fs-18 align-middle me-2 text-muted"></i>Edit
                                                      </a>
                                                  </li>
                                                  @if($inv->status !== 'Batal')
                                                  <li>
                                                      <button type="button" class="dropdown-item" onclick="confirmBatalTkb('{{ $inv->id }}', '{{ $inv->nomor_invoice }}')">
                                                          <i class="las la-folder-minus fs-18 align-middle me-2 text-muted"></i>Invoice Batal
                                                      </button>
                                                      <form id="form-batal-tkb-{{ $inv->id }}" action="{{ route('tokabe.status.batal', $inv->id) }}" method="POST" style="display:none;">
                                                          @csrf
                                                          <input type="hidden" name="alasan_batal" id="input-alasan-tkb-{{ $inv->id }}">
                                                      </form>
                                                  </li>
                                                  @endif
                                                  @endif

                                                  {{-- DELETE --}}
                                                  <li class="dropdown-divider"></li>
                                                  <li>
                                                      <form action="{{ route('delete.invoiceTokabe',['id'=> $inv->id]) }}" method="POST" id="deleteInv{{ $inv->id }}">
                                                          @csrf
                                                          @method('delete')
                                                          <button class="dropdown-item" type="button" onclick="showConfirmation('{{ $inv->nomor_invoice }}', '{{ $inv->id }}')">
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


  </div>
  <!-- container-fluid -->
</div>


<!-- Modal Buat SPK -->
<div class="modal fade" id="buatSpkModal" tabindex="-1" aria-labelledby="buatSpkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buatSpkModalLabel">Buat SPK Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="{{ route('storeSpk.Tokabe') }}" method="POST">
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


<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('js/pages/datatables.init.js')}}"></script>
<script src="{{asset('js/halaman/daftar-invoice.js')}}"></script>

<script>
    $(document).ready(function() {
        // Menangkap klik tombol "Buat SPK" dan memasukkan ID-nya ke form modal
        $('.btn-buat-spk').on('click', function() {
            var invoiceId = $(this).data('id');
            $('#spk_invoice_id').val(invoiceId);
        });
    });

    function showConfirmation(invoice, invId) {
        Swal.fire({
            html: '<lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#eee966,secondary:#c71f16" state="hover-2" style="width:200px;height:200px"></lord-icon>' +
                  '<p>Apakah Anda ingin menghapus invoice ' + invoice + '?</p>',
            showCancelButton: true,
            confirmButtonColor: '#c71f16',
            cancelButtonColor: '#eee966',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus',
            customClass: {
                popup: 'swal2-popup' 
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteInv' + invId).submit();
            }
        });
    }

    function confirmBatalTkb(id, nomorInvoice) {
        Swal.fire({
            title: 'Pembatalan Invoice',
            text: "Masukkan alasan pembatalan untuk invoice " + nomorInvoice,
            input: 'textarea',
            inputPlaceholder: 'Tulis alasan batal di sini...',
            showCancelButton: true,
            confirmButtonText: 'Proses Batal',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#d33',
            inputValidator: (value) => {
                if (!value) return 'Alasan batal harus diisi!';
            },
            customClass: { popup: 'swal2-popup' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('input-alasan-tkb-' + id).value = result.value;
                document.getElementById('form-batal-tkb-' + id).submit();
            }
        });
    }
</script>
@endsection
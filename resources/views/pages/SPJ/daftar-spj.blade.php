@section('head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@extends('layout.template')
@section('content')
{{-- @if(session('success'))
<script src="{{ asset('js/halaman/sweet-alert.js') }}"></script>
@endif --}}

<div class="page-content">
  <div class="container-fluid">
    <!-- start page title -->
    <div class="row">
      <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0">SPJ</h4>
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="#">SPJ</a></li>
              <li class="breadcrumb-item active">Daftar SPJ</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- end page title -->
 
    <!-- Tombol Tambah -->
    <div class="row pb-2 align-items-end" style="margin-top:-10px;">
      <div class="col-sm-3" style="margin-right: -150px;">
        <a href="/invoice/tambah-spj" class="btn btn-md btn-primary mt-2">
          <i class="las la-plus"></i> Tambah SPJ
        </a>
      </div>
    </div>
    
    <div class="row">
    <div class="col-xl-6 col-md-6">
                <!-- card -->
                <div class="card bg-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$totalTahunIni}}">0</span></h4>
                                <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah SPJ Terbayar </br>Tahun <script>document.write(new Date().getFullYear())</script></p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded-circle fs-3">
                                    <i class="las la-money-check-alt fs-24 text-primary"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-primary me-1">{{ $jumlahSpjTahunIni}}</span> <span class="text-white">SPJ</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
            
             <div class="col-xl-6 col-md-6">
                <!-- card -->
                <div class="card bg-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$totalBulanIni}}">0</span></h4>
                                <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah SPJ Terbayar </br>Bulan ini</script></p>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded-circle fs-3">
                                    <i class="las la-money-check-alt fs-24 text-primary"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-primary me-1">{{$jumlahSpjBulanIni}}</span> <span class="text-white">SPJ</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
    </div>
    
            
    
    

    <!-- Table Daftar SPJ -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Daftar SPJ</h5>
          </div>
          <div class="card-body">
            <table class="table table-nowrap dt-responsive align-middle table-hover table-bordered mb-0" id="scroll-horizontal" >
              <thead>
                <tr>
                  <th>No</th>
                  <th>No. SPJ</th>
                  <th>Nama Kurir/Petugas</th>
                  <th>Tanggal tugas</th>
                  <th>Tujuan</th>
                  <th>Biaya</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($spj as $item)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $item->nomor_spj }}</td>
                  <td>{{ $item->nama_kurir }}</td>
                  <td>{{ \Carbon\Carbon::parse($item->tanggal_tugas)->format('d-m-Y') }}</td>
                  <td>{{ $item->tujuan }}</td>
                  <td>Rp. {{ number_format($item->biaya_bahan_bakar, 0, ',', '.') }}</td>
                  <td>{{$item->status}}</td>
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-soft-secondary btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="las la-ellipsis-h align-middle fs-18"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                         <li>
                                    <a class="dropdown-item" href="/invoice/lihat-spj/{{$item->id}}"><i class="las la-eye fs-18 align-middle me-2 text-muted"></i>
                                        Lihat</a>
                                </li>
                                
                                <li>
                                  <a class="dropdown-item" href="/invoice/cetak-spj/{{$item->id}}"><i class="las la-print fs-18 align-middle me-2 text-muted"></i>
                                      Cetak</a>
                                </li>
                        <li>
                          <a class="dropdown-item" href="/invoice/edit-spj/{{$item->id}}">
                            <i class="las la-pen fs-18 align-middle me-2 text-muted"></i> Edit
                          </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                          <form action="/invoice/delete-spj/{{$item->id}}" method="POST" id="deletespj{{ $item->id }}">
                            @csrf
                            @method('DELETE')
                            <button class="dropdown-item" type="button" onclick="showConfirmation('{{ $item->nomor_spj }}', '{{ $item->id }}')">
                              <i class="las la-trash-alt fs-18 align-middle me-2 text-muted"></i> Hapus
                            </button>
                          </form>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- SweetAlert untuk Konfirmasi Hapus -->
<script>
  function showConfirmation(nospj, id) {
    Swal.fire({
      html: '<lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#eee966,secondary:#c71f16" state="hover-2" style="width:200px;height:200px"></lord-icon>' +
            '<p>Apakah Anda yakin ingin menghapus SPJ No. ' + nospj + '?</p>',
      showCancelButton: true,
      confirmButtonColor: '#c71f16',
      cancelButtonColor: '#eee966',
      cancelButtonText: 'Batal',
      confirmButtonText: 'Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('deletespj' + id).submit();
      }
    });
  }
</script>
@endsection

@section('plugins')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
 
  <script src="{{ asset('js/pages/datatables.init.js') }}"></script>
@endsection

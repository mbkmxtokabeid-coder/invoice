@section('head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@extends('layout.template')
@section('content')
  <div class="page-content">
    <div class="container-fluid">
      <!-- start page title -->
      <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pembelian Pada Vendor</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Vendor</a></li>
                        <li class="breadcrumb-item active">Daftar Vendor</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    {{-- Start Row 1  --}}
    {{-- End Row 1  --}}

        {{-- Start Row 2 --}}
        <div class="row pb-2 align-items-end" style="margin-top:-10px;">
          {{-- Start Button Add Barang --}}
          <div class="col-sm-3" style="margin-right: -150px;">
            <a href="{{route('vendor.add')}}" class="btn btn-md btn-primary mt-2">
              <i class="las la-plus"></i>Tambah Vendor</a>
            </div>
            {{-- End Button Add Barang --}}
          </div>
        {{-- End Row 2 --}}

        <div class="row">
          @if (Auth::user()->role === 'Pemilik')

          <div class="col-xl-4 col-md-6">
            <!-- card -->
            <div class="card">
                <div class="card-body bg-success">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$totalOutstnd}}">0</span>M</h4>
                            <p class="text-uppercase fw-medium fs-14 text-white mb-0"> Total Outstanding
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-3">
                                <i class="las la-info-circle fs-24 text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
          </div><!-- end col -->

          <div class="col-xl-4 col-md-6">
              <!-- card -->
              <div class="card">
                  <div class="card-body bg-primary">
                      <div class="d-flex align-items-center">
                          <div class="flex-grow-1">
                              <h4 class="fs-22 fw-semibold ff-secondary text-white mb-2">Rp<span class="counter-value" data-target="{{$OutstndLunas}}">0</span>M</h4>
                              <p class="text-uppercase fw-medium fs-14 text-white mb-0">Outstanding Terbayar
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

                      </div>
                  </div><!-- end card body -->
              </div><!-- end card -->
          </div><!-- end col -->
          @endif


          <div class="col-xl-4 col-md-6">
            <div class="card bg-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="fs-22 fw-semibold ff-secondary mb-2 text-white">Rp<span class="counter-value" data-target="{{$OutstndBlmLunas}}">0</span>M</h4>
                            <p class="text-uppercase fw-medium fs-14 text-white-50 mb-0">Jumlah Belum Terbayar
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light rounded-circle fs-3">
                                <i class="las la-times-circle fs-24 text-white"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
          </div><!-- end col -->
        </div><!-- end row -->
        
        {{-- Start Row 3 --}}
        <div class="row">
          {{-- Start Coloumn --}}
          <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Vendor</h5>
                </div>
                <div class="card-body">
                    <table id="alternative-pagination" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Vendor</th>
                                <th>Total Transaksi</th>
                                <th>Total Terbayar</th>
                                <th>Total Sisa</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($vendors as $vendor)
                            {{-- <tr  class="clickable-row" data-href="{{ route('pembelian.list', ['uid' => $vendor->id]) }}"> --}}
                              <tr>
                                <td class="text-center" style="width: 10px;">{{$loop->iteration}}</td>
                                <td><a href="{{ route('pembelian.list', ['uid' => $vendor->id]) }}">{{$vendor->nama_vendor}}</a></td>
                                <td>Rp.{{$vendor->formattedTotal}}</td>
                                <td>Rp.{{$vendor->formattedTerbayar}}</td>
                                <td>Rp.{{$vendor->formattedSisa}}</td>
                                <td class="text-center">
                                  <div class="dropdown text-center">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="las la-ellipsis-h align-middle fs-18"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('pembelian.list', ['uid' => $vendor->id]) }}"><i class="las la-pen fs-18 align-middle me-2 text-muted"></i>Lihat Daftar Pembelian</a>
                                        </li>
                                        {{-- @if ($pembelian->status !== 'Lunas') --}}
                                        {{-- <li class="dropdown-divider"></li> --}}
                                        <li>
                                            <a class="dropdown-item" href="{{route('pembelian.add'
                                            ,['uid'=>$vendor->id]
                                            )}}">
                                                <i class="las la-file-invoice-dollar fs-18 align-middle me-2 text-muted"></i>
                                                Tambah Pembelian
                                            </a>
                                        </li>
                                        <li>
                                          <a class="dropdown-item" href="{{route('vendor.edit',['id'=>$vendor->id])}}"><i class="las la-pen fs-18 align-middle me-2 "></i>Edit Vendor</a>

                                        </li>
                                        {{-- @endif --}}
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
        {{-- End Coloumn --}}
        </div>
        {{-- End Row 3 --}}
    </div>
  </div>
@endsection

@section('plugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="{{asset('js/pages/datatables.init.js')}}"></script>
{{-- <script src="{{asset('js/halaman/vendor.js')}}"></script> --}}
@endsection
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
                <h4 class="mb-sm-0">Pembelian dari Vendor {{$vendor->nama_vendor}}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{route('vendor.list')}}">Daftar Vendor</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Vendor {{$vendor->nama_vendor}}</a></li>
                        <li class="breadcrumb-item active">Daftar Pembelian</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    {{-- Start Row 1  --}}


    {{-- End Row 1  --}}

        {{-- Start Row 2 --}}
        <div class="row">
          @if (Auth::user()->role === 'Pemilik')

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card">
                <div class="card-body bg-danger">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="fs-22 fw-semibold ff-secondary text-white mb-2">Rp<span class="counter-value" data-target="{{$jumlahOutStndAll}}">0</span>M</h4>
                            <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah Seluruh Outstanding</p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-3">
                                {{-- <i class="las la-file-alt fs-24 text-primary"></i> --}}
                                <i class="las la-exclamation fs-24 text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <span class="badge bg-primary me-1">{{$pembelianAll}}</span><span class="text-white">Pembelian</span>
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
                            <h4 class="fs-22 fw-semibold ff-secondary text-white mb-2">Rp<span class="counter-value" data-target="{{$jumlahOutStndYear}}">0</span>M</h4>
                            <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah Outstanding <br> Tahun <script>document.write(new Date().getFullYear())</script>
                            </p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-3">
                                {{-- <i class="las la-file-alt fs-24 text-primary"></i> --}}
                                <i class="las la-exclamation fs-24 text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <span class="badge bg-primary me-1">{{$pembelianYear}}</span><span class="text-white">Pembelian Pada Tahun <script>document.write(new Date().getFullYear())</script></span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body bg-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="fs-22 fw-semibold ff-secondary text-white mb-2">Rp<span class="counter-value" data-target="{{$jumlahOutStndMonth}}">0</span>M</h4>
                            <p class="text-uppercase fw-medium fs-14 text-white mb-0">Jumlah Outstanding <br> Bulan {{ Carbon\Carbon::now()->translatedFormat('F') }}</p>

                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-3">
                                <i class="las la-exclamation-triangle  fs-24 text-success"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <span class="badge bg-success me-1">{{$pembelianMonth}}</span> <span class="text-white">Pembelian Pada Bulan {{ Carbon\Carbon::now()->translatedFormat('F') }}</span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        @endif
        </div>

        <div class="row pb-2 align-items-end" style="margin-top:-10px;">
          {{-- Start Button Add Barang --}}
          <div class="col-sm-3" style="margin-right: -150px;">
            <a href="{{route('pembelian.add',['uid'=>$vendor->id])}}" class="btn btn-md btn-primary mt-2">
              <i class="las la-plus"></i>Tambah Pembelian</a>
            </div>
            {{-- End Button Add Barang --}}
          </div>
        {{-- End Row 2 --}}

          {{-- Start Row 3 --}}
        <div class="row">
          {{-- Start Coloumn --}}
          <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Pembelian </h5>
                </div>
                <div class="card-body">
                    <table id="alternative-pagination" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Nama Vendor</th>
                                <th class="text-center">No. Invoice</th>
                                {{-- <th>Keterangan</th> --}}
                                <th class="text-center">Tanggal Pembelian</th>
                                <th class="text-center">Tanggal JTO</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Sisa</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($pembelians as $pembelian)
                           <tr>
                                <td><div class="text-center">{{$loop->iteration}}</div></td>
                                <td class="text-center">{{$vendor->nama_vendor}}</td>
                                <td>{{$pembelian->nomor_inv}}</td>
                                <td class="text-center">{{$pembelian->formatted_tgl}}</td>
                                <td class="text-center">{{$pembelian->formatted_tgl_jto}}</td>
                                {{-- <td>Souvenir</td> --}}
                                <td class="text-center">
                                  @if ($pembelian->status === 'Lunas')
                                    <span class="badge badge-soft-primary p-2">{{$pembelian->status}}</span></td>
                                  @else
                                    <span class="badge badge-soft-danger p-2">{{$pembelian->status}}</span></td>
                                  @endif
                                <td class="text-center">{{$pembelian->formattedHarga}}</td>
                                <td class="text-center">{{$pembelian->formattedSisa}}</td>
                                <td>
                                  <div class="dropdown text-center">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="las la-ellipsis-h align-middle fs-18"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{route('pembelian.edit',['uid' => $pembelian->id])}}"><i class="las la-pen fs-18 align-middle me-2 text-muted"></i>
                                                Update</a>
                                        </li>
                                        @if ($pembelian->status !== 'Lunas')
                                        <li class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item remove-item-btn" href="{{route('pembelian.lunas',['uid'=>$pembelian->id])}}">
                                                <i class="las la-file-invoice-dollar fs-18 align-middle me-2 text-muted"></i>
                                                Lunas
                                            </a>
                                        </li>
                                        @endif
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
@endsection
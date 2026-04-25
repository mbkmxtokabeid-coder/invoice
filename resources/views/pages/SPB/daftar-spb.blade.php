@section('head')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@extends('layout.template')
@section('content')
{{-- Modal --}}
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" id="deleteModal"  aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-body text-center p-5">
            <input type="hidden" name="spb_delete_id" id="spb_id">
            <lord-icon
            src="https://cdn.lordicon.com/tdrtiskw.json"
            trigger="loop"
            colors="primary:#eee966,secondary:#c71f16"
            state="hover-2"
            style="width:150px;height:150px">
            </lord-icon>
            <div class="mt-4">
              <h4 class="mb-3">Apakah ingin menghapus data?</h4>
              <div class="hstack gap-2 justify-content-center">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                  <form id="deleteForm" action="/delete-spb" method="POST">
                    @csrf
                    @method('delete')

                    <button type="submit" class="btn btn-danger mt-3">
                      <i class="las la-trash-alt fs-18 align-middle me-2"></i>
                      Delete
                    </button>
                    </form>

              </div>
          </div>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


{{-- @if(session('success'))
<script src="{{ asset('js/halaman/sweet-alert.js') }}"></script>
@endif --}}
    <div class="page-content">
      <div class="container-fluid">
        {{-- Start Page Title --}}
        <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Daftar SPB</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">SPB</a></li>
                          <li class="breadcrumb-item active">Daftar SPB</li>
                      </ol>
                </div>
             </div>
          </div>
       </div>
        {{-- End Page Title --}}
        {{-- Start Body Content Row 1 --}}
       <div class="row pb-4">
        {{-- Start Tombol Tambah --}}
        <div class="col-sm-4">
          <a href="tambah-spb" class="btn btn-primary addMembers-modal"><i class="las la-plus me-1"></i>Tambah SPB</a>
        </div>
        <div class="col-sm-auto ms-auto">
          <div class="d-flex gap-3">
            <div class="search-box">
              <input type="text" class="form-control" placeholder="Search for name or designation...">
              <i class="las la-search search-icon"></i>
          </div>
          </div>
        </div>
        {{-- End Tombol Tambah --}}
       </div>
       {{-- End Body Content Row 1 --}}
       {{-- Start Body Content Row 2 --}}
       <div class="row">
         <div class="col-xl-4 col-md-6">
          {{-- Start Card --}}
          <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fs-22 fw-semibold ff-secondary mb-2"><span class="counter-value" data-target="{{$jumlahspb}}">0</span></h4>
                        <p class="text-uppercase fw-medium fs-14 text-muted mb-0">SPB Dibuat
                        </p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light rounded-circle fs-3">
                            <i class="las la-file-alt fs-24 text-primary"></i>
                        </span>
                    </div>
                  </div>
                </div><!-- end card body -->
              </div>
             {{-- End Card --}}
           </div>
         <div class="col-xl-4 col-md-6">
          {{-- Start Card --}}
          <div class="card">
            <div class="card-body bg-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fs-22 fw-semibold ff-secondary mb-2"><span class="counter-value" data-target="{{$jumlahSelesai}}">0</span></h4>
                        <p class="text-uppercase fw-medium fs-14 text-white mb-0">SPB Telah Diantar
                        </p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light rounded-circle fs-3">
                            <i class="las la-check-square fs-24 text-primary"></i>
                        </span>
                    </div>
                  </div>
                </div><!-- end card body -->
              </div>
             {{-- End Card --}}
           </div>
         <div class="col-xl-4 col-md-6">
          {{-- Start Card --}}
          <div class="card">
            <div class="card-body bg-danger">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="fs-22 fw-semibold ff-secondary mb-2"><span class="counter-value" data-target="{{$jumlahBelumSelesai}}">0</span></h4>
                        <p class="text-uppercase fw-medium fs-14 text-white mb-0">SPB Belum Diantar
                        </p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-light rounded-circle fs-3">
                            <i class="las la-clock fs-24 text-white"></i>
                        </span>
                    </div>
                  </div>
                </div><!-- end card body -->
              </div>
             {{-- End Card --}}
           </div>
        </div>
      {{-- End Body Content Row 2 --}}
      {{-- Start Body Content Row 3 --}}
      <div class="row">
        <div class="col-xl-12">
          {{-- Start Card --}}
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Daftar SPB</h5>
            </div>
            <div class="card-body">
              {{-- Start div Untuk Table --}}
              {{-- <div class="table-responsive table-card"> --}}
                <table class="table table-hover dt-responsive table-nowrap align-middle table-bordered mb-0" id="alternative-pagination">
                  <thead>
                    <tr class="text-muted text-uppercase">
                      <th scope="col">No.</th>
                      <th scope="col">Pengantar Barang</th>
                      <th scope="col">Tanggal Antar</th>
                      <th scope="col"style="width:20%;">Customer</th>
                      <th scope="col"style="width:20%;">Perusahaan</th>
                      <th scope="col">No.Telepon</th>
                      <th scope="col">Status</th>
                      <th scope="col" class="text-center" style="width:20%;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach ($spb as $value)

                    <tr>
                      <td>
                        <p class="fw-medium mb-0">{{$loop->iteration}}</p>
                      </td>
                      <td>
                        <p class="fw-medium mb-0">
                            @if($value->nama_spb == 1)
                            Ikhtiar Berkah
                            @else
                            Total Karya Berkah
                            @endif
                            </p>
                      </td>
                      <td>{{$value->created_at->isoFormat('DD MMMM YYYY')}}</td>
                      <td>
                        <p class="fw-medium mb-0">{{$value->customer}}</p>
                      </td>
                      <td>
                        <p class="fw-medium mb-0">{{$value->perusahaan}}</p>
                      </td>
                      <td>
                        {{$value->nomor_telepon}}
                      </td>
                      <td>@if ($value->status == "Belum diantar")
                        <span class="badge badge-soft-danger p-2">Belum Diantar</span>
                      @else
                      <span class="badge badge-soft-primary p-2">Sudah Diantar</span>
                      @endif
                        </td>
                      <td class="text-center">
                        <div class="dropdown">
                          <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="las la-ellipsis-h align-middle fs-18"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                              <li>
                                  <a class="dropdown-item" href="{{route('spb.ubah-status',$value->id)}}"><i class="las la-check-square fs-18 align-middle me-2 text-muted"></i>Sudah Diantar</a>
                              </li>
                              <li>
                                  <a class="dropdown-item" target = "_blank" href="/invoice/cetak-spb/{{$value->id}}"><i class="las la-print fs-18 align-middle me-2 text-muted"></i>
                                      Cetak</a>
                              </li>
                              <li>
                                  <a class="dropdown-item" href="/invoice/edit-spb/{{$value->id}}"><i class="las la-pen fs-18 align-middle me-2 text-muted"></i>
                                      Update</a>
                              </li>
                              <li class="dropdown-divider"></li>

                              <li>
                                <button data-bs-toggle="modal" data-bs-target=".bs-example-modal-center" id="spb-id" value="{{$value->id}}" class="dropdown-item hapus-btn">
                                  <i class="las la-trash-alt fs-18 align-middle me-2 text-muted"></i>
                                  Delete
                              </button>
                              </li>
                          </ul>
                      </div>
                      </td>
                    </tr>
                    @endforeach

                  </tbody>
                  {{-- End Row --}}
                    {{-- Start Row --}}
                    {{-- End Row --}}
                </table>
              {{-- </div> --}}
              {{-- End div Untuk Table --}}
            </div>
          </div>
          {{-- End Card --}}
        </div>
      </div>
      {{-- End Body Content Row 3 --}}

      </div>
    </div>
    @extends('layout.footer')

@endsection
@section('plugins')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{asset('js/pages/datatables.init.js')}}"></script>
<!-- Modal Js -->
{{-- <script src="{{asset('js/pages/modal.init.js')}}"></script> --}}
<script src="{{asset('js/halaman/spb.js')}}"></script>

@endsection
@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
<style>
  .error-help-block{
    color: #ca131e;
  }
</style>
@endsection
@extends('layout.template')
@section('content')
{{-- Modal --}}
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" id="deleteModal"  aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-body text-center p-5">
            <input type="hidden" name="anggaran_delete_id" id="anggaran_id">
            <lord-icon
            src="https://cdn.lordicon.com/tdrtiskw.json"
            trigger="loop"
            colors="primary:#eee966,secondary:#c71f16"
            state="hover-2"
            style="width:150px;height:150px">
            </lord-icon>
            <div class="mt-4">
              <h4 class="mb-3">Apakah ingin menghapus {{$vendor->nama_vendor}}?</h4>
              <div class="hstack gap-2 justify-content-center">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                  <form id="deleteForm" action="{{ route('vendor.delete', ['id' => $vendor->id]) }}" method="POST">
                    @csrf
                    @method('delete')

                    <button type="submit" class="btn btn-danger">
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

    <div class="page-content">
      <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Edit Vendor</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Vendor</a></li>
                          <li class="breadcrumb-item"><a href="{{route('vendor.list')}}">Daftar Vendor</a></li>
                          <li class="breadcrumb-item active">Edit Vendor</li>
                      </ol>
                  </div>

              </div>
          </div>
      </div>
      <!-- end page title -->
      <div class="row justify-content-center">
        <div class="col-xxl-12">
          <div class="card">
            {{-- Start Isi Form --}}
            <form action="{{route('vendor.update',['id'=>$vendor->id])}}" class="needs-validation" novalidate method="POST">
              @csrf
              @method('put')
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      <div class="row col-lg-12 col-sm-6">
                        <label class="col-lg-4 col-form-label">Nama Vendor</label>
                        <div class="col-lg-7 mb-2">
                          <input type="text" name="vendor" class="form-control form-control-md" placeholder="Nama Vendor" value="{{$vendor->nama_vendor}}">
                         </div>
                        </div>
                          <div class="row">
                            <button type="submit" id="simpan" class="btn btn-primary fw-medium col-lg-3"><i class="ri-save-3-line me-1 align-bottom"></i>Update</button>

                            <a data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger fw-medium hapus-btn col-lg-3 mx-2" value="{{$vendor->id}}"><i class="ri-eraser-line  me-1 align-bottom"></i>Hapus</a>

                          </div>
                      </div>
                    </div>
                  </div>
                  {{-- End Row Utama --}}
                {{-- End Row Input Barang --}}
              </div>
            </form>
            {{-- End Isi Form --}}
          </div>
        </div>
      </div>
      </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\VendorRequest') !!}
@endsection
@section('plugins')
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
@endsection
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
            <input type="hidden" name="spk_delete_id" id="spk_id">
            <lord-icon
            src="https://cdn.lordicon.com/tdrtiskw.json"
            trigger="loop"
            colors="primary:#eee966,secondary:#c71f16"
            state="hover-2"
            style="width:150px;height:150px">
            </lord-icon>
            <div class="mt-4">
              <h4 class="mb-3">Apakah ingin menghapus {{$tinta->jenis_barang}}?</h4>
              <div class="hstack gap-2 justify-content-center">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                  <form id="deleteForm" action="{{ route('delete.tinta', ['id' => $tinta->id]) }}" method="POST">
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
              <h4 class="mb-sm-0">Edit Tinta</h4>

              <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                      <li class="breadcrumb-item"><a href="javascript: void(0);">Barang</a></li>
                      <li class="breadcrumb-item active">Edit Tinta</li>
                  </ol>
              </div>

          </div>
      </div>
  </div>
  <!-- end page title -->
  <div class="row justify-content-center">
    <div class="col-xxl-12">
      <div class="card pb-2">
        {{-- Start Isi Form --}}
        <form action="{{route('update.tinta',['id' => $tinta->id])}}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('put')
          <div class="card-body p-4">
            <div class="row">
              <div class="col-lg-6">
                {{-- Start Row Utama --}}
                <div class="row g-3">
                  {{-- Start Input Data Tinta --}}
                    <div class="row col-lg-12 col-sm-6">
                      <label class="col-lg-3 col-form-label">Nama Tinta</label>
                      <div class="col-lg-9 mb-2">
                        <input type="text" name="nama_tinta" class="form-control form-control-md" value="{{$tinta->jenis_barang}}">
                        @error('nama_tinta')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                       </div>
                      </div>
                      {{-- End Input Nama Customer --}}
                      <div class="row col-lg-12 col-sm-6 mb-2">
                        <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Kode Tinta</label>
                          <div class="col-lg-9">
                           <input type="text" name="kode_tinta" class="form-control form-control-md" id="colFormLabelNama" value="{{$tinta->kode_barang}}">
                           <!-- error message untuk title -->
                           @error('kode_tinta')
                           <div class="alert alert-danger mt-2">
                               {{ $message }}
                           </div>
                           @enderror
                          </div>
                      </div>
                      <div class="row col-lg-12 col-sm-6">
                        <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Stok</label>
                          <div class="col-lg-9">
                           <input name="stok" type="text" class="form-control form-control-md mb-2" value="{{$tinta->stok}}" placeholder="Stok" id="cleave-numeral">
                          </div>
                      </div>
                      <div class="row col-lg-12 col-sm-6">
                        <div class="col-lg-3">
                         <label for="target" class="form-label col-form-label col-form-label-md">Tanggal Masuk</label>
                        </div>
                          <div class="col-lg-6">
                            <input type="date" class="form-control" data-provider="flatpickr"  data-date-format="d/m/Y" id="dateInput" value="{{$format_tgl}}" placeholder=" dd/mm/YYYY" name="tgl_masuk">
                          </div>
                        </div>
                          <input name="harga_modal" type="hidden" class="form-control form-control-md" value="0">
                          </div>
                          <input name="harga_jual" type="hidden" class="form-control form-control-md" value="0">
                          </div>
                          <input name="kategori" type="hidden" class="form-control form-control-md" value="7">
                          </div>
                  </div>
                  <div class="row hstack gap-2">

                    <div class=" col-lg-1" style="margin-left: 170px;">
                      <button type="submit" class="btn btn-primary fw-medium"><i class="ri-save-3-line me-1 align-bottom"></i>Update</button>
                    </div>
                    <div class="col-lg-2">
                      <a data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger fw-medium hapus-btn" value="{{$tinta->id}}"><i class="ri-delete-bin-2-fill me-1 align-bottom"></i>Delete</a>
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
{!! JsValidator::formRequest('App\Http\Requests\TintaRequest') !!}
@endsection
@section('plugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>
@endsection
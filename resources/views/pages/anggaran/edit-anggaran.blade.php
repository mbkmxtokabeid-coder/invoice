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
              <h4 class="mb-3">Apakah ingin menghapus {{$budget->nama_budget}}?</h4>
              <div class="hstack gap-2 justify-content-center">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                  <form id="deleteForm" action="{{ route('budget.delete', ['id' => $budget->id]) }}" method="POST">
                  <!--<form id="deleteForm" action="/invoice/delete-budget/{{$budget->id}}" method="POST">-->
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
                  <h4 class="mb-sm-0">Edit Anggaran</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Anggaran</a></li>
                          <li class="breadcrumb-item"><a href="/daftar-budget">Daftar Anggaran</a></li>
                          <li class="breadcrumb-item active">Edit Anggaran</li>
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
            <form action="{{route('budget.update',['id'=>$budget->id])}}" class="needs-validation" novalidate method="POST">
              @csrf
              @method('put')
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      <div class="row col-lg-12 col-sm-6">
                        <label class="col-lg-4 col-form-label">Nama Anggaran</label>
                        <div class="col-lg-7 mb-2">
                          <input type="text" name="nama_anggaran" class="form-control form-control-md" placeholder="Nama Anggaran" value="{{$budget->nama_budget}}">
                         </div>
                        </div>
                      <div class="row col-lg-12 col-sm-6">
                        {{-- Start Input Barang --}}
                        <label  class="col-lg-4 col-form-label">Kategori</label>
                        <div class="col-lg-7 input-light mb-2">
                          <select class="form-select" name="kategori" required>
                            @foreach ($kategori as $id => $nama_kategori)
                            <option value="{{ $id }}" @if ($id == $budget->kategori_id) selected @endif>{{ $nama_kategori }}</option>
                            @endforeach
                          </select>
                         </div>
                        </div>

                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-4 col-form-label">Anggaran</label>
                          <div class="col-lg-7 mb-2">
                            <input id="cleave-numeral" name="anggaran" type="text" class="form-control form-control-md" placeholder="Anggaran" value="{{$budget->anggaran}}">
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}

                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Tanggal</label>
                              <div class="col-lg-7">
                                <input type="date" name="tanggal" class="form-control" data-provider="flatpickr" id="dateInput" placeholder="YYYY/MM/DD" value="{{$budget->tanggal}}">
                              </div>
                          </div>
                          <div class="row">
                            <button type="submit" id="simpan" class="btn btn-primary fw-medium col-lg-3"><i class="ri-save-3-line me-1 align-bottom"></i>Update</button>

                            <a data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger fw-medium hapus-btn col-lg-3 mx-2" value="{{$budget->id}}"><i class="ri-eraser-line  me-1 align-bottom"></i>Hapus</a>

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
    {!! JsValidator::formRequest('App\Http\Requests\BudgetRequest') !!}
@endsection
@section('plugins')
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>
<!--<script src="{{asset('js/halaman/budget.js')}}"></script>-->
@endsection
@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  .error-help-block{
    color: #ca131e;
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
                  <h4 class="mb-sm-0">Tambah Anggaran</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Anggaran</a></li>
                          <li class="breadcrumb-item"><a href="{{route('budget.list')}}">Daftar Anggaran</a></li>
                          <li class="breadcrumb-item active">Tambah Anggaran</li>
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
            <form action="{{route('budget.store')}}" class="needs-validation" novalidate method="POST">
              @csrf
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      <div class="row col-lg-12 col-sm-6">
                        <label class="col-lg-4 col-form-label">Nama Anggaran</label>
                        <div class="col-lg-7 mb-2">
                          <input type="text" name="nama_anggaran" class="form-control form-control-md" placeholder="Nama Anggaran">
                         </div>
                        </div>
                      <div class="row col-lg-12 col-sm-6">
                        {{-- Start Input Barang --}}
                        <label  class="col-lg-4 col-form-label">Kategori</label>
                        <div class="col-lg-7 input-light mb-2">
                          <select class="form-select" name="kategori" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            @foreach ($kategori as $id => $nama_kategori)
                            <option value="{{$id}}">{{$nama_kategori}}</option>
                            @endforeach
                          </select>
                         </div>
                        </div>

                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-4 col-form-label">Anggaran</label>
                          <div class="col-lg-7 mb-2">
                            <input id="cleave-numeral" name="anggaran" type="text" class="form-control form-control-md" placeholder="Anggaran">
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}

                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Tanggal</label>
                              <div class="col-lg-7">
                                <input type="date" name="tanggal" class="form-control" data-provider="flatpickr" id="dateInput" placeholder="YYYY/MM/DD">
                              </div>
                          </div>
                          <div class="row">
                            <button type="submit" id="simpan" class="btn btn-primary fw-medium col-lg-3"><i class="ri-save-3-line me-1 align-bottom"></i>Simpan</button>
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
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>
@endsection
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
                  <h4 class="mb-sm-0">Tambah Vendor</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Barang</a></li>
                          <li class="breadcrumb-item"><a href="{{route('vendor.list')}}">Daftar Vendor</a></li>
                          <li class="breadcrumb-item active">Tambah Vendor</li>
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
            <form action="{{route('vendor.store')}}" class="needs-validation" novalidate method="POST">
              @csrf
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      <div class="row col-lg-12 mb-5 col-sm-6">
                        <label class="col-lg-4 col-form-label">Nama Vendor</label>
                        <div class="col-lg-7 mb-2">
                          <input type="text" name="nama_vendor" class="form-control form-control-md" placeholder="Nama Vendor">
                         </div>
                         <div class="row align-items-start">
                          <button type="submit" id="simpan" class="btn btn-primary fw-medium col-lg-3"><i class="ri-save-3-line me-1 align-bottom"></i>Simpan</button>
                         </div>
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
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
@endsection
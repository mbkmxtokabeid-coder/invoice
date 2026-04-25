@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


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
                  <h4 class="mb-sm-0">Tambah Inventaris</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Inventaris</a></li>
                          <li class="breadcrumb-item active">Tambah Inventaris</li>
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
            <form action="{{route('inventaris.store')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      

                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-3 col-form-label">Kode Inventaris</label>
                          <div class="col-lg-9 mb-2">
                            <input type="text" name="kode_inventaris" class="form-control form-control-md" placeholder="Kode Inventaris" required>
                            @error('kode_inventaris')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Jenis Inventaris</label>
                              <div class="col-lg-9">
                               <input type="text" name="jenis_inventaris" class="form-control form-control-md" id="colFormLabelNama" placeholder="Jenis Inventaris">
                               <!-- error message untuk title -->
                               @error('jenis_inventaris')
                               <div class="alert alert-danger mt-2">
                                   {{ $message }}
                               </div>
                               @enderror
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Stok Inventaris</label>
                              <div class="col-lg-9">
                               <input name="stok" type="text" class="form-control form-control-md" placeholder="Stok" id="cleave-numeral">
                               @error('stok')
                               <div class="alert alert-danger mt-2">
                                   {{ $message }}
                               </div>
                               @enderror
                              </div>
                          </div>
                         
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="dateInput" class="col-lg-3 col-form-label col-form-label-md">Tanggal Pembelian</label>
                            <div class="col-lg-9">
                             <input type="date" class="form-control @error ('tgl_beli') is-invalid @enderror" data-provider="flatpickr"  name="tgl_beli" id="dateInput" placeholder=" YYYY/MM/DD" value="{{ old('tgl_beli') }}">
                             @error('tgl_beli')
                             <div class="invalid-feedback">{{ $message }}</div>
                             @enderror
                            </div>
                           </div>
                          </div>
                        </div>

                    </div>

                

                  {{-- End Row Utama --}}

                {{-- End Row Input Barang --}}
                <div class="row col-lg-2" style="margin-left: 146px;">
                  <button type="submit" class="btn btn-primary fw-medium"><i class="ri-save-3-line me-1 align-bottom"></i>Tambah</button>
                </div>
              </div>
            </form>
            {{-- End Isi Form --}}
          </div>
        </div>
      </div>
      </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\InventarisRequest') !!}
@endsection
@section('plugins')
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>
@endsection
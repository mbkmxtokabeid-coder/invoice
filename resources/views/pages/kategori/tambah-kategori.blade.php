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
                  <h4 class="mb-sm-0">Tambah Kategori Invoice</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Kategori Invoice</a></li>
                          <li class="breadcrumb-item active">Tambah Kategori Invoice</li>
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
            <form action="{{route('store.kategori')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-4 col-form-label">Nama Invoice</label>
                          <div class="col-lg-9 mb-2">
                            <input type="text" name="nama_invoice" class="form-control form-control-md" placeholder="Nama Invoice" required>
                            @error('nama_invoice')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Kode Invoice</label>
                              <div class="col-lg-9">
                               <input type="text" name="kode_invoice" class="form-control form-control-md" id="colFormLabelNama" placeholder="Kode Invoice">
                               <!-- error message untuk title -->
                               @error('kode_invoice')
                               <div class="alert alert-danger mt-2">
                                   {{ $message }}
                               </div>
                               @enderror
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6">
                            {{-- Start Input Barang --}}
                            <label  class="col-lg-4 col-form-label">Perusahaan</label>
                            <div class="col-lg-9 input-light mb-2">
                              <select name="perusahaan_id" class="form-select bg-light" required>
                                <option value="">Pilih Perusahaan</option>
                                {{-- start kategori barang --}}
                                @foreach ($perusahaans as $perusahaan)
                                <option value="{{$perusahaan->id}}">{{$perusahaan->nama_perusahaan}}</option>
                                @endforeach
                                {{-- end kategori barang --}}
                              </select>
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
    {!! JsValidator::formRequest('App\Http\Requests\BarangRequest') !!}
@endsection
@section('plugins')
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>
@endsection
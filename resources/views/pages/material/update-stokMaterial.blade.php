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
    <div class="page-content">
      <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Update Stok Material</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Barang</a></li>
                          <li class="breadcrumb-item active">Update Stok Material</li>
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
            <form action="/invoice/update-stokMaterial/{{$material->id}}" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
              @csrf
              @method('put')
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                        

                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-3 col-form-label">Kode Material</label>
                          <div class="col-lg-9 input-light mb-2">
                            <input type="text" class="form-control form-control-md" value="{{$material->kode_material}}" id="colFormLabelNama" placeholder="Kode Material" readonly>
                          </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Jenis Material</label>
                              <div class="col-lg-9">
                               <input type="text" class="form-control form-control-md" value="{{$material->jenis_material}}" id="colFormLabelNama" name="jenis" placeholder="Jenis Barang">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Stok Material</label>
                              <div class="col-lg-9">
                               <input type="text" name="stok" class="form-control" placeholder="Stok Tambahan" value="{{$material->stok}}" id="cleave-numeral">
                              </div>
                          </div>

                          {{-- Start Input Satuan --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="satuan" class="col-lg-3 col-form-label col-form-label-md">Satuan</label>
                            <div class="col-lg-9">
                                <select class="form-select form-control-md" name="satuan" id="satuan">
                                    <option disabled {{ is_null($material->satuan) ? 'selected' : '' }}>Pilih Satuan</option>
                                    <option value="mtr" {{ $material->satuan == 'mtr' ? 'selected' : '' }}>mtr</option>
                                    <option value="pcs" {{ $material->satuan == 'pcs' ? 'selected' : '' }}>pcs</option>
                                    <option value="mm" {{ $material->satuan == 'mm' ? 'selected' : '' }}>mm</option>
                                    <option value="lembar" {{ $material->satuan == 'lembar' ? 'selected' : '' }}>lembar</option>
                                    <option value="cm" {{ $material->satuan == 'cm' ? 'selected' : '' }}>cm</option>
                                    <option value="cm²" {{ $material->satuan == 'cm²' ? 'selected' : '' }}>cm²</option>
                                </select>
                                @error('satuan')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                          </div>
                          {{-- End Input Satuan --}}

                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Harga Modal</label>
                              <div class="col-lg-9">
                               <input type="text" name="harga_modal" class="form-control" placeholder="Harga Modal" value="{{$material->harga_modal}}" id="harga-modal">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Harga Jual</label>
                              <div class="col-lg-9">
                               <input type="text" name="harga_jual" class="form-control" placeholder="Harga Jual" value="{{$material->harga_jual}}" id="harga-jual">
                              </div>
                          </div>

                      </div>

                    </div>

                  </div>
                  {{-- End Row Utama --}}

                {{-- End Row Input Barang --}}
                <div class="row col-lg-2" style="margin-left: 146px;">
                  <button type="submit" class="btn btn-warning fw-medium"><i class="las la-pen me-1"></i>Update</button>
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
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>
@endsection
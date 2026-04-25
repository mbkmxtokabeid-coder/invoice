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
                  <h4 class="mb-sm-0">Update Stok Barang</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Barang</a></li>
                          <li class="breadcrumb-item active">Update Stok Barang</li>
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
            <form action="/invoice/update-stok/{{$barang->id}}" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
              @csrf
              @method('put')
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      <div class="row col-lg-12 col-sm-6">
                        {{-- Start Input Barang --}}
                        <label  class="col-lg-3 col-form-label">Item</label>
                        <div class="col-lg-9 input-light mb-2">
                          <input type="email" class="form-control form-control-md" value="{{$kategori}}" id="colFormLabelNama" placeholder="Item" readonly>
                         </div>
                        </div>

                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-3 col-form-label">Kode Barang</label>
                          <div class="col-lg-9 input-light mb-2">
                            <input type="email" class="form-control form-control-md" value="{{$barang->kode_barang}}" id="colFormLabelNama" placeholder="Kode Barang" readonly>
                          </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Jenis Barang</label>
                              <div class="col-lg-9">
                               <input type="text" class="form-control form-control-md" value="{{$barang->jenis_barang}}" id="colFormLabelNama" name="jenis" placeholder="Jenis Barang">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Stok Barang</label>
                              <div class="col-lg-9">
                               <input type="text" name="stok" class="form-control" placeholder="Stok Tambahan" value="{{$barang->stok}}" id="cleave-numeral">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Harga Modal</label>
                              <div class="col-lg-9">
                               <input type="text" name="harga_modal" class="form-control" placeholder="Harga Modal" value="{{$barang->harga_modal}}" id="harga-modal">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-3 col-form-label col-form-label-md">Harga Jual</label>
                              <div class="col-lg-9">
                               <input type="text" name="harga_jual" class="form-control" placeholder="Harga Jual" value="{{$barang->harga_jual}}" id="harga-jual">
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
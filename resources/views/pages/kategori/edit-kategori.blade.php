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
                  <h4 class="mb-sm-0">Edit Kategori</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Kategori</a></li>
                          <li class="breadcrumb-item active">Edit Kategori</li>
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
            <form action="{{ route('put.kategori',['id' => $kategori->id]) }}" method="POST">
              @csrf
              @method('put')
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-4 col-form-label">Nama Invoice</label>
                          <div class="col-lg-9 mb-2">
                            <input type="text" name="nama_invoice" class="form-control form-control-md" placeholder="Nama Invoice" required value="{{ old('nama_invoice', $kategori->nama_invoice) }}">
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-form-label col-form-label-md">Kode Invoice</label>
                              <div class="col-lg-9">
                               <input type="text" name="kode_invoice" class="form-control form-control-md" id="colFormLabelNama" placeholder="Kode Invoice" required value="{{ old('kode_invoice', $kategori->kode_invoice) }}">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6">
                            {{-- Start Input Barang --}}
                            <label  class="col-form-label">Perusahaan</label>
                            <div class="col-lg-9 input-light mb-2">
                              <select name="perusahaan_id" class="form-select bg-light" required>
                                <option value="">Pilih Perusahaan</option>
                                {{-- start kategori barang --}}
                                @foreach ($perusahaans as $perusahaan)
                                    @if(old('perusahaan_id', $kategori->perusahaan_id) === $perusahaan->id)
                                        <option value="{{ $perusahaan->id }}" selected>{{ $perusahaan->nama_perusahaan }}</option>
                                    @else
                                        <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_perusahaan }}</option>
                                    @endif
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
                <div class="row col-lg-4" style="margin-left: 146px;">
                  <button type="submit" class="btn btn-primary fw-medium"><i class="ri-save-3-line me-1 align-bottom"></i>Update</button>
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
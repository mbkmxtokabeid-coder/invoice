@section('head')
<link rel="stylesheet" href="{{asset('libs/dropzone/dropzone.css')}}" type="text/css" />
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
                  <h4 class="mb-sm-0">Tambah SPK</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">SPK</a></li>
                          <li class="breadcrumb-item active">Tambah SPK</li>
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
            <form action="/invoice/store-spk" class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      <div class="row col-lg-12 col-sm-6">
                        {{-- Start Input SPK --}}
                        <label  class="col-lg-3 col-form-label">SPK</label>
                        <div class="col-lg-9 input-light mb-2">
                          <select class="form-select bg-light" data-choices data-choices-search-false  required>
                            <option value="Ikhtiar Berkah">Ikhtiar Berkah</option>
                             <option value="Total Karya Berkah">Total Karya Berkah</option>
                          </select>
                         </div>
                        </div>
                        {{-- End Input SPK --}}
                      <div class="row col-lg-12 col-sm-6">
                        {{-- Start Input Pekerjaan --}}
                        <label  class="col-lg-3 col-form-label">Jenis Pekerjaan</label>
                        <div class="col-lg-9 input-light mb-2">
                          <select class="form-select bg-light" name="pekerjaan" required>
                            <option value="">Pilih Pekerjaan</option>
                            <option value="Plakat">Plakat</option>
                            <option value="Stempel">Stempel</option>
                            <option value="Tumbler">Tumbler</option>
                            <option value="Cutting/Grafir">Cutting/Grafir</option>
                            <option value="Print UV">Print UV</option>
                            <option value="Reklame">Reklame</option>
                            <option value="Cetakan Umum">Cetakan Umum</option>
                            <option value="Souvenir">Souvenir</option>
                            <option value="Customized">Customized</option>
                            <option value="Material">Material</option>
                          </select>
                         </div>
                         {{-- End Input Pekerjaan --}}
                        </div>
                      </div>

                    </div>
                  </div>
                  {{-- End Row Utama --}}
                  {{-- Start Row Input SPK --}}
                <div class="row mt-4">

                  <div class="col-lg-6">
                    {{-- <div class="no-border"> --}}
                      <div class="row mb-3">
                        <div class="col-lg-3">
                         <label for="dateInput" class="form-label col-form-label col-form-label-md">Tanggal</label>
                        </div>
                          <div class="col-lg-6">
                           {{-- <input type="text" class="form-control" data-provider="flatpickr" id="dateInput" value="{{$today}}"> --}}
                           <input type="text" class="form-control flatpickr-input" name="tgl_buat" data-provider="flatpickr" data-date-format="d/m/Y" id="date-field" value="{{$today}}">
                          </div>
                        </div>
                      <div class="row mb-3">
                        <div class="col-lg-3">
                         <label for="colFormNoInvoice" class="form-label col-form-label col-form-label-md">Nomor Invoice</label>
                        </div>
                          <div class="col-lg-6">
                            <input type="text" class="form-control form-control-md" name="invoice" list="invoice" id="inv" value="{{old('invoice')}}" placeholder="Nomor Invoice" required>
                            <datalist id="invoice">
                              @foreach ($invoice as $inv)
                                <option value="{{$inv}}"></option>
                              @endforeach
                            </datalist>
                          </div>
                        </div>
                      <div class="row mb-3">
                        <div class="col-lg-3">
                         <label for="pelanggan" class="form-label col-form-label col-form-label-md">Pelanggan</label>
                        </div>
                          <div class="col-lg-6">
                            <input type="text" class="form-control form-control-md" id="pelanggan" name="customer" list="customer" value="{{old('customer')}}" placeholder="Nama Customer">
                            <datalist>
                              @foreach ($customer as $cust)
                                  <option value="{{$cust}}"></option>
                              @endforeach
                            </datalist>
                          </div>
                        </div>
                      <div class="row mb-3">
                        <div class="col-lg-3">
                         <label for="jumlah" class="form-label col-form-label col-form-label-md">Jumlah</label>
                        </div>
                          <div class="col-lg-6">
                            <input type="text" class="form-control form-control-md" id="cleave-numeral" name="jumlah" id="jumlah" placeholder="Jumlah">
                          </div>
                        </div>
                      <div class="row mb-3">
                        <div class="col-lg-3">
                         <label for="satuan" class="form-label col-form-label col-form-label-md">Satuan</label>
                        </div>
                          <div class="col-lg-6">
                            <select class="form-select bg-light border-0"  id="choices-orderBy" name="satuan" required>
                              <option value="">Pilih</option>
                              <option value="Pcs">Pcs</option>
                              <option value="Set">Set</option>
                              <option value="Und">Und</option>
                              <option value="Blok">Blok</option>
                              <option value="Rim">Rim</option>
                              <option value="Lbr">Lbr</option>
                              <option value="Kotak">Kotak</option>
                              </select>
                          </div>
                        </div>
                      <div class="row mb-3">
                        <div class="col-lg-3">
                         <label for="pelanggan" class="form-label col-form-label col-form-label-md">Jenis Bahan</label>
                        </div>
                          <div class="col-lg-6">
                            <textarea class="form-control bg-light border-0" rows="1" placeholder="Jenis Bahan" name="jenis_bahan"></textarea>
                        </div>
                      </div>
                      {{-- End input data--}}
                      {{-- </div> --}}
                    </div>
                    <div class="col-lg-6">
                    <div class="row mb-3">
                      <div class="col-lg-3">
                       <label for="ketebalan" class="form-label col-form-label col-form-label-md">Ketebalan</label>
                      </div>
                        <div class="col-lg-6">
                          <textarea class="form-control bg-light border-0" rows="1" placeholder="Ketebalan" name="ketebalan" ></textarea>
                        </div>
                      </div>
                    <div class="row mb-3">
                      <div class="col-lg-3">
                       <label for="ukuran" class="form-label col-form-label col-form-label-md">Ukuran</label>
                      </div>
                        <div class="col-lg-6">
                          <textarea class="form-control bg-light border-0" rows="1" placeholder="Ukuran" name="ukuran"></textarea>
                        </div>
                      </div>
                    <div class="row mb-3">
                      <div class="col-lg-3">
                       <label for="lainnya" class="form-label col-form-label col-form-label-md">Lainnya</label>
                      </div>
                        <div class="col-lg-6">
                          <textarea class="form-control bg-light border-0" rows="1" placeholder="Lain-Lain" name="lain"></textarea>
                        </div>
                      </div>
                    <div class="row mb-3">
                      <div class="col-lg-3">
                       <label for="express" class="form-label col-form-label col-form-label-md">Express</label>
                      </div>
                        <div class="col-lg-6">
                          <select class="form-select bg-light" name="express"  required>
                            <option value="">Pilih</option>
                            <option value="Y">Ya</option>
                            <option value="N">Tidak</option>
                          </select>
                        </div>
                      </div>
                    <div class="row mb-3">
                      <div class="col-lg-3">
                       <label for="target" class="form-label col-form-label col-form-label-md">Tgl Penyelesaian</label>
                      </div>
                        <div class="col-lg-6">
                          <input type="date" class="form-control" data-provider="flatpickr" data-date-format="d/m/Y" data-enable-time id="dateInput" placeholder=" dd/mm/YYYY" name="tgl_selesai">
                        </div>
                      </div>
                    <div class="row mb-3">
                      <div class="col-lg-3">
                       <label for="jam" class="form-label col-form-label col-form-label-md">Contoh Desain</label>
                      </div>
                        <div class="col-lg-6">
                          <input type="file" name="contoh_design" class="form-control form-control-md" id="upload">
                        </div>
                      </div>
                    <div class="row mb-3">
                      <div class="col-lg-3">
                      </div>
                        <div class="col-lg-6">
                          <p>maks 1,5 MB</p>
                        </div>
                      </div>

                  </div>
                </div>
                {{-- End Row Input SPK --}}
                <div class="row">
                  <button type="submit" class="btn btn-primary col-lg-3 fw-medium"><i class="ri-save-3-line me-1 align-bottom"></i>Simpan</button>
                </div>
              </div>
            </form>
            {{-- End Isi Form --}}
          </div>
        </div>
      </div>
      </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\SPKRequest') !!}
@endsection
@section('plugins')
<script src="{{asset('libs/dropzone/dropzone-min.js')}}"></script>
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>

@endsection
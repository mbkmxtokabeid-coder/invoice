@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  .error-help-block{
    color: #ca131e;
  }
</style>
<!-- Masukkan file CSS SweetAlert -->
<!--<link href="{{ asset('vendor/sweetalert2/sweetalert2.css') }}" rel="stylesheet">-->

<!-- Masukkan file JavaScript SweetAlert -->
<!--<script src="{{ asset('vendor/sweetalert2/sweetalert2.js') }}"></script>-->
@endsection
@extends('layout.template')
@section('content')
@if(Session::has('error'))
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'SPB Gagal ditambahkan',
        });
    });
</script>
@endif

    <div class="page-content">
      <div class="container-fluid">
        {{-- Start Page Title --}}
        <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Buat SPB</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">SPB</a></li>
                          <li class="breadcrumb-item active">Buat SPB</li>
                      </ol>
                </div>
             </div>
          </div>
       </div>
        {{-- End Page Title --}}
        {{-- Start Body Content Row 1 --}}
        <div class="row justify-content-center">
          <div class="col-xxl-9">
            {{-- Start Card --}}
            <div class="card">
              {{-- Start Form --}}
              <form method="POST" action="{{ url('store-spb') }}" class="needs-validation" id="spb_form">
                @csrf
                <div class="card-body p-4">
                  {{-- Start Row 1 --}}
                  <div class="row">
                    <div class="col-lg-8">
                      {{-- Start Isi Form --}}
                      <div class="row g-3">
                        <div class="row col-lg-12 col-sm-6">
                          {{-- Start Input SPB --}}
                          <label  class="col-lg-5 col-form-label">SPB</label>
                          <div class="col-lg-7 input-light mb-2">
                            <!--<input class="form-control bg-light" value="Total Karya Berkah" name="namaSpb" readonly  required>-->
                            <!--<input class="form-control bg-light" value="Ikhtiar Berkah" name="namaSpb" readonly  required>-->
                            <select class="form-select bg-light" name="namaSpb" data-choices data-choices-search-false required>
                              <option value="" disabled selected>Pilih Perusahaan</option>
                              @foreach($namaPerusahaan as $usaha)
                              <option value="{{$usaha->id}}">{{$usaha->nama_perusahaan}}</option>
                              @endforeach
                            </select>
                           </div>
                          </div>
                          {{-- End Input SPB --}}

                        {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-5 col-form-label">Nama Customer</label>
                          <div class="col-lg-7 input-light mb-2">
                            <input class="form-control" type="text" name="customer" id="customer" list="pelanggan" placeholder="Pelanggan" value="{{old('customer')}}" required>
                            <datalist id="pelanggan">
                              @foreach ($customer as $cust)
                                  <option value="{{$cust}}">
                              @endforeach
                            </datalist>
                              

                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                        {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-5 col-form-label">Nama Perusahaan</label>
                          <div class="col-lg-7 input-light mb-2">
                            <input class="form-control" type="text" name="perusahaan" id="usaha" list="usaha" placeholder="Perusahaan" value="{{old('perusahaan')}}" required>
                            <datalist id="usaha">
                              @foreach ($perusahaan as $usaha)
                                  <option value="{{$usaha}}">
                              @endforeach
                            </datalist>

                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                        {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label for="phone-number" class="col-lg-5 col-form-label">Nomor Telepon</label>
                          <div class="col-lg-7 input-light mb-2">
                            <input type="text" value="{{old('no_telp')}}" name="no_telp" class="form-control" placeholder="xxxx xxxx xxxx" id="phone-number">
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                      </div>
                      {{-- End Isi Form --}}
                    </div>
                        <div class="col-lg-4 ms-auto">
                            <div class="profile-user mx-auto  mb-3">
                             <input id="profile-img-file-input" type="file" class="profile-img-file-input"/>
                             <label for="profile-img-file-input" class="d-block" tabindex="0">
                              <!--<span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style="height: 110px; width: 256px;">-->
                              <span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style="height: 60px; width: 256px;">
                              <!--<img src="{{asset('images/Logo-TKB.jpg')}}" class="card-logo card-logo-dark user-profile-image img-fluid" alt="logo dark">-->
                              <!--<img src="{{asset('images/Logo-TKB.jpg')}}" class="card-logo card-logo-light user-profile-image img-fluid" alt="logo light">-->
                              <img src="{{asset('images/logo-ib-black.png')}}" class="card-logo card-logo-dark user-profile-image img-fluid" alt="logo dark">
                              <img src="{{asset('images/logo-ib-white.png')}}" class="card-logo card-logo-light user-profile-image img-fluid" alt="logo light">
                              </span>
                             </label>
                            </div>
                        </div>
                  </div>
                  {{-- End Row 1 --}}
                  {{-- Start Row 2 --}}
                  <div class="row">
                    <div class="card-body p-4">
                      <table class="invoice-table table table-borderless table-nowrap mb-0">
                    <div class="table-responsive">
                      <thead class="align-middle">
                       <tr class="table-active">
                        <th scope="col" style="width: 50px;">#</th>
                        <th scope="col">Item</th>
                        <th scope="col">Satuan</th>
                        <th scope="col">Quantity</th>
                        <th scope="col" class="text-center" style="width: 200px;">Keterangan</th>
                        <th scope="col" class="text-end" style="width: 105px;"></th>
                       </tr>
                      </thead>
                      {{-- <tbody id="newlink">
                       <tr id="1" class="product">
                        <th scope="row" class="product-id">1</th>
                         <td class="text-start">
                          <div class="mb-2">
                           <label class="visually-hidden" for="productName">Preference</label>
                            <select class="form-field @error('barang_id[]') is-invalid @enderror" data-choices data-choices-sorting="true" id="productName-1" name="barang_id[]">
                             <option value=""selected disabled>Pilih Item</option>
                             @foreach ($barang as $item)
                                 <option value="{{$item->id}}" {{old('barang_id[]')}}>{{$item->nama_kategori}}</option>
                             @endforeach
                            </select>
                          </div>
                          @error('barang_id[]')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          <textarea class="form-control bg-light border-0 @error('deskripsi_item[]') is-invalid @enderror" id="productDetails-1"  name="deskripsi_item[]" rows="2" placeholder="Deskripsi Item">{{old('deskripsi_item[]')}}</textarea>
                          @error('deskripsi_item[]')
                          <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                         </td>
                         <td>
                          <select class="form-select bg-light border-0 @error ('satuan[]') is-invalid @enderror" id="satuan-1" name="satuan[]" required>
                            <option value="">Pilih</option>
                            <option value="Set" @if(old('satuan[]') == 'Set') selected @endif>Set</option>
                            <option value="Pcs" @if(old('satuan[]') == 'Pcs') selected @endif>Pcs</option>
                            <option value="Kotak" @if(old('satuan[]') == 'Kotak') selected @endif>Kotak</option>
                            <option value="Blok" @if(old('satuan[]') == 'Blok') selected @endif>Blok</option>
                            <option value="Lbr" @if(old('satuan[]') == 'Lbr') selected @endif>Lbr</option>
                          </select>
                          @error('satuan[]')
                           <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                         </td>
                          <td>
                           <div class="input-step">
                            <input type="number" class="text-center @error('qty[]') is-invalid @enderror" id="product-qty-1" value="{{ old('qty[]') ? old('qty[]') : 0 }}" name="qty[]">
                          </div>
                          @error('qty[]')
                          <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                        </td>
                          <td class="text-end">
                           <div>
                            <textarea id="productKeterangan-1" class="form-control bg-light border-0 @error('keterangan[]') is-invalid @enderror" name="keterangan[]" cols="2" placeholder="Keterangan">{{old('keterangan[]')}}</textarea>
                            @error('keterangan[]')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                          </td>
                          <td class="product-removal"><a href="javascript:void(0)" class="btn btn-success">Delete</a>
                          </td>
                         </tr>
                        </tbody> --}}
                        <tbody id="newlink">
                          <tr id="1" class="product">
                              <th scope="row" class="product-id">1</th>
                              <td class="text-start">
                                  <div class="mb-2">
                                      <label class="visually-hidden" for="productName">Preference</label>
                                      <select class="form-field @error('barang_id[]') is-invalid @enderror" data-choices data-choices-sorting="true" id="productName-1" name="barang_id[]">
                                          <option value="" selected disabled>Pilih Item</option>
                                          @foreach ($barang as $item)
                                              <option value="{{ $item->id }}" {{ old('barang_id.0') == $item->id ? 'selected' : '' }}>{{ $item->nama_kategori }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  @error('barang_id[]')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                                  <textarea class="form-control bg-light border-0 @error('deskripsi_item[]') is-invalid @enderror" id="productDetails-1" name="deskripsi_item[]" rows="2" placeholder="Deskripsi Item">{{ old('deskripsi_item.0') }}</textarea>
                                  @error('deskripsi_item[]')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </td>
                              <td>
                                  <select class="form-select bg-light border-0 @error('satuan[]') is-invalid @enderror" id="satuan-1" name="satuan[]" required>
                                      <option value="">Pilih</option>
                                      <option value="Set" {{ old('satuan.0') == 'Set' ? 'selected' : '' }}>Set</option>
                                      <option value="Pcs" {{ old('satuan.0') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                      <option value="Kotak" {{ old('satuan.0') == 'Kotak' ? 'selected' : '' }}>Kotak</option>
                                      <option value="Blok" {{ old('satuan.0') == 'Blok' ? 'selected' : '' }}>Blok</option>
                                      <option value="Lbr" {{ old('satuan0') == 'Lbr' ? 'selected' : '' }}>Lbr</option>
                                  </select>
                                  @error('satuan[]')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </td>
                              <td>
                                  <div class="input-step">
                                      <input type="number" class="text-center @error('qty[]') is-invalid @enderror" id="product-qty-1" value="{{ old('qty.0') ? old('qty.0') : 0 }}" name="qty[]">
                                  </div>
                                  @error('qty[]')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </td>
                              <td class="text-end">
                                  <div>
                                      <textarea id="productKeterangan-1" class="form-control bg-light border-0 @error('keterangan[]') is-invalid @enderror" name="keterangan[]" cols="2" placeholder="Keterangan">{{ old('keterangan.0') }}</textarea>
                                      @error('keterangan[]')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                  </div>
                              </td>
                              <td class="product-removal">
                                  <a href="javascript:void(0)" class="btn btn-success">Delete</a>
                              </td>
                          </tr>
                      </tbody>

                        <tbody>
                         <tr id="newForm" style="display: none;">
                          <td class="d-none" colspan="5"><p>Add New Form</p>
                          </td>
                         </tr>
                         <tr>
                          <td colspan="5"><a href="javascript:new_link()" id="add-item" class="btn btn-soft-secondary fw-medium"><i class="ri-add-fill me-1 align-bottom"></i> Add Item</a>
                          </td>
                         </tr>
                        </tbody>
                       </table>
                       <!--end table-->
                     </div>
                  </div>
                  {{-- End Row 2 --}}
                  <div class="row">
                    <button type="submit" id="simpan" class="btn btn-primary fw-medium col-lg-3"><i class="ri-save-3-line me-1 align-bottom"></i>Simpan</button>
                  </div>
                </div>
              </form>
              {{-- End Form --}}
            </div>
            {{-- End Card --}}
          </div>
        </div>
        {{-- End Body Content Row 1 --}}

      </div>
    </div>
@endsection
@section('plugins')
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! \JsValidator::formRequest('App\Http\Requests\SPBRequest', '#spb_form') !!}

<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/spb.js')}}"></script>
@endsection
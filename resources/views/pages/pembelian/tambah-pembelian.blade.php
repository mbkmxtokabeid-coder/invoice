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
                  <h4 class="mb-sm-0">Tambah Pembelian {{$vendors->nama_vendor}}</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Vendor</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('pembelian.list',['uid' => $vendors->id]) }}">Pembelian Vendor {{$vendors->nama_vendor}}</a></li>
                          <li class="breadcrumb-item active">Tambah Pembelian</li>
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
            <form action="{{route('pembelian.store')}}" class="needs-validation" method="POST" novalidate>
              @csrf
              <input type="hidden" name="token" value="{{$uniqueToken}}">
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                      <div class="row col-lg-12 col-sm-6">
                        {{-- Start Input Barang --}}
                        <label  class="col-lg-4 col-form-label">Pakai Anggaran</label>
                        <div class="col-lg-7 input-light mb-2">
                          <select class="form-select" name="anggaran" required>
                            <option value="{{old('anggaran')}}" disabled>Pilih Anggaran</option>
                            @foreach ($anggaran as $data)
                            <option value="{{$data->id}}">{{$data->nama_budget}} - Rp.{{number_format($data->anggaran,0,',',',')}}</option>

                            @endforeach
                          </select>
                         </div>
                        </div>

                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-4 col-form-label">Nama Vendor</label>
                          <div class="col-lg-7 mb-2">
                            <input type="hidden" class="form-control" name="vendor" value="{{$vendors->id}}" readonly>
                            <input type="text" class="form-control" value="{{$vendors->nama_vendor}}" readonly>
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Nomor Invoice</label>
                              <div class="col-lg-7">
                               <input type="text" class="form-control form-control-md" value="{{old('no_inv')}}" name="no_inv" id="colFormLabelNama" placeholder="Nomor Invoice">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Tanggal Pembelian</label>
                              <div class="col-lg-7">
                                <input type="date" value="{{old('tgl')}}" name="tgl" class="form-control" data-provider="flatpickr" id="dateInput" placeholder=" YYYY/MM/DD">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="tglJTO" class="col-lg-4 col-form-label col-form-label-md">Tanggal JTO</label>
                              <div class="col-lg-7">
                                <input type="date" value="{{old('tgl_jto')}}" name="tgl_jto" class="form-control" data-provider="flatpickr" id="dateInput" placeholder=" YYYY/MM/DD">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Status Pembelian</label>
                            <div class="col-lg-7 input-light mb-2">
                              <select class="form-select" id="statuss" name="status" value="{{old('status')}}" required>
                                <option value="">Pilih Status</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Belum Lunas">Belum Lunas</option>
                              </select>
                             </div>
                          </div>
                      </div>

                    </div>
                    <div class="col-lg-6">
                      <div class="row g-3">
                        <div id="terbayar" class="row col-lg-12 col-sm-6">
                        {{-- Start Input Barang --}}
                          <div  class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colSisa"  class="col-lg-2 col-form-label" >Terbayar</label>
                            <div class="col-lg-7">
                              <input type="text" value="{{old('terbayar')}}" id="input_bayar" class="form-control form-control-md" name="terbayar" value="0">
                              <input type="hidden" class="form-control form-control-md" id="sisa_bayar" value="{{old('sisa')}}" name="sisa" readonly>
                             </div>
                          </div>
                        </div>
                        <div  class="row col-lg-12 col-sm-6">
                          <div class="row col-lg-12 col-sm-6">
                            <label for="colFormLabelNama" class="col-lg-2 col-form-label col-form-label-md">Jumlah</label>
                              <div class="col-lg-7">
                               <input type="text" class="form-control form-control-md" value="{{old('jumlahTotal')}}" name="jumlahTotal" id="total-harga" placeholder="Jumlah" readonly>
                              </div>
                          </div>
                          </div>
                      </div>
                    </div>
                    {{-- ITEM --}}
                    <div class="card-body p-4">
                      <table class="invoice-table table table-borderless table-nowrap mb-0">
                     <div class="table-responsive">
                       <thead class="align-middle">
                        <tr class="table-active">
                         <th scope="col" style="width: 50px;">#</th>
                         <th scope="col">Keterangan</th>
                          
                         <th scope="col" style="width: 120px;">
                          <div class="d-flex currency-select input-light align-items-center">Harga
                           <select class="form-selectborder-0 bg-light" data-choices data-choices-search-false id="choices-payment-currency" onchange="otherPayment()">
                           <option value="Rp">(Rp)</option>
                           </select>
                          </div></th>
                         <th scope="col" style="width: 120px;">Quantity</th>
                         <th scope="col">Satuan</th>
                         <th scope="col" class="text-center" style="width: 150px;">Jumlah Harga</th>
                         <th scope="col" class="text-end" style="width: 105px;"></th>
                        </tr>
                       </thead>
                       <tbody id="newlink">
                        <tr id="1" class="product">
                         <th scope="row" class="product-id">1</th>
                          <td class="text-start">
                           <div class="mb-2">
                           <textarea class="form-control bg-light border-0" id="productDetails-1" value="{{old('deskripsi[]')}}" rows="1" name="deskripsi[]" placeholder="Deskripsi Item"></textarea>
                           </div>
                          </td>
                          <td>
                           <input type="text" class="form-control product-price bg-light border-0" id="productRate-1" step="0" placeholder="Rp. 0.000" value="{{old('harga[]')}}" name="harga[]" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' onchange="autoCalc(this)" required />
                            <div class="invalid-feedback">Tolong masukkan Harga
                            </div>
                           </td>
                           <td>
                            <div class="input-step">
                             <input type="text" class="product-quantity" id="product-qty-1" value="{{ old('qty[]') ? old('qty[]') : '1' }}" onchange="autoCalc(this)" name="qty[]">
                            </div>
                           </td>
                            <td>
                                    <select class="form-select @error ('satuan[]') is-invalid @enderror" id="satuan-1" name="satuan[]" required>
                                    <option value="">Pilih</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Set">Set</option>
                                    <option value="Und">Und</option>
                                    <option value="Blok">Blok</option>
                                    <option value="Rim">Rim</option>
                                    <option value="Lbr">Lbr</option>
                                    <option value="Ktk">Ktk</option>
                                    <option value="Unit">Unit</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Lainnya">Lainnya</option>
                                    </select>
                                    @error('satuan[]')
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                   </td>
                           <td class="text-end">
                            <div>
                             <input type="text" class="form-control bg-light border-0 product-line-price" value="{{old('jumlah[]')}}" name="jumlah[]" id="productPrice-1" placeholder="Rp.0.000" readonly/>
                            </div>
                           </td>
                           <td class="product-removal"><a href="javascript:void(0)" class="btn btn-success">Delete</a>
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
                    {{--  --}}
                  </div>
                  {{-- End Row Utama --}}
                  <div class="hstack gap-2 justify-content-start d-print-none mt-4">
                    <button type="submit" class="btn btn-primary fw-medium"><i class="ri-save-3-line me-1 align-bottom"></i>Simpan</button>
                  </div>
                {{-- End Row Input Barang --}}

              </div>
            </form>
            {{-- End Isi Form --}}
          </div>
        </div>
      </div>
      </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\PembelianRequest') !!}
@endsection
@section('plugins')

<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>

<script src="{{asset('js/halaman/pembelian.js')}}"></script>
@endsection
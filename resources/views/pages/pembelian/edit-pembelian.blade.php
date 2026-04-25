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
{{-- Modal --}}
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" id="deleteModal"  aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-body text-center p-5">
            <input type="hidden" name="anggaran_delete_id" id="anggaran_id">
            <lord-icon
            src="https://cdn.lordicon.com/tdrtiskw.json"
            trigger="loop"
            colors="primary:#eee966,secondary:#c71f16"
            state="hover-2"
            style="width:150px;height:150px">
            </lord-icon>
            <div class="mt-4">
              <h4 class="mb-3">Apakah Anda ingin menghapus pembelian dari vendor {{$pembelian->nama_vendor}}?</h4>
              <div class="hstack gap-2 justify-content-center">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                  <form id="deleteForm" action="{{ route('pembelian.delete', ['uid' => $pembelian->id]) }}" method="POST">
                    @csrf
                    @method('delete')

                    <button type="submit" class="btn btn-danger">
                      <i class="las la-trash-alt fs-18 align-middle me-2"></i>
                      Delete
                    </button>
                    </form>

              </div>
          </div>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    <div class="page-content">
      <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Edit Pembelian</h4>

                  
                  <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Vendor</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pembelian.list', ['uid' => $pembelian->id_vendor]) }}">Pembelian Vendor {{ $vendor->nama_vendor }}</a></li>
                        <li class="breadcrumb-item active">Edit Pembelian</li>
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
            <form action="{{route('pembelian.update',['uid'=>$pembelian->id])}}" class="needs-validation" method="POST" novalidate>
              @csrf
              @method('put')
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
                            <option selected value="{{$anggaranNow->id}}">{{$anggaranNow->nama_budget}} - Rp.{{number_format($anggaranNow->anggaran,0,',',',')}}</option>
                            @foreach ($anggaran as $data)
                            @if ($data->id != $anggaranNow->id)
                              <option value="{{$data->id}}">{{$data->nama_budget}} - Rp.{{number_format($data->anggaran,0,',',',')}}</option>
                            @endif
                            @endforeach
                          </select>
                         </div>
                        </div>

                      {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-4 col-form-label">Nama Vendor</label>
                          <div class="col-lg-7 mb-2">
                            <input type="text" class="form-control form-control-md" name="vendor" placeholder="Nama Vendor" value="{{$vendor->nama_vendor}}" readonly>
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Nomor Invoice</label>
                              <div class="col-lg-7">
                               <input type="text" class="form-control form-control-md" name="no_inv" value="{{$pembelian->nomor_inv}}" id="colFormLabelNama" placeholder="Nomor Invoice">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Tanggal Pembelian</label>
                              <div class="col-lg-7">
                                <input type="date" name="tgl" value="{{$pembelian->tanggal}}" class="form-control" data-provider="flatpickr" id="dateInput" placeholder=" YYYY/MM/DD">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="tglJTO" class="col-lg-4 col-form-label col-form-label-md">Tanggal JTO</label>
                              <div class="col-lg-7">
                                <input type="date" name="tgl_jto" value="{{$pembelian->tgl_jto}}" class="form-control" data-provider="flatpickr" id="dateInput" placeholder=" YYYY/MM/DD">
                              </div>
                          </div>
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="colFormLabelNama" class="col-lg-4 col-form-label col-form-label-md">Status Pembelian</label>
                            <div class="col-lg-7 input-light mb-2">
                              <select class="form-select" id="statuss" name="status" required>
                                <option selected value="{{$pembelian->status}}">{{$pembelian->status}}</option>
                                @foreach (['Lunas','Belum Lunas'] as $option)
                                    @if ($option !== $pembelian->status)
                                    <option value="{{$option}}">{{$option}}</option>

                                    @endif
                                @endforeach
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
                              <input type="text" value="{{$pembelian->terbayar}}" id="input_bayar" class="form-control form-control-md" name="terbayar">
                              <input type="hidden" value="{{$pembelian->sisa}}" class="form-control form-control-md"  id="sisa_bayar" name="sisa" readonly>
                             </div>
                          </div>
                        </div>
                        <div  class="row col-lg-12 col-sm-6">
                          <div class="row col-lg-12 col-sm-6">
                            <label for="colFormLabelNama" class="col-lg-2 col-form-label col-form-label-md">Jumlah</label>
                              <div class="col-lg-7">
                               <input type="text" class="form-control form-control-md" name="jumlahTotal" id="total-harga" value="{{$pembelian->jumlah_harga}}" placeholder="Jumlah" readonly>
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
                        @foreach ($pembelianBrg as $index => $beli)
                        <tr id="{{$index + 1}}" class="product">
                         <th scope="row" class="product-id">{{$index + 1}}</th>
                          <td class="text-start">
                           <div class="mb-2">
                           <textarea class="form-control bg-light border-0" id="productDetails-{{$index + 1}}" rows="1" name="deskripsi[]" placeholder="Deskripsi Item">{{$beli->deskripsi}}</textarea>
                           </div>
                          </td>
                          <td>
                           <input type="text" class="form-control product-price bg-light border-0" id="productRate-{{$index + 1}}" step="0" placeholder="Rp. 0.000" name="harga[]" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' onchange="autoCalc(this)" value="{{$beli->harga_barang}}" required />
                            <div class="invalid-feedback">
                            </div>
                           </td>
                           <td>
                            <div class="input-step">
                             <input type="text" class="product-quantity" id="product-qty-{{$index + 1}}" onchange="autoCalc(this)" name="qty[]" value="{{$beli->qty}}">
                            </div>
                           </td>
                           <td>
                            <select class="form-control bg-light border-0 @error ('satuan[]') is-invalid @enderror" data-choices data-choices-search-false id="satuan-{{$index+1}}" name="satuan[]" required>
                                    
                                    <option value="{{$beli->satuan}}"selected>{{$beli->satuan}}</option>
                                    @foreach(['Pcs', 'Set', 'Und', 'Blok', 'Rim', 'Lbr', 'Ktk', 'Unit', 'Kg', 'Lainnya', 'Pax'] as $option)
                                        @if($option !== $beli->satuan)
                                            <option value="{{$option}}">{{$option}}</option>
                                        @endif
                                    @endforeach
                                    <!--<option value=""disabled>Pilih</option>-->
                                    <!--<option value="Pcs">Pcs</option>-->
                                    <!--<option value="Set">Set</option>-->
                                    <!--<option value="Und">Und</option>-->
                                    <!--<option value="Blok">Blok</option>-->
                                    <!--<option value="Rim">Rim</option>-->
                                    <!--<option value="Lbr">Lbr</option>-->
                                    <!--<option value="Ktk">Ktk</option>-->
                                    <!--<option value="Unit">Unit</option>-->
                                    <!--<option value="Kg">Kg</option>-->
                                    <!--<option value="Lainnya">Lainnya</option>-->
                                    </select>
                                    @error('satuan[]')
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                   </td>
                           <td class="text-end">
                            <div>
                             <input type="text" class="form-control bg-light border-0 product-line-price" name="jumlah[]" id="productPrice-{{$index + 1}}" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' value="{{$beli->total}}" placeholder="Rp.0.000" readonly/>
                            </div>
                           </td>
                           <td class="product-removal"><a href="javascript:void(0)" class="btn btn-success">Delete</a>
                           </td>
                          </tr>
                          @endforeach
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
                    <button type="submit" class="btn btn-primary fw-medium"><i class="ri-save-3-line me-1 align-bottom"></i>Update</button>

                    <a data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger fw-medium hapus-btn mx-2" value="{{$pembelian->id}}"><i class="ri-eraser-line  me-1 align-bottom"></i>Hapus</a>
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

<script src="{{asset('js/halaman/update-pembelian.js')}}"></script>
@endsection
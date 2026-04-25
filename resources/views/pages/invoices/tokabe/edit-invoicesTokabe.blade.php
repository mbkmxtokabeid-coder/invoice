@section('head')
<!-- dropzone css -->

<link rel="stylesheet" href="{{asset('libs/dropzone/dropzone.css')}}" type="text/css" />

<!-- Sweet Alert css-->
<link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
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
                                <h4 class="mb-sm-0">Edit Invoice</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice</a></li>
                                        <li class="breadcrumb-item active">Edit Invoice</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row justify-content-center">
                       <div class="col-xxl-12">
                         <div class="card">
                           <form action="{{ route('update-invoiceTokabe',['id'=> $inv->id]) }}" method ="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                             <div class="card-body border-bottom border-bottom-dashed p-4">
                               <div class="row">
                                 <div class="col-lg-6">
                                   <div class="row g-3 mb-2">
                                       <!--end col-->

                                      <div class="col-lg-8 col-sm-6">
                                         <label for="choices-invoice">Invoice</label>
                                            <div class="input-light">
                                               <select  data-choices data-choices-search-false
                                               class="form-control bg-light border-0 @error('inv') is-invalid @enderror"id="choices-invoice" name="inv" required>
                                                {{-- Start Pilihan Jenis Invoice --}}
                                                <option value="{{$invoice->id}}">{{$invoice->nama_invoice}}</option>

                                                {{-- End Pilihan Jenis Invoice --}}
                                                </select>

                                            </div>
                                      </div>

                                         {{-- End coloumn --}}
                                   </div>
                                      <div class="row mb-2 col-lg-8 col-sm-6">
                                          <label for="invoicenoInput">Invoice No</label>
                                          
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control bg-light border-0" id="noInvoice" placeholder="kode Unik" name="kodeUnik" value="{{$nomor_unik}}" readonly="readonly">
                                          </div>
                                      </div>
                                        <!--end col-->
                                      <div class="row mb-2 col-lg-9">

                                          <label for="date-field">Tanggal Penjualan</label>
                                          <div class="col-lg-4">
                                            <input type="text" class="form-control bg-light border-0 flatpickr-input" id="date-field" name="tgl_jual" data-provider="flatpickr" value="{{$inv->tgl_penjualan}}" readonly>
                                          </div>
                                          <div class="col-lg-6">
                                            <input type="text" class="form-control bg-light border-0" placeholder="H:i" name="jam" value="{{$jam}}" readonly="readonly">
                                          </div>

                                        </div>
                                        <!--end col-->


                                        <!--end col-->
                                    {{-- </div> --}}

                                 </div>
                                  <!--end col-->
                                 <div class="col-lg-4 ms-auto">
                                  <div class="profile-user mx-auto  mb-3">
                                   <input id="profile-img-file-input" type="file" class="profile-img-file-input"/>
                                   <label for="profile-img-file-input" class="d-block" tabindex="0">
                                    <span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style="width: 256px;">
                                       <img src="{{asset('images/LogoTKB.png')}}" class="card-logo card-logo-dark user-profile-image img-fluid" alt="logo dark">
                                      <img src="{{asset('images/LogoTKB.png')}}" class="card-logo card-logo-light user-profile-image img-fluid" alt="logo light">
                                    </span>
                                   </label>
                                  </div>
                                 </div>
                                {{-- Customer Form --}}
                                 <div class="row mt-2">
                                  <div class="col-xxl-5">
                                  </div>
                                  <div class="col-xxl-7">
                                   {{-- <div class="card no-border border-0"> --}}
                                    <div class="row mb-3">
                                     <label for="customer" class="col-lg-5 col-form-label col-form-label-md">Nama Customer</label>
                                       <div class="col-lg-7">
                                        <input type="text" class="form-control form-control-md @error('pelanggan') is-invalid @enderror" name="pelanggan" id="customer" placeholder="Nama Customer"value="{{ $inv->customer }}">
                                        @error('pelanggan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>
                                      </div>
                                      <div class="row mb-4">
                                       <label for="usaha" class="col-lg-5 col-form-label col-form-label-md">Nama Perusahaan / Instansi</label>
                                       <div class="col-lg-7">
                                        <input type="text" class="form-control form-control-md  @error('perusahaan') is-invalid @enderror " id="usaha" name="perusahaan" placeholder="Nama Perusahaan / instansi" value="{{ $perusahaan }}">
                                        @error('perusahaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>
                                      </div>
                                         {{-- Start No.Telepon --}}
                                      <div class="row mb-3" style="margin-top: -10px;">
                                       <div class="col-lg-5">
                                        <label for="contactNumber" class="form-label col-form-label col-form-label-md">Nomor Telepon</label>
                                       </div>
                                       <div class="col-lg-7">
                                        <input type="text" class="form-control @error('tlp') is-invalid @enderror" id="phone-number" name="tlp" placeholder="08xxxxxxxx" value="{{ $inv->no_telepon }}">
                                        @error('tlp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>
                                      </div>
                                      {{-- End No.Telepon --}}
                                      {{-- Start Admin --}}
                                      <div class="row mb-3">
                                       <div class="col-lg-5">
                                        <label for="choices-admin" class="form-label col-form-label col-form-label-md">Nama Admin</label>
                                       </div>
                                       {{-- test --}}

                                       <div class="col-lg-7">
                                        <select class="form-control bg-light border-0 @error('adm') is-invalid @enderror" name="adm" data-choices data-choices-search-false id="choices-admin" required>
                                          <option value="{{ $inv->admin }}" selected>{{$nama_adm}}</option>
                                          @foreach ($admin as $adm)
                                          @if ($adm->id != $inv->admin)
                                             <option value="{{$adm->id}}">{{$adm->nama}}</option>
                                          @endif
                                         @endforeach
                                        </select>
                                        @error('adm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>

                                      </div>
                                      {{-- End Admin --}}
                                      {{-- Start Order By --}}
                                      <div class="row mb-3">
                                       <div class="col-lg-5">
                                          <label for="choices-orderBy" class="form-label col-form-label col-form-label-md">Order Dari</label>
                                       </div>
                                       <div class="col-lg-7">
                                        <select class="form-control bg-light border-0 @error('order') is-invalid @enderror" data-choices data-choices-search-false  id="choices-orderBy" name="order" required>
                                        <option value="{{ $inv->order_by }}" selected>{{ $inv->order_by }}</option>
                                        @foreach ($order as $ord)

                                        @if ($inv->order_by != $ord->order_by)
                                        <option value="{{ $ord->order_by }}">{{ $ord->order_by }}</option>
                                        @endif
                                        @endforeach
                                        </select>
                                        @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>
                                      </div>
                                      {{-- End Order By--}}
                                      {{-- start Hide div --}}
                                        <div id="divSales"style="display: none; margin-bottom: -5px;">
                                          <div class="row mb-3">
                                            <div class="col-lg-5">
                                              <label for="colFormLabelNamaSales" class="col-form-label col-form-label-md">Nama Sales</label>
                                            </div>
                                            <div class="col-lg-7" style="margin-top: -10px;">
                                                <input type="text" class="form-control form-control-md mt-2 @error('sales') is-invalid @enderror" id="colFormLabelNamaSales" placeholder="Nama Sales" name="sales" value="{{ $sales }}">
                                                @error('sales')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                          </div>
                                        </div>
                                      {{-- end Hide div --}}
                                      {{-- Start Date --}}
                                      <div class="row mb-3">
                                       <div class="col-lg-5">
                                        <label for="dateInput" class="form-label col-form-label col-form-label-md">Target Penyelesaian  </label>
                                       </div>
                                       <div class="col-lg-7">
                                        <input type="date" class="form-control @error ('tgl_selesai') is-invalid @enderror" data-provider="flatpickr"  name="tgl_selesai" id="dateInput" placeholder=" YYYY/MM/DD" value="{{ $inv->tgl_selesai }}">
                                        @error('tgl_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>
                                      </div>
                                     {{-- End Date --}}
                                   {{-- </div> --}}
                                  </div>
                                 </div>
                                        {{-- End Customer Form --}}
                                   <!--end row-->
                             <div class="card-body p-4">

                                 <!-- TEMPLATE TERSEMBUNYI UNTUK OPTION MATERIAL DAN BARANG -->
                                 <div id="template-options-material" style="display: none;">
                                    <option value="">-- Pilih Material (Opsional) --</option>
                                    @if(isset($materials))
                                      @foreach ($materials as $mat)
                                          <option value="{{ $mat->id }}" data-stok="{{ $mat->stok }}" data-satuan="{{ $mat->satuan }}">
                                              {{ $mat->kode_material }} - {{ $mat->jenis_material }} (Stok Max: {{ floor($mat->stok) == $mat->stok ? number_format($mat->stok, 0, '', '') : $mat->stok }})
                                          </option>
                                      @endforeach
                                    @endif
                                 </div>
                                 <div id="template-options-barang" style="display: none;">
                                    <option selected disabled>Pilih Item</option>
                                    @foreach ($jenisBarang as $jns)
                                        <option value="{{$jns->id}}" data-kategori-id="{{$jns->kategori_id}}">{{$jns->jenis_barang}}</option>
                                    @endforeach
                                 </div>
                                 <!-- END TEMPLATE -->

                               <table class="invoice-table  dt-responsive table table-borderless table-nowrap mb-0">
                              <div class="table-responsive">
                                <thead class="align-middle">
                                 <tr class="table-active">
                                  <th scope="col" style="width: 50px;">#</th>
                                  <th scope="col" style="min-width: 500px;">Item</th>
                                  <th scope="col">Satuan</th>
                                  <th scope="col" style="width: 120px;">
                                   <div class="d-flex currency-select input-light align-items-center">Harga
                                    <select class="form-selectborder-0 bg-light" data-choices data-choices-search-false id="choices-payment-currency" onchange="otherPayment()">
                                    <option value="Rp">(Rp)</option>
                                    </select>
                                   </div></th>
                                  <th scope="col" style="width: 120px;">Quantity</th>
                                  <th scope="col" class="text-center" style="width: 150px;">Jumlah Harga</th>
                                  <th scope="col" class="text-end" style="width: 105px;"></th>
                                 </tr>
                                </thead>
                                <tbody id="newlink">
                                  @foreach ($penjualan_barang as $index => $jual)
                                  @foreach ($barang[$index] as $item)
                                 <tr id="{{$index + 1}}" class="product">
                                  <th scope="row" class="product-id">{{$index + 1}}</th>
                                   <td class="text-start">
                                    <div class="mb-2">
                                      <label class="visually-hidden" for="productName">Item</label>

                                      {{-- PENTING: NAME MENGGUNAKAN EXPLICIT INDEX AGAR ARRAY TIDAK TERTUKAR SAAT DISUBMIT --}}
                                      <select class="form-select @error('barang_id.'.$index) is-invalid @enderror" data-choices data-choices-sorting="true" id="productName-{{$index+1}}" name="barang_id[{{$index}}]" >
                                          <option value="{{ $item->id }}" data-kategori-id="{{$item->kategori_id}}">{{ $item->jenis_barang }}</option>
                                          @foreach ($jenisBarang as $jns)
                                            @if ($item->id != $jns->id)
                                            <option value="{{$jns->id}}" data-kategori-id="{{$jns->kategori_id}}">{{$jns->jenis_barang}}</option>
                                            @endif
                                          @endforeach
                                      </select>
                                      @error('barang_id.'.$index)
                                      <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                  </div>
                                     <textarea class="form-control bg-light border-0 @error('deskripsi_item.'.$index) is-invalid @enderror" id="productDetails-{{$index+1}}" name="deskripsi_item[{{$index}}]" rows="2" placeholder="Deskripsi Item" >{{$jual->deskripsi_item}}</textarea>
                                     @error('deskripsi_item.'.$index)
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror

                                     <!-- START KOLOM MULTI-MATERIAL -->
                                     <div class="material-container-{{$index}} mt-2">
                                         @php
                                             // CEK APAKAH DATA SUDAH ARRAY ATAU MASIH STRING JSON
                                             $rawMatId = $jual->material_id;
                                             $matIds = is_string($rawMatId) ? json_decode($rawMatId, true) : $rawMatId;
                                             
                                             $rawMatQty = $jual->material_qty;
                                             $matQtys = is_string($rawMatQty) ? json_decode($rawMatQty, true) : $rawMatQty;
                                             
                                             $rawMatPanjang = $jual->material_panjang;
                                             $matPanjangs = is_string($rawMatPanjang) ? json_decode($rawMatPanjang, true) : $rawMatPanjang;
                                             
                                             $rawMatLebar = $jual->material_lebar;
                                             $matLebars = is_string($rawMatLebar) ? json_decode($rawMatLebar, true) : $rawMatLebar;

                                             // Fallback Jika Data Lama (bukan JSON/Array, melainkan ID string tunggal)
                                             if (!is_array($matIds) && !empty($rawMatId)) {
                                                 $matIds = [$rawMatId];
                                                 $matQtys = [$rawMatQty];
                                                 $matPanjangs = [$rawMatPanjang];
                                                 $matLebars = [$rawMatLebar];
                                             } elseif (!is_array($matIds)) {
                                                 $matIds = []; 
                                             }

                                             // Siapkan setidaknya 1 form kosong jika tidak ada material
                                             if (empty($matIds)) {
                                                 $matIds = [null];
                                             }
                                         @endphp

                                         @foreach($matIds as $matIndex => $matId)
                                             @php
                                                 $matQty = is_array($matQtys) ? ($matQtys[$matIndex] ?? '') : '';
                                                 $matPanjang = is_array($matPanjangs) ? ($matPanjangs[$matIndex] ?? '') : '';
                                                 $matLebar = is_array($matLebars) ? ($matLebars[$matIndex] ?? '') : '';
                                                 $currentMax = '';
                                                 $currentSatuan = '';
                                                 $sisaStok = '';

                                                 if ($matId && isset($materials)) {
                                                     foreach($materials as $m) {
                                                         if($m->id == $matId) {
                                                             $currentMax = $m->stok + (float)$matQty;
                                                             $currentSatuan = $m->satuan;
                                                             $sisaStok = $currentMax - (float)$matQty;
                                                             
                                                             if (floor($sisaStok) == $sisaStok) {
                                                                 $sisaStok = number_format($sisaStok, 0, '', '');
                                                             }
                                                             break;
                                                         }
                                                     }
                                                 }
                                             @endphp
                                             <div class="row mt-2 material-row border-top pt-2 position-relative">
                                                 <div class="col-md-4">
                                                     <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                                                     <select class="form-select form-select-sm material-select" name="material_id[{{$index}}][]">
                                                         <option value="">-- Pilih Material (Opsional) --</option>
                                                         @if(isset($materials))
                                                             @foreach ($materials as $mat)
                                                                 @php
                                                                     $maxStok = $mat->stok;
                                                                     $isSelected = '';
                                                                     if($matId == $mat->id) {
                                                                         $maxStok = $mat->stok + (float)$matQty;
                                                                         $isSelected = 'selected';
                                                                     }
                                                                 @endphp
                                                                 <option value="{{ $mat->id }}"
                                                                         data-stok="{{ $maxStok }}"
                                                                         data-satuan="{{ $mat->satuan }}"
                                                                         {{ $isSelected }}>
                                                                     {{ $mat->kode_material }} - {{ $mat->jenis_material }} (Stok Max: {{ floor($maxStok) == $maxStok ? number_format($maxStok, 0, '', '') : $maxStok }})
                                                                 </option>
                                                             @endforeach
                                                         @endif
                                                     </select>
                                                 </div>
                                                 <div class="col-md-2">
                                                     <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Panjang</label>
                                                     <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[{{$index}}][]" placeholder="0" value="{{ $matPanjang }}">
                                                 </div>
                                                 <div class="col-md-2">
                                                     <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Lebar</label>
                                                     <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[{{$index}}][]" placeholder="0" value="{{ $matLebar }}">
                                                 </div>
                                                 <div class="col-md-2">
                                                     <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                                                     <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[{{$index}}][]" placeholder="0" min="0" max="{{ $currentMax }}" value="{{ $matQty }}" readonly>
                                                     <span class="text-danger fw-medium sisa-stok-info" style="font-size: 0.75rem; {{ $matId ? '' : 'display: none;' }}">Sisa: <span class="sisa-stok-angka">{{ $sisaStok }}</span></span>
                                                 </div>
                                                 <div class="col-md-2">
                                                     <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                                                     <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[{{$index}}][]" value="{{ $currentSatuan }}" readonly>
                                                 </div>
                                                 <div class="col-md-12 text-end mt-2 mb-2">
                                                    <button type="button" class="btn btn-sm btn-soft-danger btn-hapus-material"><i class="las la-times"></i> Hapus Baris</button>
                                                 </div>
                                             </div>
                                         @endforeach
                                     </div>
                                     <div class="mt-2 text-end">
                                         <button type="button" class="btn btn-sm btn-soft-info btn-tambah-material" data-index="{{$index}}"><i class="las la-plus"></i> Tambah Material</button>
                                     </div>
                                     <!-- END KOLOM MULTI-MATERIAL -->

                                   </td>
                                   <td>
                                    <select class="form-control bg-light border-0 @error ('satuan.'.$index) is-invalid @enderror" data-choices data-choices-search-false id="satuan-{{$index+1}}" name="satuan[{{$index}}]" required>
                                    
                                    <option value="{{$jual->satuan}}"selected>{{$jual->satuan}}</option>
                                    @foreach(['Pcs', 'Set', 'Und', 'Blok', 'Rim', 'Lbr', 'Ktk', 'Unit', 'Kg', 'Lainnya', 'Pax'] as $option)
                                        @if($option !== $jual->satuan)
                                            <option value="{{$option}}">{{$option}}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                    @error('satuan.'.$index)
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                   </td>
                                   <td>
                                    <input type="text" class="form-control product-price bg-light border-0 @error('hrg.'.$index) is-invalid @enderror" id="productRate-{{$index+1}}" name="hrg[{{$index}}]" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' placeholder="Rp. 0.000" onchange="autoCalc(this)" value="{{$jual->hargaBarang}}" required/>
                                    @error('hrg.'.$index)
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror

                                    </td>
                                    <td>
                                     <div class="input-step">
                                      <input type="number" class="text-center @error('qty.'.$index) is-invalid @enderror" id="product-qty-{{$index+1}}" value="{{$jual->qty}}" name="qty[{{$index}}]" onchange="autoCalc(this)">
                                      @error('qty.'.$index)
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                     </div>
                                    </td>
                                    <td class="text-end">
                                     <div>
                                      <input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-{{$index+1}}" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' name="jlh_hrg[{{$index}}]" placeholder="Rp.0.000" value="{{$jual->jumlah_harga}}" onchange="autoCalc(this)"readonly/>
                                     </div>
                                    </td>
                                    <td class="product-removal"><a href="javascript:void(0)" class="btn btn-success">Delete</a>
                                    </td>
                                   </tr>
                                   @endforeach
                                   @endforeach
                                  </tbody>

                                  <tbody>
                                   <tr id="newForm" style="display: none;">
                                    <td class="d-none" colspan="5"><p>Add New Form</p>
                                    </td>
                                   </tr>
                                   <tr>
                                    <td colspan="2"><a href="javascript:new_link()" id="add-item" class="btn btn-soft-secondary fw-medium"><i class="ri-add-fill me-1 align-bottom"></i> Add Item</a>
                                    </td>
                                   </tr>
                                  </tbody>
                                 </table>
                                 <!--end table-->
                                 {{-- Start detail Payment --}}
                                <div class="col-lg-4">
                                  <div class="col-lg-12"></div>
                                </div>
                                <div class="col-lg-8">
                                  <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="jenisPembayaran" class="form-label col-form-label col-form-label-md">Jenis Pembayaran</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <select class="form-control bg-light border-0 @error('jns_pem') is-invalid @enderror" data-choices data-choices-search-false data-choices-removeItem id="choices-payment-type" name="jns_pem">
                                          <option selected value="{{ $inv->jenis_pembayaran }}">{{ $inv->jenis_pembayaran }}</option>
                                          @foreach (['Cash Lunas', 'Cash Belum Lunas', 'Transfer DP', 'Transfer Lunas', 'PO'] as $option)
                                            @if ($option !== $inv->jenis_pembayaran)
                                              <option value="{{ $option }}">{{ $option }}</option>
                                            @endif
                                          @endforeach
                                        </select>

                                        @error('jns_pem')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                    </div>
                                    <div class="row mb-2 col-lg-12">
                                      <div class="col lg-12" style="{{ ($inv->no_rek !== null) ? 'display: block' : 'display: none' }};">
                                        <!--<div class="form-check mb-2 col-lg-12" id="alamatPembayaran" >-->
                                        <!--  <input class="form-check-input " type="radio" name="norek" id="flexRadioDefault1" value="BNI" {{ ($inv->no_rek ==='BNI') ? 'checked' : '' }}>-->
                                        <!--  <label class="form-check-label" for="flexRadioDefault1">-->
                                        <!--    BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999-->
                                        <!--  </label>-->
                                        <!--  </div>-->
                                          <div class="form-check mb-2 col-lg-12" id="alamatPembayaran2" >
                                          <input class="form-check-input " type="radio" name="norek" id="flexRadioDefault2" value="TKB" {{ ($inv->no_rek ==='TKB') ? 'checked' : '' }}>
                                          <label class="form-check-label" for="flexRadioDefault2">
                                            BSI | A/N : PT. Total Karya Berkah | No. Rek :  3557999999
                                          </label>
                                          </div>
                                          
                                      </div>
                                    </div>
                                  <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="total-harga" class="form-label col-form-label col-form-label-md">Jumlah Harga</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <input type="text" class="form-control bg-light border-0" rows="1" naplaceholder="" id="total-harga" name ="tot_harga" value="{{ $inv->total_harga }}"readonly >
                                      </div>
                                    </div>
                                    {{-- Input untuk Dp --}}
                                    <div class="row mb-1"  >
                                      <div class="col-lg-4" id="div-label" style="display: block" >
                                        <label for="dp" class="form-label col-form-label col-form-label-md" id="label-dp">Dp</label>
                                      </div>
                                      <div class="col-lg-6" id="dp" style="display: block;">
                                        @if (!empty($inv->dp))
                                          <input id="input-dp" name="dp" type="text" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }'  class="form-control bg-light border-0" rows="1" placeholder="Rp. 0,000" value="{{ $inv->dp }}">
                                        @else
                                          <input id="input-dp" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' name="dp" type="text" class="form-control bg-light border-0" rows="1" placeholder="Rp. 0,000">
                                        @endif
                                      </div>

                                      </div>
                                      {{-- End Input --}}
                                  <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="potongan" class="form-label col-form-label col-form-label-md">Diskon/Potongan/PPN</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <select class="form-control bg-light border-0" name="select_potongan" data-choices data-choices-search-false data-choices-removeItem id="choices-potongan">
                                          <option value="-" {{ $inv->diskon || $inv->potongan != 0|| $inv->ppn ? '' : 'selected' }}>-</option>
                                          <option value="Diskon" {{ $inv->diskon ? 'selected' : '' }}>Diskon</option>
                                          <option value="Potongan" {{ $inv->potongan ? 'selected' : '' }}>Potongan Harga</option>
                                          <option value="PPN" {{ $inv->ppn ? 'selected' : '' }}>PPN</option>
                                        </select>
                                      </div>
                                    </div>
                                  <div class="row mb-1"  >
                                    <div class="col-lg-4" >
                                    </div>
                                    <div class="col-lg-6" id="harga-potongan" style="display: block;">
                                      <input id="input-potongan" type="text" class="form-control bg-light border-0" rows="1" name="biaya_lain" value="{{ $inv->diskon ? $inv->diskon : ($inv->potongan != 0 ? $inv->potongan : $inv->ppn) }}" placeholder="Rp. 0,000" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' >
                                    </div>
                                    </div>
                                    <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="totalPembayaran" class="form-label col-form-label col-form-label-md">Total Pembayaran</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <input type="text" class="form-control bg-light border-0" rows="1" placeholder="Total Pembayaran" id="total-pembelian" name="tot_pem" value="{{ old('tot_pem') }}" readonly>
                                      </div>
                                    </div>
                                    <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="sisaPembayaran" class="form-label col-form-label col-form-label-md">Sisa Pembayaran</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <input type="text" class="form-control bg-light border-0" rows="1" placeholder="Sisa Pembayaran" id="sisa-pembayaran" name="sisa_pem" value="{{$inv->sisa_pembayaran}}" readonly>
                                      </div>
                                    </div>
                                </div>
                               </div>
                               <!--end row-->
                               <div class="hstack gap-2 justify-content-start d-print-none mt-4">
                                <button type="submit" id="update" class="btn btn-info"><i class="ri-printer-line align-bottom me-1"></i>Update</button>
                                <a href="{{route('daftar_invoice')}}" class="btn btn-warning"><i class=" ri-arrow-go-back-line align-bottom me-1"></i>Kembali</a>
                               </div>
                             </div>
                            </form>
                           </div>
                          </div>
                         </div>
                        </div>
                        <!--end col-->
                      </div>
                    <!--end row-->
                <!-- container-fluid -->

              {{-- @extends('layout.vendor-script') --}}
<script>
  // var jenisBarang = @json($jenisBarang);
</script>

{!! JsValidator::formRequest('App\Http\Requests\InvoiceRequest') !!}
@endsection

@section('plugins')
<!-- dropzone min -->
<script src="{{asset('libs/dropzone/dropzone-min.js')}}"></script>

<!-- cleave.js -->
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>


<!-- Sweet Alerts js -->
<script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>

{{-- choices.js & flatpickr --}}
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('js/halaman/update-invoice.js')}}"></script>
<script src="{{asset('js/halaman/invoice.js')}}"></script>

<script>
    // Logic untuk Material Dropdown (Auto-fill Satuan, Validasi Stok & Tampilkan Sisa Awal)
    $(document).on('change', '.material-select', function(e, isInitialLoad) {
        var selectedOption = $(this).find(':selected');
        var satuan = selectedOption.data('satuan');
        var stok = selectedOption.data('stok'); // Stok riil maksimum yg bisa diambil
        
        // Cari input terkait dalam row yang sama
        var row = $(this).closest('.material-row'); 
        row.find('.material-satuan').val(satuan);
        row.find('.material-qty').attr('max', stok);
        
        if (!isInitialLoad) {
            row.find('.material-qty').val(''); // Reset qty saat ganti material
            row.find('.material-panjang').val('');
            row.find('.material-lebar').val('');
        }
        
        // Tampilkan label sisa stok tanpa nol tidak penting
        if(stok !== undefined && stok !== "") {
            row.find('.sisa-stok-info').show();
            row.find('.sisa-stok-angka').text(parseFloat(stok));
            
            if (row.find('.material-qty').val()) {
                row.find('.material-qty').trigger('input');
            }
        } else {
            row.find('.sisa-stok-info').hide();
        }
    });

    $(document).ready(function() {
        $('.material-select').each(function() {
            if ($(this).val() !== '') {
                $(this).trigger('change', [true]); 
            }
        });
    });

    // Auto Hitung "Hasil" berdasarkan input Panjang dan Lebar
    $(document).on('input', '.material-panjang, .material-lebar', function() {
        var row = $(this).closest('.material-row');
        var panjang = parseFloat(row.find('.material-panjang').val());
        var lebar = parseFloat(row.find('.material-lebar').val());
        
        // Cek jika Panjang dan Lebar bukan kosong (NaN)
        if (!isNaN(panjang) && !isNaN(lebar)) {
            var hasil = panjang * lebar;
            // Masukkan nilai hasil dan trigger input untuk validasi pemotongan stok
            row.find('.material-qty').val(parseFloat(hasil.toFixed(4))).trigger('input');
        }
    });

    // Validasi Qty/Hasil Input tidak boleh melebihi stok & Update Angka Sisa Secara Realtime
    $(document).on('input', '.material-qty', function() {
        var max = parseFloat($(this).attr('max'));
        var val = parseFloat($(this).val()) || 0; // Jika kosong anggap 0
        var row = $(this).closest('.material-row');
        
        if (!isNaN(max)) {
            if (val > max) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Kurang',
                    text: 'Jumlah material (Hasil) melebihi stok (Maksimal: ' + parseFloat(max) + ')',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $(this).val(parseFloat(max));
                val = max; // Set val ke max agar sisa tidak minus
            }
            
            // Hitung dan update tampilan sisa. ParseFloat membunuh angka nol di belakang koma (misal: 10.00 -> 10)
            var sisa = max - val;
            row.find('.sisa-stok-angka').text(parseFloat(sisa.toFixed(4))); 
        }
    });

    // Script Tambah Form Row Material Secara Dinamis dalam 1 Barang
    $(document).on('click', '.btn-tambah-material', function() {
        var index = $(this).data('index');
        var container = $(this).closest('td').find('.material-container-' + index);
        
        var materialOptionsDOM = document.getElementById('template-options-material');
        var materialOptions = materialOptionsDOM ? materialOptionsDOM.innerHTML : '<option value="">-- Pilih Material (Opsional) --</option>';

        var newRow = `
            <div class="row mt-2 material-row border-top pt-2 position-relative">
                 <div class="col-md-4">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                    <select class="form-select form-select-sm material-select" name="material_id[${index}][]">
                        ${materialOptions}
                    </select>
                 </div>
                 <div class="col-md-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Panjang</label>
                    <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[${index}][]" placeholder="0">
                 </div>
                 <div class="col-md-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Lebar</label>
                    <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[${index}][]" placeholder="0">
                 </div>
                 <div class="col-md-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                    <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[${index}][]" placeholder="0" min="0" readonly>
                    <span class="text-danger fw-medium sisa-stok-info" style="font-size: 0.75rem; display: none;">Sisa: <span class="sisa-stok-angka">0</span></span>
                 </div>
                 <div class="col-md-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                    <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[${index}][]" readonly>
                 </div>
                 <div class="col-md-12 text-end mt-2 mb-2">
                    <button type="button" class="btn btn-sm btn-soft-danger btn-hapus-material"><i class="las la-times"></i> Hapus Baris</button>
                 </div>
            </div>
        `;
        container.append(newRow);
    });

    // Menghapus baris form material
    $(document).on('click', '.btn-hapus-material', function() {
        $(this).closest('.material-row').remove();
    });


    // OVERRIDE FUNGSI new_link DARI update-invoice.js / tabel-item.js
    var count = document.querySelectorAll('#newlink tr.product').length;
    
    window.new_link = function() {
        var tr = document.createElement("tr");
        tr.id = count + 1;
        tr.className = "product";
        
        // 1. Siapkan Opsi Material (dari PHP ke JS)
        var materialOptionsDOM = document.getElementById('template-options-material');
        var materialOptions = materialOptionsDOM ? materialOptionsDOM.innerHTML : '<option value="">-- Pilih Material (Opsional) --</option>';

        // 2. Siapkan Opsi Item Barang (dari PHP ke JS)
        var itemOptionsDOM = document.getElementById('template-options-barang');
        var itemOptions = itemOptionsDOM ? itemOptionsDOM.innerHTML : '<option selected disabled>Pilih Item</option>';

        // 3. Template HTML Baris Baru
        var template = `
            <th scope="row" class="product-id">${count + 1}</th>
            <td class="text-start">
                <div class="mb-2">
                    <select class="form-select" name="barang_id[${count}]" id="productName-${count + 1}">
                        ${itemOptions}
                    </select>
                </div>
                <textarea class="form-control bg-light border-0" id="productDetails-${count + 1}" name="deskripsi_item[${count}]" rows="2" placeholder="Deskripsi Item"></textarea>
                
                <!-- KOLOM MULTI-MATERIAL DI ITEM BARU -->
                <div class="material-container-${count} mt-2">
                    <div class="row mt-2 material-row border-top pt-2 position-relative">
                         <div class="col-md-4">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                            <select class="form-select form-select-sm material-select" name="material_id[${count}][]">
                                ${materialOptions}
                            </select>
                         </div>
                         <div class="col-md-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Panjang</label>
                            <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[${count}][]" placeholder="0">
                         </div>
                         <div class="col-md-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Lebar</label>
                            <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[${count}][]" placeholder="0">
                         </div>
                         <div class="col-md-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                            <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[${count}][]" placeholder="0" min="0" readonly>
                            <span class="text-danger fw-medium sisa-stok-info" style="font-size: 0.75rem; display: none;">Sisa: <span class="sisa-stok-angka">0</span></span>
                         </div>
                         <div class="col-md-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                            <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[${count}][]" readonly>
                         </div>
                         <div class="col-md-12 text-end mt-2 mb-2">
                            <button type="button" class="btn btn-sm btn-soft-danger btn-hapus-material"><i class="las la-times"></i> Hapus Baris</button>
                         </div>
                    </div>
                </div>
                <div class="mt-2 text-end">
                    <button type="button" class="btn btn-sm btn-soft-info btn-tambah-material" data-index="${count}"><i class="las la-plus"></i> Tambah Material</button>
                </div>
            </td>
            <td>
                <select class="form-select" name="satuan[${count}]" id="satuan-${count + 1}" required>
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
                    <option value="Pax">Pax</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control product-price bg-light border-0" id="productRate-${count + 1}" name="hrg[${count}]" placeholder="Rp. 0.000" onchange="autoCalc(this)" required />
            </td>
            <td>
                <div class="input-step">
                    <input type="number" class="text-center" id="product-qty-${count + 1}" value="1" name="qty[${count}]" onchange="autoCalc(this)">
                </div>
            </td>
            <td class="text-end">
                <div>
                    <input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-${count + 1}" name="jlh_hrg[${count}]" placeholder="Rp.0.000" readonly />
                </div>
            </td>
            <td class="product-removal">
                <a href="javascript:void(0)" class="btn btn-success">Delete</a>
            </td>
        `;

        tr.innerHTML = template;
        document.getElementById("newlink").appendChild(tr);

        if (typeof Cleave !== 'undefined') {
             new Cleave(`#productRate-${count + 1}`, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        }
        
        count++; // Increment AFTER rendering so indices are correct (0, 1, 2, ...)
    }
</script>
@endsection
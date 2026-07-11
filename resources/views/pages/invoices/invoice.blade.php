@section('head')
<!-- dropzone css -->

<link rel="stylesheet" href="{{asset('libs/dropzone/dropzone.css')}}" type="text/css" />

<!-- Sweet Alert css-->
<link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('#loading-overlay').hide();
    $("#loading-spinner").hide();

    // Logic untuk Material Dropdown (Auto-fill Satuan & Validasi Stok)
    $(document).on('change', '.material-select', function() {
        var selectedOption = $(this).find(':selected');
        var satuan = selectedOption.data('satuan');
        var stok = selectedOption.data('stok'); 
        
        // Cari input terkait dalam row yang sama
        var row = $(this).closest('.material-row'); 
        row.find('.material-satuan').val(satuan);
        row.find('.material-qty').attr('max', stok);
        row.find('.material-panjang').val(''); // Reset panjang saat ganti material
        row.find('.material-lebar').val(''); // Reset lebar saat ganti material
        row.find('.material-qty').val(''); // Reset qty saat ganti material
    });

    // Kalkulasi Ukuran P x L dan Validasi Qty Input tidak boleh melebihi stok
    $(document).on('input', '.material-panjang, .material-lebar', function() {
        var row = $(this).closest('.material-row');
        var p = parseFloat(row.find('.material-panjang').val()) || 0;
        var l = parseFloat(row.find('.material-lebar').val()) || 0;
        
        var hasil = p * l;
        var qtyInput = row.find('.material-qty');
        
        // Set hasil jika > 0
        qtyInput.val(hasil > 0 ? hasil : '');

        var max = parseFloat(qtyInput.attr('max'));
        
        if (!isNaN(max) && hasil > max) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Kurang',
                text: 'Hasil perkalian (' + hasil + ') melebihi stok tersedia (' + max + ')',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            // Mengosongkan field agar tidak bisa disubmit jika lebih dari stok
            row.find('.material-panjang').val('');
            row.find('.material-lebar').val('');
            qtyInput.val('');
        }
    });

    // Hapus Material Row (Sub-Item)
    $(document).on('click', '.remove-material', function() {
        $(this).closest('.material-row').remove();
    });

    // Tambah Material Row (Sub-Item)
    $(document).on('click', '.add-material', function() {
        var container = $(this).siblings('.materials-container');
        var rowIndex = container.data('row-index');
        var materialOptionsDOM = document.getElementById('template-options-material');
        var materialOptions = materialOptionsDOM ? materialOptionsDOM.innerHTML : '<option value="">-- Pilih Material (Opsional) --</option>';

        var newMaterialRow = `
            <div class="row mt-2 material-row border-top pt-2">
                 <div class="col-xl-4 col-lg-12 mb-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                    <select class="form-select form-select-sm material-select" name="material_id[${rowIndex}][]">
                        ${materialOptions}
                    </select>
                 </div>
                 <div class="col-xl-3 col-lg-6 col-12 mb-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Ukuran (P × L)</label>
                    <div class="d-flex align-items-center gap-1">
                        <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[${rowIndex}][]" placeholder="P" min="0">
                        <span>×</span>
                        <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[${rowIndex}][]" placeholder="L" min="0">
                    </div>
                 </div>
                 <div class="col-xl-2 col-lg-3 col-5 mb-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                    <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[${rowIndex}][]" placeholder="0" readonly>
                 </div>
                 <div class="col-xl-2 col-lg-2 col-4 mb-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                    <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[${rowIndex}][]" readonly>
                 </div>
                 <div class="col-xl-1 col-lg-1 col-3 mb-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-danger remove-material w-100 fw-bold">X</button>
                 </div>
            </div>
        `;
        container.append(newMaterialRow);
    });

    // 1. BUAT MAPPING KATEGORI DI JAVASCRIPT
    const kategoriMap = {};
    // @if(isset($jenisBarang))
        // @foreach ($jenisBarang as $jns)
            kategoriMap["{{$jns->id}}"] = "{{$jns->kategori_id}}";
        // @endforeach
    // @endif

    $("#invoice_form").submit(function (e) {
    e.preventDefault();

    // --- START VALIDASI MATERIAL WAJIB BERDASARKAN KATEGORI ---
    let isMaterialValid = true;
    let materialErrorMsg = "";

    // KATEGORI ID YANG MEWAJIBKAN MATERIAL (Contoh: 1 dan 2)
    const KATEGORI_WAJIB_MATERIAL = ["2", "4"];

    $('#newlink .product').each(function(index) {
        let rowNum = index + 1;
        
        // 2. AMBIL ID BARANG YANG DIPILIH
        // Karena namanya sekarang barang_id[x], gunakan selector atribut name yang berakhiran ']'
        let selectedBarangId = $(this).find('select[name^="barang_id["]').val();
        
        // 3. COCOKKAN ID BARANG DENGAN MAP KATEGORI KITA
        let kategoriId = kategoriMap[selectedBarangId];
        
        // Cek jika kategori_id termasuk di dalam daftar KATEGORI_WAJIB_MATERIAL
        if (KATEGORI_WAJIB_MATERIAL.includes(String(kategoriId))) {
            let hasValidMaterial = false;
            
            // Cek semua baris material di item ini
            $(this).find('.material-row').each(function() {
                let materialId = $(this).find('.material-select').val();
                let materialQty = $(this).find('.material-qty').val();

                if (materialId && materialId !== "" && materialQty && materialQty > 0) {
                    hasValidMaterial = true;
                }
            });

            if (!hasValidMaterial) {
                isMaterialValid = false;
                materialErrorMsg = `Baris ke-${rowNum}: Minimal 1 Material WAJIB dipilih dan diisi ukurannya karena item ini memerlukan material!`;
                return false; // Menghentikan loop .each()
            }
        }
    });

    if (!isMaterialValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Validasi Material',
            text: materialErrorMsg,
        });
        return false; // Membatalkan proses submit form
    }
    // --- END VALIDASI MATERIAL WAJIB BERDASARKAN KATEGORI ---

    $.ajax({
      type: $(this).attr("method"),
      url: $(this).attr("action"),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: function () {
        $("#loading-spinner").show();
        $('#loading-overlay').show();
      },
      success: function (response) {
        $("#loading-spinner").hide();
        $('#loading-overlay').hide();

        if (response.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: response.message,
          }).then(function () {
            window.location.href = "{{ route('daftar_invoice') }}";
          });
        } else {
             Swal.fire({
                icon: "error",
                title: "Gagal",
                text: response.message,
            });
        }
      },
      error: function (xhr, status, error) {
        $("#loading-spinner").hide();
        $('#loading-overlay').hide();
        
        var msg = "Terjadi kesalahan! Data belum lengkap atau stok tidak mencukupi.";
        if(xhr.responseJSON && xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
        }

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: msg,
        });
      },
    });
  });
});
</script>
@endsection
@extends('layout.template')
@section('content')
@include('layout.loading-overlay')

<div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Tambah Invoice</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice</a></li>
                                        <li class="breadcrumb-item active">Tambah Invoice</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row justify-content-center">
                       <div class="col-xxl-12">
                         <div class="card">
                           <form method="POST" action="{{ route('store.invoice') }}" validate id="invoice_form" enctype="multipart/form-data">
                            @csrf
                             <div class="card-body border-bottom border-bottom-dashed p-4">
                               <div class="row">
                                 <div class="col-lg-6">
                                   <div class="row g-3 mb-2">
                                        <!--end col-->

                                       <div class="col-lg-8 col-sm-6">
                                          <label for="choices-invoice">Invoice</label>
                                             <div class="input-light">
                                                <select class="form-select   @error('inv') is-invalid @enderror"id="choices-invoice" name="inv" required>
                                                 {{-- Start Pilihan Jenis Invoice --}}
                                                 <option value="">Pilih Jenis Invoice</option>
                                                 @foreach ($invoice as $inv)
                                                 <option value="{{$inv->nama_invoice}}" {{old('inv') == $inv->nama_invoice ? 'selected' : ''}}>{{$inv->nama_invoice}}</option>

                                                 @endforeach
                                                 {{-- End Pilihan Jenis Invoice --}}
                                                 </select>

                                                 @error('inv')
                                                 <div class="invalid-feedback">{{ $message }}</div>
                                                 @enderror
                                             </div>
                                       </div>

                                          {{-- End coloumn --}}
                                   </div>
                                      <div class="row mb-2 col-lg-8 col-sm-6">
                                          <label for="invoicenoInput">Invoice No</label>
                                          <div class="col-lg-4">
                                            <input type="text" class="form-control bg-light border-0" id="invoicenoInput" placeholder="kode" name="kode" value="{{old('kode')}}" readonly>
                                          </div>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control bg-light border-0" id="noInvoice" placeholder="kode Unik" name="kodeUnik" value="{{$no_invoice}}"readonly>
                                          </div>
                                      </div>
                                        <!--end col-->
                                      <div class="row mb-2 col-lg-9">

                                          <label for="date-field">Tanggal Penjualan</label>
                                          <div class="col-lg-4">
                                            <input type="text" class="form-control bg-light border-0 flatpickr-input" id="date-field" name="tgl_jual" data-provider="flatpickr" value="{{$hariIni}}" readonly="readonly">
                                          </div>
                                          <div class="col-lg-6">
                                            <input type="text" class="form-control bg-light border-0" placeholder="H:i" name="jam" value="{{$formatJam}}" readonly>
                                          </div>

                                      </div>
                                 </div>
                                  <!--end col-->
                                 <div class="col-lg-4 ms-auto">
                                  <div class="profile-user mx-auto  mb-3">
                                   <input id="profile-img-file-input" type="file" class="profile-img-file-input"/>
                                   <label for="profile-img-file-input" class="d-block" tabindex="0">
                                    <span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style="height: 90px; width: 256px;">
                                      <img src="{{asset('images/Logo IBEKA id samping.png')}}" class="card-logo card-logo-dark user-profile-image img-fluid" alt="logo dark">
                                      <img src="{{asset('images/Logo IBEKAMI Ikhtiar berkah TULISAN PUTIH.png')}}" class="card-logo card-logo-light user-profile-image img-fluid" alt="logo light">
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
                                    
                                    <!--New function auto fill -->
                                     <div class="row mb-3">
                                       <label for="customer" class="col-lg-5 col-form-label col-form-label-md">Nama Customer</label>
                                       <div class="col-lg-7">
                                           <input type="text" class="form-control form-control-md @error('pelanggan') is-invalid @enderror" name="pelanggan" id="customer" placeholder="Nama Customer" value="{{ old('pelanggan') }}" list="customerList">
                                           <datalist   id="customerList">
                                               @foreach ($kontak as $data)
                                                   <option value="{{ $data->customer }}" data-company="{{ $data->perusahaan }}" data-phone="{{ $data->no_telepon }}">{{ $data->customer }}</option>
                                               @endforeach
                                           </datalist>
                                           @error('pelanggan')
                                               <div class="invalid-feedback">{{ $message }}</div>
                                           @enderror
                                       </div>
                                     </div>
                                     <div class="row mb-4">
                                       <label for="usaha" class="col-lg-5 col-form-label col-form-label-md">Nama Perusahaan / Instansi</label>
                                       <div class="col-lg-7">
                                           <input type="text" class="form-control form-control-md  @error('perusahaan') is-invalid @enderror " id="usaha" name="perusahaan" placeholder="Nama Perusahaan / instansi" value="{{ old('perusahaan') }}">
                                           @error('perusahaan')
                                               <div class="invalid-feedback">{{ $message }}</div>
                                           @enderror
                                       </div>
                                     </div>
                                     <div class="row mb-3" style="margin-top: -10px;">
                                       <div class="col-lg-5">
                                           <label for="contactNumber" class="form-label col-form-label col-form-label-md">Nomor Telepon</label>
                                       </div>
                                       <div class="col-lg-7">
                                           <input type="text" class="form-control @error('tlp') is-invalid @enderror" id="phone-number" name="tlp" placeholder="08xxxxxxxx" value="{{ old('tlp') }}">
                                           @error('tlp')
                                               <div class="invalid-feedback">{{ $message }}</div>
                                           @enderror
                                       </div>
                                     </div>
                                     <!--End function auto fill-->
                                     
                                       {{-- End No.Telepon --}}
                                       {{-- Start Admin --}}
                                       <div class="row mb-3">
                                        <div class="col-lg-5">
                                         <label for="choices-admin" class="form-label col-form-label col-form-label-md">Nama Admin</label>
                                        </div>
                                        {{-- test --}}

                                        <div class="col-lg-7">
                                         <select class="form-select @error('adm') is-invalid @enderror" name="adm" id="choices-admin" aria-placeholder="Admin" required>
                                          <option value="">Pilih Admin</option>
                                          @foreach ($admin as $adm)
                                          <option value="{{$adm->id}}" {{old('adm') == $adm->id ? 'selected' : ''}}>{{$adm->nama}}</option>

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
                                         <select class="form-select @error('order') is-invalid @enderror"  id="choices-orderBy" name="order" required>
                                         <option value="">Order By</option>
                                         @foreach ($order as $ord)
                                         <option value="{{$ord->order_by}}" {{old('order') == $ord->order_by ? 'selected' : ''}}>{{$ord->order_by}}</option>
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
                                                 <input type="text" class="form-control form-control-md mt-2 @error('sales') is-invalid @enderror" id="colFormLabelNamaSales" placeholder="Nama Sales" name="sales" value="{{ old('sales') }}">
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
                                         <input type="date" class="form-control @error ('tgl_selesai') is-invalid @enderror" data-provider="flatpickr"  name="tgl_selesai" id="dateInput" placeholder=" YYYY/MM/DD" value="{{ old('tgl_selesai') }}">
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
                                 <!-- Solusi mempercepat Load Web: Data diletakkan di elemen HTML, BUKAN di string JS -->
                                 <div id="template-options-material" style="display: none;">
                                    <option value="">-- Pilih Material (Opsional) --</option>
                                    @if(isset($materials))
                                      @foreach ($materials as $mat)
                                          <option value="{{ $mat->id }}" data-stok="{{ $mat->stok }}" data-satuan="{{ $mat->satuan }}">
                                              {{ $mat->kode_material }} - {{ $mat->jenis_material }} (Stok: {{ $mat->stok }})
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
                                    <!-- Menambahkan min-width: 500px pada TH agar kolom tidak dipress pada layar laptop -->
                                    <th scope="col" style="min-width: 500px;">Item</th>
                                    <th scope="col">Satuan</th>
                                    <th scope="col" style="width: 120px;">
                                     <div class="d-flex currency-select input-light align-items-center">Harga
                                      <select class="form-select border-0 bg-light" data-choices data-choices-search-false id="choices-payment-currency" onchange="otherPayment()">
                                      <option value="Rp">(Rp)</option>
                                      </select>
                                     </div></th>
                                    <th scope="col" style="width: 120px;">Quantity</th>
                                    <th scope="col" class="text-center" style="width: 150px;">Jumlah Harga</th>
                                    <th scope="col" class="text-end" style="width: 105px;"></th>
                                   </tr>
                                  </thead>
                                  <tbody id="newlink">
                                   <tr id="1" class="product">
                                    <th scope="row" class="product-id">1</th>
                                     <td class="text-start">
                                      <div class="mb-2">
                                        <label class="visually-hidden" for="productName">Item</label>
                                        <!-- Perhatikan index diubah jadi [0] -->
                                        <select class="form-select @error('barang_id.0') is-invalid @enderror" data-choices data-choices-sorting="true" id="productName-1" name="barang_id[0]">
                                            <option selected disabled>Pilih Item</option>
                                            @foreach ($jenisBarang as $jns)
                                            <option value="{{$jns->id}}" data-kategori-id="{{$jns->kategori_id}}" {{old('barang_id.0') == $jns->id ? 'selected' : ''}}>{{$jns->jenis_barang}}</option>
                                            @endforeach
                                        </select>
                                        @error('barang_id.0')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                      </div>
                                       <!-- Perhatikan index diubah jadi [0] -->
                                       <textarea class="form-control bg-light border-0 @error('deskripsi_item.0') is-invalid @enderror" id="productDetails-1" name="deskripsi_item[0]" rows="2" placeholder="Deskripsi Item"></textarea>
                                       @error('deskripsi_item.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror

                                       <!-- CONTAINER MATERIAL (BISA DITAMBAH BERKALI-KALI) -->
                                       <div class="materials-container" data-row-index="0">
                                           <div class="row mt-2 material-row border-top pt-2">
                                              <!-- Perhatikan indexnya menjadi [0][] -->
                                              <div class="col-xl-4 col-lg-12 mb-2">
                                                 <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                                                 <select class="form-select form-select-sm material-select" name="material_id[0][]">
                                                     <option value="">-- Pilih Material (Opsional) --</option>
                                                     @if(isset($materials))
                                                       @foreach ($materials as $mat)
                                                           <option value="{{ $mat->id }}" data-stok="{{ $mat->stok }}" data-satuan="{{ $mat->satuan }}">
                                                               {{ $mat->kode_material }} - {{ $mat->jenis_material }} (Stok: {{ $mat->stok }})
                                                           </option>
                                                       @endforeach
                                                     @endif
                                                 </select>
                                              </div>
                                              <div class="col-xl-3 col-lg-6 col-12 mb-2">
                                                 <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Ukuran (P × L)</label>
                                                 <div class="d-flex align-items-center gap-1">
                                                     <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[0][]" placeholder="P" min="0">
                                                     <span>×</span>
                                                     <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[0][]" placeholder="L" min="0">
                                                 </div>
                                              </div>
                                              <div class="col-xl-2 col-lg-3 col-5 mb-2">
                                                 <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                                                 <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[0][]" placeholder="0" readonly>
                                              </div>
                                              <div class="col-xl-2 col-lg-2 col-4 mb-2">
                                                 <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                                                 <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[0][]" readonly>
                                              </div>
                                              <div class="col-xl-1 col-lg-1 col-3 mb-2 d-flex align-items-end">
                                                 <!-- Tombol X disembunyikan/dibiarkan untuk menghapus jika tidak butuh -->
                                                 <button type="button" class="btn btn-sm btn-danger remove-material w-100 fw-bold">X</button>
                                              </div>
                                           </div>
                                       </div>
                                       
                                       <!-- TOMBOL UNTUK MENAMBAH MATERIAL -->
                                       <button type="button" class="btn btn-sm btn-soft-primary mt-2 add-material">+ Tambah Material Lainnya</button>

                                     </td>
                                     <td>
                                      <select class="form-select @error ('satuan.0') is-invalid @enderror" id="satuan-1" name="satuan[0]" required>
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
                                      @error('satuan.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                     </td>
                                     <td>
                                      <input type="text" class="form-control product-price bg-light border-0 @error('hrg.0') is-invalid @enderror" id="productRate-1" name="hrg[0]" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }'  placeholder="Rp. 0.000" onchange="autoCalc(this)" required/>

                                      @error('hrg.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                     </td>
                                     <td>
                                      <div class="input-step">
                                       <input type="number" class="text-center @error('qty.0') is-invalid @enderror" id="product-qty-1" value="1" name="qty[0]" onchange="autoCalc(this)">
                                       @error('qty.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                      </div>
                                     </td>
                                     <td class="text-end">
                                      <div>
                                       <input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-1" name="jlh_hrg[0]" placeholder="Rp.0.000" onchange="autoCalc(this)" readonly/>
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
                                   <div class="row">
                                     <div class="col-lg-4">
                                     <label for="jenisPembayaran" class="form-label col-form-label col-form-label-md">Jenis Pembayaran</label>
                                     </div>
                                       <div class="col-lg-6">
                                         <select class="form-select bg-light @error('jns_pem') is-invalid @enderror" id="choices-payment-type" name="jns_pem" value="{{ old('jns_pem') }}">
                                           <option disabled selected value="">Payment Method</option>
                                           <option value="Cash Lunas">Cash Lunas</option>
                                           <option value="Cash Belum Lunas">Cash Outstanding</option>
                                           <option value="Transfer DP">Transfer Dp</option>
                                           <option value="Transfer Lunas">Transfer Lunas</option>
                                           <option value="PO">PO</option>
                                         </select>
                                         @error('jns_pem')
                                           <div class="invalid-feedback">{{ $message }}</div>
                                           @enderror
                                       </div>
                                   </div>
                                   <div class="row mb-1 col-lg-12">
                                     <div class="col lg-12">
                                       <div class="form-check mb-1 col-lg-12" id="alamatPembayaran" style="display: none;">
                                         <input class="form-check-input" type="radio" name="norek" id="flexRadioDefault1" value="Mandiri" checked>
                                         <label class="form-check-label" for="flexRadioDefault1">
                                           BSI | A/N : Yusni Kurniasih | No. Rek : 2845999999
                                         </label>
                                         </div>
                                       
                                     </div>
                                   </div>
                                 <div class="row">
                                   <div class="col-lg-4">
                                   <label for="total-harga" class="form-label col-form-label col-form-label-md">Jumlah Harga</label>
                                   </div>
                                     <div class="col-lg-6">
                                       <input type="text" class="form-control bg-light border-0" rows="1" naplaceholder="" id="total-harga" name ="tot_harga" value="{{ old('tot_harga') }}"readonly >
                                     </div>
                                   </div>
                                   {{-- Input untuk Dp --}}
                                   <div class="row mb-1"  >
                                     <div class="col-lg-4" id="div-label" style="display: none" >
                                       <label for="dp" class="form-label col-form-label col-form-label-md" id="label-dp">Dp</label>
                                     </div>
                                       <div class="col-lg-6" id="dp" style="display: none;">
                                         <input id="input-dp" name="dp" type="text" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' class="form-control bg-light border-0" rows="1" placeholder="Rp. 0,000" value="{{ old('dp') }}">
                                       </div>
                                     </div>
                                     {{-- End Input --}}
                                 <div class="row mb-1">
                                   <div class="col-lg-4">
                                   <label for="potongan" class="form-label col-form-label col-form-label-md">Spesial Diskon</label>
                                   </div>
                                     <div class="col-lg-6">
                                       <select class="form-control bg-light border-0" name="select-potongan" value="{{ old('select-potongan') }}" data-choices data-choices-search-false data-choices-removeItem id="choices-potongan">
                                         <option value="-">-</option>
                                         <option value="Potongan">Spesial Diskon</option>
                                       </select>
                                     </div>
                                   </div>
                                 <div class="row mb-1"  >
                                   <div class="col-lg-4" >
                                   </div>
                                     <div class="col-lg-6" id="harga-potongan" style="display: none;">
                                       <input id="input-potongan"  type="text" class="form-control bg-light border-0" rows="1" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' placeholder="Rp. 0,000">
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
                                       <input type="text" class="form-control bg-light border-0" rows="1" placeholder="Sisa Pembayaran" id="sisa-pembayaran" name="sisa_pem" value="{{ old('sisa_pem') }}" readonly>
                                     </div>
                                   </div>
                               </div>
                              </div>
                               <!--end row-->
                               <div class="hstack gap-2 justify-content-start d-print-none mt-4">
                                <button type="submit" id="simpan" class="btn btn-info btn-load">
                                  <span class="d-flex align-items-center">
                                      <span id="loading-spinner" class="spinner-border flex-shrink-0" role="status">
                                          <span class="visually-hidden">Loading...</span>
                                      </span>
                                      <span class="flex-grow-1 ms-2">
                                        <i class="ri-printer-line align-bottom me-1"></i>Simpan
                                      </span>
                                  </span>
                                </button>
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
    <script>
        document.getElementById('customer').addEventListener('change', function() {
            var selectedOption = this.value;
            var dataList = document.getElementById('customerList').getElementsByTagName('option');
            for (var i = 0; i < dataList.length; i++) {
            var option = dataList[i];
            if (option.value === selectedOption) {
                document.getElementById('usaha').value = option.getAttribute('data-company');
                document.getElementById('phone-number').value = option.getAttribute('data-phone');
                break;
            }
        }
        });
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
<script src="{{asset('js/halaman/tabel-item.js')}}"></script>
<script src="{{asset('js/halaman/invoice.js')}}"></script>

<script>
    var count = document.querySelectorAll('#newlink tr.product').length;
    
    window.new_link = function() {
        // PERHATIKAN: Variabel count ini juga dipakai untuk mengindeks array agar tidak bentrok
        var materialOptionsDOM = document.getElementById('template-options-material');
        var materialOptions = materialOptionsDOM ? materialOptionsDOM.innerHTML : '<option value="">-- Pilih Material (Opsional) --</option>';

        var itemOptionsDOM = document.getElementById('template-options-barang');
        var itemOptions = itemOptionsDOM ? itemOptionsDOM.innerHTML : '<option selected disabled>Pilih Item</option>';

        var template = `
            <th scope="row" class="product-id">${count + 1}</th>
            <td class="text-start">
                <div class="mb-2">
                    <select class="form-select" name="barang_id[${count}]" id="productName-${count + 1}">
                        ${itemOptions}
                    </select>
                </div>
                <textarea class="form-control bg-light border-0" id="productDetails-${count + 1}" name="deskripsi_item[${count}]" rows="2" placeholder="Deskripsi Item"></textarea>
                
                <!-- CONTAINER MATERIAL UNTUK ITEM BARU -->
                <div class="materials-container" data-row-index="${count}">
                    <div class="row mt-2 material-row border-top pt-2">
                         <div class="col-xl-4 col-lg-12 mb-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                            <select class="form-select form-select-sm material-select" name="material_id[${count}][]">
                                ${materialOptions}
                            </select>
                         </div>
                         <div class="col-xl-3 col-lg-6 col-12 mb-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Ukuran (P × L)</label>
                            <div class="d-flex align-items-center gap-1">
                                <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[${count}][]" placeholder="P" min="0">
                                <span>×</span>
                                <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[${count}][]" placeholder="L" min="0">
                            </div>
                         </div>
                         <div class="col-xl-2 col-lg-3 col-5 mb-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                            <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[${count}][]" placeholder="0" readonly>
                         </div>
                         <div class="col-xl-2 col-lg-2 col-4 mb-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                            <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[${count}][]" readonly>
                         </div>
                         <div class="col-xl-1 col-lg-1 col-3 mb-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-danger remove-material w-100 fw-bold">X</button>
                         </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-soft-primary mt-2 add-material">+ Tambah Material Lainnya</button>

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

        var tr = document.createElement("tr");
        tr.id = count + 1;
        tr.className = "product";
        tr.innerHTML = template;
        document.getElementById("newlink").appendChild(tr);

        // Init Cleave.js untuk input harga yang baru
        if (typeof Cleave !== 'undefined') {
             new Cleave(`#productRate-${count + 1}`, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        }
        count++;
    }
</script>
@endsection
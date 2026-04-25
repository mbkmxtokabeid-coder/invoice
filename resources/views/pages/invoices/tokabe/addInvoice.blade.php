@section('head')
<!-- dropzone css -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{-- <link rel="stylesheet" href="{{asset('libs/dropzone/dropzone.css')}}" type="text/css" /> --}}

<!-- Sweet Alert css-->
<link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function () {
    $('#loading-overlay').hide();
    $("#loading-spinner").hide();

    // Gunakan ID yang sesuai dengan elemen <select> Anda
    // Jika menggunakan library Choices.js, pastikan ID-nya benar
    $(document).on('change', '#choices-potongan', function() {
        var pilihan = $(this).val();
        var inputPotongan = $('#input-potongan');

        if (pilihan === 'Diskon' || pilihan === 'Potongan' || pilihan === 'PPN' || pilihan === 'PPH') {
            $('#harga-potongan').show();
            
            // --- PERBAIKAN KRUSIAL: Pastikan atribut 'name' ada ---
            inputPotongan.attr('name', 'ptg'); 
            
            // Ganti placeholder sesuai pilihan
            if(pilihan === 'Diskon' || pilihan === 'PPN' || pilihan === 'PPH') {
                inputPotongan.attr('placeholder', 'Masukkan Persentase (%)');
            } else {
                inputPotongan.attr('placeholder', 'Nominal (Rp)');
            }
        } else {
            $('#harga-potongan').hide();
            inputPotongan.val(''); 
            inputPotongan.removeAttr('name'); // Opsional: hapus name jika tidak dipakai
        }
    });


    // --- LOGIKA MULTI-MATERIAL ---
    // Script Tambah Form Row Material Secara Dinamis dalam 1 Barang
    $(document).on('click', '.btn-tambah-material', function() {
        var index = $(this).data('index');
        var container = $(this).closest('td').find('.material-container-' + index);
        
        var materialOptionsDOM = document.getElementById('template-options-material');
        var materialOptions = materialOptionsDOM ? materialOptionsDOM.innerHTML : '<option value="">-- Pilih Material (Opsional) --</option>';

        var newRow = `
            <div class="row mt-2 material-row border-top pt-2 position-relative">
                 <div class="col-xl-4 col-lg-12 mb-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                    <select class="form-select form-select-sm material-select" name="material_id[${index}][]">
                        ${materialOptions}
                    </select>
                 </div>
                 <div class="col-xl-4 col-lg-6 col-12 mb-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Ukuran (P × L)</label>
                    <div class="d-flex align-items-center gap-1">
                        <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[${index}][]" placeholder="P" min="0">
                        <span>×</span>
                        <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[${index}][]" placeholder="L" min="0">
                    </div>
                 </div>
                 <div class="col-xl-2 col-lg-3 col-6 mb-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                    <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[${index}][]" placeholder="0" readonly>
                    <span class="text-danger fw-medium sisa-stok-info" style="font-size: 0.75rem; display: none;">Sisa: <span class="sisa-stok-angka">0</span></span>
                 </div>
                 <div class="col-xl-2 col-lg-3 col-6 mb-2">
                    <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                    <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[${index}][]" readonly>
                 </div>
                 <div class="col-xl-12 col-12 text-end mb-2">
                    <button type="button" class="btn btn-sm btn-soft-danger btn-hapus-material"><i class="las la-times"></i> Hapus Baris Material</button>
                 </div>
            </div>
        `;
        container.append(newRow);
    });

    // Menghapus baris form material
    $(document).on('click', '.btn-hapus-material', function() {
        $(this).closest('.material-row').remove();
    });

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
        
        // Tampilkan label sisa stok tanpa nol tidak penting
        if(stok !== undefined && stok !== "") {
            row.find('.sisa-stok-info').show();
            row.find('.sisa-stok-angka').text(parseFloat(stok));
        } else {
            row.find('.sisa-stok-info').hide();
        }
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
        // Trigger input agar perhitungan sisa stok berjalan
        qtyInput.trigger('input'); 
    });

    // Validasi Qty Input tidak boleh melebihi stok & hitung sisa
    $(document).on('input', '.material-qty', function() {
        var max = parseFloat($(this).attr('max'));
        var val = parseFloat($(this).val()) || 0;
        var row = $(this).closest('.material-row');
        
        if (!isNaN(max)) {
            if (val > max) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Kurang',
                    text: 'Jumlah material melebihi stok tersedia (Maksimal: ' + max + ')',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $(this).val(max);
                val = max;
            }
            var sisa = max - val;
            row.find('.sisa-stok-angka').text(parseFloat(sisa.toFixed(4))); 
        }
    });

    // 1. BUAT MAPPING KATEGORI DI JAVASCRIPT
    const kategoriMap = {};
    @if(isset($jenisBarang))
        @foreach ($jenisBarang as $jns)
            kategoriMap["{{$jns->id}}"] = "{{$jns->kategori_id}}";
        @endforeach
    @endif

    $("#invoice_form").submit(function (e) {
    e.preventDefault();

    // --- START VALIDASI MATERIAL WAJIB BERDASARKAN KATEGORI ---
    let isMaterialValid = true;
    let materialErrorMsg = "";

    // KATEGORI ID YANG MEWAJIBKAN MATERIAL (Contoh: 1 dan 2)
    const KATEGORI_WAJIB_MATERIAL = ["2", "4"];

    $('#newlink .product').each(function(index) {
        let rowNum = index + 1;
        
        // 2. AMBIL ID BARANG YANG DIPILIH (Pencarian menggunakan starts with karena array index)
        let selectedBarangId = $(this).find('select[name^="barang_id"]').val();
        
        // 3. COCOKKAN ID BARANG DENGAN MAP KATEGORI KITA
        let kategoriId = kategoriMap[selectedBarangId];
        
        // Cek jika kategori_id termasuk di dalam daftar KATEGORI_WAJIB_MATERIAL
        if (KATEGORI_WAJIB_MATERIAL.includes(String(kategoriId))) {
            // Karena Multi Material, kita periksa setidaknya 1 material terisi
            let hasValidMaterial = false;
            $(this).find('.material-row').each(function() {
                let mId = $(this).find('.material-select').val();
                let mQty = $(this).find('.material-qty').val();
                
                if (mId !== "" && mQty > 0) {
                    hasValidMaterial = true;
                }
            });

            if (!hasValidMaterial) {
                isMaterialValid = false;
                materialErrorMsg = `Baris ke-${rowNum}: Item ini wajib memiliki minimal 1 Material dengan Qty/Hasil > 0!`;
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
          // Data berhasil disimpan
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: response.message,
          }).then(function () {
            // Redirect ke /list-invoice/tokabe
            window.location.href = "/invoice/list-invoice/tokabe";
          });
        }
      },
      error: function (xhr, status, error) {
        $("#loading-spinner").hide();
        $('#loading-overlay').hide();

        // Tangkap pesan error dari server JSON response (400 Bad Request)
        var msg = "Terjadi kesalahan! Data belum lengkap.";
        if (xhr.responseJSON && xhr.responseJSON.message) {
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

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Tambah Invoice Tokabe</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice</a></li>
                                        <li class="breadcrumb-item active">Tambah Invoice</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                       <div class="col-xxl-12">
                         <div class="card">
                           <form method="POST" action="{{ route('storeInvoice.Tokabe') }}" validate id="invoice_form" enctype="multipart/form-data">
                            @csrf
                             <div class="card-body border-bottom border-bottom-dashed p-4">
                               <div class="row">
                                 <div class="col-lg-6">
                                   <div class="row g-3 mb-2">
                                        <div class="col-lg-8 col-sm-6">
                                         <label for="choices-invoice">Invoice</label>
                                            <div class="input-light">
                                               <select
                                               class="form-select   @error('inv') is-invalid @enderror"id="choices-invoice" name="inv" required>
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
                                          <input type="text" class="form-control bg-light border-0" id="invoicenoInput" placeholder="Kode" name="kode" value="{{ explode('/', $no_invoice)[1] }}" readonly>
                                      </div>
                                      <div class="col-lg-7">
                                          <input type="text" class="form-control bg-light border-0" id="noInvoice" placeholder="Kode Unik" name="kodeUnik" value="{{ $no_invoice }}" readonly>
                                      </div>
                                  </div>

                                  <div class="row mb-2 col-lg-9">
                                      <label for="date-field">Tanggal Penjualan</label>
                                      <div class="col-lg-4">
                                          <input type="text" class="form-control bg-light border-0 flatpickr-input" id="date-field" name="tgl_jual" data-provider="flatpickr" value="{{ $hariIni }}" readonly="readonly">
                                      </div>
                                      <div class="col-lg-6">
                                          <input type="text" class="form-control bg-light border-0" placeholder="H:i" name="jam" value="{{ $formatJam }}" readonly="readonly">
                                      </div>
                                  </div>
                                        {{-- </div> --}}

                                 </div>
                                  <div class="col-lg-4 ms-auto">
                                  <div class="profile-user mx-auto  mb-3">
                                   <input id="profile-img-file-input" type="file" class="profile-img-file-input"/>
                                   <label for="profile-img-file-input" class="d-block" tabindex="0">
                                    <span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style=" width: 256px;">
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
                                          <input type="text" class="form-control form-control-md @error('pelanggan') is-invalid @enderror" name="pelanggan" id="customer" placeholder="Nama Customer" value="{{ old('pelanggan') }}" list="customerList">
                                          <datalist id="customerList">
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

                                        <!-- INDEX [0] DITAMBAHKAN SECARA EKSPLISIT -->
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
                                       <textarea class="form-control bg-light border-0 @error('deskripsi_item.0') is-invalid @enderror" id="productDetails-1" name="deskripsi_item[0]" rows="2" placeholder="Deskripsi Item"></textarea>
                                       @error('deskripsi_item.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror

                                       <!-- START KOLOM MULTI-MATERIAL (BARU) -->
                                       <div class="material-container-0 mt-2">
                                           <div class="row mt-2 material-row border-top pt-2 position-relative">
                                             <div class="col-xl-4 col-lg-12 mb-2">
                                                <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                                                <!-- INDEX [0] DITAMBAHKAN SECARA EKSPLISIT -->
                                                <select class="form-select form-select-sm material-select" name="material_id[0][]">
                                                    <option value="">-- Pilih Material (Opsional) --</option>
                                                    @if(isset($materials))
                                                      @foreach ($materials as $mat)
                                                          <option value="{{ $mat->id }}" data-stok="{{ $mat->stok }}" data-satuan="{{ $mat->satuan }}">
                                                              {{ $mat->kode_material }} - {{ $mat->jenis_material }} (Stok: {{ $mat->stok }})
                                                          </option>
                                                      @endforeach
                                                    @else
                                                      <option disabled>Data material tidak tersedia</option>
                                                    @endif
                                                </select>
                                             </div>
                                             <div class="col-xl-4 col-lg-6 col-12 mb-2">
                                                <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Ukuran (P × L)</label>
                                                <div class="d-flex align-items-center gap-1">
                                                    <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[0][]" placeholder="P" min="0">
                                                    <span>×</span>
                                                    <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[0][]" placeholder="L" min="0">
                                                </div>
                                             </div>
                                             <div class="col-xl-2 col-lg-3 col-6 mb-2">
                                                <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                                                <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[0][]" placeholder="0" readonly>
                                                <span class="text-danger fw-medium sisa-stok-info" style="font-size: 0.75rem; display: none;">Sisa: <span class="sisa-stok-angka">0</span></span>
                                             </div>
                                             <div class="col-xl-2 col-lg-3 col-6 mb-2">
                                                <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                                                <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[0][]" readonly>
                                             </div>
                                           </div>
                                       </div>
                                       <div class="mt-2 text-end">
                                           <button type="button" class="btn btn-sm btn-soft-info btn-tambah-material" data-index="0"><i class="las la-plus"></i> Tambah Material</button>
                                       </div>
                                       <!-- END KOLOM MULTI-MATERIAL -->

                                     </td>
                                     <td>
                                      <!-- INDEX [0] DITAMBAHKAN SECARA EKSPLISIT -->
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
                                      <option value="Pax">Pax</option>
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
                                           <option value="Cash Belum Lunas">Cash Belum Lunas</option>
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
                                         <div class="form-check mb-1 col-lg-12" id="alamatPembayaran2">
                                         <input class="form-check-input" type="radio" name="norek" id="flexRadioDefault2" value="TKB" checked>
                                         <label class="form-check-label" for="flexRadioDefault2">
                                           BSI | A/N : PT. Total Karya Berkah | No. Rek :  3557999999
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
                                   <label for="potongan" class="form-label col-form-label col-form-label-md">Diskon/Potongan/PPN</label>
                                   </div>
                                     <div class="col-lg-6">
                                       <select class="form-control bg-light border-0" name="select-potongan" value="{{ old('select-potongan') }}" data-choices data-choices-search-false data-choices-removeItem id="choices-potongan">
                                         <option value="-">-</option>
                                         <option value="Diskon">Diskon</option>
                                         <option value="Potongan">Potongan Harga</option>
                                         <option value="PPN">PPN</option>
                                         <option value="PPH">PPH</option>
                                       </select>
                                     </div>
                                   </div>
                                 <div class="row mb-1"  >
                                   <div class="col-lg-4" >
                                   </div>
                                     <div class="col-lg-6" id="harga-potongan" style="display: none;">
                                       <!-- ATTRIBUTE NAME="PTG" DITAMBAHKAN DI BAWAH INI -->
                                       <input id="input-potongan" name="ptg" type="text" class="form-control bg-light border-0" rows="1" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' placeholder="Rp. 0,000">
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
           
          <script>
   document.addEventListener("DOMContentLoaded", function () {
     let dateField = document.getElementById("date-field");
     let invoiceInput = document.getElementById("noInvoice");
 
     flatpickr(dateField, {
         dateFormat: "Y-m-d",
         defaultDate: "{{ $hariIni }}",
         onChange: function (selectedDates, dateStr) {
             fetch(`/invoice/get-invoice-number?tanggal=${dateStr}`)
                 .then(response => response.json())
                 .then(data => {
                     invoiceInput.value = data.no_invoice;
                 })
                 .catch(error => console.error('Error fetching invoice number:', error));
         }
     });
   });
   </script> 
           
{!! JsValidator::formRequest('App\Http\Requests\InvoiceRequest') !!}
@endsection

@section('plugins')

<!-- dropzone min -->
<script src="{{asset('libs/dropzone/dropzone-min.js')}}"></script>

<!-- cleave.js -->
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>

<!--Invoice create init js-->
{{-- <script src="{{asset('js/pages/invoicecreate.init.js')}}"></script> --}}

<!-- Sweet Alerts js -->
<script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>

{{-- choices.js & flatpickr --}}
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>

<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('js/halaman/tabel-item-tokabe.js')}}"></script>
<script src="{{asset('js/halaman/invoice.js')}}"></script>
{{-- <script src="{{asset('js/halaman/form-masks.js')}}"></script> --}}

<script>
    // OVERRIDE FUNGSI new_link DARI tabel-item.js
    // Masalah: Fungsi bawaan tabel-item.js tidak mengetahui adanya form Material yang baru ditambahkan.
    // Solusi: Kita timpa fungsi tersebut di sini agar baris baru menyertakan kolom Material.
    
    // Pastikan count sesuai dengan jumlah baris yang ada saat ini
    var count = document.querySelectorAll('#newlink tr.product').length;
    
    window.new_link = function() {
        var tr = document.createElement("tr");
        tr.id = count;
        tr.className = "product";
        
        // MENGAMBIL DATA DARI ELEMEN HIDDEN HTML (BUKAN MELOOPING PHP DI DALAM SCRIPT)
        // Ini adalah kunci yang mengembalikan kecepatan web Anda menjadi normal
        var materialOptionsDOM = document.getElementById('template-options-material');
        var materialOptions = materialOptionsDOM ? materialOptionsDOM.innerHTML : '<option value="">-- Pilih Material (Opsional) --</option>';

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
                
                <!-- KOLOM MULTI-MATERIAL DI ITEM BARU (SUDAH DI PERBARUI AGAR RESPONSIF) -->
                <div class="material-container-${count} mt-2">
                    <div class="row mt-2 material-row border-top pt-2 position-relative">
                         <div class="col-xl-4 col-lg-12 mb-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Pilih Material</label>
                            <select class="form-select form-select-sm material-select" name="material_id[${count}][]">
                                ${materialOptions}
                            </select>
                         </div>
                         <div class="col-xl-4 col-lg-6 col-12 mb-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Ukuran (P × L)</label>
                            <div class="d-flex align-items-center gap-1">
                                <input type="number" step="any" class="form-control form-control-sm material-panjang" name="material_panjang[${count}][]" placeholder="P" min="0">
                                <span>×</span>
                                <input type="number" step="any" class="form-control form-control-sm material-lebar" name="material_lebar[${count}][]" placeholder="L" min="0">
                            </div>
                         </div>
                         <div class="col-xl-2 col-lg-3 col-6 mb-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Hasil</label>
                            <input type="number" step="any" class="form-control form-control-sm material-qty bg-light" name="material_qty[${count}][]" placeholder="0" readonly>
                            <span class="text-danger fw-medium sisa-stok-info" style="font-size: 0.75rem; display: none;">Sisa: <span class="sisa-stok-angka">0</span></span>
                         </div>
                         <div class="col-xl-2 col-lg-3 col-6 mb-2">
                            <label class="form-label text-muted mb-1" style="font-size: 0.8rem;">Satuan</label>
                            <input type="text" class="form-control form-control-sm material-satuan bg-light" name="material_satuan[${count}][]" readonly>
                         </div>
                         <div class="col-xl-12 col-12 text-end mb-2">
                            <button type="button" class="btn btn-sm btn-soft-danger btn-hapus-material"><i class="las la-times"></i> Hapus Baris Material</button>
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
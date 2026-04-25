@section('head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
@extends('layout.template')
@section('content')
  <div class="page-content">
    <div class="container-fluid">
      <!-- start page title -->
      <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Laporan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Laporan</a></li>
                        <li class="breadcrumb-item active">Laporan Penjualan</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    {{-- Start Row 1  --}}
      <div class="card">
    <div class="card-body row g-3">
      <div class="row col-lg-6 mt-2">
        <h5 id="changePerusahaan">Laporan Penjualan </h5>
      </div>
      <form action="/invoice/laporan-export" method="POST">
      @csrf
      <div class="row col-lg-12 col-sm-6 mt-3">
      <label class="col-lg-2 col-form-label" >Perusahaan</label>
      <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px;">
        <select class="form-select bg-light" name="perusahaan" id="perusahaanTerpilih" required>
          <option value="" disabled selected>Pilih Perusahaan</option>
          @foreach ( $perusahaan as $id =>$namaperusahaan )
          <option value="{{$id}}">{{$namaperusahaan}}</option>
          @endforeach
        </select>
      </div>
      </div>

      <div class="row col-lg-12 col-sm-6 mt-3">
        <label class="col-lg-2 col-form-label" >Semua Invoice</label>
        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px;">
          <select name="invoice" id="invoiceTerpilih"  disabled class="form-control invoice" >
            <option value="semua" disabled selected >Semua Kategori Invoice</option>
           
        </select>
        </div>
        
        <label class="col-lg-1 col-form-label" style="margin-left: 30px">Status</label>
        <div class="col-lg-2 input-light mb-4" style="margin-left: -10px; margin-right:-20px">
          <select name="status" class="form-select bg-light" id="">
            <option value="semua">Semua Status</option>
            <option value="Lunas">Lunas</option>
            <option value="Belum Lunas">Belum Lunas</option>
            <option value="Batal">Batal</option>
          </select>
        </div>
        
        <div class="col-lg-1 m-1 dropdown">
          <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="las la-ellipsis-h align-middle fs-18"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
              {{-- View BUTTON --}}
              <li>
                <button class="dropdown-item" name="export_type" value="pdf" type="submit"><i class="las la-eye fs-18 align-middle me-2 text-muted"></i>Lihat Laporan</button>
            </li>
              <li>
                  <button class="dropdown-item" name="export_type" value="excel" type="submit"><i class="mdi mdi-file-excel-outline fs-18 align-middle me-2 text-muted"></i>Download Excel</button>
              </li>
          </ul>
      </div>
      </div>
    </form>
    </div>
   </div>
    {{-- Start Card --}}
   <div class="card">
    <div class="card-body row g-3">
      <div class="row col-lg-6 mt-2">
        <h5>Laporan Penjualan Custom</h5>
      </div>
      <form action="/invoice/laporan-export-date" method="POST" target="_blank">
        @csrf
        <input type="hidden" name="perusahaan" class="perusahaanHidden" value="">
      <div class="row col-lg-12 col-sm-6 mt-3">
        <label class="col-lg-2 col-form-label" >Pilih Tanggal</label>
        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px;">
          <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" placeholder="Pilih Tanggal" name="tgl_custom">
        </div>
        <label class="col-lg-1 col-form-label" style="margin-left: 30px">Invoice</label>
        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px">
          <select class="form-select bg-light invoice" id="invoiceTerpilihCstm" name="invoice" required>
            <option value="semua">Semua Invoice</option>
            @foreach ($katInv as $id => $namaInvoice)
            <option value="{{$id}}">{{$namaInvoice}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-1 m-1 dropdown">
          <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="las la-ellipsis-h align-middle fs-18"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
              {{-- DOWNLOAD BUTTON --}}
              <li>
                <button class="dropdown-item" type="submit" name="export_type" value="pdf"><i class="las la-eye fs-18 align-middle me-2 text-muted"></i>Lihat Laporan</button>
              </li>
              <li>
                  <button class="dropdown-item" type="submit" name="export_type" value="excel"><i class="mdi mdi-file-excel-outline fs-18 align-middle me-2 text-muted"></i>Export Excel</button>
              </li>

          </ul>
      </div>
      </div>
    </form>
    </div>
  </div>
    {{-- Start Card --}}
    <div class="card">
    <div class="card-body row g-3">
      <form action="/invoice/laporan-export-month" method="POST" target="_blank">
        @csrf
      <input type="hidden" name="perusahaan" class="perusahaanHidden" value="">
      <div class="row col-lg-6 mt-2">
        <h5>Laporan Penjualan Bulanan Tahun <script>document.write(new Date().getFullYear())</script></h5>
      </div>
      <div class="row col-lg-12 col-sm-6 mt-3">
        <label class="col-lg-2 col-form-label" >Pilih Bulan</label>
        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px;">
          <select class="form-select bg-light" name="bulan" required>
            @foreach ($namaBulan as $bulan)
            <option value="{{$bulan}}">{{$bulan}}</option>
            @endforeach
          </select>
        </div>
        <label class="col-lg-1 col-form-label" style="margin-left: 30px">Invoice</label>
        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px">
          <select class="form-select bg-light invoice" id="invoiceTerpilih" name="invoice" required>
            <option value="semua">Semua Invoice</option>
            @foreach ($katInv as $id => $namaInvoice)
            <option value="{{$id}}">{{$namaInvoice}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-1 m-1 dropdown">
          <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="las la-ellipsis-h align-middle fs-18"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            {{-- View BUTTON --}}
             <li>
                 <button class="dropdown-item" name="export_type" value="pdf" type="submit" ><i class="las la-eye fs-18 align-middle me-2 text-muted"></i>Lihat Laporan</button>
             </li>
              <li>
                  <button class="dropdown-item" type="submit" name="export_type" value="excel"><i class="mdi mdi-file-excel-outline fs-18 align-middle me-2 text-muted"></i>Export Excel</button>
              </li>


          </ul>
      </div>
      </div>
    </form>
    </div>
  </div>
    <div class="card">
    <div class="card-body row g-3">
       <form action="/invoice/laporan-export-year" method="POST" target="_blank">
        @csrf
        <input type="hidden" name="perusahaan" class="perusahaanHidden" value="">
      <div class="row col-lg-6 mt-2">
        <h5>Laporan Penjualan Tahunan</h5>
      </div>
      <div class="row col-lg-12 col-sm-6 mt-3">
        <label class="col-lg-2 col-form-label" >Pilih Tahun</label>
        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px;">
          <select class="form-select bg-light" name="tahun" required >
            @foreach ($daftarTahun as $tahun)
              @if ($tahun == date('Y'))
                <option value="{{ $tahun }}" selected>{{ $tahun }}</option>
              @else
                <option value="{{ $tahun }}">{{ $tahun }}</option>
              @endif
            @endforeach
          </select>
        </div>
        <label class="col-lg-1 col-form-label" style="margin-left: 30px">Invoice</label>
        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px">
          <select class="form-select bg-light invoice" id="invoiceTerpilih" name="invoice" required>
            <option value="semua">Semua Invoice</option>
            @foreach ($katInv as $id => $namaInvoice)
            <option value="{{$id}}">{{$namaInvoice}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-1 m-1 dropdown">
          <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="las la-ellipsis-h align-middle fs-18"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <button class="dropdown-item" type="submit" name="export_type" value="pdf"><i class="las la-eye fs-18 align-middle me-2 text-muted"></i>Lihat Laporan</button>
            </li>
            <li>
              <button class="dropdown-item" type="submit"name="export_type" value="excel"><i class="mdi mdi-file-excel-outline fs-18 align-middle me-2 text-muted"></i>Export Excel</button>
            </li>
              {{-- DOWNLOAD BUTTON --}}

          </ul>
      </div>
      </div>
    </form>
    </div>
  </div>
<!--<div class="card">-->
<!--    <div class="card-body row g-3">-->
<!--      <div class="row col-lg-6 mt-2">-->
<!--        <h5>Laporan Penjualan Barang Custom</h5>-->
<!--      </div>-->
<!--      <form action="{{ route('export.barang') }}" method="POST" target="_blank">-->
<!--        @csrf-->
<!--    <input type="hidden" name="perusahaan" class="perusahaanHidden" value="">-->

<!--      <div class="row col-lg-12 col-sm-6 mt-3">-->
<!--        <label class="col-lg-2 col-form-label" >Pilih Tanggal</label>-->
<!--        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px;">-->
<!--          <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" placeholder="Pilih Tanggal" name="tgl_custom">-->
<!--        </div>-->
<!--        <label class="col-lg-1 col-form-label" style="margin-left: 30px">Invoice</label>-->
<!--        <div class="col-lg-3 input-light mb-4" style="margin-left: -10px; margin-right:-20px">-->
<!--           <select class="form-select bg-light invoice" name="invoice" required>-->
<!--            <option value="semua">Semua Invoice</option>-->
<!--            @foreach ($katInv as $id => $namaInvoice)-->
<!--            <option value="{{$id}}">{{$namaInvoice}}</option>-->
<!--            @endforeach-->
<!--          </select>-->
<!--        </div>-->
<!--        <div class="col-lg-1 m-1 dropdown">-->
<!--          <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">-->
<!--              <i class="las la-ellipsis-h align-middle fs-18"></i>-->
<!--          </button>-->
<!--          <ul class="dropdown-menu dropdown-menu-end">-->
<!--              {{-- DOWNLOAD BUTTON --}}-->
<!--              <li>-->
<!--                <button class="dropdown-item" type="submit" name="export_type" value="pdf"><i class="las la-eye fs-18 align-middle me-2 text-muted"></i>Lihat Laporan</button>-->
<!--              </li>-->
<!--              <li>-->
<!--                  <button class="dropdown-item" type="submit" name="export_type" value="excel"><i class="mdi mdi-file-excel-outline fs-18 align-middle me-2 text-muted"></i>Export Excel</button>-->
<!--              </li>-->

<!--          </ul>-->
<!--      </div>-->
<!--      </div>-->
<!--    </form>-->
<!--    </div>-->
<!--  </div>-->

  </div>
  </div>
@endsection

@section('plugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script> --}}

<script src="{{asset('js/pages/datatables.init.js')}}"></script>
<script src="{{asset('js/halaman/laporan.js')}}"></script>
<script src="{{asset('js/halaman/customer.js')}}"></script>
@endsection
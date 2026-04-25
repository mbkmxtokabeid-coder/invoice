@section('head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
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
                        <li class="breadcrumb-item active">Laporan Daftar Pelanggan</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    {{-- Start Row 1  --}}


    {{-- End Row 1  --}}

          {{-- Start Row 3 --}}
        <div class="row">
          {{-- Start Coloumn --}}
          <div class="col-lg-12">
            <div class="card">

                {{-- <div class="col-sm-4 m-2">
                    <a href="{{ route('tambah.pelanggan') }}" class="btn btn-primary addMembers-modal"><i class="las la-plus me-1"></i>Tambah Customer</a>
                  </div> --}}
                <form action="{{ route('export.pelanggan') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="invoice" id="invoiceInput" value="{{ $selectedInvoice }}">

                    <div class="col-lg-3 m-2">
                        <button class="btn btn-soft-danger btn-sm" type="submit" name="export_type" value="pdf" aria-expanded="false">
                            <i class="las la-file-pdf align-middle fs-18"></i>PDF
                        </button>
                        <button class="btn btn-soft-primary btn-sm mx-2" type="submit" name="export_type" value="excel" aria-expanded="false">
                            <i class="las la-file-excel fs-18 align-middle"></i>Excel
                        </button>
                    </div>

                </form>
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Pembelian</h5>
                </div>
                <div class="row p-2 col-6 justify-content-right">
                  <div class="col-6">
                      <form action="{{ route('daftar.pelanggan') }}" method="GET">
                          @csrf
                          <div class="form-group">
                              <select name="invoice" class="form-control" id="invoiceDropdown">
                                  <option value="">Semua Kategori Invoice</option>
                                  @foreach ($invoices as $invoice)
                                      <option value="{{ $invoice->id }}" {{ $invoice->id == $selectedInvoice ? 'selected' : '' }}>
                                          {{ $invoice->nama_invoice }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                  <div class="col-md-6">
                      <button type="submit" class="btn btn-primary">Filter</button>
                  </form>
              </div>
            </div>


                <div class="card-body">
                  <table id="alternative-pagination" class="table nowrap align-middle" style="width:100%">
                      <thead>
                          <tr>
                              <th>No.</th>
                              <th>Customer</th>
                              <th>Kategori Invoice</th>
                              <th>Perusahaan</th>
                              <th>No. Kontak</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($customers as $cust)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $cust->customer }}</td>
                            <td>{{ $invoices->where('id', $cust->invoice)->first()->nama_invoice }}</td>
                            <td>{{ $cust->perusahaan }}</td>
                            <td>{{ $cust->no_telepon }}</td>
                        </tr>
                         @endforeach

                      </tbody>
                  </table>
              </div>
            </div>
        </div>
        {{-- End Coloumn --}}
        </div>
        {{-- End Row 3 --}}
    </div>
  </div>
@endsection

<script>
  document.getElementById("invoiceDropdown").addEventListener("change", function() {
      // Dapatkan nilai yang dipilih pada dropdown
      var selectedInvoiceId = this.value;

      // Setel nilai invoice pada form lainnya
      document.getElementById("invoiceInput").value = selectedInvoiceId;
  });
</script>

@section('plugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{asset('js/pages/datatables.init.js')}}"></script>
@endsection
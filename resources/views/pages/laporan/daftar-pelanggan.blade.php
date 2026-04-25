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
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Pembelian</h5>
                </div>
                <div class="card-body">
                  <table id="buttons-datatables" class="table nowrap align-middle" style="width:100%">
                      <thead>
                          <tr>
                              <th>No.</th>
                              <th>Customer</th>
                              <th>Perusahaan</th>
                              <th>No. Kontak</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>01</td>
                              <td>Ari</td>
                              <td>Ikhtiar Berkah</td>
                              <td>087689098765</td>
                          </tr>
                          <tr>
                              <td>02</td>
                              <td>Chayrul</td>
                              <td>Senopati</td>
                              <td>087689098770</td>
                          </tr>
                          <tr>
                              <td>03</td>
                              <td>Ira</td>
                              <td>Berkah</td>
                              <td>088689098765</td>
                          </tr>
                          <tr>
                              <td>04</td>
                              <td>Ai</td>
                              <td>Ikhtiar</td>
                              <td>087689099965</td>
                          </tr>
                          <tr>
                              <td>05</td>
                              <td>Arifin</td>
                              <td>Senopati Jaya</td>
                              <td>087689698765</td>
                          </tr>
                          <tr>
                              <td>06</td>
                              <td>Chayrul Arifin</td>
                              <td>Laundry</td>
                              <td>087669698765</td>
                          </tr>
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
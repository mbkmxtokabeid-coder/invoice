@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
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
                <h4 class="mb-sm-0">Kategori</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Kategori</a></li>
                        <li class="breadcrumb-item active">Daftar Kategori</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

        {{-- Start Row 2 --}}
        <div class="row pb-2 align-items-end" style="margin-top:-10px;">
          {{-- Start Button Add Barang --}}
          <div class="col-sm-3" style="margin-right: -150px;">
            <a href="{{route('tambah.kategori')}}" class="btn btn-md btn-primary mt-2">
              <i class="las la-plus"></i>Tambah Kategori</a>
            </div>
            <div class="mt-3">
              @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session()->has('update'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('update') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session()->has('delete'))
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('delete') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            {{-- End Button Add Barang --}}
          </div>
        {{-- End Row 2 --}}

          {{-- Start Row 3 --}}
        <div class="row">
          {{-- Start Coloumn --}}
          <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Kategori</h5>
                </div>
                <div class="card-body">
                  <table id="scroll-horizontal" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Invoice</th>
                                <th>Kode Invoice</th>
                                <th>Perusahaan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($invoices as $invoice)

                            <tr>
                              <td><div class="text-center">{{$loop->iteration}}</div></td>
                              <td>{{$invoice->nama_invoice}}</td>
                              <td>{{$invoice->kode_invoice}}</td>
                              <td>{{$invoice->nama_perusahaan}}</td>
                              <td>
                                <a href="#" onclick="showEditConfirmation('{{ $invoice->nama_invoice }}', {{ $invoice->id }})" class="btn btn-warning">
                                  <i class="ri-pencil-line"></i>
                              </a>
                              <form action="{{ route('destroy.kategori',['id'=>$invoice->id]) }}" method="POST" id="deleteForm{{ $invoice->id }}">
                                  @csrf
                                  @method('delete')
                                  <a href="#" onclick="showConfirmation('{{ $invoice->nama_invoice }}', {{ $invoice->id }})" class="btn btn-danger">
                                      <i class="ri-delete-bin-6-line"></i>
                                  </a>
                              </form>
                              </td>
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

@section('plugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
{{-- <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> --}}
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="{{asset('js/pages/datatables.init.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>
<script src="{{asset('js/halaman/barang.js')}}"></script>

<script>
function showEditConfirmation(NamaInvoice, InvoiceID ) {
  Swal.fire({
      title: 'Konfirmasi',
      text: 'Yakin ingin mengedit kategori ' + NamaInvoice + '?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          // Redirect to the edit page with the specified ID
          window.location.href = '/invoice/edit-kategori/' + InvoiceID ;
      }
  });
}
</script>


<script>
function showConfirmation(NamaInvoice, InvoiceID ) {
  Swal.fire({
      title: 'Konfirmasi',
      text: 'Yakin kategori ' + NamaInvoice + ' dihapus?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya',
      cancelButtonText: 'Batal'
  }).then((result) => {
      if (result.isConfirmed) {
          // Submit the form with the specified ID
          document.getElementById('deleteForm' + InvoiceID ).submit();
      }
  });
}
</script>
@endsection
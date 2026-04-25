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
                <h4 class="mb-sm-0">Perusahaan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Perusahaan</a></li>
                        <li class="breadcrumb-item active">Daftar Perusahaan</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    {{-- Start Row 1  --}}
    {{-- End Row 1  --}}

        {{-- Start Row 2 --}}
        <div class="row pb-2 align-items-end" style="margin-top:-10px;">
          {{-- Start Button Add Barang --}}
          <div class="col-sm-3" style="margin-right: -150px;">
            <a href="{{route('perusahaan.add')}}" class="btn btn-md btn-primary mt-2">
              <i class="las la-plus"></i>Tambah Perusahaan</a>
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
                    <h5 class="card-title mb-0">Daftar Perusahaan</h5>
                </div>
                <div class="card-body">
                    <table id="alternative-pagination" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Logo</th>
                                <th>Nama Perusahaan</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($company as $item)
                          <tr>
                            <td class="text-center" style="width: 10px;">{{$loop->iteration}}</td>
                            <td><img src="{{ asset('storage/images/perusahaan/'.$item->logo) }}" alt="Gambar" style="max-width:70px"></td>
                            <td>{{$item->nama_perusahaan}}</td>
                            <td>{{$item->alamat_perusahaan}}</td>
                            <td>{{$item->no_hp}}</td>
                            <td>
                              <div class="dropdown text-center">
                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="las la-ellipsis-h align-middle fs-18"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{route('perusahaan.edit', ['id'=>$item->id])}}"><i class="las la-pen fs-18 align-middle me-2 text-muted"></i>
                                            Edit</a>
                                    </li>
                                    
                                    <li class="dropdown-divider"></li>
                                    <li>
                                      <form action="{{ route('perusahaan.delete',['id'=> $item->id]) }}" method="POST" id="deleteperusahaan{{ $item->id }}">
                                          @csrf
                                          @method('delete')
                                          <button class="dropdown-item" type="button" onclick="showConfirmation('{{ $item->nama_perusahaan }}', '{{ $item->id }}')">
                                              <i class="las la-trash-alt fs-18 align-middle me-2 text-muted"></i>
                                              Delete
                                          </button>
                                      </form>
                                  </li>
                                </ul>
                            </div>
                            </td>
                        </tr>
                        @endforeach

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
  <script>
    function showConfirmation(perusahaan, Id) {
        Swal.fire({
            html: '<lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#eee966,secondary:#c71f16" state="hover-2" style="width:200px;height:200px"></lord-icon>' +
                  '<p>Apakah Anda ingin menghapus Perusahaan ' + perusahaan + '?</p>',
            showCancelButton: true,
            confirmButtonColor: '#c71f16',
            cancelButtonColor: '#eee966',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus',
            customClass: {
                popup: 'swal2-popup' // Menggunakan gaya khusus untuk SweetAlert
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form with the specified ID
                document.getElementById('deleteperusahaan' + Id).submit();
            }
        });
    }
</script>
@endsection

@section('plugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
{{-- <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> --}}
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="{{asset('js/pages/datatables.init.js')}}"></script>
@endsection
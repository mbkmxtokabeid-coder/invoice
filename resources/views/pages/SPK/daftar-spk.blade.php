  @section('head')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.43.0/apexcharts.min.js" integrity="sha512-vv0F8Er+ByFK3l86WDjP5Zc0h8uxNWPzF+l4wGK0/BlHWxDiFHbYr/91dn8G0OO8tTnN40L4s2Whom+X2NxPog==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.43.0/apexcharts.min.css" integrity="sha512-nnNXPeQKvNOEUd+TrFbofWwHT0ezcZiFU5E/Lv2+JlZCQwQ/ACM33FxPoQ6ZEFNnERrTho8lF0MCEH9DBZ/wWw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script>
    setTimeout(function () {
    location.reload();
  }, 600000);
  </script>
  <script>

    Pusher.logToConsole = true;

    var pusher = new Pusher('55bca2ab4a2ee71bf08f', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('spk-channel');
    channel.bind('spk-created', function(data) {

      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "-",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
      toastr.error('SPK '+JSON.stringify(data.spk) + ' Telah Dibuat',{
        onclick:function(){
          var spkId = data.spk.id;
          console.log(spkId);
          redirectToSPK(spkId);
        }
      })
      // var audio = new Audio('public/sound-alert.mp3');
      // audio.play();
    });

  </script>
  @if(session('reload'))
      <script>
          // Using JavaScript to reload the page
          setTimeout(function () {
              location.reload();
          }, 1000); // Reload the page after 2 seconds (adjust the timing as needed)
      </script>
    @endif
  <script>
    function redirectToSPK(id) {
      var url = '/invoice/lihat-spk/'+id;
      window.location.href = url;
    }
  </script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



  <style>
    .toast-error {
    background-color: #F79327 !important;

  }
  </style>

  <style>
    .no-caret::after {
      display: none !important;
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
              <input type="hidden" name="spk_delete_id" id="spk_id">
              <lord-icon
              src="https://cdn.lordicon.com/tdrtiskw.json"
              trigger="loop"
              colors="primary:#eee966,secondary:#c71f16"
              state="hover-2"
              style="width:150px;height:150px">
              </lord-icon>
              <div class="mt-4">
                <h4 class="mb-3">Apakah ingin menghapus data?</h4>
                <div class="hstack gap-2 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <form id="deleteForm" action="{{ url('/delete-spk') }}" method="POST">
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
                  <h4 class="mb-sm-0">SPK</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">SPK</a></li>
                          <li class="breadcrumb-item active">Daftar SPK</li>
                      </ol>
                  </div>

              </div>
          </div>
      </div>
      <!-- end page title -->
      {{-- Start Row 1  --}}
      <div class="row">
        <div class="col-sm-3">
          <a href="#" class="btn btn-lg btn-warning">
            <i class="las la-tools me-1"></i>Belum Selesai<span class="badge bg-danger ms-1">{{$belum_selesai}}</span></a>
          </div>
          <div class="col-sm-3" ">
            <a href="#" class="btn btn-lg btn-danger">
              <i class=" las la-exclamation me-1"></i>Belum Diproses<span class="badge bg-primary ms-1">{{$belum_proses}}</span></a>
            </div>
          </div>
          {{-- End Row 1  --}}

          {{-- Start Row 2 --}}

          {{-- End Row 2 --}}
          {{-- Start Row 3 --}}
          <!--<div class="row pb-2 align-items-end" style="margin-top:-10px;">-->
          <!--  {{-- Start Button Add SPK --}}-->
          <!--  @if (Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik'))-->

          <!--  <div class="col-sm-3" style="margin-right: -150px; margin-bottom: -15px; margin-top:10px;">-->
          <!--    <a href="/tambah-spk" class="btn btn-md btn-primary mt-2">-->
          <!--      <i class="las la-plus"></i>Tambah SPK</a>-->
          <!--    </div>-->
            @endif
              {{-- End Button Add SPK --}}
            </div>
            <div class="row mt-3">
              <div class="col-xl-12">
                  <div class="card">
                      <div class="card-header align-items-center d-flex">
                          <h4 class="card-title flex-grow-1 mb-0">SPK Timeline Charts</h4>
                          <div>
                            {{-- <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('day')" id="btn-day">
                                Today
                            </button> --}}
                            <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('week')" id="btn-week">
                                1W
                            </button>
                            <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('month')" id="btn-month">
                              1M
                          </button>
                            {{-- <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('year')" id="btn-year">
                                1Y
                            </button> --}}

                            {{-- <button type="button" class="btn btn-soft-info btn-sm" onclick="timeFrame('month')">
                                6M
                            </button> --}}
                          </div>

                      </div><!-- end card header -->
                      <div class="card-body">
                          <div id="column_chart" data-colors='[ "--in-primary", "--in-success"]' class="apex-charts" dir="ltr"></div>
                      </div><!-- end card-body -->
                  </div><!-- end card -->
              </div>
            </div>
            {{-- End Row 3 --}}
            {{-- Start Row 4 --}}
          <div class="row mt-3">
            <div class="col-xl-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title mb-0">Daftar SPK</h5>
                </div>
                <div class="card-body">
                  {{-- <div class="table-responsive table-card"> --}}
                    <table class="table table-nowrap dt-responsive align-middle table-hover table-bordered mb-0" id="scroll-horizontal" >
                      <thead>
                        <tr class="text-muted text-uppercase">
                          <th scope="col">No.</th>
                          <th scope="col" style="width: 12%;" class="text-center">Status</th>
                          @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik')
                        
                          <th scope="col">Nomor Invoice</th>
                          <th scope="col" >Tanggal SPK</th>
                          @endif
                          
                          <th scope="col">Pekerjaan</th>
                          <th scope="col" class="text-center">Pelanggan</th>
                          <th scope="col" style="width: 8%;">Jenis</th>
                          <th scope="col">Target Penyelesaian</th>
                          <th scope="col"class="text-center">Timeline</th>
                          <th scope="col" style="width: 10%;" class="text-center">Respon</th>

                          <th scope="col" class="text-center" style="width: 12%;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($spk as $surat)
                        <tr @if ($surat->color == 'red') class="text-danger"
                          @elseif ($surat->express == 'Y' && $surat->status_kerja != 'Selesai') class="text-warning"
                          @elseif ($surat->color == 'purple') class="text-success"
                          @elseif ($surat->status_kerja == 'Selesai' || $surat->status_spk == 'Selesai') class="text-primary"
                          @endif>
                          <td>
                            <p class="fw-medium mb-0">{{$loop->iteration}}</p>
                          </td>
                          
                          
                          <td class="text-center">
                    @if (Auth::check())
                      {{-- Jika role Admin --}}
                      @if (Auth::user()->role === 'Admin' && $surat->timeline_produksi)
                        <div class="dropdown ">
                          @if ($surat->status_spk === 'Selesai')
                            <span class="badge badge-soft-primary p-2">{{ $surat->status_spk }}</span>
                          @else
                            <button class="btn btn-md dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <span class="badge badge-soft-danger p-2">{{ $surat->status_spk }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              @if ($surat->status_spk === 'Belum Selesai')
                                <li>
                                  <a class="dropdown-item" href="{{ url('/ubah-status-spk/' . $surat->id) }}">
                                    <i class="las la-clipboard-check fs-18 align-middle me-2 text-muted"></i> Tandai Selesai
                                  </a>
                                </li>
                              @endif
                            </ul>
                          @endif
                        </div>
                  
                      {{-- Jika role Produksi --}}
                                            @elseif (
                              Auth::user()->role === 'Produksi' 
                              && $surat->status_spk === 'Belum Selesai' 
                              && $surat->status_kerja === 'Selesai'
                          )
                              @php
                                  $adminWa = '628115239999'; // ganti dengan nomor WA Admin
                                  $linkApprove = url('/ubah-status-spk/' . $surat->id);
                                  $pesan = urlencode("Halo Admin, mohon approve status SPK untuk surat Nomor Invoice: {$surat->nomor_invoice}. Link approve: {$linkApprove}");
                                  $waUrl = "https://wa.me/{$adminWa}?text={$pesan}";
                              @endphp
                              <a href="{{ $waUrl }}" target="_blank" class="btn btn-success btn-sm">
                                  <i class="lab la-whatsapp fs-18 align-middle me-1"></i> Minta Approve Admin
                              </a>
                          
                          {{-- Role lain (atau kondisi tidak terpenuhi) --}}
                          @else
                              @if ($surat->status_spk === 'Selesai')
                                  <span class="badge badge-soft-primary p-2">{{ $surat->status_spk }}</span>
                              @else
                                  <span class="badge badge-soft-danger p-2">{{ $surat->status_spk }}</span>
                              @endif
                          @endif
                          @endif

                  </td>

                          
                          
                          @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik')
                        
                          <td>
                            <p class="fw-medium mb-0 text-center">{{$surat->nomor_invoice}}</p>
                          </td>
                          <td>
                            <p class="fw-medium mb-0">{{$surat->tgl_mulai}}</p>
                          </td>
                          @endif

                          <td>
                            <p class="fw-medium mb-0">{{$surat->pekerjaan}}</p>
                          </td>

                          <td>
                            <p class="fw-medium text-center mb-0">{{$surat->customer}}</p>
                          </td>
                          <td>
                            <p class="fw-medium mb-0">{{$surat->jenis_bahan}}</p>
                          </td>
                          <td>
                            <p class="fw-medium mb-0 text-center">{{$surat->target_selesai}}</p>
                          </td>
                          <td>
                            <p id="timeline-{{$surat->id}}" data-spk-id="{{$surat->id}}" class="timeline fw-medium mb-0 text-center">{{$surat->timeline}}</p>
                            <p class="timeline fw-medium mb-0 text-center">{{$surat->timeline_design}}</p>
                            <p class="timeline fw-medium mb-0 text-center">{{$surat->timeline_produksi}}</p>
                          </td>

                          <td class="text-center">
                            @if (Auth::check() && Auth::user()->role == 'Produksi')
                            <div class="dropdown">
                              @if ($surat->status_kerja === 'Selesai')
                                <span class="badge badge-soft-primary align-self-center p-2">{{$surat->status_kerja}}</span>
                              @else
                                <button class="btn btn-md dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  @if($surat->status_kerja === 'Proses Design' || $surat->status_kerja === 'Proses Produksi')
                                    <a href=""><span class="badge badge-soft-success p-2">{{$surat->status_kerja}}</span></a>
                                  @else
                                    <a href=""><span class="badge badge-soft-danger p-2">{{$surat->status_kerja}}</span></a>
                                  @endif
                                </button>
                              @endif
                              <ul class="dropdown-menu dropdown-menu-end">
                                @if ($surat->status_kerja == 'Belum Diproses')
                                  <li>
                                    <a class="dropdown-item" href="/ubah-proses-spk/{{$surat->id}}">
                                            <i class="las la-tools fs-18 align-middle me-2 text-muted"></i>
                                            Proses Design
                                        </a>
                                    </li>
                                @elseif ($surat->status_kerja == 'Proses Design')
                                    <li>
                                        <a class="dropdown-item" href="/ubah-proses-spk/{{$surat->id}}">
                                            <i class="las la-clipboard-check fs-18 align-middle me-2 text-muted"></i>
                                            Proses Produksi
                                        </a>
                                    </li>
                                @elseif ($surat->status_kerja == 'Proses Produksi')
                                    <li>
                                        <a class="dropdown-item" href="/ubah-proses-spk/{{$surat->id}}">
                                            <i class="las la-clipboard-check fs-18 align-middle me-2 text-muted"></i>
                                            Selesai
                                        </a>
                                    </li>
                                @endif
                              </ul>
                            </div>
                            @elseif(Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik'))
                              @if ($surat->status_kerja === 'Selesai')
                                <span class="badge badge-soft-primary align-self-center p-2">{{$surat->status_kerja}}</span>
                              @elseif($surat->status_kerja === 'Proses Design' || $surat->status_kerja === 'Proses Produksi')
                                <span class="badge badge-soft-success p-2">{{$surat->status_kerja}}</span>
                              @else
                                <span class="badge badge-soft-danger p-2">{{$surat->status_kerja}}</span>
                              @endif
                            @endif
                          </td>

                          <td>
                            <div class="dropdown text-center">
                              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="las la-ellipsis-h align-middle fs-18"></i>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">
                                  <li>
                                      <a class="dropdown-item" href="{{ url('/lihat-spk/' . $surat->id) }}"><i class="las la-eye fs-18 align-middle me-2 text-muted"></i>
                                          Lihat</a>
                                  </li>
                                  @if (Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik'))
                                  <li>
                                    <a class="dropdown-item" href="{{ url('/cetak-spk/' . $surat->id) }}"><i class="las la-print fs-18 align-middle me-2 text-muted"></i>
                                        Cetak</a>
                                  </li>
                                  <!--<li>-->
                                  <!--  <a class="dropdown-item" href="/edit-spk/{{$surat->id}}"><i class="las la-pen fs-18 align-middle me-2 text-muted"></i>-->
                                  <!--      Edit</a>-->
                                  <!--</li>-->
                                  <li class="dropdown-divider"></li>
                                  <li>
                                      <button data-bs-toggle="modal" data-bs-target="bs-example-modal-center" class="dropdown-item hapus-btn" value="{{$surat->id}}" data-url="{{ url('/delete-spk/' . $surat->id) }}">
                                      <i class="las la-trash-alt fs-18 align-middle me-2 text-muted"></i>
                                      Delete
                                      </button>
                                  </li>
                                  @endif
                              </ul>
                          </div>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  {{-- </div> --}}
                </div>
              </div>
            </div>
          </div>
          {{-- End Row 4 --}}
      </div>
    </div>
    <script>
      const nomorInvWeek = {!! json_encode($noInvWeek) !!};
      const tlDesignsWeek = {!! json_encode($tlDesgnWeek) !!};
      const tlProductionsWeek = {!! json_encode($tlProdWeek) !!};
      const tlKeseluruhanWeek = {!! json_encode($tlWeek) !!};
      const nomorInvMonth = {!! json_encode($noInvMonth) !!};
      const tlDesignsMonth = {!! json_encode($tlDesgnMonth) !!};
      const tlKeseluruhanMonth = {!! json_encode($tlMonth) !!};
      const tlProductionsMonth = {!! json_encode($tlProdMonth) !!};

    </script>
  @endsection

  @section('plugins')
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="{{asset('js/pages/datatables.init.js')}}"></script>
  <script src="{{asset('js/halaman/spk.js')}}?v={{ time() }}"></script>
  <script src="{{asset('js/halaman/apexcharts.min.js')}}"></script>
  @endsection
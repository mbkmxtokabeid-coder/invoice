@section('head')
{{-- <link href="{{asset('libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" /> --}}
@endsection

@extends('layout.template')
@section('content')

{{--
    @extends('layout.menu')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    @section('content')
    <div class="main-content">


        {{-- @extends('layout.pagetitle') --}}

        <div class="page-content">
         <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard Ibekami</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>

                    </div>
                </div>
              </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Payment Activity</h4>
                                    <div>
                                       @if (Auth::user()->role === 'Pemilik')
                                        <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('day')" id="btn-day">
                                            Today
                                        </button>
                                        <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('week')" id="btn-week">
                                            1W
                                        </button>
                                        <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('month')" id="btn-month">
                                            1M
                                        </button>
                                        <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('year')" id="btn-year">
                                            1Y
                                        </button>
                                        <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('lastYear')" id="btn-lastYear">
                                            LastYear
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="timeFrame('month')" id="btn-month">
                                            1M
                                        </button>
                                    @endif

                                        {{-- <button type="button" class="btn btn-soft-info btn-sm" onclick="timeFrame('month')">
                                            6M
                                        </button> --}}

                                    </div>

                                </div>
                                <div class="card-body py-1">
                                    <div class="row gy-2">
                                        @if (auth()->user()->role === 'Pemilik')

                                        <div class="col-md-4">
                                            <h4 class="fs-22 mb-0">Rp. {{$jumlahPerTahun}}
                                                <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">
    Year Incomes until {{ \Carbon\Carbon::now()->format('d M Y') }}
</span></h4>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex main-chart justify-content-end">
                                                <div class="px-4 border-end">
                                                    <h4 class="text-primary fs-22 mb-0" id="harga">Rp.
                                                        {{-- {{$jumlahPerBulan}} --}}
                                                        <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span></h4>
                                                </div>
                                                <!--<div class="ps-4">-->
                                                <!--    <h4 class="text-primary fs-22 mb-0">Rp. {{$jumlahPerBulan}}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Total Seluruh Pendapatan Perbulan</span></h4>-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-4">
                                            <h4 class="fs-22 mb-0">Rp. {{$jumlahBBPerTahun}}
                                                <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Year Outstanding</span></h4>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex main-chart justify-content-end">
                                                <div class="ps-4">
                                                    <h4 class="text-primary fs-22 mb-0">Rp. {{$jumlahBBPerBulan}}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Monthly Outstanding</span></h4>
                                                </div>
                                            </div>
                                        </div>

                                        @endif
                                    </div>

                                    <div id="stacked_column_chart" class="apex-charts" data-colors='["--in-primary", "--in-light"]' dir="ltr"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="card-title mb-2 text-truncate">Structure</h5>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <div class="dropdown">
                                                <a class="text-reset" href="" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <span class="fw-semibold text-uppercase fs-14">Sort By:</span> <span
                                                        class="text-muted" id="periode"><i class="las la-angle-down fs-12 ms-2"></i></span>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @if (auth()->user()->role === 'Pemilik')
                                                    <a class="dropdown-item" href="#" onclick="timeRange('year')">Yearly</a>
                                                    <a class="dropdown-item" href="#" onclick="timeRange('lastYear')">Last Year</a>
                                                    <a class="dropdown-item" href="#" onclick="timeRange('week')">Weekly</a>
                                                    @endif
                                                    <a class="dropdown-item" href="#" onclick="timeRange('month')">Monthly</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="structure_widget" data-colors='["--in-primary", "--in-primary-rgb, 0.7", "--in-light"]' class="apex-charts" dir="ltr"></div>


                                    <div class="px-2">
                                        <div class="structure-list d-flex justify-content-between border-bottom">
                                            <p class="mb-0"><i class="las la-dot-circle fs-18 text-primary me-2"></i>Terbayar</p>
                                            <div>
                                                <span class="pe-2 pe-sm-5" id="terbayar"></span>
                                                <span class="badge" id="persenTerbayar"> </span>
                                            </div>
                                        </div>
                                        <div class="structure-list d-flex justify-content-between border-bottom">
                                            <p class="mb-0"><i class="las la-dot-circle fs-18 text-primary me-2"></i>Belum Bayar</p>
                                            <div>
                                                <span class="pe-2 pe-sm-5" id="belumTerbayar"></span>
                                                <span class="badge" id="persenBlmTerbayar"> </span>
                                            </div>
                                        </div>
                                        <div class="structure-list d-flex justify-content-between pb-0">
                                            <p class="mb-0"><i class="las la-dot-circle fs-18 text-primary me-2"></i>Batal</p>
                                            <div>
                                                <span class="pe-2 pe-sm-5" id="batal"></span>
                                                <span class="badge" id="persenBatal"> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                {{-- BATAS ANTARA CHART UMUM DENGAN CHART PO--}}

                    <!-- end row -->

                    <div class="row">
                        <!--PO-->
                    <!--    <div class="col-xl-8">-->
                    <!--        <div class="card">-->
                    <!--            <div class="card-header border-0 align-items-center d-flex">-->
                    <!--                <h4 class="card-title mb-0 flex-grow-1">PO Activity</h4>-->
                    <!--                <div>-->
                    <!--                    @if (Auth::user()->role === 'Pemilik')-->

                    <!--                    <button type="button" class="btn btn-soft-info tombolPO btn-sm" onclick="timeFramePO('day')" id="btn_day">-->
                    <!--                        Today-->
                    <!--                    </button>-->
                    <!--                    <button type="button" class="btn btn-soft-info tombolPO btn-sm" onclick="timeFramePO('week')" id="btn_week">-->
                    <!--                        1W-->
                    <!--                    </button>-->
                    <!--                    <button type="button" class="btn btn-soft-info tombolPO btn-sm" onclick="timeFramePO('year')" id="btn_year">-->
                    <!--                        1Y-->
                    <!--                    </button>-->
                    <!--                    @endif-->
                    <!--                    <button type="button" class="btn btn-soft-info tombolPO btn-sm" onclick="timeFramePO('month')" id="btn_month">-->
                    <!--                        1M-->
                    <!--                    </button>-->
                    <!--                    {{-- <button type="button" class="btn btn-soft-info btn-sm" onclick="timeFrame('month')">-->
                    <!--                        6M-->
                    <!--                    </button> --}}-->

                    <!--                </div>-->

                    <!--            </div>-->
                    <!--            <div class="card-body py-1">-->
                    <!--                <div class="row gy-2">-->
                    <!--                    @if (auth()->user()->role === 'Pemilik')-->

                    <!--                    <div class="col-md-4">-->
                    <!--                        <h4 class="fs-22 mb-0 text-warning">Rp. {{$jumlahPO}}-->
                    <!--                            <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">ALL PO</span></h4>-->
                    <!--                    </div>-->
                    <!--                    <div class="col-md-8">-->
                    <!--                        <div class="d-flex main-chart justify-content-end">-->
                    <!--                            <div class="px-4 border-end">-->
                    <!--                                <h4 class="text-danger fs-22 mb-0" id="">Rp.-->
                    <!--                                    {{$jumlahPOBlmLunas}}-->
                    <!--                                    <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">ALL OUTSTANDING PO</span></h4>-->
                    <!--                            </div>-->
                    <!--                            <div class="ps-4">-->
                    <!--                                <h4 class="text-primary fs-22 mb-0">Rp. {{$jumlahPOLunas}}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">ALL SETTLED PO</span></h4>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                    @else-->
                    <!--                    <div class="col-md-4">-->
                    <!--                        <h4 class="fs-22 mb-0">Rp. {{$jumlahBBPerTahunPO}}-->
                    <!--                            <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">All Outstanding PO</span></h4>-->
                    <!--                    </div>-->
                    <!--                    <div class="col-md-4">-->
                    <!--                        <div class="d-flex main-chart justify-content-end">-->
                    <!--                            <div class="ps-4">-->
                    <!--                                <h4 class="text-danger fs-22 mb-0">Rp. {{$jumlahBBPerBulanPO}}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Monthly Outstanding PO</span></h4>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->

                    <!--                    @endif-->
                    <!--                </div>-->
                         {{-- BATAS MULAI UNTUK PO SESUAI TOMBOL YANG DITEKAN --}}
                    <!--                <div class="row gy-2 mt-2">-->
                    <!--                    @if (auth()->user()->role === 'Pemilik')-->

                    <!--                    <div class="col-md-4">-->
                    <!--                        <h4 class="fs-22 mb-0 text-warning" id="jumlahPO">Rp.-->
                    <!--                            <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2"></span></h4>-->
                    <!--                    </div>-->
                    <!--                    <div class="col-md-8">-->
                    <!--                        <div class="d-flex main-chart justify-content-end">-->
                    <!--                            <div class="px-4 border-end">-->
                    <!--                                <h4 class="text-danger fs-22 mb-0" id="jumlahOutstandingPO">Rp.-->
                    <!--                                    {{-- {{$jumlahPerBulan}} --}}-->
                    <!--                                    <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">PO Terbayar</span></h4>-->
                    <!--                            </div>-->
                    <!--                            <div class="ps-4">-->
                    <!--                                <h4 class="text-primary fs-22 mb-0" id="jumlahSettlePO">Rp. <span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2"></span></h4>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                    @endif-->
                    <!--                </div>-->

                    <!--                <div id="stacked_column_chart_po" class="apex-charts" data-colors='["--in-primary", "--in-light"]' dir="ltr"></div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->

                    <!--BATAS-->

                    <!--    <div class="col-xl-4">-->
                    <!--        <div class="card">-->
                    <!--            <div class="card-header border-0 align-items-center d-flex">-->
                    <!--                <h4 class="card-title mb-0 flex-grow-1">PO Invoice List</h4>-->
                    <!--            </div>-->
                    <!--            <div class="card-body pt-2">-->
                    <!--                <div class="table-responsive table-card">-->
                    <!--                    <table class="table table-striped table-nowrap align-middle mb-0">-->
                    <!--                        <thead>-->
                    <!--                            <tr class="text-muted text-uppercase">-->
                    <!--                                <th scope="col">Customer</th>-->
                    <!--                                <th scope="col">Tanggal</th>-->
                    <!--                                <th scope="col" style="width: 16%;">Status</th>-->
                    <!--                            </tr>-->
                    <!--                        </thead>-->

                    <!--                        <tbody>-->
                    <!--                          @foreach ($invoicePO as $invoice)-->
                    <!--                            <tr>-->
                    <!--                             <td>-->
                    <!--                                <p class="text-body align-middle">{{$invoice->customer}}</p>-->
                    <!--                             </td>-->
                    <!--                             <td>-->
                    <!--                                {{$invoice->formatted_tgl}}-->
                    <!--                             </td>-->
                    <!--                             <td>-->
                    <!--                                @if ($invoice->status == 'Lunas')-->
                    <!--                                    <span class="badge badge-soft-primary p-2">Lunas</span>-->
                    <!--                                @elseif($invoice->status === 'Belum Lunas')-->
                    <!--                                    <span class="badge badge-soft-danger p-2">Belum Lunas</span>-->
                    <!--                                @endif-->
                    <!--                            </td>-->

                    <!--                            </tr>-->
                    <!--                            @endforeach-->
                            <!--            </tbody>    <!-- end tbody -->
                            <!--  </table><!-- end table -->
                            <!--</div><!-- end table responsive -->

                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
        
                        <!-- BATAS -->
                        <div class="col-xl-8">
                            <div class="card">
                                 <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Outstanding Vendor</h4>
                                </div>

                                <div class="row p-3">
                                    <div class="card-body">
                                        <div id="vendorChart" class="apex-charts" dir="ltr">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card" >
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Vendor List</h4>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="table-responsive table-card"   style="max-height: 425px;">
                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                            <thead style="position: sticky;
                                            top: 0; background-color:#fbfbfb;" >
                                                <tr class="text-muted text-uppercase">
                                                    <th scope="col">Nama Vendor</th>
                                                    <th scope="col">Total Pembelian</th>
                                                    <th scope="col">Total Sisa</th>
                                                    <th scope="col">Total Terbayar</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                              @foreach ($vendorList as $vnd)
                                                <tr>
                                                 <td>
                                                    <p class="text-body align-middle">{{$vnd->nama_vendor}}</p>
                                                 </td>
                                                 <td>
                                                    {{$vnd->formattedTotal}}
                                                 </td>
                                                 <td>
                                                    {{$vnd->formattedSisa}}
                                                 </td>
                                                 <td>
                                                    {{$vnd->formattedBayar}}
                                                 </td>


                                                </tr>
                                                @endforeach
                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div><!-- end table responsive -->

                                </div>
                            </div>
                        </div>
                        

                                    


                                    
{{--
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <!-- end row -->

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @extends('layout.footer')
        {{-- </div> --}}
        <!-- end main content-->
        @endsection


@section('plugins')
    <!-- apexcharts -->
    <script src="{{asset('libs/apexcharts/apexcharts.min.js')}}"></script>


    <!-- Vector map-->
    <script src="{{asset('libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{asset('libs/jsvectormap/maps/world-merc.js')}}"></script>

    <!-- Dashboard init -->
    <script>
        const hourArrayJS = {!! json_encode($hourArray) !!};
        const dateArrayJS = {!! json_encode($dateArray) !!};
        const dateArrayLastYearJS = {!! json_encode($dateArrayLastYear) !!};
        const weekArrayJS = {!! json_encode($weekArray) !!};
        const monthArrayJS = {!! json_encode($monthArray) !!};
        const monthArrayLastYearJS = {!! json_encode($monthArrayLastYear) !!};
        const totalJamArrayJS = {!! json_encode($totalJamArray) !!};
        const totalHarianArrayJS = {!! json_encode($totalHarianArray) !!};
        const totalHarianLastYearArrayJS = {!! json_encode($totalHarianArrayLastYear) !!};
        const totalMingguanArrayJS = {!! json_encode($totalMingguanArray) !!};
        const totalBulananArrayJS = {!! json_encode($totalBulananArray) !!};
        const totalBulananArrayLastYearJS = {!! json_encode($totalBulananArrayLastYear) !!};
        const totalPerHari = {!! json_encode($jumlahPerHari) !!};
        const totalPerMinggu = {!! json_encode($jumlahPerMinggu) !!};
        const totalPerBulan = {!! json_encode($jumlahPerBulan) !!};
        const totalPerTahun = {!! json_encode($jumlahPerTahun) !!};
        // Week
        const datasWeekJS = {!! json_encode($datasInWeek) !!};
        const percentInWeekJS = {!! json_encode($percentageInWeek) !!};
        const percentNotPaidWeekJS = {!! json_encode($percentageNotPaidWeek) !!};
        const percentCanceledWeekJS = {!! json_encode($percentageCanceledWeek) !!};
        // Month
        const datasMonthJS = {!! json_encode($datasInMonth) !!};
        const percentInMonthJS = {!! json_encode($percentageInMonth) !!};
        const percentNotPaidMonthJS = {!! json_encode($percentageNotPaidMonth) !!};
        const percentCanceledMonthJS = {!! json_encode($percentageCanceledMonth) !!};
        // Year
        const datasYearJS = {!! json_encode($datasInYear) !!};
        const percentInYearJS = {!! json_encode($percentageInYear) !!};
        const percentNotPaidYearJS = {!! json_encode($percentageNotPaidYear) !!};
        const percentCanceledYearJS = {!! json_encode($percentageCanceledYear) !!};

// ✅ TAMBAHKAN BAGIAN INI (Last Year)
        const datasLastYearJS = {!! json_encode($datasInLastYear) !!};
        const percentInLastYearJS = {!! json_encode($percentageInLastYear) !!};
        const percentNotPaidLastYearJS = {!! json_encode($percentageNotPaidLastYear) !!};
        const percentCanceledLastYearJS = {!! json_encode($percentageCanceledLastYear) !!};
        //PO

        const hourArrayJSPO = {!! json_encode($hourArrayPO) !!};
        const dateArrayJSPO = {!! json_encode($dateArrayPO) !!};
        const weekArrayJSPO = {!! json_encode($weekArrayPO) !!};
        const monthArrayJSPO = {!! json_encode($monthArrayPO) !!};
        const totalJamArrayJSPO = {!! json_encode($totalJamArrayPO) !!};
        const totalHarianArrayJSPO = {!! json_encode($totalHarianArrayPO) !!};
        const totalMingguanArrayJSPO = {!! json_encode($totalMingguanArrayPO) !!};
        const totalBulananArrayJSPO = {!! json_encode($totalBulananArrayPO) !!};
        const totalPerHariPO = {!! json_encode($jumlahPerHariPO) !!};
        const totalPerMingguPO = {!! json_encode($jumlahPerMingguPO) !!};
        const totalPerBulanPO = {!! json_encode($jumlahPerBulanPO) !!};
        const totalPerTahunPO = {!! json_encode($jumlahPerTahunPO) !!};
       
        const lunasTodayPO = {!! json_encode($jumlahPOLunasToday) !!};
        const blmLunasTodayPO = {!! json_encode($jumlahPOBlmLunasToday) !!};

        const lunasWeekPO = {!! json_encode($jumlahPOLunasWeek) !!};
        const blmLunasWeekPO = {!! json_encode($jumlahPOBlmLunasWeek) !!};

        const lunasMonthPO = {!! json_encode($jumlahPOLunasMonth) !!};
        const blmLunasMonthPO = {!! json_encode($jumlahPOBlmLunasMonth) !!};

        const lunasYearPO = {!! json_encode($jumlahPOLunasYear) !!};
        const blmLunasYearPO = {!! json_encode($jumlahPOBlmLunasYear) !!};
        
        const namaVendor = {!! json_encode($datavendor) !!};
        const totalSisa = {!! json_encode($datasisa) !!};
    </script>
    <script src="{{asset('js/halaman/dashboard.js')}}"></script>


@endsection


{{-- </body> --}}

{{-- </html> --}}

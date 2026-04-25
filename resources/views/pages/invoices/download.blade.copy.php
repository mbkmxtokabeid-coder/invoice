<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>{{$namaInv}} - {{$penjualan->customer}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta content="Percetakan Terbaik di Kota Medan" name="description" /> --}}
    <meta content="Ikhtiar Berkah" name="author" />
    <!-- App favicon -->

      <link rel="shortcut icon" href="{{asset('images/ikhtiarberkah.png')}}">

      <title>Ikhtiar Berkah</title>

      {{-- HEAD CSS  --}}

  <!-- Layout config Js -->
   <script src="{{asset('js/layout.js')}}"></script>
   <!-- Bootstrap Css -->
   <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />
<script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
{{-- <div class="main-content"> --}}

            {{-- <div class="page-content"> --}}
                <div class="container-fluid">

                    <!-- start page title -->

                    <!-- end page title -->

                    <div class="row justify-content-center">
                        <div class="col-xxl-12">
                            <div class="card" id="demo">
                                   <div class="card-body">
                                    <div class="row ">
                                        <div class="col-lg-12">
                                            <h3 class="fw-bold mb-3">Invoice: Ikhtiar Berkah </h3>
                                            <div class="row g-3">
                                                <div class="col-lg-4 col-4">
                                                    <p class="text-muted mb-1 text-uppercase fw-medium fs-14">Invoice No</p>
                                                    <h5 class="fs-16 mb-0"><span id="invoice-no">{{$penjualan->nomor_invoice}}</span></h5>
                                                </div>
                                                <div class="col-lg-6 col-6"></div>
                                                <!--end col-->
                                                <div class="col-lg-2 col-2">
                                                    <img src="{{asset('images/ikhtiarberkah.png')}}" class="card-logo card-logo-dark" alt="logo dark" height="35">

                                                </div>
                                                <!--end col-->
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        {{-- sini --}}
                                                        <p class="text-muted mb-1 text-uppercase fw-medium fs-14">Tanggal</p>
                                                        <h5 class="fs-16 mb-0"><span id="invoice-date">{{$formatTanggal}}</span></h5>
                                                    </div>
                                                    <h6 class="text-muted text-uppercase fw-semibold mb-1">Penerima</h6>
                                                    <h5 class="fw-bold mb-2" id="billing-name">{{$penjualan->customer}}</h5>
                                                    <p class="text-muted mb-1"><span>Perusahaan: </span><span id="billing-address-line-1" class="fw-bold">{{$penjualan->perusahaan}}</span></p>
                                                    <p class="text-muted mb-1"><span>Telepon: </span><span id="billing-phone-no" class="fw-bold">{{$penjualan->no_telepon}}</span></p>

                                                </div>
                                                <div class="col-lg-5 col-5" style="margin-left: 100px"></div>
                                                <div class="col-lg-2 col-2">
                                                    <p class="text-muted mb-1 text-uppercase fw-medium fs-14">Payment Status</p>
                                                    @if ($penjualan->status == 'Lunas')
                                                        <span class="badge badge-soft-primary fs-13 mb-3" id="payment-status">{{$penjualan->status}}</span>
                                                    @else
                                                        <span class="badge badge-soft-danger fs-13 mb-3" id="payment-status">{{$penjualan->status}}</span>
                                                    @endif
                                                    <h6 class="text-muted text-uppercase fw-semibold">Alamat</h6>
                                                <p class="text-muted mb-1" id="address-details">JL Setia Budi Komplek Setia Budi Point NO D-10 KEL TANJUNG SARI, MEDAN</p>
                                                <h6><span class="text-muted fw-normal"></span> <a href="https://ikhtiarberkah.com/" class="link-primary" target="_blank" id="website">www.ikhtiarberkah.com</a></h6>
                                                <h6><span class="text-muted fw-normal">Admin 1 : </span> <a href="https://bit.ly/admin2IB" class="link-primary" target="_blank" id="website">08112272727</a></h6>
                                                <h6><span class="text-muted fw-normal">Admin 2:</span> <a href="https://bit.ly/admin9IB" class="link-primary" target="_blank" id="website">08170769999</a></h6>
                                                </div>

                                                <!--end col-->
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mt-lg-0 ">


                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="row p-1 border-top border-top-dashed">
                                        <div class="col-lg-9">
                                            <div class="row g-3">

                                                <!--end col-->
                                               
                                                <!--end col-->
                                            </div>
                                            <!--end row-->
                                        </div><!--end col-->

                                        

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card-body p-3">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                                        <thead>
                                                            <tr class="table-active">
                                                                <th scope="col" style="width: 50px;">#</th>
                                                                <th scope="col">Deskripsi Barang</th>
                                                                <th scope="col">Harga</th>
                                                                <th scope="col">Qty</th>
                                                                <th scope="col" class="text-end">Jumlah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="products-list">
                                                            @foreach ($penjualan_barang as $index => $jual)
                                                                @foreach ($barang[$index] as $item)
                                                                   @php
                                                                    $nomor = $index + 1;
                                                                   @endphp
                                                                   <tr>
                                                                    <th scope="row">{{$nomor}}</th>
                                                                    <td class="text-start">
                                                                        <span class="fw-medium">{{$item->jenis_barang}}</span>
                                                                        <p class="text-muted mb-0">{{$jual->deskripsi_item}}</p>
                                                                    </td>
                                                                    <td>Rp.{{$hargaMod[$index]}}</td>
                                                                    <td>{{$jual->qty}}</td>
                                                                    <td class="text-end">{{$jumlahHarga[$index]}}</td>
                                                                </tr>
                                                                @endforeach
                                                            @endforeach

                                                        </tbody>
                                                    </table><!--end table-->
                                                </div>

                                                <div class="border-top border-top-dashed mt-0">
                                                    @if (strpos($penjualan->jenis_pembayaran, 'Cash') === false && $penjualan->no_rek === 'BNI')
                                                     <div class="col-lg-6 mt-1">
                                                         <p style="color:rgb(227, 25, 25);">Mohon Transfer Ke :<br>
                                                             BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999</p>
                                                     </div>
                                                     @elseif (strpos($penjualan->jenis_pembayaran, 'Cash') === false && $penjualan->no_rek === 'TKB')
                                                     <div class="col-lg-6 mt-1">
                                                         <p style="color:rgb(227, 25, 25);">Mohon Transfer Ke :<br>
                                                            Mandiri | A/N : PT. Total Karya Berkah | No. Rek : 1050009589999</p>
                                                     </div>
                                                    @endif

                                                    <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                                        <tbody>
                                                            <tr>
                                                                <td>Total</td>
                                                                <td class="text-end">{{$totHargaMod}}</td>
                                                            </tr>
                                                            <tr>
                                                                @if (!is_null($dp))
                                                                <td>DP</td>
                                                                <td class="text-end">-Rp.{{$dp}}</td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                            @if (!is_null($penjualan->diskon) && $penjualan->potongan == 0)
                                                            <td>Diskon<small class="text-muted">({{$persen}})</small></td>
                                                            <td class="text-end">- {{$biayaLain}}</td>
                                                            @endif
                                                            @if (!is_null($penjualan->potongan) && is_null($penjualan->ppn) && is_null($penjualan->diskon)
                                                            <td>Potongan</td>
                                                            <td class="text-end">- {{$biayaLain}}</td>
                                                            @endif
                                                            @if ($penjualan->potongan == 0 && !is_null($penjualan->ppn))
                                                            <td>PPN<small class="text-muted">({{$persen}})</small></td>
                                                            <td class="text-end"> {{$biayaLain}}</td>
                                                            @endif
                                                            @if (is_null($penjualan->diskon) && is_null($penjualan->potongan) && is_null($penjualan->ppn))
                                                            <td></td><br>
                                                            @endif
                                                            </tr>

                                                            <tr class="border-top border-top-dashed fw-bold fs-15"
                                                            @if ($penjualan->status === 'Belum Lunas')
                                                            style="color:rgb(227, 25, 25);"
                                                            @else
                                                            style="color: rgb(67,138,122);"
                                                            @endif>
                                                                <th scope="row" class="fw-bold fs-15">Sisa Pembayaran</th>
                                                                <th class="text-end fw-bold fs-15">Rp.{{$sisaBayarMod}}</th>

                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!--end table-->
                                                </div>


                                                <!--<div class="hstack gap-2 justify-content-end d-print-none mt-4">-->
                                                <!--    <a href="javascript:window.print()" class="btn btn-info"><i class="ri-printer-line align-bottom me-1"></i> Print</a>-->
                                                <!--    {{-- <a href="javascript:window.open(Downloads)" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a> --}}-->
                                                <!--</div>-->
                                            </div>
                                            <!--end card-body-->
                                        </div><!--end col-->
                                    </div>
                                   </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>

                </div>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-3">
                                <script>document.write(new Date().getFullYear())</script> © IkhtiarBerkah.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-start d-none d-sm-block">
                                     Develop by IkhtiarBerkah
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- container-fluid -->
            {{-- </div> --}}
            <!-- End Page-content -->


        {{-- </div> --}}
        <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('js/plugins.js')}}"></script>

{{-- @yield('plugins') --}}
<!-- App js -->
<script src="{{asset('js/app.js')}}"></script>
</html>
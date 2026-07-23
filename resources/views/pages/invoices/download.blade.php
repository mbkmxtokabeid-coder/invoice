<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>{{$namaInv}} - {{$penjualan->customer}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta content="Percetakan Terbaik di Kota Medan" name="description" /> --}}
    <meta content="Ibekami" name="author" />
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
                                            <h3 class="fw-bold mb-3"></h3>
                                            <div class="row g-3">
                                                <div class="col-lg-4 col-4">
                                                    <p class="text-muted mb-1 text-uppercase fw-medium fs-14">Invoice No</p>
                                                    <h5 class="fs-16 mb-0"><span id="invoice-no">{{$penjualan->nomor_invoice}}</span></h5>
                                                </div>
                                                <div class="col-lg-6 col-6"></div>
                                                <!--end col-->
                                                <div class="col-lg-2 col-2 text-center d-flex flex-column align-items-center">
                                                <img src="{{asset('images/Logo IBEKAMI.png')}}" class="card-logo card-logo-dark" alt="logo dark" height="50">
                                                <a href="https://ibekami.id/" class="link-primary mt-2" target="_blank" id="website">ibekami.id</a>
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
                                                <div class="col-lg-6 col-6" style="margin-left: 0px"></div>
                                                <div class="col-lg-2 col-2">
                                                    <!--<p class="text-muted mb-1 text-uppercase fw-medium fs-14">Payment Status</p>-->
                                                    <!--@if ($penjualan->status == 'Lunas')-->
                                                    <!--    <span class="badge badge-soft-primary fs-13 mb-3" id="payment-status">{{$penjualan->status}}</span>-->
                                                    <!--@else-->
                                                    <!--    <span class="badge badge-soft-danger fs-13 mb-3" id="payment-status">{{$penjualan->status}}</span>-->
                                                    <!--@endif-->
                                                    <h6 class="text-muted text-uppercase fw-semibold">Alamat</h6>
                                                <p class="text-muted mb-1" id="address-details">JL Setia Budi Komplek Setia Budi Point NO D-10 KEL TANJUNG SARI, MEDAN</p>
                                                <h6><span class="text-muted fw-normal"></span> <a href="https://ibekami.id/" class="link-primary" target="_blank" id="website">Ibekami.id</a></h6>
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
                                                        <style>
                                                            .table {
                                                                table-layout: fixed;
                                                                width: 100%;
                                                            }
                                                            
                                                            th:nth-child(1), td:nth-child(1) { /* Kolom Nomor */
                                                                width: 5%;
                                                            }
                                                            
                                                            th:nth-child(2), td:nth-child(2) { /* Deskripsi Barang */
                                                                width: 40%;
                                                            }
                                                            
                                                            th:nth-child(3), td:nth-child(3) { /* Harga */
                                                                width: 15%;
                                                            }
                                                            
                                                            th:nth-child(4), td:nth-child(4) { /* Qty */
                                                                width: 10%;
                                                            }
                                                            
                                                            th:nth-child(5), td:nth-child(5) { /* Jumlah */
                                                                width: 15%;
                                                            }
                                                            
                                                            td.text-start {
                                                                word-wrap: break-word;
                                                                white-space: normal;
                                                            }
                                                            
                                                            p {
                                                                white-space: normal !important;
                                                            }
                                                        </style>
                                                    
                                                        <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                                            <thead>
                                                                <tr class="table-active">
                                                                    <th scope="col">#</th>
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
                                                                                <div>
                                                                                    <span class="fw-medium">{{$item->jenis_barang}}</span>
                                                                                    <p class="text-muted mb-0">{{$jual->deskripsi_item}}</p>
                                                                                </div>
                                                                            </td>
                                                                            <td>Rp.{{$hargaMod[$index]}}</td>
                                                                            <td>{{$jual->qty}}</td>
                                                                            <td class="text-end">{{$jumlahHarga[$index]}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>


                                                

                                                <div class="border-top border-top-dashed mt-0">
                                                    @if (strpos($penjualan->jenis_pembayaran, 'Cash') === false && $penjualan->no_rek === 'BNI')
                                                     <div class="col-lg-6 mt-1">
                                                         <p style="color:rgb(227, 25, 25); font-size:15px;">Mohon Transfer Ke :
                                                             BNI | A/N : Oky Irawan | No. Rek : 816029999</p>
                                                     </div>
                                                     @elseif (strpos($penjualan->jenis_pembayaran, 'Cash') === false && $penjualan->no_rek === 'Mandiri')
                                                     <div class="col-lg-6 mt-1">
                                                         <p style="color:rgb(227, 25, 25); font-size:15px;">Mohon Transfer Ke :
                                                            BNI | A/N : Oky Irawan | No. Rek : 816029999</p>
                                                     </div>
                                                    @endif

                                                    <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                                        <tbody>
                                                            <tr>
                                                                <td>Total Harga</td>
                                                                <td class="text-end">{{$totalHarga}}</td>
                                                            </tr>
                                                        
                                                            
                                                            <tr>
                                                            @if (!is_null($penjualan->diskon) && $penjualan->potongan == 0)
                                                            <td>Diskon<small class="text-muted">({{$persen}})</small></td>
                                                            <td class="text-end">- {{$biayaLain}}</td>
                                                            @endif
                                                            @if (!is_null($penjualan->potongan) && $penjualan->potongan > 0 && is_null($penjualan->ppn) && is_null($penjualan->diskon))
                                                                <td>Spesial Diskon</td>
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
                                                            <tr>
                                                                <td>Total Pembayaran</td>
                                                                <td class="text-end">{{$totHargaMod}}</td>
                                                            </tr>
                                                            <tr>
                                                                @if (!is_null($dp))
                                                                <td>DP</td>
                                                                <td class="text-end">-Rp.{{$dp}}</td>
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
                                                <div style="position: absolute; width: 240mm; left: 0; right: 0; margin-left: auto; margin-right: auto; bottom:5px; font-size: 8px">
                                                  @if($toko == 'Total Karya Berkah')
                                                  <center><i>Kunjungi website Kami, tokabe.id | Total Karya Berkah, member of KADIN INDONESIA.</i></center>
                                                  @else
                                                  <center><i>Terima Kasih telah mendukung UMKM kami, dengan senang hati melayani.</i></center>
                                                  @endif
                                                </div>
                                            
                                                <div class="row">
                                                  <div class="column" style="text-align: center; font-size: 10px">
                                                    <div style="position: absolute; left: 20px;bottom: -5px;">
                                                      <h5>diterima oleh</h5>
                                                      <p><br><br><br><br><br><br></p>
                                                      <h5>( {{$penjualan->customer}} )</h5>
                                                    </div>
                                                  </div>
                                            
                                                  <div class="column" style="text-align: center; font-size: 10px">
                                                    <div style="position: absolute; right:340px;bottom: -5px;">
                                            
                                                      <h5>Medan,{{$formatTanggal}}</h5>
                                                      
                                                      @if($penjualan->status == 'Lunas')
                                                        <div style="position: relative; height: 90px; margin-top: 10px; margin-bottom: 10px; z-index: 1;">
                                                            <!-- GAMBAR STEMPEL -->
                                                            <img src="{{asset('images/Logo IBEKA ID.png')}}" style="position: absolute; top: 15px; left: 50%; transform: translateX(-70%); height: 80px; z-index: 1;" alt="Stempel Lunas">
                                                            <!-- GAMBAR TTD -->
                                                            <img src="{{asset('images/ttd.png')}}" style="position: absolute; top: 0; left: 50%; transform: translateX(-30%); height: 80px; z-index: 2;" alt="TTD Lunas">
                                                        </div>
                                                      @else
                                                        <p><br><br><br><br><br><br></p>
                                                      @endif

                                                      @if($toko == 'Total Karya Berkah')
                                                      <h5>Oky Irawan</h5>
                                                      @else
                                                      <h5>{{$admin}}</h5>
                                                      @endif
                                                    </div>
                                                  </div>
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
                <!--<footer class="footer">-->
                <!--    <div class="container-fluid">-->
                <!--        <div class="row">-->
                <!--            <div class="col-sm-3">-->
                <!--                <script>document.write(new Date().getFullYear())</script> © IkhtiarBerkah.-->
                <!--            </div>-->
                <!--            <div class="col-sm-6">-->
                <!--                <div class="text-sm-start d-none d-sm-block">-->
                <!--                    Develop by IkhtiarBerkah-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</footer>-->
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
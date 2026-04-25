<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>{{$penjualan->nomor_invoice}} - {{$penjualan->customer}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Ikhtiar Berkah" name="author" />
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('images/ikhtiarberkah.png')}}">
    <title>Total Karya Berkah</title>

    <!-- Layout config Js -->
    <script src="{{asset('js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />
    
    <script>
        window.onload = function () {
            window.print()
        }
    </script>
</head>

<body>
    <div class="container-fluid">
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
                                    <div class="col-lg-2 col-2 d-flex flex-column align-items-center text-center">
                                        <img src="{{asset('images/Logo-TKB.jpg')}}" class="card-logo card-logo-dark" alt="logo dark" height="50">
                                        <a href="https://tokabe.id/" class="link-primary" target="_blank" id="website">tokabe.id</a>
                                    </div>

                                    <div class="col-4">
                                        <div class="mb-3">
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
                                        <h6 class="text-muted text-uppercase fw-semibold">Alamat</h6>
                                        <p class="text-muted mb-1" id="address-details">JL Setia Budi Komplek Setia Budi Point NO D-10 KEL TANJUNG SARI, MEDAN</p>
                                        <h6><span class="text-muted fw-normal"></span> <a href="https://tokabe.id/" class="link-primary" target="_blank" id="website">www.tokabe.id</a></h6>
                                        <h6><span class="text-muted fw-normal">Admin 1 : </span> <a href="https://bit.ly/orderIB" class="link-primary" target="_blank" id="website">08112272727</a></h6>
                                        <h6><span class="text-muted fw-normal">Admin 2:</span> <a href="https://bit.ly/admin9IB" class="link-primary" target="_blank" id="website">08170769999</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mt-lg-0 "></div>
                            </div>
                        </div>

                        <div class="row p-1 border-top border-top-dashed">
                            <div class="col-lg-9">
                                <div class="row g-3"></div>
                            </div>
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
                                            th:nth-child(1), td:nth-child(1) { width: 5%; }
                                            th:nth-child(2), td:nth-child(2) { width: 40%; }
                                            th:nth-child(3), td:nth-child(3) { width: 15%; }
                                            th:nth-child(4), td:nth-child(4) { width: 10%; }
                                            th:nth-child(5), td:nth-child(5) { width: 15%; }
                                            td.text-start {
                                                word-wrap: break-word;
                                                white-space: normal;
                                            }
                                            p { white-space: normal !important; }
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
                                        <div class="col-lg-6 mt-1">
                                            <p style="color:rgb(227, 25, 25);">Mohon Transfer ke : {{$norek}}</p><br>
                                        </div>

                                        <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                            <tbody>
                                                <tr>
                                                    <td>Total</td>
                                                    <td class="text-end">{{$totHargaMod}}</td>
                                                </tr>
                                                <tr>
                                                    @if (!empty($dp) && $dp > 0)
                                                    <td>DP</td>
                                                    <td class="text-end">-Rp.{{$dp}}</td>
                                                    @endif
                                                </tr>
                                                
                                                {{-- LOGIKA PPN/DISKON/PPH YANG SUDAH KEBAl ERROR --}}
                                                @if (isset($penjualan->diskon) && (float)$penjualan->diskon > 0)
                                                <tr>
                                                    <td>Diskon<small class="text-muted"> ({{$persen}}%)</small></td>
                                                    <td class="text-end">- Rp.{{ ltrim($biayaLain, '-') }}</td>
                                                </tr>
                                                @elseif (isset($penjualan->potongan) && (float)$penjualan->potongan > 0)
                                                <tr>
                                                    <td>Spesial Diskon</td>
                                                    <td class="text-end">- {{$biayaLain}}</td>
                                                </tr>
                                                @elseif (isset($penjualan->ppn) && (float)$penjualan->ppn > 0)
                                                <tr>
                                                    <td>PPN<small class="text-muted"> ({{$persen}})</small></td>
                                                    <td class="text-end">{{$biayaLain}}</td>
                                                </tr>
                                                @elseif (isset($penjualan->pph) && (float)$penjualan->pph > 0)
                                                <tr>
                                                    <td>PPH<small class="text-muted"> ({{$persen}}%)</small></td>
                                                    <td class="text-end">Rp.{{$biayaLain}}</td>
                                                </tr>
                                                @endif

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
                                        
                                        <div class="column" style="text-align:center; font-size: 5px">
                                            <div style="position: absolute; right: 500px ; bottom: 30px;">
                                              <h5>Medan, {{$formatTanggal}}</h5>
                                              <p><br><br><br><br><br><br><br><br><br><br><br></p>
                                              <h5>{{$admin}}</h5>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('js/plugins.js')}}"></script>
    <script src="{{asset('js/app.js')}}"></script>

</body>
</html>
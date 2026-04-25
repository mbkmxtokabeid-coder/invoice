<!doctype html>
<html lang="en" data-layout-mode="dark" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

<meta charset="utf-8" />
<meta name="Kasir Ibekami" content="Ibekami">
<title>Ibekami Kasir</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Kasir Ibekami" name="description" />

<!-- App favicon -->

   <link rel="shortcut icon" href="{{asset('images/Logo IBEKA ID.png')}}">

      {{-- HEAD CSS  --}}

      @yield('head')
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Layout config Js -->
   <script src="{{asset('js/layout.js')}}"></script>
   <!-- Bootstrap Css -->
   <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />
  
  <style>
      .error-help-block{
        color: #ca131e;
    }
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    .spinner-border{
        width: 7rem;
        height: 7rem;
    }
  </style>
  </head>

  {{-- batas --}}
  <body>
    @include('sweetalert::alert')
    <!-- Begin page -->
    <div id="layout-wrapper">
        {{-- TOPBAR --}}
      <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box horizontal-logo">
                        <a href="/invoice" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{asset('images/ibekamiputih.png')}}" alt="" height="50">
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('images/ibekamiputih.png')}}" alt="" height="50">
                            </span>
                        </a>

                        <a href="/invoice" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{asset('images/ibekamiputih.png')}}" alt="" height="50">
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('images/ibekamiputih.png')}}" alt="" height="50">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                </div>

                <div class="d-flex align-items-center">

                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle" data-toggle="fullscreen">
                            <i class='las la-expand fs-24'></i>
                        </button>
                    </div>

                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle light-dark-mode">
                            <i class='las la-moon fs-24'></i>
                        </button>
                    </div>

                    
                    <div class="dropdown header-item">
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle header-profile-user" src="{{asset('images/users/user-dummy-img.jpg')}}" alt="Header Avatar" >
                                <span class="text-start ms-xl-2">
                                    <span class="d-none d-xl-inline-block fw-medium user-name-text fs-16">{{Auth::user()->nama}} <i class="las la-angle-down fs-12 ms-1"></i></span>
                                </span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="{{ route('auth.profile') }}"><i class="bx bx-user fs-15 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"  onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i class="bx bx-power-off fs-15 align-middle me-1 text-danger"></i> <span key="t-logout">{{ __('Logout') }}</span></a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </header>

      <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
      <!-- ========== App Menu (TOPBAR & SIDE BAR) ========== -->
      <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <!-- Dark Logo-->
            <a href="/invoice" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{asset('images/Logo IBEKA.png')}}" alt="" height="50">
                </span>
                <span class="logo-lg">
                    <img src="{{asset('images/logo-ib-black.png')}}" alt="" height="50">
                </span>
            </a>
            <!-- Light Logo-->
            <a href="/invoice" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{asset('images/Logo IBEKA.png')}}" alt="logo" height="50">
                </span>
                <span class="logo-lg">
                    <img src="{{asset('images/Logo IBEKAMI Ikhtiar berkah TULISAN PUTIH.png')}}" alt="logo" height="50">
                </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>
        {{-- END TOPBAR --}}

        {{-- START SIDE BAR --}}
        <div id="scrollbar">
            <div class="container-fluid">

                <div id="two-column-menu">
                </div>
                <ul class="navbar-nav" id="navbar-nav">
                    @if (Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik') ||  (Auth::user()->role == 'Produksi') ||  (Auth::user()->role == 'Magang'))


                    @if (Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik'))
                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                    <li class="nav-item">
                       <a class="nav-link menu-link" href="#sidebarDashboard" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboard">
                            <i class="las la-house-damage"></i> <span data-key="t-invoices">Dashboard</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboard" >
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="/invoice">
                                        <i class="las la-database"></i> <span data-key="t-invoices">Dashboard Ibekami</span>
                                    </a>
                                    @if (Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik'))
                                    <a class="nav-link menu-link" href="/invoice/welcomeTKB">
                                        <i class="las la-database"></i> <span data-key="t-invoices">Dashboard Tokabe</span>
                                    </a>
                                    @endif
                                </li>
                                
                            </ul>
                        </div>
                    </li>
                    <!--BATAS-->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarInvoiceManagement" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarInvoiceManagement">
                            <i class="las la-file-invoice"></i> <span data-key="t-invoices">Invoices Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarInvoiceManagement">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="{{ route('daftar_invoice') }}">
                                        <i class="las la-store"></i> <span data-key="t-invoices">Invoice Ibekami</span>
                                    </a>
                                    <a class="nav-link menu-link" href="{{ route('list_invoice_tokabe') }}">
                                        <i class="las la-store"></i> <span data-key="t-invoices">Invoice tokabe</span>
                                    </a>

                                </li>

                            </ul>
                        </div>
                    </li>
                     @if (Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik'))
                    <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarProduct" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProduct">
                          <i class="las la-boxes"></i> <span data-key="t-invoices">Barang</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarProduct">
                          <ul class="nav nav-sm flex-column">

                              <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/listBarang">
                                  <i class="las la-box-open "></i> <span data-key="t-invoices">Daftar Barang</span>
                              </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/listMaterial">
                                  <i class="las la-tools "></i> <span data-key="t-invoices">Daftar Material</span>
                              </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/listInventaris">
                                  <i class="las la-dolly-flatbed "></i> <span data-key="t-invoices">Daftar Inventaris</span>
                              </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link menu-link" href="{{route('vendor.list')}}">
                                    <i class="mdi mdi-cash-minus"></i> <span data-key="t-dashboard">Pembelian</span>
                                </a>
                              </li>
                          </ul>
                      </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarSurat" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSurat">
                          <i class="las la-envelope"></i> <span data-key="t-invoices">Surat</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSurat">
                          <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                             <a class="nav-link menu-link" href="/invoice/daftar-spb">
                              <i class="ri-truck-line"></i> <span data-key="t-invoices">SPB</span>
                             </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/daftar-spk">
                                <i class="ri-mail-settings-line"></i> <span data-key="t-invoices">SPK</span>
                            </a>
                            </li>
                            
                             <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/daftar-spj">
                                <i class="ri-mail-send-line"></i> <span data-key="t-invoices">SPJ</span>
                            </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarLaporan" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLaporan">
                            <i class="ri-clipboard-line"></i> <span data-key="t-invoices">Laporan</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLaporan">
                            <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/daftar-laporan">
                                <i class="ri-file-chart-line"></i> <span data-key="t-invoices">Laporan Penjualan</span>
                            </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/daftar-laporanPembelian">
                                  <i class="ri-calculator-line"></i><span data-key="t-invoices">Laporan Pembelian</span>
                            </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/daftar-pelanggan">
                                <i class="ri-file-user-line"></i> <span data-key="t-invoices">Daftar Customer</span>
                            </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    @if (Auth::user()->role == 'Pemilik')

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarkategori" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarkategori">
                            <i class="ri-grid-line"></i> <span data-key="t-invoices">Kategori Invoice</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarkategori">
                            <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{route('tambah.kategori')}}">
                                <i class="ri-file-add-line"></i> <span data-key="t-invoices">Tambah Kategori</span>
                            </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{route('daftar.kategori')}}">
                                <i class="ri-file-list-line"></i> <span data-key="t-invoices">Daftar Kategori</span>
                            </a>
                            </li>
                            </ul>
                        </div>
                        <li class="nav-item">
                                <a class="nav-link menu-link" href="{{route('perusahaan.list')}}">
                                    <i class="las la-city"></i> <span data-key="t-invoices">Perusahaan</span>
                                </a>
                                </li>
                    </li>
                    <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">SDM</span></li>

                    <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarKaryawan" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarKaryawan">
                        <i class="las la-users"></i> <span data-key="t-invoices">Karyawan</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarKaryawan">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                            <a class="nav-link menu-link" href="/invoice/daftar-user">
                                <i class="las la-address-book"></i> <span data-key="t-invoices">Daftar Karyawan</span>
                            </a>
                            </li>

                            <li class="nav-item">
                            <a class="nav-link menu-link" href="/invoice/add-user">
                                <i class="las la-user-plus"></i> <span data-key="t-invoices">Tambah Karyawan</span>
                            </a>
                            </li>
                        </ul>
                    </div>
                    </li>
                    {{-- Menu sidebar budgeting --}}
                    <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Budget Management</span></li>

                                <li class="nav-item">
                                <a class="nav-link menu-link" href="{{route('budget.list')}}">
                                    <i class="las la-money-bill"></i> <span data-key="t-invoices">Daftar budget</span>
                                </a>
                                </li>

                    <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Clean db</span></li>
                    @endif

                    {{-- ===== TAMBAHAN KHUSUS UNTUK MAGANG ===== --}}
                    @elseif (strtolower(Auth::user()->role) == 'magang')
                    <li class="menu-title"><span data-key="t-menu">Menu Magang</span></li>
                    <li class="nav-item">
                      <a class="nav-link menu-link" href="#sidebarProduct" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProduct">
                          <i class="las la-boxes"></i> <span data-key="t-invoices">Barang</span>
                      </a>
                      <div class="collapse menu-dropdown" id="sidebarProduct">
                          <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/listBarang">
                                  <i class="las la-box-open "></i> <span data-key="t-invoices">Daftar Barang</span>
                              </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/listMaterial">
                                  <i class="las la-tools "></i> <span data-key="t-invoices">Daftar Material</span>
                              </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/listInventaris">
                                  <i class="las la-dolly-flatbed "></i> <span data-key="t-invoices">Daftar Inventaris</span>
                              </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link menu-link" href="{{route('vendor.list')}}">
                                    <i class="mdi mdi-cash-minus"></i> <span data-key="t-dashboard">Pembelian</span>
                                </a>
                              </li>
                          </ul>
                      </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarSurat" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSurat">
                          <i class="las la-envelope"></i> <span data-key="t-invoices">Surat</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSurat">
                          <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                             <a class="nav-link menu-link" href="/invoice/daftar-spb">
                              <i class="ri-truck-line"></i> <span data-key="t-invoices">SPB</span>
                             </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/daftar-spk">
                                <i class="ri-mail-settings-line"></i> <span data-key="t-invoices">SPK</span>
                            </a>
                            </li>
                            
                             <li class="nav-item">
                                <a class="nav-link menu-link" href="/invoice/daftar-spj">
                                <i class="ri-mail-send-line"></i> <span data-key="t-invoices">SPJ</span>
                            </a>
                            </li>
                            </ul>
                        </div>
                    </li>
                    {{-- ======================================= --}}

                    @else
                    <li class="menu-title mt-3"><i class="ri-more-fill"></i> <span data-key="t-pages">Surat</span></li>
                     <li class="nav-item">
                       <a class="nav-link menu-link" href="/invoice/daftar-spk">
                        <i class="ri-mail-settings-line"></i> <span data-key="t-invoices">SPK</span>
                       </a>
                     </li>
                    @endif
                    @if (Auth::user()->role === 'Produksi')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{route('stokTinta')}}">
                            <i class="mdi mdi-printer-3d-nozzle"></i> <span>Stok Tinta</span>
                        </a>
                        </li>
                    @endif
                    @endif
                    @if (Auth::user()->role === 'Stockist')
                    <li class="nav-item mt-2">
                        <a class="nav-link menu-link" href="/invoice/listBarang">
                          <i class="las la-box-open "></i> <span data-key="t-invoices">Daftar Barang</span>
                      </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link menu-link" href="{{route('stokTinta')}}">
                            <i class="mdi mdi-printer-3d-nozzle"></i> <span>Stok Tinta</span>
                        </a>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- Sidebar -->
        </div>{{--Scroll Bar End--}}
        {{-- END SIDE --}}
        <div class="sidebar-background"></div>
      </div>
      <!-- Left Sidebar End -->
      <!-- Vertical Overlay-->
      <div class="vertical-overlay"></div>
      <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
        @yield('content')

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>document.write(new Date().getFullYear())</script> © Ibekami
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div>
          </footer>
        </div>

    </div>
    {{-- @extends('layout.customizer') --}}

    @extends('layout.vendor-script')

  </body>
</html>
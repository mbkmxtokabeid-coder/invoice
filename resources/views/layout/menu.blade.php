{{-- @extends('layout.app') --}}
@extends('layout.topbar')
@extends('layout.sidebar')
<div class="main-content">

  <div class="page-content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Dashboard</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                          <li class="breadcrumb-item active">Dashboard</li>
                      </ol>
                  </div>

              </div>
          </div>
        </div>

      </div>
  </div>
</div>

{{-- @yield('content') --}}
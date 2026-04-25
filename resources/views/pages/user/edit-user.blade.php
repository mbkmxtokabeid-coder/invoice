@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  .error-help-block{
    color: #ca131e;
  }
</style>
<!-- Masukkan file CSS SweetAlert -->
@endsection
@extends('layout.template')
@section('content')

    <div class="page-content">
      <div class="container-fluid">
        {{-- Start Page Title --}}
        <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Edit Karyawan</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Karyawan</a></li>
                          <li class="breadcrumb-item active">Edit Karyawan</li>
                      </ol>
                </div>
             </div>
          </div>
       </div>
        {{-- End Page Title --}}
        {{-- Start Body Content Row 1 --}}
        <div class="row justify-content-center">
          <div class="col-xxl-9">
            {{-- Start Card --}}
            <div class="card">
              {{-- Start Form --}}
              <form method="POST" action="{{route('user.update',$user->id)}}" class="needs-validation">
                @csrf
                @method('put')
                <div class="card-body p-4">
                  {{-- Start Row 1 --}}
                  <div class="row">
                    <div class="col-lg-8">
                      {{-- Start Isi Form --}}
                      <div class="row g-3">
                        <div class="row col-lg-12 col-sm-6">
                          {{-- Start Input Karyawan --}}
                          <label  class="col-lg-5 col-form-label">Nama Karyawan</label>
                          <div class="col-lg-7 input-light mb-2">
                            <input class="form-control" placeholder="Nama Karyawan" name="namaKaryawan" value="{{$user->nama}}" required>
                           </div>
                          </div>
                          {{-- End Input Karyawan --}}

                        {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-5 col-form-label">Email Karyawan</label>
                          <div class="col-lg-7 input-light mb-2">
                            <input class="form-control" type="email" name="email" placeholder="Email" value="{{$user->email}}" required>
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                        {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-5 col-form-label">Password</label>
                          <div class="col-lg-7 input-light mb-2 auth-pass-inputgroup">
                            <input class="form-control password-input" type="password" id="password-input" name="password" placeholder="Password" value="{{old('password')}}" required>
                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="las la-eye align-middle fs-18"></i></button>
                           </div>
                          </div>
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-5 col-form-label">Ulangi Password</label>
                          <div class="col-lg-7 auth-pass-inputgroup input-light mb-2">
                            <input class="form-control password-input" type="password" name="re_password" placeholder="Password" value="{{old('re_password')}}" required>
                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="las la-eye align-middle fs-18"></i></button>
                           </div>
                          </div>
                          {{-- End Input Nama Customer --}}
                        {{-- Start Input Nama Customer --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label for="phone-number" class="col-lg-5 col-form-label">Nomor Telepon</label>
                          <div class="col-lg-7 input-light mb-2">
                            <input type="text" value="{{$user->nomor_telepon}}" name="no_telp" class="form-control" placeholder="xxxx xxxx xxxx" id="phone-number">
                           </div>
                          </div>
                        <div class="row col-lg-12 col-sm-6">
                          <label for="role" class="col-lg-5 col-form-label">Role</label>
                          <div class="col-lg-7 input-light mb-2">
                            <select type="text" name="role" class="form-select">
                              <option value="{{$user->role}}" selected>{{$user->role}}</option>
                              @foreach($roles as $role)
                              @if($role !== $user->role)
                                  <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                      {{ $role }}
                                  </option>
                              @endif
                          @endforeach
                          </select>
                           </div>
                          </div>

                          {{-- End Input Nama Customer --}}
                      </div>
                      {{-- End Isi Form --}}
                    </div>

                  </div>
                  {{-- End Row 1 --}}
                  {{-- Start Row 2 --}}
                  <div class="row">
                    <div class="card-body p-4">

                       <!--end table-->
                     </div>
                  </div>
                  {{-- End Row 2 --}}
                  <div class="row">
                    <button type="submit" id="simpan" class="btn btn-primary fw-medium col-lg-3"><i class="ri-save-3-line me-1 align-bottom"></i>Simpan</button>
                  </div>
                </div>
              </form>
              {{-- End Form --}}
            </div>
            {{-- End Card --}}
          </div>
        </div>
        {{-- End Body Content Row 1 --}}

      </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\UserRequest') !!}
@endsection
@section('plugins')
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

<script src="{{asset('js/pages/password-addon.init.js')}}"></script>

@endsection
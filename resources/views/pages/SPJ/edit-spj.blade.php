@extends('layout.template')
@section('head')
<link rel="stylesheet" href="{{ asset('libs/dropzone/dropzone.css') }}" type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  .error-help-block {
    color: #ca131e;
  }
</style>
@endsection

@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0">Edit SPJ</h4>
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="#">SPJ</a></li>
              <li class="breadcrumb-item active">Edit SPJ</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-xxl-12">
        <div class="card">
          <form action="{{ url('/update-spj/'. $spj->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="card-body p-4">
              <div class="row g-3">
                <div class="col-lg-6">
                 <div class="mb-3 row">
                  <label class="col-lg-3 col-form-label">SPJ</label>
                  <div class="col-lg-9">
                    <select class="form-select" name="spj" id="spj-select">
                      <option value="">-- Pilih SPJ --</option>
                     <option value="Ibeka" {{ old('spj', $spj->perusahaan) == 'Ibeka' ? 'selected' : '' }}>Ibeka</option>
                     <option value="Tokabe" {{ old('spj', $spj->perusahaan) == 'Tokabe' ? 'selected' : '' }}>Tokabe</option>
                     <option value="Personal" {{ old('spj', $spj->perusahaan) == 'Personal' ? 'selected' : '' }}>Personal</option>

                    </select>
                    @error('spj')
                      <span class="error-help-block">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Nomor SPJ</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="nomor_spj" id="nomor-spj" placeholder="Nomor SPJ" readonly value="{{ old('nomor_spj', $spj->nomor_spj) }}">
                      @error('nomor_spj')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Tanggal Tugas</label>
                    <div class="col-lg-9">
                     <input type="text" class="form-control flatpickr-input" name="tanggal_tugas" data-provider="flatpickr" data-date-format="d/m/Y" placeholder="dd/mm/yyyy" value="{{ old('tanggal_tugas', \Carbon\Carbon::parse($spj->tanggal_tugas)->format('d/m/Y')) }}">
                      @error('tanggal_tugas')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Waktu Berangkat</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="waktu_berangkat" name="waktu_berangkat" placeholder="HH:MM" value="{{ old('waktu_berangkat',$spj->waktu_berangkat) }}">
                      @error('waktu_berangkat')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Tujuan</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="tujuan" placeholder="Tujuan Perjalanan" value="{{ old('tujuan',$spj->tujuan) }}">
                      @error('tujuan')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                   <div class="mb-3 row">
                <label class="col-lg-3 col-form-label">Nama Pemberi Tugas</label>
                <div class="col-lg-9">
                  <input type="text" class="form-control" name="nama_pemberi_tugas" placeholder="Nama Pemberi Tugas"  value="{{ old('nama_pemberi_tugas', $spj->nama_pemberi_tugas) }}">
                  @error('nama_pemberi_tugas')
                    <span class="error-help-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Nama Kurir/Petugas</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="nama_kurir" placeholder="Nama Kurir"  value="{{ old('nama_kurir', $spj->nama_kurir) }}">
                      @error('nama_kurir')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Biaya Bahan Bakar</label>
                    <div class="col-lg-9">
                      <input type="number" step="0.01" class="form-control" name="biaya_bahan_bakar" placeholder="Biaya Bahan Bakar" value="{{ old('biaya_bahan_bakar', $spj->biaya_bahan_bakar) }}">
                      @error('biaya_bahan_bakar')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Jarak Tempuh (km)</label>
                    <div class="col-lg-9">
                      <input type="number" step="0.01" class="form-control" name="jarak_tempuh" placeholder="Jarak Tempuh" value="{{ old('jarak_tempuh', $spj->jarak_tempuh) }}">
                      @error('jarak_tempuh')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Deskripsi Barang</label>
                    <div class="col-lg-9">
                      <textarea class="form-control" name="deskripsi_barang" rows="2" placeholder="Deskripsi Barang/Peralatan">{{ old('deskripsi_barang',$spj->deskripsi_barang) }}</textarea>
                      @error('deskripsi_barang')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                  <div class="mb-3 row">
                  <label class="col-lg-3 col-form-label">Deskripsi Tugas</label>
                  <div class="col-lg-9">
                    <textarea class="form-control" name="deskripsi_tugas" rows="2" placeholder="Deskripsi Tugas">{{ old('deskripsi_tugas', $spj->deskripsi_tugas) }}</textarea>
                    @error('deskripsi_tugas')
                      <span class="error-help-block">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-lg-3 col-form-label">Keterangan Tambahan</label>
                  <div class="col-lg-9">
                    <textarea class="form-control" name="keterangan_tambahan" rows="2" placeholder="Keterangan Tambahan">{{ old('keterangan_tambahan',$spj->keterangan_tambahan) }}</textarea>
                    @error('keterangan_tambahan')
                      <span class="error-help-block">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                <div class="mb-3 row">
                  <label class="col-lg-3 col-form-label">Jam Pulang</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" id="jam_kembali" name="jam_kembali" placeholder="HH:MM" value="{{ old('jam_kembali',$spj->jam_kembali) }}">
                    @error('jam_kembali')
                      <span class="error-help-block">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                   <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Status Pembayaran</label>
                    <div class="col-lg-9">
                      <select class="form-select" name="status" required>
                        <option value="Terbayar" {{ old('status', $spj->status) == 'Terbayar' ? 'selected' : '' }}>Terbayar</option>
                        <option value="Belum Bayar" {{ old('status',$spj->status ) == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                      </select>
                      @error('status')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              

              <div class="row mt-4">
                <div class="col-lg-12 text-center">
                  <button type="submit" class="btn btn-primary">
                    <i class="ri-save-3-line me-1 align-bottom"></i> Update
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- {!! JsValidator::formRequest('App\Http\Requests\SPJRequest') !!} --}}
@endsection

@section('plugins')
<script src="{{ asset('libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ asset('libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
<script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{asset('js/halaman/spj.js')}}"></script>
<script>
flatpickr("#waktu_berangkat", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
  });

  flatpickr("#jam_kembali", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
  });
</script>
@endsection

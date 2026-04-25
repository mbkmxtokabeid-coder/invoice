<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan {{$namaVendor}} {{$status}}</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" >
  {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}


  <style>
    h4 {
      /* gaya huruf dan ukuran yang sama dengan Bootstrap */
      font-family: Arial, sans-serif;
      font-size: 30px;
      font-weight: 600;
      color: #333;
      margin: 20px 0;
    }
    .table {
      font-size: 12px;
      font-family: Arial, sans-serif;
    }
    .table-striped tbody tr:nth-of-type(even) {
      background-color: #4f6a9c;
    }
    /* Batas */
    .signatures {
            display: flex;
            justify-content: space-evenly;
            margin-top: 40px;

        }
        .signature div{
          width: 100px;
          height: 100px;
        }
        .signature {
            text-align: center;
        }
        .signature-name {
            margin-top: 20px;
        }
        .underline {
            text-decoration: underline;
        }

  </style>

</head>
<body>

  <div class="container">
    <div class="row justify-content-center">
      <h4 class="col m-4 text-center">Laporan Pembelian {{$namaVendor}} {{$status}}</h4>
    </div>
    <div class="row">
        <table class="table">
         <thead class="thead-dark">
          <tr>
           <th scope="col">NO</th>
           <th scope="col">NO INVOICE</th>
           <th scope="col">TANGGAL PEMBELIAN</th>
           <th scope="col">NAMA VENDOR</th>
           <th scope="col">TOTAL TRANSAKSI</th>
           <th scope="col">TOTAL TERBAYAR</th>
           <th scope="col">SISA</th>
           <th scope="col">STATUS</th>
           <th scope="col">TANGGAL JATUH TEMPO</th>
           
          </tr>
         </thead>
        <tbody>
        @foreach ($pembelians as $beli)
         <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$beli->nomor_inv}}</td>
          <td>{{$beli->formatted_tgl_pembelian}}</td>
          <td>{{ $beli->vendor->nama_vendor ?? 'Tidak Diketahui' }}</td>
          <td>{{$beli->formatted_total_transaksi}}</td>
          <td>{{$beli->formatted_total_terbayar}}</td>
          <td>{{$beli->formatted_total_sisa}}</td>
          <td>{{$beli->status}}</td>
          <td>{{$beli->formatted_tgl_jto}}</td>
         </tr>
        @endforeach
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-bold ">Jumlah</td>
          <td class="text-bold ">{{$formatGrandTotal}}</td>
         </tr>
        </tbody>
       </table>
      </div>
     <div class="signatures">
      {{-- <div class="col-2"></div> --}}
      <div class="col-4">
        <p class="text-center">{{$namaPerusahaan}}, {{$tanggal}}</p>

        <p class="text-center">Disiapkan Oleh,</p>
        <p class="text-center">&nbsp;</p> <!-- Gunakan paragraf kosong untuk membuat jarak -->
        <p class="text-center">&nbsp;
        <p class="text-center" style="text-decoration: underline;">{{auth()->user()->nama}}</p>
        <p class="text-center">{{auth()->user()->role}}</p>

      </div>
      <div class="col-3">
        <p class="text-center">&nbsp;</p>

        <p class="text-center">Diketahui Oleh,</p>
        <p class="text-center">&nbsp;</p> <!-- Gunakan paragraf kosong untuk membuat jarak -->
        <p class="text-center">&nbsp;
        <p class="text-center" style="text-decoration: underline;">. . . . . . . . . . . .</p>
        <p class="text-center">Pimpinan</p>
      </div>
     </div>
     {{-- <div class="signatures">
      <div >
          <div>Dibuat Oleh,</div>
          <div class="signature-name underline"><strong>(Nama Admin)</strong></div>
          <div class="underline"></div>
          <div><em>Jabatan</em></div>
      </div>

      <div>
          <div>Diketahui Oleh,</div>
          <div class="signature-name"><strong>(Nama Pemilik)</strong></div>
          <div class="underline"></div>
          <div><em>Pemilik</em></div>
      </div>
  </div> --}}

  {{-- <div class="signature-date">
      Medan, {{ date('d F Y') }}
  </div> --}}

      </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
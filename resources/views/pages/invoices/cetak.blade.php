<html>

<head>
  <title>Print Document</title>
  <link href="style.css" type="text/css" rel="stylesheet" />


</head>
<style>
  body {
    font-family: Bookman Old Style, sans-serif;
    letter-spacing: 1px;
    color: #000000;
    font-size: 12px;

  }

  /* Create two equal columns that floats next to each other */
  .column {
    float: left;

    width: 50%;

  }

  /* Clear floats after the columns */
  .row:after {
    content: "";
    display: table;
    clear: both;
  }

  .tess {g
    font-size: 26px;
    line-height: -20px;
  }

  .noborders td {
    border: 0;

  }

  .no td {
    border: 0;
    border-top: 0;

  }

  .tp td {

    border: 0;
    border-top: 1px solid black;
  }

  .ts th {
    font-family: Bookman Old Style, sans-serif;
    letter-spacing: 1px;

  }
</style>

<body>


  <div class="row">
    <div class="column">
      <b style="font-size: 16px;font-family: Bookman Old Style, sans-serif;
  letter-spacing: 1px;
  font-style: normal;
  font-weight: 900;
  color: #000000;">{{ $toko }}</b><br>
  <div style="text-align:left; font-size: 12px;">
        @if ($toko == 'Total Karya Berkah')
        JL GARUDA NO 27B KOTA MEDAN 20122<br>
        @endif
        JL SETIA BUDI KOMP. SETIA BUDI POINT NO D-10<br>KOTA MEDAN 20132<br>
        Wa: 08112272727
        </div><br>
        <br>
      Nomor Invoice : {{$penjualan->nomor_invoice}}
    </div>
    <div class="column" style="text-align:right; font-size: 12px;">
      <div style="position: absolute; left: 400px;top: 10px;">
        Kepada :<br>
        Perusahaan/Instansi :<br>
        No. Telp :</div>
    </div>
    <div class="column" style="text-align:left;">
      <div style="position: absolute; left:560px;top: 10px;">
        <b style="font-size: 12px">{{$penjualan->customer}}</b><br>
        <b>{{$penjualan->perusahaan}}</b><br>
        <b>{{$penjualan->no_telepon}}</b>
      </div>
    </div>

    <table width="100%" style=" border-collapse:collapse; font-size: 12px; border-bottom: 1px solid black;">
      <tr class="ts" style="border-bottom: 1px solid black;">
        <th rowspan="1">No</th>
        <th>Deskripsi Barang</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Jumlah</th>
      </tr>

      @foreach ($penjualan_barang as $index => $jual)
          @foreach ($barang[$index] as $item)
          @php
              $nomor = $index + 1;
          @endphp
          <tr id="rowHover" class="noborders">
            <td width="1%" align="center"> {{$nomor}}</td>
            <td width="25%" style="padding-left: 10px;"> {{$item->jenis_barang}} {{$jual->deskripsi_item}}</td>
            <td width="5%" id="column_padding" align="center"> {{$jual->qty}} {{$jual->satuan}}</td>
            <td width="10%" id="column_padding" align="center">Rp.{{$hargaMod[$index]}}</td>
            <td width="10%" id="column_padding" align="center">Rp.{{$jumlahHarga[$index]}}</td>

          </tr>
          @endforeach
      @endforeach

    </table>
    <div style="position: absolute; bottom: 220px; font-size: 10px">{{$norek}}</div>

    <div class="column" style="text-align: left; font-size: 12px">
    <div style="position: absolute; right: 180px; bottom: 65px;">
        <b>Total</b><br>
        <b>DP</b><br>
        @if (!is_null($penjualan->diskon))
            <b>Diskon</b><br>
        @endif
        @if (!is_null($penjualan->potongan))
            <b>Potongan</b><br>
        @endif
        @if (!is_null($penjualan->ppn))
            <b>PPN(11%)</b><br>
        @endif
        @if (is_null($penjualan->diskon) && is_null($penjualan->potongan) && is_null($penjualan->ppn))
            <b></b><br>
        @endif
        <b>Sisa Bayar</b><br>
    </div>
</div>

    <div class="column" style="text-align: left; font-size: 12px">
      <div style="position: absolute; right: 10px;bottom: 65px;">
        <b>Rp.{{$totHargaMod}}</b><br>
        <b>Rp.{{$dp}}</b><br>
        <b>({{$biayaLain}})</b><br>
        <b>Rp.{{$sisaBayarMod}}</b><br>
      </div>
    </div>

    <div style="position: absolute; width: 240mm; left: 0; right: 0; margin-left: auto; margin-right: auto; bottom:5px; font-size: 10px">
      @if($toko == 'Total Karya Berkah')
      <center><i>Kunjungi website Kami, tokabe.id | Total Karya Berkah, member of KADIN INDONESIA.</i></center>
      @else
      <center><i>Terima Kasih telah mendukung UMKM kami, dengan senang hati melayani.</i></center>
      @endif
    </div>

    <div class="row">
      <div class="column" style="text-align: center; font-size: 12px">
        <div style="position: absolute; left: 20px;bottom: 10px;">
          <h4>diterima oleh</h4>
          <p><br><br><br><br><br><br></p>
          <h4>( {{$penjualan->customer}} )</h4>
        </div>
      </div>

      <div class="column" style="text-align: center; font-size: 12px">
        <div style="position: absolute; right:340px;bottom: 10px;">

          <h4>Medan,{{$formatTanggal}}</h4>
          
          @if($penjualan->status == 'Lunas')
            <div style="position: relative; height: 110px; margin-top: 10px; margin-bottom: 10px;">
                <!-- GAMBAR STEMPEL -->
                <img src="{{asset('images/Logo IBEKA ID.png')}}" style="position: absolute; top: 15px; left: 50%; transform: translateX(-70%); height: 90px; z-index: -2;" alt="Stempel Lunas">
                
                <!-- GAMBAR TTD -->
                <img src="{{asset('images/ttd.png')}}" style="position: absolute; top: 0; left: 50%; transform: translateX(-30%); height: 90px; z-index: -1;" alt="TTD Lunas">
            </div>
          @else
            <p><br><br><br><br><br><br></p>
          @endif

          @if($toko == 'Total Karya Berkah')
          <h4>Oky Irawan</h4>
          @else
          <h4>{{$admin}}</h4>
          @endif
        </div>
      </div>
    </div>



    <script>
      window.load = print_d();

      function print_d() {
        window.print();
      }
    </script>
</body>

</html>
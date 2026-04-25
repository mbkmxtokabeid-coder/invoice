<html>
<head>
    <title>Print Document</title>

    <style type="text/css">
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: auto;
            font-weight: bold;
        }

        #customers td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        * {
            box-sizing: border-box;
        }

        .container {
            display: flex;
            margin-top: 30px;
        }

        .table-container {
            flex: 1;
            padding: 10px;
            margin-left: 100px;
            align-items: center;
            justify-content: center;
        }

        .image-container {
            flex: 1;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: -100px;
        }
    </style>
</head>

<body>

<div style="text-align: center; font-size: 15px;">
    <u><b>SPK (Surat Perintah Kerja)<b></u>
    <p>

    <div class="container">
        <div class="table-container">

            <table border="2" id="customers">
                <tr>
                    <td>Tanggal</td>
                    <td>: {{$tanggal}} </td>
                </tr>

                <tr>
                    <td>Nomor Invoice</td>
                    <td>: {{$spk->nomor_invoice}}</td>
                </tr>

                <tr>
                    <td>Pelanggan</td>
                    <td>: {{$spk->customer}} </td>
                </tr>

                <tr>
                    <td>Jenis Pekerjaan</td>
                    <td>: {{$spk->pekerjaan}}</td>
                </tr>

                <tr>
                    <td>Jumlah</td>
                    <td>: {{$spk->jumlah}} </td>
                </tr>

                <tr>
                    <td>Jenis Bahan</td>
                    <td>: {{$spk->jenis_bahan}} </td>
                </tr>

                <tr>
                    <td>Ukuran</td>
                    <td>: {{$spk->ukuran}} </td>
                </tr>

                <tr>
                    <td>Lainnya</td>
                    <td>: {{$spk->lain}} </td>
                </tr>

                <tr>
                    <td>Express</td>
                    <td>
                        @if($spk->express === 'Y')
                            <span style='color:red;'>: Ya</span>
                        @else
                            <span>: Tidak</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Target Penyelesaian</td>
                    <td>: {{$target}} </td>
                </tr>
            </table>

        </div>
        <div class="image-container">
            @if($spk->gambar !== null)
                <a href="{{ asset('images/spk/' . $spk->gambar) }}"><img src="{{ asset('images/spk/' . $spk->gambar) }}" style="width:300px;"></a>
            @endif
        </div>
    </div>
</div>

{{-- <script>
    window.load = print_d();
    function print_d(){
        window.print();
    }
</script> --}}
</body>
</html>

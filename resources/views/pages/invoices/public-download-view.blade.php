<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>{{$namaInv}} - {{$penjualan->customer}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        /* RESET & BASIC STYLES */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            background-color: #f0f0f0; /* Background abu-abu di layar HP */
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        /* CONTAINER A4 - KUNCI AGAR TIDAK RESPONSIF */
        .invoice-box {
            max-width: 210mm; /* Lebar A4 */
            margin: auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        /* LAYOUT DENGAN TABLE (LEBIH STABIL DARI FLEXBOX UNTUK PDF/PRINT) */
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        /* HEADER */
        .title-header td {
            padding-bottom: 20px;
        }

        .title-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .company-info {
            text-align: right;
            font-size: 12px;
            color: #555;
        }

        /* INFO INVOICE & CUSTOMER */
        .info-section td {
            padding-bottom: 40px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
            font-size: 10px;
            margin-bottom: 2px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .address-value {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }

        /* TABEL BARANG */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            padding: 10px;
            border-bottom: 2px solid #ddd;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        /* TOTAL & PAYMENT */
        .total-section {
            width: 100%;
        }
        
        .payment-info {
            font-size: 12px;
            color: #d9534f; /* Merah sesuai request */
            line-height: 1.5;
            padding-top: 10px;
            border-top: 2px dashed #eee;
        }

        .total-table {
            width: 300px;
            float: right;
        }

        .total-table td {
            padding: 5px 0;
            text-align: right;
        }

        .total-table .label {
            color: #777;
            padding-right: 15px;
        }
        
        .total-table .value {
            font-weight: bold;
        }

        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #438a7a; /* Hijau sesuai request */
            border-top: 2px dashed #eee;
            padding-top: 10px !important;
        }
        
        .grand-total-unpaid {
             color: #d9534f; /* Merah */
        }

        /* FOOTER SIGNATURE */
        .signature-section {
            margin-top: 50px;
            width: 100%;
            font-size: 11px;
            text-align: center;
        }
        
        .sign-box {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* PRINT SETTINGS */
        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }
            .invoice-box {
                box-shadow: none;
                border: none;
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
            /* Hilangkan tombol print saat dicetak */
            .no-print {
                display: none;
            }
        }
        
        /* TOMBOL PRINT DI HP */
        .fab-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            padding: 15px 20px;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            text-decoration: none;
            font-weight: bold;
            z-index: 9999;
        }
    </style>
</head>

<body>

    <!-- Tombol Print Floating (Hanya muncul di Layar HP/Web) -->
    <a href="javascript:window.print()" class="fab-print no-print">
        🖨️ Simpan PDF
    </a>

    <div class="invoice-box">
        <!-- HEADER -->
        <table class="title-header">
            <tr>
                <td style="width: 60%;">
                    <img src="{{asset('images/Logo IBEKAMI.png')}}" alt="Logo" style="height: 50px; margin-bottom: 2px;">
                    <!-- Teks Tambahan di bawah Logo -->
                    <div style="font-size: 11px; font-weight: bold; color: #555; line-height: 1.2; margin-bottom: 5px;">
                        Ikhtiar Berkah, Ekonomi Kreatif <br>
                        Asli Medan Indonesia (IBEKAMI)
                    </div>
                    <a href="https://ibekami.id" style="color:#007bff; text-decoration:none; font-weight:bold;">Ibekami.id</a>
                </td>
                <td class="company-info">
                    <h1 style="color: #333;">INVOICE</h1>
                    Nomor: <strong>#{{$penjualan->nomor_invoice}}</strong><br>
                    Tanggal: {{$formatTanggal}}
                </td>
            </tr>
        </table>

        <!-- INFO PELANGGAN -->
        <table class="info-section">
            <tr>
                <td style="width: 50%;">
                    <div class="info-label">Ditagihkan Kepada (Bill To):</div>
                    <div class="info-value">{{$penjualan->customer}}</div>
                    <div class="address-value">
                        <strong>{{$penjualan->perusahaan}}</strong><br>
                        {{$penjualan->no_telepon}}
                    </div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="info-label">Dari (From):</div>
                    <div class="info-value">{{ $toko }}</div>
                    <div class="address-value">
                        JL Setia Budi Komplek Setia Budi Point NO D-10<br>
                        Kel. Tanjung Sari, Medan<br>
                        www.ibekami.id
                    </div>
                </td>
            </tr>
        </table>

        <!-- TABEL BARANG -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">#</th>
                    <th style="width: 50%;">Deskripsi Barang</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 20%; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan_barang as $index => $jual)
                    @foreach ($barang[$index] as $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{$item->jenis_barang}}</strong><br>
                            <span style="font-size: 11px; color: #777;">{{$jual->deskripsi_item}}</span>
                        </td>
                        <td>Rp.{{$hargaMod[$index]}}</td>
                        <td style="text-align: center;">{{$jual->qty}}</td>
                        <td style="text-align: right;">{{$jumlahHarga[$index]}}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL & PAYMENT INFO -->
        <table class="total-section">
            <tr>
                <!-- KOLOM KIRI: INFO PEMBAYARAN -->
                <td style="width: 60%; vertical-align: top;">
                    @if (strpos($penjualan->jenis_pembayaran, 'Cash') === false)
                        <div class="payment-info">
                            <strong>MOHON TRANSFER KE:</strong><br>
                            @if($penjualan->no_rek === 'BNI')
                                BSI | A/N: Yusni Kurniasih<br>
                                No. Rek: 2845999999
                            @elseif($penjualan->no_rek === 'Mandiri')
                                BSI | A/N: Yusni Kurniasih<br>
                                No. Rek: 2845999999
                            @endif
                        </div>
                    @endif
                </td>

                <!-- KOLOM KANAN: HITUNGAN TOTAL -->
                <td style="width: 40%; vertical-align: top;">
                    <table class="total-table">
                        <tr>
                            <td class="label">Total Harga:</td>
                            <td class="value">{{$totalHarga}}</td>
                        </tr>

                        @if (!is_null($penjualan->diskon) && $penjualan->potongan == 0)
                        <tr>
                            <td class="label">Diskon ({{$persen}}):</td>
                            <td class="value">- {{$biayaLain}}</td>
                        </tr>
                        @endif

                        @if (!is_null($penjualan->potongan) && $penjualan->potongan > 0)
                        <tr>
                            <td class="label">Spesial Diskon:</td>
                            <td class="value">- {{$biayaLain}}</td>
                        </tr>
                        @endif

                        @if ($penjualan->potongan == 0 && !is_null($penjualan->ppn))
                        <tr>
                            <td class="label">PPN ({{$persen}}):</td>
                            <td class="value">{{$biayaLain}}</td>
                        </tr>
                        @endif

                        <tr>
                            <td class="label">Total Pembayaran:</td>
                            <td class="value" style="font-size: 14px;">{{$totHargaMod}}</td>
                        </tr>

                        @if (!is_null($dp))
                        <tr>
                            <td class="label">DP / Panjar:</td>
                            <td class="value">- Rp.{{$dp}}</td>
                        </tr>
                        @endif

                        <tr>
                            <td class="grand-total {{ $penjualan->status === 'Belum Lunas' ? 'grand-total-unpaid' : '' }} label" style="border-top: 2px solid #ddd;">Sisa Tagihan:</td>
                            <td class="grand-total {{ $penjualan->status === 'Belum Lunas' ? 'grand-total-unpaid' : '' }} value" style="border-top: 2px solid #ddd;">Rp.{{$sisaBayarMod}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- FOOTER TANDA TANGAN -->
        <table class="signature-section">
            <tr>
                <td style="width: 30%;">
                    Diterima Oleh,<br>
                    ( {{$penjualan->customer}} )
                    <div class="sign-box" style="margin-top: 50px;"></div>
                </td>
                <td style="width: 40%;"></td>
                <td style="width: 30%;">
                    Medan, {{$formatTanggal}}<br>
                    Hormat Kami,
                    
                    <!-- LOGIKA STEMPEL & TTD LUNAS -->
                    @if($penjualan->status == 'Lunas')
                        <div style="position: relative; height: 50px; margin-top: 0px; z-index: 1;">
                            <!-- GAMBAR STEMPEL -->
                            <img src="{{asset('images/Logo IBEKA ID.png')}}" style="position: absolute; top: 3px; left: 50%; transform: translateX(-75%); height: 60px; z-index: 1;" alt="Stempel Lunas">
                            <!-- GAMBAR TTD -->
                            <img src="{{asset('images/ttd.png')}}" style="position: absolute; top: 3px; left: 50%; transform: translateX(-20%); height: 65px; z-index: 2;" alt="TTD Lunas">
                        </div>
                        <div class="sign-box" style="margin-top: 25px;">
                    @else
                        <div class="sign-box">
                    @endif
                            @if($toko == 'Total Karya Berkah')
                                Oky Irawan
                            @else
                                {{$admin}}
                            @endif
                        </div>
                </td>
            </tr>
        </table>
        
        <!-- FOOTER WEBSITE -->
        <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #888; border-top: 1px solid #eee; padding-top: 10px;">
            @if($toko == 'Total Karya Berkah')
                Kunjungi website Kami, tokabe.id | Total Karya Berkah, member of KADIN INDONESIA.
            @else
                Terima Kasih telah mendukung UMKM kami, dengan senang hati melayani.
            @endif
        </div>
    </div>

    <!-- Script auto print jika dibuka di desktop, opsional -->
    <script>
        // Cek jika layar lebar (PC), otomatis print preview
        if(window.innerWidth > 768) {
             window.print();
        }
    </script>
</body>
</html>
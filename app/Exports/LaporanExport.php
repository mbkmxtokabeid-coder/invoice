<?php

namespace App\Exports;

use App\Models\Penjualan;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\PenjualanBarang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan DB facade untuk query relasi

class LaporanExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents, WithHeadingRow
{
    /**
     * @return \Illuminate\Support\Collection
     */
    
    protected $penjualans;

    public function __construct(Collection $penjualans)
    {
        $this->penjualans = $penjualans;
    }

    public function headings(): array
    {
        return [
            // Disesuaikan menjadi 11 kolom
            ['#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#'],
            [
                'No',
                'Nomor Invoice',
                'Tanggal Penjualan',
                'Pelanggan',
                'Perusahaan',
                'Deskripsi Item',      // Kolom Baru
                'Jumlah Item',         // Kolom Baru
                'Total Harga',
                'Status',
                'Potongan/Diskon/PPN',
                'Grand Total',
            ]
        ];
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function array(): array
    {
        Carbon::setLocale('id');
        $data = [];

        foreach ($this->penjualans as $index => $penjualan) {
            
            $penjualan->tgl_penjualan = Carbon::parse($penjualan->tgl_penjualan)->locale('id')->isoFormat('DD MMMM, YYYY');

            // --- AMBIL DATA BARANG SAMA SEPERTI PDF ---
            if ($penjualan instanceof \App\Models\Penjualan) {
                $items = DB::table('penjualan_barang')
                    ->join('barang', 'penjualan_barang.barang_id', '=', 'barang.id')
                    ->where('penjualan_barang.penjualan_id', $penjualan->id)
                    ->whereNull('penjualan_barang.deleted_at')
                    ->select('barang.jenis_barang', 'penjualan_barang.deskripsi_item', 'penjualan_barang.qty', 'penjualan_barang.satuan')
                    ->get();
            } else {
                $items = collect([]);
            }

            $deskripsiArr = [];
            $jumlahArr = [];

            if ($items->count() > 0) {
                foreach ($items as $item) {
                    // Gabungkan jenis barang dan deskripsi dengan baris baru
                    $desc = $item->jenis_barang;
                    if (!empty($item->deskripsi_item)) {
                        $desc .= "\n(" . $item->deskripsi_item . ")";
                    }
                    $deskripsiArr[] = $desc;
                    $jumlahArr[] = $item->qty . ' ' . $item->satuan;
                }
            } else {
                $deskripsiArr[] = '-';
                $jumlahArr[] = '-';
            }

            // Gabungkan array menjadi string dipisah dua enter agar renggang per-item
            $deskripsiGabungan = implode("\n\n", $deskripsiArr);
            $jumlahGabungan = implode("\n\n", $jumlahArr);
            // ------------------------------------------

            if ($penjualan->diskon || $penjualan->potongan || $penjualan->ppn) {
                if ($penjualan->diskon) {
                    $penjualan->lain = 'dsc ' . $penjualan->diskon . '%';
                } elseif ($penjualan->ppn) {
                    $penjualan->lain = 'ppn ' . $penjualan->ppn . '%';
                } else {
                    $penjualan->lain = 'pot Rp.' . number_format($penjualan->potongan, 0, ',', '.');
                }
            } else {
                $penjualan->lain = '-';
            }

            $data[] = [
                $index + 1, // Nomor otomatis berdasarkan index
                $penjualan->nomor_invoice,
                $penjualan->tgl_penjualan,
                $penjualan->customer,
                $penjualan->perusahaan,
                $deskripsiGabungan,    // Ditampilkan di Excel (Wrap Text)
                $jumlahGabungan,       // Ditampilkan di Excel (Wrap Text)
                $penjualan->total_harga,
                $penjualan->status,
                $penjualan->lain,
                $penjualan->total_pembayaran,
            ];
        }

        return $data;
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge and center the title sampai kolom K (11 Kolom)
                $event->sheet->getDelegate()->mergeCells('A1:K1');
                $event->sheet->getDelegate()->getStyle('A1:K1')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                ]);

                $event->sheet->getDelegate()->setCellValue('A1', 'LAPORAN PENJUALAN IKHTIAR BERKAH ');
                
                // HEADING
                $heading = 'A2:K2';
                $event->sheet->getDelegate()->getStyle($heading)->getFont()->setBold(true);
                
                // Apply border to all cells in the table
                $lastColumn = $event->sheet->getDelegate()->getHighestColumn(); // Seharusnya menjadi 'K'
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $range = 'A2:' . $lastColumn . $lastRow;
                
                // Wajib untuk multiline cell (agar \n berfungsi di Excel) & rata atas
                $event->sheet->getDelegate()->getStyle('A3:' . $lastColumn . $lastRow)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A3:' . $lastColumn . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

                $event->sheet->getDelegate()->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Calculate and apply total in column K (Grand Total sekarang di K)
                $totalRange = 'K' . ($lastRow + 1);
                $event->sheet->getDelegate()->setCellValue($totalRange, '=SUM(K3:K' . $lastRow . ')');
                $columnK = 'K3:K' . $lastRow;
                $columnH = 'H3:H' . $lastRow; // Total harga sekarang ada di H
                
                // Geser kata 'Jumlah' ke kolom J
                $event->sheet->getDelegate()->setCellValue('J' . ($lastRow + 1), 'Jumlah');
                $event->sheet->getDelegate()->getStyle('J' . ($lastRow + 1))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($totalRange)->getFont()->setBold(true);

                // Formatting Rupiah
                $event->sheet->getDelegate()->getStyle($columnK)->getNumberFormat()->setFormatCode('Rp#,##0.00');
                $event->sheet->getDelegate()->getStyle($columnH)->getNumberFormat()->setFormatCode('Rp#,##0.00');
                $event->sheet->getDelegate()->getStyle($totalRange)->getNumberFormat()->setFormatCode('Rp#,##0.00');

                $footerDataKiri = [
                    [
                        'row' => $lastRow + 3,
                        'text' => 'IKHTIAR BERKAH, ' . Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY'),
                        'underline' => false,
                    ],
                    [
                        'row' => $lastRow + 4,
                        'text' => 'Disiapkan Oleh,',
                        'underline' => false,
                    ],
                    [
                        'row' => $lastRow + 8,
                        'text' => Auth::user()->nama ?? 'Admin',
                        'underline' => true,
                    ],
                    [
                        'row' => $lastRow + 9,
                        'text' => Auth::user()->role ?? 'Staff',
                        'underline' => false,
                    ],
                ];

                foreach ($footerDataKiri as $data) {
                    $row = $data['row'];
                    $text = $data['text'];
                    $underline = $data['underline'];

                    $event->sheet->getDelegate()->mergeCells('A' . $row . ':' . 'E' . $row);
                    $event->sheet->getDelegate()->getStyle('A' . $row)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => false,
                            'size' => 10,
                            'underline' => $underline ? Font::UNDERLINE_SINGLE : Font::UNDERLINE_NONE,
                        ],
                    ]);
                    $event->sheet->getDelegate()->setCellValue('A' . $row, $text);
                }

                $footerDataKanan = [
                    [
                        'row' => $lastRow + 4,
                        'text' => 'Diketahui Oleh,',
                        'underline' => false,
                    ],
                    [
                        'row' => $lastRow + 8,
                        'text' => '...............',
                        'underline' => true,
                    ],
                    [
                        'row' => $lastRow + 9,
                        'text' => 'Pimpinan',
                        'underline' => false,
                    ],
                ];

                foreach ($footerDataKanan as $data) {
                    $row = $data['row'];
                    $text = $data['text'];
                    $underline = $data['underline'];

                    // Menggeser posisi tanda tangan dari kolom F ke kolom I agar sejajar kanan tabel
                    $event->sheet->getDelegate()->getStyle('I' . $row)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => false,
                            'size' => 10,
                            'underline' => $underline ? Font::UNDERLINE_SINGLE : Font::UNDERLINE_NONE,
                        ],
                    ]);
                    $event->sheet->getDelegate()->setCellValue('I' . $row, $text);
                }
            },
        ];
    }
}
<?php

namespace App\Exports;

use App\Models\Barang;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection(): Collection
    {
        return Barang::all();
    }

    public function map($barang): array
    {
        // Batasi panjang nama maksimal 50 karakter
        $nama = mb_strlen($barang->nama) > 50
            ? mb_substr($barang->nama, 0, 50) . '...'
            : $barang->nama;

        return [
            $nama,
            $barang->satuan,
            $barang->stok_dipesan,
            $barang->stok_tersedia,
            $barang->stok_dibutuhkan,
            $barang->created_at ? $barang->created_at->format('Y-m-d H:i') : '',
        ];
    }

    public function headings(): array
    {
        return [
            '名',               // Nama
            '単位',             // Satuan
            '在庫注文',         // Stok Dipesan
            '利用可能な在庫',   // Stok Tersedia
            '在庫が必要です',   // Stok Dibutuhkan
            '日付',             // Tanggal
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = $this->collection()->count() + 1;
        $columnCount = count($this->headings());
        $lastColumn = Coordinate::stringFromColumnIndex($columnCount);
        $range = "A1:{$lastColumn}{$rowCount}";

        // Border seluruh sel
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => '000000'],
                ],
            ],
        ]);

        // Header tebal
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true);

        // Bungkus teks kolom nama (kolom A)
        $sheet->getStyle("A2:A{$rowCount}")
              ->getAlignment()
              ->setWrapText(true);
    }
}

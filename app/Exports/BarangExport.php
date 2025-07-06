<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $barangs = Barang::all();
        // Ubah ke array dan tambahkan nomor urut
        return $barangs->map(function ($barang, $index) {
            return [
                'No'       => $index + 1,
                'Nama'     => $barang->nama,
                'Satuan'   => $barang->satuan,
                'Stok'     => $barang->stok_dipesan,
                'Stok Tersedia' => $barang->stok_tersedia,
                'Stok Dibutuhkan' => $barang->stok_dibutuhkan,
                'Tanggal'  => $barang->created_at ? $barang->created_at->format('Y-m-d H:i') : '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            '名',
            '単位',
            '在庫注文',
            '利用可能な在庫',
            '在庫が必要です',
            '日付',
        ];
    }
}

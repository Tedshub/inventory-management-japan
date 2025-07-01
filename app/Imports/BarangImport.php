<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;

class BarangImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Barang([
            'nama'     => $row[0],
            'kategori' => $row[1],
            'stok'     => $row[2],
            'satuan'   => $row[3],
        ]);
    }
}

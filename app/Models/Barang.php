<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'satuan',
        'stok_dipesan',
        'stok_tersedia',
        'stok_dibutuhkan',
    ];
}

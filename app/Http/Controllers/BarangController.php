<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;
use App\Imports\BarangImport;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return Inertia::render('Barang/Index', ['barangs' => $barangs]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer',
            'satuan' => 'required',
        ]);

        Barang::create($request->all());
        return redirect()->back()->with('success', 'Barang ditambahkan');
    }

    public function export()
    {
        return Excel::download(new BarangExport, 'data-barang.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);
        Excel::import(new BarangImport, $request->file('file'));
        return redirect()->back()->with('success', 'Import berhasil');
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'stok' => 'required|integer',
            'satuan' => 'required',
        ]);

        $barang->update($request->all());
        return redirect()->back()->with('success', 'Barang diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->back()->with('success', 'Barang dihapus');
    }

}

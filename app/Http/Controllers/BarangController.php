<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;
use App\Imports\BarangImport;
use App\Http\Requests\BulkDeleteBarangRequest;

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
            'satuan' => 'nullable',
            'stok_dipesan' => 'nullable|integer',
            'stok_tersedia' => 'nullable|integer',
            'stok_dibutukan' => 'nullable|integer',
        ]);

        Barang::create($request->all());
        return redirect()->back()->with('success', 'Barang ditambahkan');
    }

    public function export()
    {
        return Excel::download(new BarangExport, '輸出_アイテム.xlsx');
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
            'satuan' => 'nullable',
            'stok_dipesan' => 'nullable|integer',
            'stok_tersedia' => 'nullable|integer',
            'stok_dibutukan' => 'nullable|integer',
        ]);

        $barang->update($request->all());
        return redirect()->back()->with('success', 'Barang diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->back()->with('success', 'Barang dihapus');
    }

        public function bulkDelete(BulkDeleteBarangRequest $request)
    {
        try {
            $ids = $request->validated()['ids'];

            // Ambil data barang yang akan dihapus
            $barangsToDelete = Barang::whereIn('id', $ids)
                ->select('id', 'nama')
                ->get();

            if ($barangsToDelete->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada barang yang ditemukan');
            }

            // Lakukan bulk delete dengan transaction untuk keamanan
            \DB::transaction(function () use ($ids) {
                Barang::whereIn('id', $ids)->delete();
            });

            $deletedCount = $barangsToDelete->count();

            // Log aktivitas
            \Log::info('Bulk delete barang berhasil', [
                'count' => $deletedCount,
                'ids' => $ids,
                'user_id' => auth()->id(),
                'barangs' => $barangsToDelete->pluck('nama')->toArray()
            ]);

            return redirect()->back()->with('success', "{$deletedCount} barang berhasil dihapus");

        } catch (\Exception $e) {
            \Log::error('Error dalam bulk delete barang', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids', []),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

}

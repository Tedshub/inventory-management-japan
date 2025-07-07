<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;
use App\Imports\BarangImport;
use App\Http\Requests\BulkDeleteBarangRequest;

class BarangController extends Controller
{
    private function checkAccess()
    {
        $userId = Auth::id();
        if (!in_array($userId, [1, 2, 3, 4])) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }
    }

    public function index()
    {
        $this->checkAccess();

        $barangs = Barang::all();
        return Inertia::render('Barang/Index', ['barangs' => $barangs]);
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'nama' => 'required',
            'satuan' => 'nullable',
            'stok_dipesan' => 'nullable|integer',
            'stok_tersedia' => 'nullable|integer',
            'stok_dibutuhkan' => 'nullable|integer',
        ]);

        Barang::create($request->all());
        return redirect()->back()->with('success', 'Barang ditambahkan');
    }

    public function export()
    {
        $this->checkAccess();

        return Excel::download(new BarangExport, '輸出_アイテム.xlsx');
    }

    public function import(Request $request)
    {
        $this->checkAccess();

        try {
            Log::info('Import request received', [
                'file_uploaded' => $request->hasFile('file'),
                'filename' => $request->file('file')->getClientOriginalName() ?? 'no file',
            ]);

            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:10240'
            ]);

            $import = new BarangImport();
            Excel::import($import, $request->file('file'));

            if ($import->failures()->isNotEmpty()) {
                $failureMessages = [];

                foreach ($import->failures() as $failure) {
                    $row = $failure->row();
                    $errors = implode(', ', $failure->errors());
                    $values = json_encode($failure->values());

                    Log::warning("Import failure on row {$row}: {$errors}. Values: {$values}");
                    $failureMessages[] = "Baris {$row}: {$errors}";
                }

                return back()->with([
                    'error' => 'Import gagal pada beberapa baris.',
                    'failures' => $failureMessages
                ]);
            }

            Log::info('Import selesai tanpa error');
            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Barang $barang)
    {
        $this->checkAccess();

        $request->validate([
            'nama' => 'required',
            'satuan' => 'nullable',
            'stok_dipesan' => 'nullable|integer',
            'stok_tersedia' => 'nullable|integer',
            'stok_dibutuhkan' => 'nullable|integer',
        ]);

        $barang->update($request->all());
        return redirect()->back()->with('success', 'Barang diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $this->checkAccess();

        $barang->delete();
        return redirect()->back()->with('success', 'Barang dihapus');
    }

    public function bulkDelete(BulkDeleteBarangRequest $request)
    {
        $this->checkAccess();

        try {
            $ids = $request->validated()['ids'];

            $barangsToDelete = Barang::whereIn('id', $ids)
                ->select('id', 'nama')
                ->get();

            if ($barangsToDelete->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada barang yang ditemukan');
            }

            \DB::transaction(function () use ($ids) {
                Barang::whereIn('id', $ids)->delete();
            });

            $deletedCount = $barangsToDelete->count();

            Log::info('Bulk delete barang berhasil', [
                'count' => $deletedCount,
                'ids' => $ids,
                'user_id' => Auth::id(),
                'barangs' => $barangsToDelete->pluck('nama')->toArray()
            ]);

            return redirect()->back()->with('success', "{$deletedCount} barang berhasil dihapus");
        } catch (\Exception $e) {
            Log::error('Error dalam bulk delete barang', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids', []),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
}

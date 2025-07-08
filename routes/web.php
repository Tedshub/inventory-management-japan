<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk cek login

// Halaman utama - redirect ke /barang jika sudah login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('barang.index');
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route setelah login (masih bisa dipakai kalau perlu)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// Group route untuk Barang (auth + verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::post('/barang/import', [BarangController::class, 'import'])->name('barang.import');
    Route::get('/barang/export', [BarangController::class, 'export'])->name('barang.export');
    Route::put('/barang/{barang}', [BarangController::class, 'update']);
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy']);
    Route::post('/barang/bulk-delete', [BarangController::class, 'bulkDelete'])
        ->name('barang.bulk-delete')
        ->middleware('throttle:10,1');
});

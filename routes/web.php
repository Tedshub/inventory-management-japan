<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BarangController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'verified', 'auth'])->group(function () {
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::post('/barang/import', [BarangController::class, 'import'])->name('barang.import');
    Route::get('/barang/export', [BarangController::class, 'export'])->name('barang.export');
    Route::put('/barang/{barang}', [BarangController::class, 'update']);
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy']);
    Route::post('/barang/bulk-delete', [BarangController::class, 'bulkDelete'])
    ->name('barang.bulk-delete')
    ->middleware('throttle:10,1'); // Maksimal 10 request per menit
});

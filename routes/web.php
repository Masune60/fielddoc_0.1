<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KhsController;
use App\Http\Controllers\KrController;
use App\Http\Controllers\PkController;
use App\Http\Controllers\TahapPenagihanController;
use App\Http\Controllers\ExportZipController;
use App\Http\Controllers\FotoDokumentasiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Resource Management User
    Route::resource('users', UserController::class)->middleware('can:manage users');

    // Route Resource Master KHS
    // Modifikasi (create, store, edit, update, destroy) memerlukan 'manage khs'
    // View (index, show) memerlukan 'view khs'
    // PERHATIAN: Route create/store/edit HARUS didaftarkan SEBELUM show agar
    // URI khs/create tidak tertangkap oleh khs/{kh} (model binding).
    Route::resource('khs', KhsController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('can:manage khs');
    Route::resource('khs', KhsController::class)->only(['index', 'show'])->middleware('can:view khs');

    // Route Resource Master KR (tidak punya index/show sendiri; diakses via KHS show)
    Route::resource('kr', KrController::class)->except(['index', 'show'])->middleware('can:manage kr');

    // Route Resource Master Tahap Penagihan
    // Modifikasi memerlukan 'manage tahap'; View memerlukan 'view tahap'
    Route::resource('tahap', TahapPenagihanController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware('can:manage tahap');
    Route::resource('tahap', TahapPenagihanController::class)->only(['index', 'show'])->middleware('can:view tahap');

    // Route PK/SPK
    // create/store -> 'create pk'; edit/update/destroy -> 'edit pk'; index/show -> 'view pk'
    Route::resource('pk', PkController::class)->only(['create', 'store'])->middleware('can:create pk');
    Route::resource('pk', PkController::class)->only(['edit', 'update', 'destroy'])->middleware('can:edit pk');
    Route::resource('pk', PkController::class)->only(['index', 'show'])->middleware('can:view pk');

    // Route Upload & Hapus Foto
    Route::post('/pk/{pk}/foto', [FotoDokumentasiController::class, 'store'])->name('foto.store')->middleware('can:upload foto');
    Route::delete('/foto/{foto}', [FotoDokumentasiController::class, 'destroy'])->name('foto.destroy')->middleware('can:delete foto');

    // Route Export ZIP
    Route::get('/tahap/{tahap}/export', [ExportZipController::class, 'download'])->name('tahap.export')->middleware('can:export zip');
});

require __DIR__.'/auth.php';
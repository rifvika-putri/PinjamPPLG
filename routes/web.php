<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\KategoriController;


Route::get('/', function () {
    return view('welcome');
});

    // --- GROUP ROUTE UNTUK AUTH (ADMIN, PETUGAS, SISWA) ---
    Route::middleware(['auth'])->group(function () {
        
    Route::get('/dashboard', [DashboardController::class, 'siswa'])->name('dashboard');
    Route::get('/pinjam-alat', [DashboardController::class, 'pinjamAlat'])->name('pinjam.alat');
    
   // Ganti baris pengembalian siswa jadi ini
    Route::post('/proses-pengembalian-barang', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    
    // 2. PROFILE SETTINGS
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. --- PREFIX ADMIN ---
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // CRUD Petugas (Hanya di dalam Admin)
        Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
        Route::put('/petugas/{id}', [PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');

        // CRUD Barang
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

        // Manajemen Peminjaman
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/setujui/{id}', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::put('/peminjaman/selesaikan/{id}', [PeminjamanController::class, 'selesaikan'])->name('peminjaman.selesaikan');
        Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');

        Route::get('/admin/pengembalian', [PeminjamanController::class, 'indexPengembalian'])->name('pengembalian.index');
        Route::post('/admin/pengembalian/verifikasi/{id}', [PeminjamanController::class, 'verifikasiAkhir'])->name('pengembalian.verifikasi');
    
        Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/admin/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        });

    // 4. --- PREFIX PETUGAS ---
    Route::prefix('petugas')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'petugas'])->name('petugas.dashboard');
        
        // Petugas juga butuh akses ke Barang & Peminjaman
        Route::get('/barang', [BarangController::class, 'index'])->name('petugas.barang.index');
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('petugas.peminjaman.index');
    });

        Route::get('/riwayat', [PeminjamanController::class, 'riwayat'])->name('siswa.riwayat');
        Route::delete('/peminjaman/batal/{id}', [PeminjamanController::class, 'batalkan'])->name('siswa.peminjaman.batal');
        Route::post('/peminjaman/kembali', [PeminjamanController::class, 'kembalikan'])->name('siswa.peminjaman.kembali');
    });

require __DIR__.'/auth.php';
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

// --- GROUP ROUTE UNTUK SEMUA YANG SUDAH LOGIN ---
Route::middleware(['auth'])->group(function () {
    
    // 1. PROFILE SETTINGS (Bisa diakses semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2. AKSES KHUSUS SISWA
    // Kita pisahkan agar dashboard siswa tidak tertukar
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pinjam-alat', [DashboardController::class, 'pinjamAlat'])->name('pinjam.alat');
    Route::get('/riwayat', [PeminjamanController::class, 'riwayat'])->name('siswa.riwayat');
    
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::post('/peminjaman/kembali', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::post('/peminjaman/bayar', [PeminjamanController::class, 'bayarDenda'])->name('siswa.peminjaman.bayar');
    Route::delete('/peminjaman/batal/{id}', [PeminjamanController::class, 'batalkan'])->name('peminjaman.batal');

    // 3. --- AKSES KHUSUS ADMIN & PETUGAS ---
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

       // --- DETAIL CRUD BARANG ---
        Route::get('/barang', [BarangController::class, 'index'])->name('admin.barang.index');          // Tampilan Tabel
        Route::get('/barang/create', [BarangController::class, 'create'])->name('admin.barang.create');   // Form Tambah
        Route::post('/barang/store', [BarangController::class, 'store'])->name('admin.barang.store');     // Proses Simpan
        Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('admin.barang.edit');    // Form Edit
        Route::put('/barang/{id}', [BarangController::class, 'update'])->name('admin.barang.update');     // Proses Update
        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('admin.barang.destroy'); // Proses Hapus

        // --- DETAIL CRUD KATEGORI ---
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        // --- DETAIL CRUD PETUGAS ---
        Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store'); // Untuk Simpan
        Route::get('/petugas/{id}', [PetugasController::class, 'show'])->name('petugas.show');
        Route::put('/petugas/{id}', [PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');

        // Manajemen Peminjaman (Admin Side)
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/setujui/{id}', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::put('/peminjaman/selesaikan/{id}', [PeminjamanController::class, 'selesaikan'])->name('peminjaman.selesaikan');
        
        // Verifikasi Denda (Sudah dihapus /admin-nya karena sudah ada di prefix)
        Route::get('/verifikasi-denda', [PeminjamanController::class, 'indexDenda'])->name('admin.denda.index');
        Route::post('/peminjaman/verifikasi-denda/{id}', [PeminjamanController::class, 'verifikasiPembayaran'])->name('admin.peminjaman.verifikasi-denda');

        // Pengembalian & Notifikasi
        Route::get('/pengembalian', [PeminjamanController::class, 'indexPengembalian'])->name('pengembalian.index');
        Route::get('/peminjaman/kirim-wa/{id}', [PeminjamanController::class, 'kirimNotifPeminjaman'])->name('peminjaman.kirimWA');
        
        // Perbaikan: Pakai PeminjamanController karena PengembalianController tidak di-import
        Route::get('/pengembalian/kirim-wa/{id}', [PeminjamanController::class, 'kirimNotifDenda'])->name('pengembalian.kirimWA');
        
        // Rekap Laporan PDF
        Route::get('/rekap-laporan', [LaporanController::class, 'index'])->name('laporan.index');
        // Tambahkan ini nanti untuk fitur cetak
        Route::get('/rekap-laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    });

    // 4. --- AKSES KHUSUS PETUGAS (Hanya rute unik petugas) ---
    Route::prefix('petugas')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'petugas'])->name('petugas.dashboard');
    });
});

require __DIR__.'/auth.php';
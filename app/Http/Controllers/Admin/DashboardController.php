<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // DASHBOARD ADMIN
    public function index()
    {
        $jumlahBarang = Barang::count();
        $jumlahPetugas = User::where('role', 'petugas')->count();
        $jumlahSiswa = User::where('role', 'siswa')->count();
        $pinjamanAktif = Peminjaman::where('status', 'dipinjam')->count();

        return view('admin.dashboard', compact('jumlahBarang', 'jumlahPetugas', 'jumlahSiswa', 'pinjamanAktif'));
    }

    // DASHBOARD PETUGAS
    public function petugas()
    {
        $jumlahBarang = Barang::count();
        $jumlahSiswa = User::where('role', 'siswa')->count();
        $pinjamanAktif = Peminjaman::where('status', 'dipinjam')->count();

        return view('petugas.dashboard', compact('jumlahBarang', 'jumlahSiswa', 'pinjamanAktif'));
    }

   // 1. DASHBOARD SISWA (Hanya Ringkasan)
    public function siswa()
    {
        // Di dashboard kita cuma tampilin 4 barang terbaru buat "sneak peek"
        $barangs = Barang::where('status', 'tersedia') 
                         ->latest()
                         ->take(4) 
                         ->get();

        // Ambil riwayat peminjaman siswa yang sedang login
        $pinjaman_saya = Peminjaman::with('barang')
                                    ->where('user_id', Auth::id())
                                    ->latest()
                                    ->take(5)
                                    ->get();

        return view('dashboard', compact('barangs', 'pinjaman_saya'));
    }

    // 2. HALAMAN PINJAM ALAT (Katalog Lengkap)
    public function pinjamAlat()
    {
        // Ambil SEMUA barang yang tersedia untuk katalog lengkap
        $barangs = Barang::where('status', 'tersedia')
                         ->latest()
                         ->get();
        $user = Auth::user();
        
        // Kita arahkan ke file view baru nanti
        return view('siswa.pinjam-alat', compact('barangs', 'user'));
    }
}
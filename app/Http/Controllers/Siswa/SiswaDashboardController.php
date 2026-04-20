<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        
        // 1. Ambil data statistik siswa
        $countDipinjam = Peminjaman::where('user_id', $user_id)->where('status', 'dipinjam')->count();
        $countPending = Peminjaman::where('user_id', $user_id)->where('status', 'pending')->count();
        $countSelesai = Peminjaman::where('user_id', $user_id)->where('status', 'selesai')->count();

        // 2. Ambil riwayat peminjaman terbaru
        $riwayats = Peminjaman::where('user_id', $user_id)->with('barang')->latest()->take(5)->get();

        // 3. LOGIC PETUGAS PIKET
        // Kita ambil hari ini (Senin, Selasa, dsb)
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd'); 
        
        // Cari user yang rolenya petugas dan jadwal_hari-nya cocok
        $petugasPiket = User::where('role', 'petugas')
                    ->where('jadwal_kerja', 'LIKE', '%' . $hariIni . '%')
                    ->first();

        return view('dashboard', compact('countDipinjam', 'countPending', 'countSelesai', 'riwayats', 'petugasPiket'));
    }
}
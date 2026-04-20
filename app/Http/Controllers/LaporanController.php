<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\KondisiLog; // Pastikan model ini ada jika ingin mencatat log kerusakan
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Set Range Waktu (Default: Bulan Ini)
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // 2. Rekap Data Utama
        $rekap = [
            'barang_rusak' => Barang::where('kondisi', 'Rusak')->count(),
            'total_pinjam' => Peminjaman::count(),
            'total_barang' => Barang::count(),
            'siswa_denda'  => Peminjaman::where('total_denda', '>', 0)->count(),
        ];

        // 3. Logika Data Grafik (Tren 12 Bulan Tahun Ini)
        $labels = [];
        $dataPeminjaman = [];
        $dataKerusakan = [];

        for ($m = 1; $m <= 12; $m++) {
            // Nama bulan (Jan, Feb, Mar, dst)
            $labels[] = Carbon::create()->month($m)->format('M');
            
            // Grafik 1: Hitung jumlah peminjaman per bulan
            $dataPeminjaman[] = Peminjaman::whereMonth('tanggal_pinjam', $m)
                ->whereYear('tanggal_pinjam', date('Y'))
                ->count();
                
            // Grafik 2: Hitung jumlah barang rusak per bulan 
            // (Asumsi kamu punya kolom created_at di tabel barangs atau log khusus)
            $dataKerusakan[] = Barang::where('kondisi', 'Rusak')
                ->whereMonth('created_at', $m)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        // 4. Kirim ke View
        return view('admin.laporan.index', compact(
            'rekap', 
            'labels', 
            'dataPeminjaman', 
            'dataKerusakan'
        ));
    } // Penutup fungsi index
} // Penutup class LaporanController
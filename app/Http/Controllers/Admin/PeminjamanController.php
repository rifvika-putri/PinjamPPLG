<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'barang'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function setujui($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $pinjam->update(['status' => 'dipinjam']);

        // Update status barang
        Barang::where('id', $pinjam->barang_id)->update(['status' => 'Dipinjam']);

        \App\Models\Aktivitas::catat(
        "Menyetujui peminjaman " . $pinjam->barang->nama_barang . " untuk " . $pinjam->user->name, 
        "check-circle", 
        "indigo"
        );

         return back()->with('success', 'Peminjaman disetujui!');
    }

    public function selesaikan(Request $request, $id)
    {
        $p = Peminjaman::findOrFail($id); 

        $dendaTelat = $request->denda_telat ?? 0;
        $dendaKerusakan = $request->denda_kerusakan ?? 0;
        $totalDenda = (int)$dendaTelat + (int)$dendaKerusakan;

        // Status peminjaman
        $statusAkhir = ($totalDenda > 0) ? 'kembalikan pending' : 'selesai'; 
        $statusBayar = ($totalDenda > 0) ? 'Belum Bayar' : 'Lunas';

        $p->update([
            'status' => $statusAkhir, 
            'kondisi_kembali' => $request->kondisi_kembali,
            'denda_telat' => $dendaTelat,
            'denda_kerusakan' => $dendaKerusakan,
            'total_denda' => $totalDenda,
            'catatan_kerusakan' => $request->catatan_kerusakan,
            'status_pembayaran' => $statusBayar,
        ]);

        // 1. UPDATE STATUS BARANG (Mengikuti kondisi yang diinput admin)
        // Jika admin pilih 'Baik', maka barang jadi 'Tersedia'
        // Jika admin pilih 'Rusak', maka barang jadi 'Rusak' (supaya tidak bisa dipinjam dulu)
        $statusBarang = ($request->kondisi_kembali == 'Baik') ? 'Tersedia' : $request->kondisi_kembali;
        
        Barang::where('id', $p->barang_id)->update(['status' => $statusBarang]);

        // 2. CATAT KEJADIAN KE TABEL LOG (Untuk Rekap Laporan)
        \App\Models\KondisiLog::create([
            'barang_id' => $p->barang_id,
            'kondisi_saat_itu' => $request->kondisi_kembali, // 'Baik' atau 'Rusak'
            'tanggal' => now(), // Tanggal kejadian
            'catatan' => 'Dicatat otomatis saat pengembalian oleh ' . $p->user->name . '. ' . ($request->catatan_kerusakan ?? '')
        ]);

        return redirect()->back()->with('success', 'Pengembalian diverifikasi! ' . ($totalDenda > 0 ? 'Menunggu pembayaran denda.' : 'Transaksi selesai.'));
    }
    

    public function kembalikan(Request $request)
{
    // 1. Cari data peminjamannya
    $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

    // 2. Upload foto bukti kembali jika ada
    $fotoPath = null;
    if ($request->hasFile('foto_kembali')) {
        $fotoPath = $request->file('foto_kembali')->store('bukti_kembali', 'public');
    }

    // 3. UPDATE DATA DI TABEL PEMINJAMANS
    $peminjaman->update([
        'status' => 'kembalikan_pending', // INI KUNCINYA: Harus berubah jadi 'dikembalikan'
        'tanggal_kembali_realisasi' => now(),
        'foto_kembali' => $fotoPath,
        'kondisi_kembali' => $request->kondisi_kembali,
    ]);

    return redirect()->back()->with('success', 'Barang berhasil dikembalikan, menunggu pengecekan denda oleh admin.');
}

    public function batalkan($id) // Ganti nama dari 'batal' ke 'batalkan' agar sesuai web.php
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete(); 
        return redirect()->back()->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function bayarDenda(Request $request)
{
    $request->validate([
        'peminjaman_id' => 'required',
        'bukti_bayar' => 'required|image|max:2048',
        'metode' => 'required'
    ]);

    $p = Peminjaman::findOrFail($request->peminjaman_id);
    
    if ($request->hasFile('bukti_bayar')) {
        $file = $request->file('bukti_bayar');
        $nama_file = time() . "_" . Auth::user()->name . "_denda." . $file->getClientOriginalExtension();
        
        // Simpan file
        $file->move(public_path('uploads/bukti_denda'), $nama_file);
        
        $p->update([
            // Pastikan string ini sama dengan yang dicek di Blade
            'status_pembayaran' => 'PENDING', 
            'bukti_pembayaran' => $nama_file, 
            'metode_pembayaran' => $request->metode
        ]);
    }

    return redirect()->back()->with('success', 'Bukti bayar terkirim! Menunggu verifikasi admin.');
}

    public function indexDenda()
    {
        $dendas = Peminjaman::with(['user', 'barang'])
                            ->where('total_denda', '>', 0)
                            ->where('status_pembayaran', 'PENDING')
                            ->get();
        return view('admin.peminjaman.denda', compact('dendas'));
    }

    public function verifikasiPembayaran($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $peminjaman->update([
            'status_pembayaran' => 'Lunas',
            'status' => 'selesai'
        ]);

        return redirect()->back()->with('success', 'Pembayaran denda berhasil diverifikasi! Transaksi selesai.');
    }

    public function riwayat()
    {
        $peminjamans = Peminjaman::with('barang')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('siswa.riwayat', compact('peminjamans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'    => 'required|exists:barangs,id',
            'keperluan'    => 'required|string|min:5',
            'opsi_kembali' => 'required|in:hari_ini,manual',
            'bukti_ambil'  => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $tglRencana = ($request->opsi_kembali == 'hari_ini') ? now()->setTime(15, 0, 0) : $request->tgl_kembali;

        $namaFoto = null;
        if ($request->hasFile('bukti_ambil')) {
            $file = $request->file('bukti_ambil');
            $namaFoto = time() . '_' . Auth::user()->name . '_pinjam.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti_peminjaman'), $namaFoto);
        }

        Peminjaman::create([
            'user_id'                 => auth()->id(),
            'barang_id'               => $request->barang_id,
            'tanggal_pinjam'          => now(),
            'tanggal_kembali_rencana'  => $tglRencana,
            'keperluan'               => $request->keperluan,
            'status'                  => 'pending',
            'foto_pinjam'             => $namaFoto,
            'kondisi_pinjam'          => 'baik',
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil diajukan!');
    }

    public function indexPengembalian()
    {
        $pengembalians = Peminjaman::whereIn('status', ['kembalikan_pending', 'selesai'])
            ->with(['user', 'barang'])
            ->latest()
            ->get();

        return view('admin.pengembalian.index', compact('pengembalians'));
    }

    private function kirimPesanWA($no_hp, $pesan)
{
    $token = "TOKEN_FONNTE_ANDA"; // Ganti dengan token dari Fonnte

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => array(
            'target' => $no_hp,
            'message' => $pesan,
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

public function kirimNotifPeminjaman($id)
{
    $pinjam = Peminjaman::with(['user', 'barang'])->findOrFail($id);
    
    $pesan = "Halo *" . $pinjam->user->name . "*,\n\n" .
             "Peminjaman barang *" . $pinjam->barang->nama_barang . "* telah *DISETUJUI* oleh Admin.\n" .
             "Harap gunakan barang dengan bijak dan kembalikan tepat waktu.\n\n" .
             "Terima kasih.";

    $this->kirimPesanWA($pinjam->user->no_wa, $pesan);

    return redirect()->back()->with('success', 'Notifikasi peminjaman terkirim ke WA siswa!');
}

}
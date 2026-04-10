<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
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

        return back()->with('success', 'Peminjaman disetujui!');
    }

    // FIX: Fungsi Verifikasi Admin (Selesaikan)
    public function selesaikan(Request $request, $id)
    {
        $p = Peminjaman::findOrFail($id); 

        $dendaTelat = $request->denda_telat ?? 0;
        $dendaKerusakan = $request->denda_kerusakan ?? 0;
        $totalDenda = (int)$dendaTelat + (int)$dendaKerusakan;

        $p->update([
            'status' => 'selesai', 
            'kondisi_kembali' => $request->kondisi_kembali,
            'denda_telat' => $dendaTelat,
            'denda_kerusakan' => $dendaKerusakan,
            'total_denda' => $totalDenda,
            'catatan_kerusakan' => $request->catatan_kerusakan,
            'status_pembayaran' => $request->status_pembayaran,
            'metode_pembayaran' => $request->metode_pembayaran, // Tambahkan ini
        ]);

        // Setelah selesai, kembalikan status barang jadi Tersedia
        Barang::where('id', $p->barang_id)->update(['status' => 'Tersedia']);

        return redirect()->back()->with('success', 'Pengembalian berhasil diverifikasi!');
    }

    // FIX: Fungsi Siswa Balikin Barang (Hanya pakai SATU fungsi ini saja)
    public function kembalikan(Request $request)
{
    $request->validate([
        'peminjaman_id' => 'required',
        'foto_kembali' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $p = Peminjaman::findOrFail($request->peminjaman_id);

    if ($request->hasFile('foto_kembali')) {
        $file = $request->file('foto_kembali');
        $nama_file = time() . "_" . Auth::user()->name . "_kembali." . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/bukti_kembali'), $nama_file);
        $p->foto_kembali = $nama_file;
    }

    // UPDATE STATUS
    $p->status = 'kembalikan pending';
    $p->tanggal_kembali_realisasi = now();
    
    // PENTING: Jangan masukkan 'kondisi_siswa' karena kolomnya tidak ada di DB!
    $p->save(); 

    return redirect()->back()->with('success', 'Bukti terkirim! Menunggu verifikasi admin.');
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
        // ... (Kode store kamu sudah cukup oke, pastikan path move sama dengan folder lain)
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
            'user_id'                   => auth()->id(),
            'barang_id'                 => $request->barang_id,
            'tanggal_pinjam'            => now(),
            'tanggal_kembali_rencana'   => $tglRencana,
            'keperluan'                 => $request->keperluan,
            'status'                    => 'pending',
            'foto_pinjam'               => $namaFoto,
            'kondisi_pinjam'            => 'baik',
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil diajukan!');
    }

    public function indexPengembalian()
    {
        $pengembalians = Peminjaman::whereIn('status', ['kembalikan pending', 'selesai'])
            ->with(['user', 'barang'])
            ->latest()
            ->get();

        return view('admin.pengembalian.index', compact('pengembalians'));
    }
}
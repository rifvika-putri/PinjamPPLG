<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
   public function index()
{
    // withCount akan otomatis membuat kolom bayangan bernama 'barangs_count'
    $kategoris = Kategori::withCount('barangs')->latest()->get();
    
    return view('admin.kategori.index', compact('kategoris'));
}

public function store(Request $request)
{
    $request->validate([
        'nama_kategori' => 'required|unique:kategoris,nama_kategori'
    ]);

    // 1. Simpan Kategorinya dulu
    Kategori::create([
        'nama_kategori' => $request->nama_kategori,
        'slug' => str()->slug($request->nama_kategori)
    ]);

    // 2. Simpan Aktivitasnya (TARUH DI SINI, SEBELUM RETURN)
    \App\Models\Aktivitas::create([
        'pesan' => 'Menambah kategori baru: ' . $request->nama_kategori,
        'icon' => 'tag',
        'warna' => 'blue',
        'user_name' => auth()->user()->name ?? 'Admin'
    ]);

    // 3. Baru panggil return untuk pindah halaman
    return redirect()->back()->with('success', 'Kategori berhasil ditambah!');
}

public function destroy($id)
{
    $kategori = Kategori::findOrFail($id);
    $namaKategori = $kategori->nama_kategori; // Ambil nama dulu
    
    $kategori->delete(); // Baru hapus

    // Sekarang panggil fungsi catat yang sudah dibuat di Model tadi
    \App\Models\Aktivitas::catat(
        "Menghapus kategori barang: " . $namaKategori,
        "trash-2",
        "rose"
    );

    return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_kategori' => 'required|string|max:255',
    ]);

    $kategori = \App\Models\Kategori::findOrFail($id);
    $kategori->update([
        'nama_kategori' => $request->nama_kategori,
        'slug' => \Illuminate\Support\Str::slug($request->nama_kategori),
    ]);

    return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
}

}

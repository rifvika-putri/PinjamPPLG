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

    // Sekarang ini sudah aman karena sudah ada $fillable di model
    Kategori::create([
        'nama_kategori' => $request->nama_kategori,
        'slug' => str()->slug($request->nama_kategori)
    ]);

    return redirect()->back()->with('success', 'Kategori berhasil ditambah!');
}

public function destroy($id)
{
    $kategori = Kategori::findOrFail($id);
    $kategori->delete();
    return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
}
}

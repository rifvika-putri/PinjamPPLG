<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategoris = Kategori::all(); // Selalu ambil kategori untuk dropdown modal

        if ($search) {
            $results = Barang::with('kategori')
                ->where('nama_barang', 'like', "%{$search}%")
                ->orWhere('kode_barang', 'like', "%{$search}%")
                ->get();

            return view('admin.barang.index', [
                'barangTersedia' => $results,
                'barangDipinjam' => collect(),
                'barangRusak'    => collect(),
                'kategoris'      => $kategoris, // Kirim kategori agar modal tetap fungsi saat search
                'isSearching'    => true
            ]);
        }

        // TAB TERSEDIA: Barangnya BAIK dan sedang TIDAK DIPINJAM
        $barangTersedia = Barang::with('kategori')
            ->where('kondisi', 'Baik')
            ->where('status', 'Tersedia')
            ->get();

        // TAB DIPINJAM: Barangnya sedang dibawa siswa
        $barangDipinjam = Barang::with('kategori')
            ->where('status', 'Dipinjam')
            ->get();

        // TAB RUSAK: Mencakup kondisi 'Rusak' DAN 'Perbaikan'
        $barangRusak = Barang::with('kategori')
            ->whereIn('kondisi', ['Rusak', 'Perbaikan'])
            ->get();

        return view('admin.barang.index', compact('barangTersedia', 'barangDipinjam', 'barangRusak', 'kategoris'))
            ->with('isSearching', false);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'kode_barang' => 'required|unique:barangs',
            'foto'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'kondisi'     => 'required',
            'stok'        => 'nullable|numeric',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama_foto = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/barang'), $nama_foto);
            $data['foto'] = $nama_foto;
        }

        $data['status'] = 'Tersedia';

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan ke katalog!');
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        
        $request->validate([
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'kondisi'     => 'required',
        ]);

        $data = $request->except(['status', 'foto']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($barang->foto && File::exists(public_path('uploads/barang/' . $barang->foto))) {
                File::delete(public_path('uploads/barang/' . $barang->foto));
            }

            $file = $request->file('foto');
            $nama_foto = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/barang'), $nama_foto);
            $data['foto'] = $nama_foto;
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Data barang diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->foto && File::exists(public_path('uploads/barang/' . $barang->foto))) {
            File::delete(public_path('uploads/barang/' . $barang->foto));
        }

        $barang->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus!');
    }
}
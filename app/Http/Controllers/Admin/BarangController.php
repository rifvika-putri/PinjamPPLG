<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BarangController extends Controller
{
    public function index()
{
    // Ambil semua barang agar fitur search Alpine.js di blade bisa memfilter semuanya
    $barangs = Barang::with('kategori')->get();
    $kategoris = Kategori::all();

    return view('admin.barang.index', compact('barangs', 'kategoris'));
}

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.barang.edit', compact('barang', 'kategoris'));
    }

    public function store(Request $request)
{
    // 1. Tangkap kondisi dan buat hurufnya kecil semua agar sinkron dengan form
    $kondisi = strtolower($request->kondisi);

    $rules = [
        'nama_barang' => 'required',
        'kategori_id' => 'required|exists:kategoris,id',
        'kode_barang' => 'required|unique:barangs',
        'lokasi'      => 'required|string', // Tambahkan lokasi
        'foto'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        'kondisi'     => 'required|in:baik,rusak,perbaikan', // Gunakan huruf kecil
    ];

    // 2. Validasi tambahan jika kondisi rusak
    if ($kondisi === 'rusak') {
        $rules['level_kerusakan'] = 'required|in:ringan,sedang,berat';
        $rules['catatan_kerusakan'] = 'required|string|max:500';
    }

    $request->validate($rules);

    // 3. Siapkan data untuk disimpan
    $data = $request->except(['foto']); // Ambil semua kecuali foto dulu

    // Bersihkan catatan jika kondisinya bukan rusak
    if ($kondisi !== 'rusak') {
        $data['level_kerusakan'] = null;
        $data['catatan_kerusakan'] = null;
    }

    // 4. Proses Foto
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $nama_foto = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/barang'), $nama_foto);
        $data['foto'] = $nama_foto;
    }

    $data['status'] = 'Tersedia';

    // 5. Simpan ke Database dan tampung ke variabel $barang
    $barang = \App\Models\Barang::create($data);
   
    // 6. Catat Aktivitas jika berhasil
    if ($barang) {
        \App\Models\Aktivitas::catat(
            "Berhasil menambah barang baru: " . $request->nama_barang, 
            "package", 
            "blue"
        );
    }

    return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan ke katalog!');
}

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        
        // 1. Simpan kondisi lama sebelum di-update untuk pengecekan nanti
        $kondisiLama = $barang->kondisi; 
        $kondisiBaru = $request->kondisi;

        $rules = [
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $id,
            'lokasi'      => 'required',
            'deskripsi'   => 'nullable|string',
            'kondisi'     => 'required|in:Baik,Rusak,Perbaikan',
            'foto'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];

        if ($kondisiBaru === 'Rusak') {
            $rules['level_kerusakan'] = 'required|in:ringan,sedang,berat';
            $rules['catatan_kerusakan'] = 'required|string|max:500';
        } else {
            $rules['level_kerusakan'] = 'nullable';
            $rules['catatan_kerusakan'] = 'nullable';
        }

        $request->validate($rules);

        $data = $request->except(['status', 'foto']);

        if ($kondisiBaru === 'Baik') {
            $data['level_kerusakan'] = null;
            $data['catatan_kerusakan'] = null;
        }

        if ($request->hasFile('foto')) {
            if ($barang->foto && \File::exists(public_path('uploads/barang/' . $barang->foto))) {
                \File::delete(public_path('uploads/barang/' . $barang->foto));
            }

            $file = $request->file('foto');
            $nama_foto = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/barang'), $nama_foto);
            $data['foto'] = $nama_foto;
        }

        // 2. Update data barang
        $barang->update($data);

        // 3. LOGIKA REKAP: Catat ke KondisiLog JIKA status kondisinya berubah
        if ($kondisiLama !== $kondisiBaru) {
            \App\Models\KondisiLog::create([
                'barang_id'        => $barang->id,
                'kondisi_saat_itu' => $kondisiBaru,
                'tanggal'          => now(),
                'catatan'          => $kondisiBaru === 'Rusak' 
                                    ? $request->catatan_kerusakan 
                                    : 'Update status manual oleh admin/petugas'
            ]);
        }

        return redirect()->route('admin.barang.index')->with('success', 'Data barang berhasil diperbarui!');
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
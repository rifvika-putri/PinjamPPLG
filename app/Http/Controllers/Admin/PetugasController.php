<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        // Hanya ambil user dengan role admin atau petugas
        $petugas = User::whereIn('role', ['admin', 'petugas'] )->get();
        return view('admin.petugas.index', compact('petugas'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required',
        // Update validasi agar sesuai dengan input baru di form
        'hari' => 'required|array',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required'
    ]);

    // Gabungkan array hari: ["Senin", "Rabu"] jadi "Senin, Rabu"
    $daftarHari = implode(', ', $request->hari);

    // GABUNGKAN DATA: Menjadi format "Senin (08:00 - 14:00)"
    $jadwalGabungan = $daftarHari . ' (' . $request->jam_mulai . ' - ' . $request->jam_selesai . ')';

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'jadwal_kerja' => $jadwalGabungan, // Simpan hasil gabungan ke kolom tunggal
    ]);

    return redirect()->back()->with('success', 'Petugas berhasil ditambahkan!');
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
            'hari' => 'required|array', // Menerima checkbox hari
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $petugas = User::findOrFail($id);
        
        // Gabungkan hari menjadi string (misal: "Senin, Selasa")
        $hariString = implode(', ', $request->hari);
        // Format jadwal kerja: "Senin, Selasa (08:00 - 15:00)"
        $jadwalLengkap = $hariString . " (" . $request->jam_mulai . " - " . $request->jam_selesai . ")";

        $petugas->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'jadwal_kerja' => $jadwalLengkap, // Simpan ke kolom jadwal_kerja yang ada di DB
        ]);

        return redirect()->back()->with('success', 'Data petugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Petugas dihapus!');
    }
}
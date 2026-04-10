<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class Barang extends Model
{

public function kategori()
{
    return $this->belongsTo(\App\Models\Kategori::class, 'kategori_id', 'id');
}

   protected $fillable = [
    'foto', 
    'kode_barang', 
    'nama_barang', 
    'kategori_id', 
    'lokasi', 
    'kondisi', 
    'deskripsi', 
    'status' // Status ini otomatis (Tersedia/Dipinjam/Rusak)
];
}

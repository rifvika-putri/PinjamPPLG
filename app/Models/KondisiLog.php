<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'kondisi_saat_itu',
        'tanggal',
        'catatan'
    ];

    // Opsional: Hubungkan ke model Barang agar rekapnya bisa panggil nama barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
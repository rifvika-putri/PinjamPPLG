<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peminjaman extends Model
{
    protected $table = 'peminjamans'; 

    protected $guarded = [];

    protected $casts = [
    'tanggal_pinjam' => 'datetime',
    'tanggal_kembali_rencana' => 'datetime',
    'tanggal_kembali_realisasi' => 'datetime',
    'total_denda' => 'integer', // Pastikan kolom ini ada di migration peminjamans kamu
];

    // Relasi ke User (Peminjam)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Barang
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

}
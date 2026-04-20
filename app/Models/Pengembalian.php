<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    // Supaya Laravel nggak nyari tabel 'pengembalians' (pake s) kalau di database kamu namanya beda
    protected $table = 'pengembalians';

    protected $guarded = [];

    // Relasi balik ke Peminjaman
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
}
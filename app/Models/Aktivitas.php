<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    use HasFactory;

    protected $table = 'aktivitas';
    protected $fillable = ['pesan', 'icon', 'warna', 'user_name'];

    // FUNGSI INI YANG KURANG DI MODEL KAMU:
    public static function catat($pesan, $icon = 'info', $warna = 'blue')
    {
        return self::create([
            'pesan' => $pesan,
            'icon' => $icon,
            'warna' => $warna,
            'user_name' => auth()->check() ? auth()->user()->name : 'System',
        ]);
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->enum('status', ['pending', 'dipinjam', 'kembalikan pending', 'dikembalikan', 'selesai'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->enum('status', ['pending', 'dipinjam', 'kembalikan pending', 'selesai'])->default('pending')->change();
        });
    }
};


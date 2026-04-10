<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained()->onDelete('cascade');
            $table->dateTime('tanggal_pinjam');
            $table->dateTime('tanggal_kembali_rencana');
            $table->dateTime('tanggal_kembali_realisasi')->nullable();
            $table->text('keperluan');
            $table->enum('status', ['pending', 'dipinjam', 'kembalikan pending', 'selesai'])->default('pending');
            $table->string('kondisi_pinjam')->default('Baik');
            $table->string('kondisi_kembali')->nullable();
            $table->string('foto_pinjam'); 
            $table->string('foto_kembali')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
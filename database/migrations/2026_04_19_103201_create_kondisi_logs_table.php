<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kondisi_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('barang_id')->constrained('barangs');
    $table->string('kondisi_saat_itu'); // Baik, Rusak, atau Perbaikan
    $table->text('catatan')->nullable(); // Alasan rusak / detail perbaikan
    $table->date('tanggal'); // Tanggal kejadian (PENTING untuk rekap)
    $table->timestamps();
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_logs');
    }
};

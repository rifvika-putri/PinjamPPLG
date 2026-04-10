<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('barangs', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang')->unique();
        $table->string('nama_barang');
        $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
        $table->string('lokasi');   // Lab PPLG, Gudang, dll
        $table->enum('kondisi', ['Baik', 'Rusak', 'Perbaikan']);
        $table->text('deskripsi')->nullable();
        $table->enum('status', ['Tersedia', 'Dipinjam'])->default('Tersedia');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};

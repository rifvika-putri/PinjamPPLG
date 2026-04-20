<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('aktivitas', function (Blueprint $table) {
        $table->id();
        $table->string('pesan');      // Contoh: "Menambahkan barang baru: Laptop ASUS"
        $table->string('icon');       // Contoh: "package", "user", "shopping-cart"
        $table->string('warna');      // Contoh: "blue", "emerald", "amber"
        $table->string('user_name');  // Siapa yang melakukan (Admin/Petugas)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas');
    }
};

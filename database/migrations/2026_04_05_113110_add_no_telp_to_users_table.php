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
    Schema::table('users', function (Blueprint $table) {
        // Tambahkan panjang 15 karakter agar hemat memori & lebih rapi
        // Tambahkan komentar agar orang lain tahu ini untuk format WA
        $table->string('no_telp', 15)->nullable()->after('email')->comment('Format: 628xxx');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('no_telp');
    });
}
};

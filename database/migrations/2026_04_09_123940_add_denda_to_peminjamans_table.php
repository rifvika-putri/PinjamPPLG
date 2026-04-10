<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->integer('denda_telat')->default(0)->after('foto_kembali');
        $table->integer('denda_kerusakan')->default(0)->after('denda_telat');
        $table->integer('total_denda')->default(0)->after('denda_kerusakan');
        $table->text('catatan_kerusakan')->nullable()->after('total_denda');
        $table->string('status_pembayaran')->default('Lunas')->after('catatan_kerusakan');
    });
}

public function down(): void
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->dropColumn(['denda_telat', 'denda_kerusakan', 'total_denda', 'catatan_kerusakan', 'status_pembayaran']);
    });
}
};

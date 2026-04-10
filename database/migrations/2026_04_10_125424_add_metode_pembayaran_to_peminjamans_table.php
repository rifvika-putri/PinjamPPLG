<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        // Kita tambah kolom metode_pembayaran setelah status_pembayaran
        $table->string('metode_pembayaran')->nullable()->after('status_pembayaran');
    });
}

public function down()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->dropColumn('metode_pembayaran');
    });
}

};

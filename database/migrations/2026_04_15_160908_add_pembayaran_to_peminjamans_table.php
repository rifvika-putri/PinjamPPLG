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
    Schema::table('peminjamans', function (Blueprint $table) {
        if (!Schema::hasColumn('peminjamans', 'status_pembayaran')) {
            $table->string('status_pembayaran')->default('PENDING')->after('status');
        }
        if (!Schema::hasColumn('peminjamans', 'metode_pembayaran')) {
            $table->string('metode_pembayaran')->nullable()->after('status_pembayaran');
        }
        if (!Schema::hasColumn('peminjamans', 'bukti_pembayaran')) {
            $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
        }
    });
}

public function down(): void
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->dropColumn(['status_pembayaran', 'metode_pembayaran', 'bukti_pembayaran']);
    });
}
};

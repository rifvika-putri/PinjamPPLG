<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        // Kita ubah kolom status agar mendukung pilihan baru
        $table->enum('status', [
            'pending', 
            'tidak_disetujui', 
            'dipinjam', 
            'kembalikan_pending', 
            'pending_pembayaran', 
            'dikembalikan', 
            'selesai'
        ])->default('pending')->change();
        
        // Tambahkan kolom pendukung alur baru jika belum ada
        if (!Schema::hasColumn('peminjamans', 'denda')) {
            $table->integer('denda')->default(0)->after('status');
            $table->text('catatan_kerusakan')->nullable()->after('denda');
            $table->string('bukti_bayar')->nullable()->after('catatan_kerusakan');
            $table->text('alasan_penolakan')->nullable()->after('bukti_bayar');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            //
        });
    }
};

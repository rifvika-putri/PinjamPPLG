<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->enum('level_kerusakan', ['ringan', 'sedang', 'berat'])->nullable()->after('kondisi');
            $table->text('catatan_kerusakan')->nullable()->after('level_kerusakan');
        });
    }

    public function down()
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['level_kerusakan', 'catatan_kerusakan']);
        });
    }
};


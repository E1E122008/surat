<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Riwayat aktivitas tidak ikut terhapus saat akun pengguna dihapus.
 * - user_id dikosongkan (bukan cascade) ketika akunnya dihapus
 * - nama_pengguna menyimpan salinan nama pelaku agar tetap bisa ditampilkan sebagai "Akun dihapus"
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('nama_pengguna')->nullable()->after('user_id');
        });

        // Isi nama untuk riwayat yang sudah ada
        DB::table('activity_logs')
            ->join('users', 'users.id', '=', 'activity_logs.user_id')
            ->update(['activity_logs.nama_pengguna' => DB::raw('users.name')]);

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->dropColumn('nama_pengguna');
        });
    }
};

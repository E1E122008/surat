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
        Schema::table('spt_dalam_daerah', function (Blueprint $table) {
            $table->json('lampiran')->nullable()->change();
        });
        
        Schema::table('spt_luar_daerah', function (Blueprint $table) {
            $table->json('lampiran')->nullable()->change();
        });
        
        Schema::table('sppd_dalam_daerah', function (Blueprint $table) {
            $table->json('lampiran')->nullable()->change();
        });
        
        Schema::table('sppd_luar_daerah', function (Blueprint $table) {
            $table->json('lampiran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spt_dalam_daerah', function (Blueprint $table) {
            $table->text('lampiran')->nullable()->change();
        });
        
        Schema::table('spt_luar_daerah', function (Blueprint $table) {
            $table->text('lampiran')->nullable()->change();
        });
        
        Schema::table('sppd_dalam_daerah', function (Blueprint $table) {
            $table->text('lampiran')->nullable()->change();
        });
        
        Schema::table('sppd_luar_daerah', function (Blueprint $table) {
            $table->text('lampiran')->nullable()->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom disposisi menyimpan gabungan status persetujuan, tujuan, diteruskan ke,
 * tanggal, dan catatan. Batas varchar(255) membuat disposisi dengan catatan
 * panjang gagal tersimpan ("Data too long"), jadi diperlebar ke TEXT.
 */
return new class extends Migration
{
    private array $tables = ['surat_masuk', 'sks', 'perda', 'pergub'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->text('disposisi')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('disposisi')->nullable()->change();
            });
        }
    }
};

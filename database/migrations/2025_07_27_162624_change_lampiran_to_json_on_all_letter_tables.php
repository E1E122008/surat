<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->json('lampiran')->nullable()->change();
        });
        Schema::table('sks', function (Blueprint $table) {
            $table->json('lampiran')->nullable()->change();
        });
        Schema::table('perda', function (Blueprint $table) {
            $table->json('lampiran')->nullable()->change();
        });
        Schema::table('pergub', function (Blueprint $table) {
            $table->json('lampiran')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->text('lampiran')->nullable()->change();
        });
        Schema::table('sk', function (Blueprint $table) {
            $table->text('lampiran')->nullable()->change();
        });
        Schema::table('perda', function (Blueprint $table) {
            $table->text('lampiran')->nullable()->change();
        });
        Schema::table('pergub', function (Blueprint $table) {
            $table->text('lampiran')->nullable()->change();
        });
    }
};

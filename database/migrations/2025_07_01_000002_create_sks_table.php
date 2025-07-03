<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sks', function (Blueprint $table) {
            $table->id();
            $table->integer('no_agenda');
            $table->string('no_surat')->unique();
            $table->string('pengirim');
            $table->date('tanggal_surat')->nullable();
            $table->date('tanggal_terima');
            $table->string('perihal');
            $table->string('disposisi')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->text('catatan')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sks');
    }
}; 
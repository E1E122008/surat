<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spt_luar_daerah', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->date('tanggal');
            $table->string('perihal');
            $table->text('nama_petugas');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spt_luar_daerah');
    }
}; 
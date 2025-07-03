<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('approval_requests')) {
            Schema::create('approval_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('letter_type');
                $table->string('sender');
                $table->date('tanggal_surat')->nullable();
                $table->string('perihal')->nullable();
                $table->string('lampiran')->nullable();
                $table->string('no_surat')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('fisik_diterima')->default(false);
                $table->enum('fisik_status', ['belum', 'sudah'])->default('belum');
                $table->timestamp('fisik_diterima_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('approval_requests');
    }
}; 
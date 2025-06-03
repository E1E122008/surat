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
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('no_agenda')->nullable()->after('id');
            $table->string('catatan')->nullable()->after('lampiran');
            $table->string('disposisi')->nullable()->after('catatan');
            $table->string('sub_disposisi')->nullable()->after('disposisi');
            $table->date('tanggal_disposisi')->nullable()->after('sub_disposisi');
            $table->string('status')->default('pending_review')->after('tanggal_disposisi');
            $table->unsignedBigInteger('submitted_by')->nullable()->after('status');
            $table->string('admin_notes')->nullable()->after('submitted_by');
            
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropColumn([
                'no_agenda',
                'catatan',
                'disposisi',
                'sub_disposisi',
                'tanggal_disposisi',
                'status',
                'submitted_by',
                'admin_notes'
            ]);
        });
    }
};

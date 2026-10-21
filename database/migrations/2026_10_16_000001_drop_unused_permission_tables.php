<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel sisa paket spatie/laravel-permission yang tidak pernah terpasang.
 * Role pengguna disimpan di kolom users.role, sehingga tabel ini selalu kosong.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach (['role_has_permissions', 'model_has_roles', 'model_has_permissions', 'roles', 'permissions'] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Tidak dibuat ulang: fitur role berbasis Spatie sudah ditiadakan.
    }
};

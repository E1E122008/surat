<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('approval_requests', function (Blueprint $table) {
            $table->boolean('archived')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('approval_requests', function (Blueprint $table) {
            $table->dropColumn('archived');
        });
    }
}; 
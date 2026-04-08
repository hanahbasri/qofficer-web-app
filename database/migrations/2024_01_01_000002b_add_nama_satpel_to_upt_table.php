<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('upt', function (Blueprint $table) {
            $table->string('nama_satpel')->nullable()->after('nama');
        });
    }

    public function down(): void
    {
        Schema::table('upt', function (Blueprint $table) {
            $table->dropColumn('nama_satpel');
        });
    }
};

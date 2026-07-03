<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('upt', function (Blueprint $table) {
            $table->string('alias', 60)->nullable()->after('nama')
                ->comment('Nama singkat UPT untuk tampilan dashboard pimpinan, contoh: BBKHIT DKI JKT');
        });
    }

    public function down(): void
    {
        Schema::table('upt', function (Blueprint $table) {
            $table->dropColumn('alias');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum dengan raw SQL (tidak butuh doctrine/dbal)
        \DB::statement("ALTER TABLE surat_tugas_petugas MODIFY COLUMN status_penerimaan ENUM('tertunda','diterima','berangkat') NOT NULL DEFAULT 'tertunda'");

        Schema::table('surat_tugas_petugas', function (Blueprint $table) {
            $table->timestamp('berangkat_at')->nullable()->after('diterima_at');
        });
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE surat_tugas_petugas MODIFY COLUMN status_penerimaan ENUM('tertunda','diterima') NOT NULL DEFAULT 'tertunda'");

        Schema::table('surat_tugas_petugas', function (Blueprint $table) {
            $table->dropColumn('berangkat_at');
        });
    }
};

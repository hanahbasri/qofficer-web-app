<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Simpan lokasi pada hasil pemeriksaan supaya anggota tim lain bisa
     * melihat lokasi mana yang sudah diperiksa rekannya (kolaborasi real-time)
     * dan guard "semua lokasi harus diperiksa" bisa menghitung isian rekan.
     */
    public function up(): void
    {
        Schema::table('hasil_pemeriksaan', function (Blueprint $table) {
            $table->string('id_lokasi')->nullable()->after('id_petugas');
            $table->string('nama_lokasi')->nullable()->after('id_lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_pemeriksaan', function (Blueprint $table) {
            $table->dropColumn(['id_lokasi', 'nama_lokasi']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_pemeriksaan', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // UUID v4 dibuat dari mobile
            $table->string('id_surat_tugas', 50);
            $table->unsignedBigInteger('id_petugas');
            $table->string('lat', 20)->nullable();
            $table->string('long', 20)->nullable();
            $table->text('target')->nullable();
            $table->text('metode')->nullable();
            $table->text('temuan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('komoditas')->nullable();
            $table->enum('status_review', ['belum_direview', 'sudah_direview'])->default('belum_direview');
            $table->datetime('tgl_periksa');
            $table->timestamps();

            $table->foreign('id_surat_tugas')->references('id')->on('surat_tugas')->cascadeOnDelete();
            $table->foreign('id_petugas')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['status_review', 'id_surat_tugas']);
            $table->index(['tgl_periksa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_pemeriksaan');
    }
};

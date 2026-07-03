<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi_periksa', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // UUID v4
            $table->string('id_pemeriksaan', 50);
            $table->string('foto_path')->nullable(); // path file di server storage
            $table->longText('foto_display')->nullable(); // base64 resolusi tinggi
            $table->longText('foto_server')->nullable(); // base64 terkompresi (≤100KB)
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id')->on('hasil_pemeriksaan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_periksa');
    }
};

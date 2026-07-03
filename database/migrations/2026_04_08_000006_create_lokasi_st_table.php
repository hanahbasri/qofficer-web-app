<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi_st', function (Blueprint $table) {
            $table->id();
            $table->string('surat_tugas_id', 50);
            $table->string('nama_lokasi');
            $table->string('lat', 20)->nullable();
            $table->string('long', 20)->nullable();
            $table->text('detail_lokasi')->nullable();
            $table->timestamps();

            $table->foreign('surat_tugas_id')->references('id')->on('surat_tugas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi_st');
    }
};

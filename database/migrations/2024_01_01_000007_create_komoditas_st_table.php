<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komoditas_st', function (Blueprint $table) {
            $table->id();
            $table->string('surat_tugas_id', 50);
            $table->string('nama_komoditas');
            $table->string('nama_latin')->nullable();
            $table->decimal('volume', 15, 3)->nullable();
            $table->string('satuan', 30)->nullable();
            $table->char('jenis_karantina', 1); // H | T | I
            $table->timestamps();

            $table->foreign('surat_tugas_id')->references('id')->on('surat_tugas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komoditas_st');
    }
};

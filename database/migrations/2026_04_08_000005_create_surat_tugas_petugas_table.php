<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_tugas_petugas', function (Blueprint $table) {
            $table->id();
            $table->string('surat_tugas_id', 50);
            $table->unsignedBigInteger('petugas_id');
            $table->enum('status_penerimaan', ['tertunda', 'diterima'])->default('tertunda');
            $table->timestamp('diterima_at')->nullable();
            $table->timestamps();

            $table->foreign('surat_tugas_id')->references('id')->on('surat_tugas')->cascadeOnDelete();
            $table->foreign('petugas_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['surat_tugas_id', 'petugas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_petugas');
    }
};

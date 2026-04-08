<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // UUID dari server
            $table->string('ptk_id', 50)->nullable(); // ID PTK di BEST-TRUST
            $table->string('no_st', 100);
            $table->date('tanggal');
            $table->string('perihal');
            $table->text('dasar_hukum')->nullable();
            $table->string('nama_penandatangan')->nullable();
            $table->string('nip_penandatangan', 30)->nullable();
            $table->enum('status', ['tertunda', 'aktif', 'dikirim', 'selesai'])->default('tertunda');
            $table->char('jenis_karantina', 1); // H | T | I
            $table->unsignedBigInteger('koordinator_id')->nullable();
            $table->string('upt_id', 20)->nullable();
            $table->string('link', 500)->nullable(); // URL PDF ST
            $table->timestamps();

            $table->foreign('koordinator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('upt_id')->references('kode')->on('upt')->nullOnDelete();
            $table->index(['status', 'upt_id']);
            $table->index(['tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};

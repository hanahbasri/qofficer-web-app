<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi_karantina', function (Blueprint $table) {
            $table->id();
            $table->string('id_hasil_pemeriksaan', 50);
            $table->unsignedBigInteger('koordinator_id');
            $table->enum('tindakan', ['pelepasan', 'penolakan', 'perlakuan', 'pemusnahan']);
            $table->text('catatan'); // wajib diisi
            $table->enum('best_trust_status', ['pending', 'success', 'failed'])->default('success');
            $table->text('best_trust_response')->nullable();
            $table->timestamps();

            $table->foreign('id_hasil_pemeriksaan')->references('id')->on('hasil_pemeriksaan')->cascadeOnDelete();
            $table->foreign('koordinator_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique('id_hasil_pemeriksaan'); // satu pemeriksaan satu rekomendasi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_karantina');
    }
};

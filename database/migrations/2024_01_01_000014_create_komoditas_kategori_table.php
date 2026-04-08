<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komoditas_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Hewan, Ikan, Tumbuhan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komoditas_kategori');
    }
};

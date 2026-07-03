<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_temuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->char('jenis_karantina', 1); // H | T | I
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('jenis_karantina');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_temuan');
    }
};

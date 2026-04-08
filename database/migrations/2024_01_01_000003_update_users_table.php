<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop default columns yang tidak dipakai
            $table->dropColumn(['name', 'email_verified_at']);

            // Tambah kolom sesuai PDD
            $table->string('nip', 30)->unique()->after('id');
            $table->string('nama')->after('nip');
            $table->string('upt_id', 20)->nullable()->after('email');
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete()->after('upt_id');
            $table->boolean('is_active')->default(true)->after('role_id');
            $table->string('foto_profil')->nullable()->after('is_active');
            $table->string('golongan', 10)->nullable()->after('foto_profil');
            $table->string('pangkat')->nullable()->after('golongan');
            $table->string('fcm_token')->nullable()->after('pangkat');

            $table->foreign('upt_id')->references('kode')->on('upt')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['upt_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn(['nip', 'nama', 'upt_id', 'role_id', 'is_active', 'foto_profil', 'golongan', 'pangkat', 'fcm_token']);
            $table->string('name')->after('id');
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('must_change_password')->default(false)->after('fcm_token');
            $table->timestamp('password_changed_at')->nullable()->after('must_change_password');
            $table->timestamp('password_expires_at')->nullable()->after('password_changed_at');
        });

        $changedAt = now();

        DB::table('users')->update([
            'must_change_password' => false,
            'password_changed_at' => $changedAt,
            'password_expires_at' => $changedAt->copy()->addDays(30),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'must_change_password',
                'password_changed_at',
                'password_expires_at',
            ]);
        });
    }
};

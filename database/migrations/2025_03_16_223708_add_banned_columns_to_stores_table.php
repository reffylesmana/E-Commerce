<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->boolean('is_banned')->nullable();
            $table->timestamp('banned_until')->nullable();
            $table->integer('violation_count')->nullable();
            $table->text('last_violation_reason')->nullable();
            $table->timestamp('last_violation_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'is_banned',
                'banned_until',
                'violation_count',
                'last_violation_reason',
                'last_violation_at'
            ]);
        });
    }
};
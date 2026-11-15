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
        Schema::create('ip_bans', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->string('reason');
            $table->unsignedTinyInteger('strike_count')->default(1);
            $table->timestamp('banned_until')->nullable();
            $table->boolean('is_permanent')->default(false);
            $table->text('user_agent')->nullable();
            $table->string('incident_hash', 32)->nullable();
            $table->timestamp('last_violation_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_bans');
    }
};

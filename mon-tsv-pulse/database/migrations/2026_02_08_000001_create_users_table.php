<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->decimal('vma', 5, 2)->nullable();
            $table->decimal('endurance_pace', 5, 2)->nullable();
            $table->decimal('threshold_pace', 5, 2)->nullable();
            $table->integer('weight')->nullable();
            $table->integer('resting_heart_rate')->nullable();
            $table->integer('max_heart_rate')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar_path')->nullable();
            $table->enum('experience_level', ['débutant', 'intermédiaire', 'confirmé', 'expert'])->default('intermédiaire');

            $table->string('strava_id')->nullable()->unique();
            $table->text('strava_access_token')->nullable();
            $table->text('strava_refresh_token')->nullable();
            $table->timestamp('strava_token_expires_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

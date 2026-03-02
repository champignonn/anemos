<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_races', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('race_date');
            $table->string('type'); // trail, vertical, ultra, backyard
            $table->decimal('distance', 8, 2);
            $table->integer('elevation_gain');
            $table->string('location');
            $table->string('city');
            $table->string('country')->default('France');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('organizer')->nullable();
            $table->string('website_url')->nullable();
            $table->string('registration_url')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('max_participants')->nullable();
            $table->string('difficulty_level'); // débutant, intermédiaire, expert
            $table->json('features')->nullable(); // ravitaillements, balisage, etc.
            $table->string('image_url')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('user_public_races', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('public_race_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['intéressé', 'inscrit', 'terminé'])->default('intéressé');
            $table->timestamps();

            $table->unique(['user_id', 'public_race_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_public_races');
        Schema::dropIfExists('public_races');
    }
};

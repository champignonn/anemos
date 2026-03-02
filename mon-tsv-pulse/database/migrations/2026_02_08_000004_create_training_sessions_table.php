<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();
            $table->enum('type', ['VMA', 'Seuil', 'Tempo', 'Sortie Longue', 'Endurance Fondamentale', 'Fartlek', 'Côtes', 'Trail Technique', 'Récupération', 'Repos']);
            $table->integer('duration_minutes')->nullable();
            $table->decimal('planned_distance', 8, 2)->nullable();
            $table->integer('planned_elevation')->nullable();
            $table->enum('intensity_zone', ['Z1', 'Z2', 'Z3', 'Z4', 'Z5'])->nullable();
            $table->decimal('target_pace_min', 5, 2)->nullable();
            $table->decimal('target_pace_max', 5, 2)->nullable();
            $table->integer('target_heart_rate')->nullable();
            $table->json('workout_structure')->nullable();
            $table->enum('status', ['prévu', 'en_cours', 'réalisé', 'manqué', 'reporté'])->default('prévu');
            $table->dateTime('completed_at')->nullable();
            $table->integer('actual_duration_minutes')->nullable();
            $table->decimal('actual_distance', 8, 2)->nullable();
            $table->integer('actual_elevation')->nullable();
            $table->decimal('actual_pace', 5, 2)->nullable();
            $table->integer('average_heart_rate')->nullable();
            $table->integer('max_heart_rate')->nullable();
            $table->integer('rpe')->nullable();
            $table->integer('fatigue_level')->nullable();
            $table->text('notes')->nullable();
            $table->json('weather_conditions')->nullable();
            $table->string('strava_activity_id')->nullable()->unique();
            $table->boolean('imported_from_strava')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};

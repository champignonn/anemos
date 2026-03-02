<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('race_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_weeks');
            $table->integer('base_phase_weeks')->nullable();
            $table->integer('build_phase_weeks')->nullable();
            $table->integer('peak_phase_weeks')->nullable();
            $table->integer('taper_weeks')->default(2);
            $table->integer('weekly_distance_min')->nullable();
            $table->integer('weekly_distance_max')->nullable();
            $table->integer('weekly_elevation_min')->nullable();
            $table->integer('weekly_elevation_max')->nullable();
            $table->integer('sessions_per_week')->default(4);
            $table->integer('long_run_frequency')->default(7);
            $table->decimal('intensity_ratio', 5, 2)->default(0.20);
            $table->enum('status', ['brouillon', 'actif', 'terminé', 'abandonné'])->default('brouillon');
            $table->boolean('is_generated')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_plans');
    }
};

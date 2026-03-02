<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('race_date');
            $table->enum('type', ['boucle', 'classique'])->default('classique');
            $table->decimal('distance', 8, 2);
            $table->integer('elevation_gain');
            $table->integer('elevation_loss')->nullable();
            $table->string('location')->nullable();
            $table->time('target_time')->nullable();
            $table->enum('priority', ['A', 'B', 'C'])->default('B');
            $table->enum('status', ['a_venir', 'en_preparation', 'terminee', 'abandonnee'])->default('a_venir');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('races');
    }
};

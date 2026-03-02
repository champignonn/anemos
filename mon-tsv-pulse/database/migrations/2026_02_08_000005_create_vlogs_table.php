<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vlogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_thumbnail')->nullable();
            $table->json('photo_paths')->nullable();
            $table->string('location')->nullable();
            $table->json('tags')->nullable();
            $table->enum('mood', ['excellent', 'bien', 'moyen', 'difficile', 'terrible'])->nullable();
            $table->integer('likes_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->enum('visibility', ['public', 'followers', 'private'])->default('private');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vlogs');
    }
};

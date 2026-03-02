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
        // 1. Table Pivot pour les Likes (Like unique par personne)
        Schema::create('vlog_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vlog_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Empêche techniquement un utilisateur de liker 2 fois le même vlog
            $table->unique(['user_id', 'vlog_id']);
        });

        // 2. Table Pivot pour les Vues (Vue unique par profil)
        Schema::create('vlog_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vlog_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // On ne compte qu'une seule vue par utilisateur par vlog
            $table->unique(['user_id', 'vlog_id']);
        });

        // 3. Table pour les Commentaires
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vlog_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('vlog_views');
        Schema::dropIfExists('vlog_likes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vlog_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vlog_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Un utilisateur ne peut liker qu'une seule fois un vlog
            $table->unique(['vlog_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vlog_likes');
    }
};

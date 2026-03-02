<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Changer le type ENUM en VARCHAR pour plus de flexibilité
        DB::statement("ALTER TABLE races MODIFY COLUMN type VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        // Retour à l'ENUM original
        DB::statement("ALTER TABLE races MODIFY COLUMN type ENUM('trail', 'route', 'boucle') NOT NULL");
    }
};

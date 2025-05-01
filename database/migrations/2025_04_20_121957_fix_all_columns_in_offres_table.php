<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter des valeurs par défaut pour toutes les colonnes problématiques
        DB::statement('ALTER TABLE offres MODIFY title VARCHAR(255) DEFAULT ""');
        DB::statement('ALTER TABLE offres MODIFY salaire_proposer VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE offres MODIFY type_offre VARCHAR(255) DEFAULT NULL');
        
        // Si d'autres colonnes posent problème, ajoutez-les ici de la même façon
    }

    public function down(): void
    {
        // Code pour revenir en arrière si nécessaire
    }
};
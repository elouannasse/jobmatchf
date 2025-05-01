<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('offres', function (Blueprint $table) {
            // Si 'title' existe mais pas 'titre', renommez-le
            if (Schema::hasColumn('offres', 'title') && !Schema::hasColumn('offres', 'titre')) {
                $table->renameColumn('title', 'titre');
            }
            
            // Ou ajoutez 'title' avec une valeur par défaut s'il manque
            if (!Schema::hasColumn('offres', 'title') && Schema::hasColumn('offres', 'titre')) {
                $table->string('title')->default('');
            }
            
            // Si aucune des deux colonnes n'existe, ajoutez 'titre' et 'title'
            if (!Schema::hasColumn('offres', 'titre') && !Schema::hasColumn('offres', 'title')) {
                $table->string('titre');
                $table->string('title')->default('');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            // Si nécessaire, annulez les changements
            if (Schema::hasColumn('offres', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};
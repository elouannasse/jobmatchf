<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if date_debut column exists, if not, add it with a default value
        if (!Schema::hasColumn('offres', 'date_debut')) {
            Schema::table('offres', function (Blueprint $table) {
                $table->date('date_debut')->nullable();
            });
        }
        
        // Add a default value to date_debut
        DB::statement('ALTER TABLE offres MODIFY date_debut DATE DEFAULT CURRENT_TIMESTAMP');
        
        // Update existing records that have NULL values
        DB::statement('UPDATE offres SET date_debut = CURRENT_DATE WHERE date_debut IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't do anything to avoid data loss
    }
};
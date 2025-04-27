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
        Schema::table('offres', function (Blueprint $table) {
            if (!Schema::hasColumn('offres', 'titre')) {
                $table->string('titre');
            }
            
            if (!Schema::hasColumn('offres', 'description')) {
                $table->text('description');
            }
            
            if (!Schema::hasColumn('offres', 'lieu')) {
                $table->string('lieu');
            }
            
            if (!Schema::hasColumn('offres', 'type_contrat')) {
                $table->string('type_contrat');
            }
            
            if (!Schema::hasColumn('offres', 'salaire')) {
                $table->decimal('salaire', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('offres', 'date_expiration')) {
                $table->date('date_expiration')->nullable();
            }
            
            if (!Schema::hasColumn('offres', 'etat')) {
                $table->boolean('etat')->default(true);
            }
            
            if (!Schema::hasColumn('offres', 'user_id')) {
                $table->unsignedBigInteger('user_id');
                if (Schema::hasTable('users') && count(DB::select('SELECT * FROM offres')) == 0) {
                    $table->foreign('user_id')->references('id')->on('users');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
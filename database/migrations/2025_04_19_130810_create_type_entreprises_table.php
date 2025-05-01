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
        Schema::create('type_entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        DB::table('type_entreprises')->insert([
            ['nom' => 'Startup', 'description' => 'Jeune entreprise innovante'],
            ['nom' => 'PME', 'description' => 'Petite et moyenne entreprise'],
            ['nom' => 'Grande entreprise', 'description' => 'Grande entreprise'],
        ]);    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_entreprises');
    }
};

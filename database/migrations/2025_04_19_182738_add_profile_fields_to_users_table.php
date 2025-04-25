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
        Schema::table('users', function (Blueprint $table) {
            $table->string('tel')->nullable()->after('email');
            $table->text('adresse')->nullable()->after('tel');
            $table->string('nom_entreprise')->nullable()->after('adresse');
            $table->text('description_entreprise')->nullable()->after('nom_entreprise');
            $table->foreignId('type_entreprise_id')->nullable()->after('description_entreprise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tel', 'adresse', 'nom_entreprise', 'description_entreprise', 'type_entreprise_id']);
        });
    }
};
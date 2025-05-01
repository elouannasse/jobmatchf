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
    Schema::table('candidatures', function (Blueprint $table) {
       
        if (!Schema::hasColumn('candidatures', 'cv')) {
            $table->string('cv')->nullable();
        }
        
        
        if (!Schema::hasColumn('candidatures', 'lettre_motivation')) {
            $table->text('lettre_motivation')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            //
        });
    }
};

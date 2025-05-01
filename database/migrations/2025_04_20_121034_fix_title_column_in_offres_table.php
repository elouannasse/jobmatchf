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
        DB::statement('ALTER TABLE offres MODIFY title VARCHAR(255) DEFAULT ""');
    }
    
    public function down()
    {
        DB::statement('ALTER TABLE offres MODIFY title VARCHAR(255) NOT NULL');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCvPathToCandidaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->string('cv_path')->nullable()->after('offre_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn('cv_path');
        });
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCvToCvPathInCandidaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidatures', function (Blueprint $table) {
            // أولاً، تأكد من وجود cv_path
            if (!Schema::hasColumn('candidatures', 'cv_path')) {
                $table->string('cv_path')->nullable();
            }
            
            // نسخ البيانات من cv إلى cv_path إذا لزم الأمر
            DB::statement('UPDATE candidatures SET cv_path = cv WHERE cv_path IS NULL');
            
            // ثم حذف العمود cv
            $table->dropColumn('cv');
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
            // إعادة إنشاء العمود cv
            if (!Schema::hasColumn('candidatures', 'cv')) {
                $table->string('cv')->nullable();
            }
            
            // نسخ البيانات من cv_path إلى cv
            DB::statement('UPDATE candidatures SET cv = cv_path WHERE cv IS NULL');
        });
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // التحقق من أن العمود غير موجود قبل محاولة إضافته
        if (!Schema::hasColumn('offres', 'etat')) {
            Schema::table('offres', function (Blueprint $table) {
                $table->boolean('etat')->default(true)->after('description');
            });
        }
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn('etat');
        });
    }
};
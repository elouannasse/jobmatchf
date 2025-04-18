<?php 

use App\Models\User;
use App\Models\Offre;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->string('lettre_motivation');
            $table->string('cv');
            $table->string('statut')->default('en_cours'); 
            $table->foreignIdFor(Offre::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};

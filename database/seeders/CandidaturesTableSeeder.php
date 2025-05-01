<?php

namespace Database\Seeders;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Database\Seeder;

class CandidaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidats = User::whereHas('role', function ($query) {
            $query->where('name', 'Candidat');
        })->pluck('id')->toArray();

        $offres = Offre::where('etat', true)->pluck('id')->toArray();

        if (empty($candidats) || empty($offres)) {
            $this->command->info('لا يوجد مرشحون أو عروض في قاعدة البيانات. يرجى إنشاء حسابات مرشحين وعروض أولاً.');
            return;
        }

        $statuts = ['en_attente', 'acceptee', 'refusee'];

        for ($i = 0; $i < 30; $i++) {
            $candidat_id = $candidats[array_rand($candidats)];
            $offre_id = $offres[array_rand($offres)];
            
            $exists = Candidature::where('user_id', $candidat_id)
                ->where('offre_id', $offre_id)
                ->exists();
            
            if (!$exists) {
                Candidature::create([
                    'user_id' => $candidat_id,
                    'offre_id' => $offre_id,
                    'lettre_motivation' => 'أنا مهتم جدًا بهذه الفرصة ومتحمس للانضمام إلى فريقكم. لدي خبرة في هذا المجال وأعتقد أن مهاراتي تتناسب تمامًا مع متطلبات هذا المنصب. أتطلع إلى مناقشة كيف يمكنني المساهمة في نجاح شركتكم.',
                    'statut' => $statuts[array_rand($statuts)],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            } else {
                $i--;
            }
        }

        $this->command->info('candidatures seeded successfully!');
    }
}
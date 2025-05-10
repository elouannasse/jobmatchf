<?php

namespace App\Services\Admin;

use App\Models\Offre;
use App\Models\User;
use App\Notifications\OffreStatusNotification;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminOffreService
{
   
    public $offreRepository;

    
     
    public function __construct(OffreRepositoryInterface $offreRepository)
    {
        $this->offreRepository = $offreRepository;
    }

   
    public function getAllOffers(array $filters = [], int $perPage = 10)
    {
        return $this->offreRepository->getAdminOffresPaginated($filters, $perPage);
    }

    
    public function getPendingOffers(int $perPage = 10)
    {
        return $this->offreRepository->getPendingApprovalPaginated($perPage);
    }

   
    public function approveOffer(int $offreId)
    {
        $offre = $this->offreRepository->findById($offreId);
        
        $updateData = [
            'etat' => true,
            'approved' => true
        ];
        
        $result = $this->offreRepository->update($offreId, $updateData);
        
        if ($result && $offre->user) {
            $offre->user->notify(new OffreStatusNotification($offre, true));
        }
        
        return $result;
    }

   
    public function rejectOffer(int $offreId)
    {
        $offre = $this->offreRepository->findById($offreId);
        
        $updateData = [
            'etat' => false,
            'approved' => false
        ];
        
        $result = $this->offreRepository->update($offreId, $updateData);
        
        if ($result && $offre->user) {
            $offre->user->notify(new OffreStatusNotification($offre, false));
        }
        
        return $result;
    }

    
    public function toggleOfferStatus(int $offreId)
    {
        $offre = $this->offreRepository->findById($offreId);
        
        $newStatus = !$offre->etat;
        
        $updateData = [
            'etat' => $newStatus,
        ];
        
        if ($newStatus) {
            $updateData['approved'] = true;
        }
        
        return $this->offreRepository->update($offreId, $updateData);
    }

   
    public function getOfferStatistics()
    {
        $statsContrat = Offre::select('type_contrat', DB::raw('count(*) as total'))
            ->groupBy('type_contrat')
            ->orderBy('total', 'desc')
            ->get();

        $statsLieux = Offre::select('lieu', DB::raw('count(*) as total'))
            ->groupBy('lieu')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $statsMonthly = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $labels[] = $date->format('M Y');

            $count = Offre::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $statsMonthly[] = $count;
        }

        return [
            'statsContrat' => $statsContrat,
            'statsLieux' => $statsLieux,
            'statsMonthly' => $statsMonthly,
            'labels' => $labels
        ];
    }
}

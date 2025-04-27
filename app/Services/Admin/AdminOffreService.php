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
    /**
     * @var OffreRepositoryInterface
     */
    public $offreRepository;

    /**
     * AdminOffreService constructor.
     *
     * @param OffreRepositoryInterface $offreRepository
     */
    public function __construct(OffreRepositoryInterface $offreRepository)
    {
        $this->offreRepository = $offreRepository;
    }

    /**
     * Get all offers with pagination and filters for the admin panel
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllOffers(array $filters = [], int $perPage = 10)
    {
        return $this->offreRepository->getAdminOffresPaginated($filters, $perPage);
    }

    /**
     * Get pending offers with pagination
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPendingOffers(int $perPage = 10)
    {
        return $this->offreRepository->getPendingApprovalPaginated($perPage);
    }

    /**
     * Approve a job offer
     *
     * @param int $offreId
     * @return bool
     */
    public function approveOffer(int $offreId)
    {
        $offre = $this->offreRepository->findById($offreId);
        
        $updateData = [
            'etat' => true,
            'approved' => true
        ];
        
        $result = $this->offreRepository->update($offreId, $updateData);
        
        // Notify the recruiter
        if ($result && $offre->user) {
            $offre->user->notify(new OffreStatusNotification($offre, true));
        }
        
        return $result;
    }

    /**
     * Reject a job offer
     *
     * @param int $offreId
     * @return bool
     */
    public function rejectOffer(int $offreId)
    {
        $offre = $this->offreRepository->findById($offreId);
        
        $updateData = [
            'etat' => false,
            'approved' => false
        ];
        
        $result = $this->offreRepository->update($offreId, $updateData);
        
        // Notify the recruiter
        if ($result && $offre->user) {
            $offre->user->notify(new OffreStatusNotification($offre, false));
        }
        
        return $result;
    }

    /**
     * Toggle the status of a job offer
     *
     * @param int $offreId
     * @return bool
     */
    public function toggleOfferStatus(int $offreId)
    {
        $offre = $this->offreRepository->findById($offreId);
        
        $newStatus = !$offre->etat;
        
        $updateData = [
            'etat' => $newStatus,
        ];
        
        // If activating, also set approved to true
        if ($newStatus) {
            $updateData['approved'] = true;
        }
        
        return $this->offreRepository->update($offreId, $updateData);
    }

    /**
     * Get statistics for the job offers
     *
     * @return array
     */
    public function getOfferStatistics()
    {
        // 1. Statistics by contract type
        $statsContrat = Offre::select('type_contrat', DB::raw('count(*) as total'))
            ->groupBy('type_contrat')
            ->orderBy('total', 'desc')
            ->get();

        // 2. Statistics by location (top 5)
        $statsLieux = Offre::select('lieu', DB::raw('count(*) as total'))
            ->groupBy('lieu')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // 3. Monthly statistics (last 6 months)
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

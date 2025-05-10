<?php

namespace App\Services;

use App\Models\Candidature;
use App\Repositories\Interfaces\CandidatureRepositoryInterface;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatureService
{
    
    protected $candidatureRepository;
    
    
    protected $offreRepository;

   
    public function __construct(
        CandidatureRepositoryInterface $candidatureRepository,
        OffreRepositoryInterface $offreRepository
    ) {
        $this->candidatureRepository = $candidatureRepository;
        $this->offreRepository = $offreRepository;
    }

    
    public function getAllCandidatures(int $perPage = 10)
    {
        return $this->candidatureRepository->getAllCandidaturesPaginated($perPage);
    }

    
    public function getCandidaturesByOffer(int $offreId, int $perPage = 10)
    {
        return $this->candidatureRepository->getByOffrePaginated($offreId, $perPage);
    }

   
    public function getCandidaturesByUser(int $userId, int $perPage = 10)
    {
        return $this->candidatureRepository->getByUserPaginated($userId, $perPage);
    }

    
    public function getCandidatureDetails(int $candidatureId)
    {
        return $this->candidatureRepository->findById($candidatureId, ['*'], ['user', 'offre', 'offre.user']);
    }

   
    public function createCandidature(array $data, $cvFile = null)
    {
        $offre = $this->offreRepository->findById($data['offre_id']);
        if (!$offre || !$offre->etat || !$offre->approved) {
            return null;
        }

        if ($this->candidatureRepository->hasUserApplied(Auth::id(), $data['offre_id'])) {
            return null;
        }

        $cvPath = null;
        if ($cvFile) {
            $cvPath = $cvFile->store('cv', 'public');
        }

        $candidatureData = [
            'user_id' => Auth::id(),
            'offre_id' => $data['offre_id'],
            'lettre_motivation' => $data['lettre_motivation'] ?? null,
            'statut' => 'en_attente',
            'cv_path' => $cvPath,
        ];

        return $this->candidatureRepository->create($candidatureData);
    }

    
    public function updateStatus(int $candidatureId, string $status)
    {
        $validStatuses = ['en_attente', 'acceptee', 'refusee'];
        
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        return $this->candidatureRepository->update($candidatureId, [
            'statut' => $status
        ]);
    }


    public function deleteCandidature(int $candidatureId)
    {
        $candidature = $this->candidatureRepository->findById($candidatureId);
        
        if ($candidature && $candidature->cv_path) {
            Storage::disk('public')->delete($candidature->cv_path);
        }
        
        return $this->candidatureRepository->deleteById($candidatureId);
    }
}

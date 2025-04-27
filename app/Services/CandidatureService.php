<?php

namespace App\Services;

use App\Models\Candidature;
use App\Repositories\Interfaces\CandidatureRepositoryInterface;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatureService
{
    /**
     * @var CandidatureRepositoryInterface
     */
    protected $candidatureRepository;
    
    /**
     * @var OffreRepositoryInterface
     */
    protected $offreRepository;

    /**
     * CandidatureService constructor.
     *
     * @param CandidatureRepositoryInterface $candidatureRepository
     * @param OffreRepositoryInterface $offreRepository
     */
    public function __construct(
        CandidatureRepositoryInterface $candidatureRepository,
        OffreRepositoryInterface $offreRepository
    ) {
        $this->candidatureRepository = $candidatureRepository;
        $this->offreRepository = $offreRepository;
    }

    /**
     * Get all candidatures with pagination
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllCandidatures(int $perPage = 10)
    {
        return $this->candidatureRepository->getAllCandidaturesPaginated($perPage);
    }

    /**
     * Get candidatures by offer
     *
     * @param int $offreId
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCandidaturesByOffer(int $offreId, int $perPage = 10)
    {
        return $this->candidatureRepository->getByOffrePaginated($offreId, $perPage);
    }

    /**
     * Get candidatures by user
     *
     * @param int $userId
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCandidaturesByUser(int $userId, int $perPage = 10)
    {
        return $this->candidatureRepository->getByUserPaginated($userId, $perPage);
    }

    /**
     * Get candidature details
     *
     * @param int $candidatureId
     * @return Candidature
     */
    public function getCandidatureDetails(int $candidatureId)
    {
        return $this->candidatureRepository->findById($candidatureId, ['*'], ['user', 'offre', 'offre.user']);
    }

    /**
     * Create a new candidature
     *
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $cvFile
     * @return Candidature|null
     */
    public function createCandidature(array $data, $cvFile = null)
    {
        // Validate that the offer exists and is active
        $offre = $this->offreRepository->findById($data['offre_id']);
        if (!$offre || !$offre->etat || !$offre->approved) {
            return null;
        }

        // Check if user has already applied
        if ($this->candidatureRepository->hasUserApplied(Auth::id(), $data['offre_id'])) {
            return null;
        }

        // Handle CV upload
        $cvPath = null;
        if ($cvFile) {
            $cvPath = $cvFile->store('cv', 'public');
        }

        // Prepare candidature data
        $candidatureData = [
            'user_id' => Auth::id(),
            'offre_id' => $data['offre_id'],
            'lettre_motivation' => $data['lettre_motivation'] ?? null,
            'statut' => 'en_attente',
            'cv_path' => $cvPath,
        ];

        // Create candidature
        return $this->candidatureRepository->create($candidatureData);
    }

    /**
     * Update candidature status
     *
     * @param int $candidatureId
     * @param string $status
     * @return bool
     */
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

    /**
     * Delete a candidature
     *
     * @param int $candidatureId
     * @return bool
     */
    public function deleteCandidature(int $candidatureId)
    {
        $candidature = $this->candidatureRepository->findById($candidatureId);
        
        // Delete CV file if it exists
        if ($candidature && $candidature->cv_path) {
            Storage::disk('public')->delete($candidature->cv_path);
        }
        
        return $this->candidatureRepository->deleteById($candidatureId);
    }
}

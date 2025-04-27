<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface CandidatureRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get candidatures by job offer.
     * 
     * @param int $offreId
     * @return Collection
     */
    public function getByOffre(int $offreId): Collection;
    
    /**
     * Get candidatures by user.
     * 
     * @param int $userId
     * @return Collection
     */
    public function getByUser(int $userId): Collection;
    
    /**
     * Get candidatures by job offer with pagination.
     * 
     * @param int $offreId
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getByOffrePaginated(int $offreId, int $perPage = 10);
    
    /**
     * Get candidatures by user with pagination.
     * 
     * @param int $userId
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getByUserPaginated(int $userId, int $perPage = 10);
    
    /**
     * Get all candidatures with pagination.
     * 
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllCandidaturesPaginated(int $perPage = 10);
    
    /**
     * Check if user has already applied to a job offer.
     * 
     * @param int $userId
     * @param int $offreId
     * @return bool
     */
    public function hasUserApplied(int $userId, int $offreId): bool;
}

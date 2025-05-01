<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface OffreRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get all active job offers.
     * 
     * @return Collection
     */
    public function getAllActive(): Collection;
    
    /**
     * Get all active job offers with pagination.
     * 
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllActivePaginated(int $perPage = 12);
    
    /**
     * Get job offers by user.
     * 
     * @param int $userId
     * @return Collection
     */
    public function getByUser(int $userId): Collection;
    
    /**
     * Get job offers by user with pagination.
     * 
     * @param int $userId
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getByUserPaginated(int $userId, int $perPage = 10);
    
    /**
     * Get job offers pending approval.
     * 
     * @return Collection
     */
    public function getPendingApproval(): Collection;
    
    /**
     * Get job offers pending approval with pagination.
     * 
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPendingApprovalPaginated(int $perPage = 10);
    
    /**
     * Get all job offers with filters for admin panel.
     * 
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAdminOffresPaginated(array $filters = [], int $perPage = 10);
    
    /**
     * Update job offer approval status.
     * 
     * @param int $offreId
     * @param bool $approved
     * @return bool
     */
    public function updateApprovalStatus(int $offreId, bool $approved): bool;
}

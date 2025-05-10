<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface OffreRepositoryInterface extends EloquentRepositoryInterface
{
   public function getAllActive(): Collection;
    
   
    public function getAllActivePaginated(int $perPage = 12);
    
   
    public function getByUser(int $userId): Collection;
    
   
    public function getByUserPaginated(int $userId, int $perPage = 10);
    
   public function getPendingApproval(): Collection;
    
   
    public function getPendingApprovalPaginated(int $perPage = 10);
    
    
    public function getAdminOffresPaginated(array $filters = [], int $perPage = 10);
    
    
    public function updateApprovalStatus(int $offreId, bool $approved): bool;
}

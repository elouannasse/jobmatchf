<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface CandidatureRepositoryInterface extends EloquentRepositoryInterface
{
    
    public function getByOffre(int $offreId): Collection;
    
    
    public function getByUser(int $userId): Collection;
    
    
    public function getByOffrePaginated(int $offreId, int $perPage = 10);
    
    
    public function getByUserPaginated(int $userId, int $perPage = 10);
    
    
    public function getAllCandidaturesPaginated(int $perPage = 10);
    

    public function hasUserApplied(int $userId, int $offreId): bool;
}

<?php

namespace App\Repositories;

use App\Models\Candidature;
use App\Repositories\Interfaces\CandidatureRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CandidatureRepository extends BaseRepository implements CandidatureRepositoryInterface
{
    
    public function __construct(Candidature $model)
    {
        parent::__construct($model);
    }

   
    public function getByOffre(int $offreId): Collection
    {
        return $this->model->where('offre_id', $offreId)->get();
    }

    
    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }
    
    
    public function getByOffrePaginated(int $offreId, int $perPage = 10)
    {
        return $this->model->where('offre_id', $offreId)
            ->with(['user', 'offre'])
            ->latest()
            ->paginate($perPage);
    }
    
    
    public function getByUserPaginated(int $userId, int $perPage = 10)
    {
        return $this->model->where('user_id', $userId)
            ->with(['offre', 'offre.user'])
            ->latest()
            ->paginate($perPage);
    }
    
  
    public function getAllCandidaturesPaginated(int $perPage = 10)
    {
        return $this->model->with(['user', 'offre', 'offre.user'])
            ->latest()
            ->paginate($perPage);
    }

   
    public function hasUserApplied(int $userId, int $offreId): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('offre_id', $offreId)
            ->exists();
    }
}

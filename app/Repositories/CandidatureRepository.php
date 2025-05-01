<?php

namespace App\Repositories;

use App\Models\Candidature;
use App\Repositories\Interfaces\CandidatureRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CandidatureRepository extends BaseRepository implements CandidatureRepositoryInterface
{
    /**
     * CandidatureRepository constructor.
     * 
     * @param Candidature $model
     */
    public function __construct(Candidature $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function getByOffre(int $offreId): Collection
    {
        return $this->model->where('offre_id', $offreId)->get();
    }

    /**
     * @inheritdoc
     */
    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }
    
    /**
     * @inheritdoc
     */
    public function getByOffrePaginated(int $offreId, int $perPage = 10)
    {
        return $this->model->where('offre_id', $offreId)
            ->with(['user', 'offre'])
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * @inheritdoc
     */
    public function getByUserPaginated(int $userId, int $perPage = 10)
    {
        return $this->model->where('user_id', $userId)
            ->with(['offre', 'offre.user'])
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * @inheritdoc
     */
    public function getAllCandidaturesPaginated(int $perPage = 10)
    {
        return $this->model->with(['user', 'offre', 'offre.user'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * @inheritdoc
     */
    public function hasUserApplied(int $userId, int $offreId): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('offre_id', $offreId)
            ->exists();
    }
}

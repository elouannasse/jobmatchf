<?php

namespace App\Repositories;

use App\Models\Offre;
use App\Repositories\Interfaces\OffreRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OffreRepository extends BaseRepository implements OffreRepositoryInterface
{
    /**
     * OffreRepository constructor.
     * 
     * @param Offre $model
     */
    public function __construct(Offre $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function getAllActive(): Collection
    {
        return $this->model
            ->where('approved', true)
            ->where('etat', true)
            ->whereDate('date_fin', '>=', now())
            ->get();
    }
    
    /**
     * @inheritdoc
     */
    public function getAllActivePaginated(int $perPage = 12)
    {
        return $this->model
            ->where('approved', true)
            ->where('etat', true)
            ->whereDate('date_fin', '>=', now())
            ->with('user')
            ->latest()
            ->paginate($perPage);
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
    public function getByUserPaginated(int $userId, int $perPage = 10)
    {
        return $this->model->where('user_id', $userId)->latest()->paginate($perPage);
    }

    /**
     * @inheritdoc
     */
    public function getPendingApproval(): Collection
    {
        return $this->model->where('approved', false)->get();
    }
    
    /**
     * @inheritdoc
     */
    public function getPendingApprovalPaginated(int $perPage = 10)
    {
        return $this->model
            ->where('approved', false)
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * @inheritdoc
     */
    public function getAdminOffresPaginated(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->with(['user', 'candidatures']);
        
        // Apply filters
        if (isset($filters['etat'])) {
            $query->where('etat', $filters['etat']);
        }
        
        if (isset($filters['approved'])) {
            $query->where('approved', $filters['approved']);
        }
        
        if (isset($filters['pending']) && $filters['pending']) {
            $query->whereNull('approved');
        }
        
        return $query->latest()->paginate($perPage);
    }

    /**
     * @inheritdoc
     */
    public function updateApprovalStatus(int $offreId, bool $approved): bool
    {
        $offre = $this->findById($offreId);
        return $offre->update(['approved' => $approved]);
    }
}

<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     * 
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function getUsersByRole(int $roleId): Collection
    {
        return $this->model->where('role_id', $roleId)->get();
    }
    
    /**
     * @inheritdoc
     */
    public function getUsersByRolePaginated(int $roleId, int $perPage = 10)
    {
        return $this->model->where('role_id', $roleId)
            ->with('role')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * @inheritdoc
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
    
    /**
     * @inheritdoc
     */
    public function getAllUsersPaginated(int $perPage = 10)
    {
        return $this->model->with('role')
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * @inheritdoc
     */
    public function deleteUser(int $userId): bool
    {
        try {
            $user = $this->findById($userId);
            return $user->delete();
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error deleting user: ' . $e->getMessage());
            return false;
        }
    }
}

<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get users by role.
     * 
     * @param int $roleId
     * @return Collection
     */
    public function getUsersByRole(int $roleId): Collection;
    
    /**
     * Get users by role with pagination.
     * 
     * @param int $roleId
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUsersByRolePaginated(int $roleId, int $perPage = 10);
    
    /**
     * Find user by email.
     * 
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
    
    /**
     * Get all users with pagination.
     * 
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllUsersPaginated(int $perPage = 10);
    
    /**
     * Delete a user by ID.
     * 
     * @param int $userId
     * @return bool
     */
    public function deleteUser(int $userId): bool;
}

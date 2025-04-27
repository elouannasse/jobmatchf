<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users with pagination
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllUsers(int $perPage = 10)
    {
        return $this->userRepository->getAllUsersPaginated($perPage);
    }

    /**
     * Get users by role with pagination
     *
     * @param int $roleId
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUsersByRole(int $roleId, int $perPage = 10)
    {
        return $this->userRepository->getUsersByRolePaginated($roleId, $perPage);
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User|null
     */
    public function createUser(array $data)
    {
        // Prepare data for repository
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ];

        if (isset($data['prenom'])) {
            $userData['prenom'] = $data['prenom'];
        }

        return $this->userRepository->create($userData);
    }

    /**
     * Create a new candidate
     *
     * @param array $data
     * @return User|null
     */
    public function createCandidate(array $data)
    {
        // Get candidate role
        $candidatRole = Role::where('name', 'candidat')->first();
        
        if (!$candidatRole) {
            Log::error('Candidate role not found');
            return null;
        }

        // Prepare data for repository
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $candidatRole->id,
        ];

        if (isset($data['prenom'])) {
            $userData['prenom'] = $data['prenom'];
        }

        return $this->userRepository->create($userData);
    }

    /**
     * Update user
     *
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateUser(int $userId, array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role_id'],
        ];

        if (isset($data['prenom'])) {
            $userData['prenom'] = $data['prenom'];
        }

        $result = $this->userRepository->update($userId, $userData);

        if ($result && isset($data['password']) && !empty($data['password'])) {
            $this->userRepository->update($userId, [
                'password' => Hash::make($data['password']),
            ]);
        }

        return $result;
    }

    /**
     * Delete user
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUser(int $userId)
    {
        return $this->userRepository->deleteUser($userId);
    }

    /**
     * Toggle user status
     *
     * @param int $userId
     * @return bool
     */
    public function toggleUserStatus(int $userId)
    {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            return false;
        }

        $newStatus = !$user->is_active;
        
        return $this->userRepository->update($userId, [
            'is_active' => $newStatus
        ]);
    }
}

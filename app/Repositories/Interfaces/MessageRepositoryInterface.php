<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface MessageRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get messages between two users.
     * 
     * @param int $senderId
     * @param int $receiverId
     * @return Collection
     */
    public function getMessagesBetweenUsers(int $senderId, int $receiverId): Collection;
    
    /**
     * Get conversations for a user.
     * 
     * @param int $userId
     * @return Collection
     */
    public function getConversationsForUser(int $userId): Collection;
    
    /**
     * Mark messages as read.
     * 
     * @param int $receiverId
     * @param int $senderId
     * @return bool
     */
    public function markAsRead(int $receiverId, int $senderId): bool;
}

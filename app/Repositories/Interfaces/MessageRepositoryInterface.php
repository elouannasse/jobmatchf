<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface MessageRepositoryInterface extends EloquentRepositoryInterface
{
    
    public function getMessagesBetweenUsers(int $senderId, int $receiverId): Collection;

    public function getConversationsForUser(int $userId): Collection;
    
   
    public function markAsRead(int $receiverId, int $senderId): bool;
}

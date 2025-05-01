<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{
    /**
     * MessageRepository constructor.
     * 
     * @param Message $model
     */
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function getMessagesBetweenUsers(int $senderId, int $receiverId): Collection
    {
        return $this->model
            ->where(function($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })
            ->orWhere(function($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * @inheritdoc
     */
    public function getConversationsForUser(int $userId): Collection
    {
        $userIds = DB::table('messages')
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->select(
                DB::raw('CASE 
                    WHEN sender_id = ' . $userId . ' THEN receiver_id 
                    ELSE sender_id 
                END as user_id')
            )
            ->distinct()
            ->pluck('user_id');

        return \App\Models\User::whereIn('id', $userIds)->get();
    }

    /**
     * @inheritdoc
     */
    public function markAsRead(int $receiverId, int $senderId): bool
    {
        return $this->model
            ->where('receiver_id', $receiverId)
            ->where('sender_id', $senderId)
            ->where('read', false)
            ->update(['read' => true]);
    }
}

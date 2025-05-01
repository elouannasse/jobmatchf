<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'content',
        'read_at',
        'deleted_by_sender',
        'deleted_by_recipient'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of the message.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Check if the message is read.
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        if ($this->read_at === null) {
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include messages received by a specific user.
     */
    public function scopeReceivedBy($query, $userId)
    {
        return $query->where('recipient_id', $userId)
                     ->where('deleted_by_recipient', false);
    }

    /**
     * Scope a query to only include messages sent by a specific user.
     */
    public function scopeSentBy($query, $userId)
    {
        return $query->where('sender_id', $userId)
                     ->where('deleted_by_sender', false);
    }
}
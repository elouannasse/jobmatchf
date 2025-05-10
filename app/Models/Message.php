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

   
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    
    public function isRead()
    {
        return $this->read_at !== null;
    }

    
    public function markAsRead()
    {
        if ($this->read_at === null) {
            $this->read_at = now();
            $this->save();
        }
    }

    
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    
    public function scopeReceivedBy($query, $userId)
    {
        return $query->where('recipient_id', $userId)
                     ->where('deleted_by_recipient', false);
    }

    
    public function scopeSentBy($query, $userId)
    {
        return $query->where('sender_id', $userId)
                     ->where('deleted_by_sender', false);
    }
}
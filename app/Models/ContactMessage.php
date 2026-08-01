<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'user_id',
        'ip_address',
        'user_agent',
        'read_at',
        'replied_by',
        'replied_at',
        'reply_message',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    /**
     * Get the user who sent the message (if logged in)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who replied
     */
    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Mark as replied
     */
    public function markAsReplied($userId, $replyMessage)
    {
        $this->update([
            'status' => 'replied',
            'replied_by' => $userId,
            'replied_at' => now(),
            'reply_message' => $replyMessage,
        ]);
    }
}


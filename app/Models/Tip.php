<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tip extends Model
{
    protected $fillable = [
        'subject',
        'content',
        'status',
        'user_id',
        'email',
        'name',
        'ip_address',
        'user_agent',
        'reviewed_at',
        'reviewed_by',
        'admin_notes',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the tip (if logged in)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who reviewed the tip
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Mark as reviewed
     */
    public function markAsReviewed($userId, $status = 'reviewed', $notes = null)
    {
        $this->update([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => $userId,
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Scope for pending tips
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for reviewed tips
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', '!=', 'pending');
    }
}


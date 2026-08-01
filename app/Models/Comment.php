<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'article_id',
        'user_id',
        'name',
        'email',
        'content',
        'parent_id',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'article_id' => 'integer',
        'user_id' => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * Get the article that owns the comment
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user who made the comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment (supports nested replies)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('status', 'approved')
            ->with('replies') // Eager load nested replies
            ->orderBy('created_at', 'asc');
    }

    /**
     * Scope for approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending comments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}


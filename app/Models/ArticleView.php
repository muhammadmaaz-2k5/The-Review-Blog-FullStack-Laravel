<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleView extends Model
{
    protected $fillable = [
        'article_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'device_type',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Get the article that was viewed
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user who viewed (if logged in)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


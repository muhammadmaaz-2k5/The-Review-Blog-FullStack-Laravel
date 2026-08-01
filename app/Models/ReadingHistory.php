<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    protected $table = 'reading_history';

    protected $fillable = [
        'user_id',
        'article_id',
        'progress',
        'last_read_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'progress' => 'integer',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the article
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}


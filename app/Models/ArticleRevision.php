<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleRevision extends Model
{
    protected $fillable = [
        'article_id',
        'created_by',
        'title',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'status',
        'is_featured',
        'allow_comments',
        'published_at',
        'meta',
        'change_summary',
        'revision_number',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'meta' => 'array',
        'revision_number' => 'integer',
    ];

    /**
     * Get the article that owns this revision
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user who created this revision
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the category for this revision
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

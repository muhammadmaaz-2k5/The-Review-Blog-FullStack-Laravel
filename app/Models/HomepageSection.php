<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HomepageSection extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'display_order',
        'is_active',
        'category_ids',
        'articles_per_section',
        'section_title',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_active' => 'boolean',
        'category_ids' => 'array',
        'articles_per_section' => 'integer',
    ];

    /**
     * Get categories for this section
     */
    public function categories(): HasMany
    {
        return Category::whereIn('id', $this->category_ids ?? []);
    }

    /**
     * Get articles for this section
     */
    public function getArticlesAttribute(int $limit = null)
    {
        $limit = $limit ?? $this->articles_per_section ?? 4;
        
        if (empty($this->category_ids)) {
            return collect();
        }

        return Article::published()
            ->with(['author', 'category'])
            ->whereIn('category_id', $this->category_ids)
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Scope for active sections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}

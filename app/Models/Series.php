<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Traits\ClearsSitemapCache;

class Series extends Model
{
    use SoftDeletes, ClearsSitemapCache;

    protected $table = 'article_series';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'featured_image',
        'author_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the author of the series
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all articles in this series
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class)->where('status', 'published')->orderBy('series_order', 'asc');
    }

    /**
     * Get all articles in this series (including drafts)
     */
    public function allArticles(): HasMany
    {
        return $this->hasMany(Article::class)->orderBy('series_order', 'asc');
    }

    /**
     * Generate a unique slug from the title
     */
    public function generateUniqueSlug(): string
    {
        $slug = Str::slug($this->title);
        $originalSlug = $slug;
        $count = 1;

        while (static::withTrashed()->where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return asset('images/placeholder.jpg'); // Or any default image you have
        }

        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        if (str_starts_with($this->featured_image, '/storage/')) {
            return asset($this->featured_image);
        }

        if (str_starts_with($this->featured_image, 'storage/')) {
            return asset($this->featured_image);
        }

        return asset('storage/' . $this->featured_image);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($series) {
            if (empty($series->slug)) {
                $series->slug = $series->generateUniqueSlug();
            }
        });

        static::updating(function ($series) {
            if ($series->isDirty('title') && empty($series->slug)) {
                $series->slug = $series->generateUniqueSlug();
            }
        });
    }

    /**
     * Get total articles count
     */
    public function getTotalArticlesAttribute(): int
    {
        return $this->articles()->count();
    }

    /**
     * Get progress percentage (0-100)
     */
    public function getProgressAttribute(): float
    {
        $total = $this->articles()->count();
        if ($total === 0) return 0;
        
        $published = $this->articles()->where('status', 'published')->count();
        return ($published / $total) * 100;
    }
}

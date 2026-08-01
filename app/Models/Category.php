<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\ClearsSitemapCache;

class Category extends Model
{
    use SoftDeletes, ClearsSitemapCache;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all articles in this category
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class)->published();
    }

    /**
     * Get all articles including drafts
     */
    public function allArticles(): HasMany
    {
        return $this->hasMany(Article::class);
    }



    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = $category->generateUniqueSlug();
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = $category->generateUniqueSlug();
            }
        });
    }

    /**
     * Generate a unique slug from the name.
     */
    public function generateUniqueSlug()
    {
        $slug = Str::slug($this->name);
        $originalSlug = $slug;
        $count = 1;

        while (static::withTrashed()->where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get articles count
     */
    public function getArticlesCountAttribute()
    {
        return $this->articles()->count();
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        if (str_starts_with($this->image, '/storage/')) {
            return asset($this->image);
        }

        return asset('storage/' . $this->image);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\ClearsSitemapCache;

class Tag extends Model
{
    use SoftDeletes, ClearsSitemapCache;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    /**
     * Get all articles with this tag
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag')
            ->withTimestamps();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = $tag->generateUniqueSlug();
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = $tag->generateUniqueSlug();
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
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'type',
        'criteria',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'criteria' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get users who have this badge
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Generate a unique slug from the name
     */
    public function generateUniqueSlug(): string
    {
        $slug = Str::slug($this->name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($badge) {
            if (empty($badge->slug)) {
                $badge->slug = $badge->generateUniqueSlug();
            }
        });

        static::updating(function ($badge) {
            if ($badge->isDirty('name') && empty($badge->slug)) {
                $badge->slug = $badge->generateUniqueSlug();
            }
        });
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

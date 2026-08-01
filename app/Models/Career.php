<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Career extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'requirements',
        'location',
        'type', // full-time, part-time, contract, remote
        'department',
        'experience_level', // entry, mid, senior, executive
        'salary_range',
        'application_deadline',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'application_deadline' => 'date',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($career) {
            if (empty($career->slug)) {
                $career->slug = $career->generateUniqueSlug();
            }
        });

        static::updating(function ($career) {
            if ($career->isDirty('title') && empty($career->slug)) {
                $career->slug = $career->generateUniqueSlug();
            }
        });
    }

    /**
     * Generate a unique slug from the title.
     */
    public function generateUniqueSlug()
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
     * Scope for active careers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('application_deadline')
                  ->orWhere('application_deadline', '>=', now());
            });
    }

    /**
     * Scope for featured careers
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Check if application deadline has passed
     */
    public function isDeadlinePassed()
    {
        return $this->application_deadline && $this->application_deadline->isPast();
    }

    /**
     * Get formatted salary range
     */
    public function getFormattedSalaryAttribute()
    {
        if (!$this->salary_range) {
            return 'Not specified';
        }
        return $this->salary_range;
    }
}


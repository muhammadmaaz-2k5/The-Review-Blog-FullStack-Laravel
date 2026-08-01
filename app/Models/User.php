<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'cover_image',
        'bio',
        'website',
        'twitter',
        'github',
        'linkedin',
        'location',
        'is_author',
        'role',
        'profile_public',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_author' => 'boolean',
            'profile_public' => 'boolean',
            'last_activity_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($user) {
            // Clear sitemap cache if author status or profile visibility changes
            if ($user->isDirty(['is_author', 'role', 'profile_public', 'username'])) {
                if (app()->bound(\App\Services\SitemapService::class)) {
                    app(\App\Services\SitemapService::class)->clearCache();
                }
            }
        });
    }

    /**
     * Get articles written by this user
     */
    public function articles()
    {
        return $this->hasMany(\App\Models\Article::class, 'author_id');
    }

    /**
     * Get bookmarks for this user
     */
    public function bookmarks()
    {
        return $this->hasMany(\App\Models\Bookmark::class);
    }

    /**
     * Get reading history for this user
     */
    public function readingHistory()
    {
        return $this->hasMany(\App\Models\ReadingHistory::class);
    }

    /**
     * Get article likes for this user
     */
    public function articleLikes()
    {
        return $this->hasMany(\App\Models\ArticleLike::class);
    }

    /**
     * Get comments by this user
     */
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    /**
     * Get author requests for this user
     */
    public function authorRequests()
    {
        return $this->hasMany(\App\Models\AuthorRequest::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is author
     */
    public function isAuthor(): bool
    {
        return $this->is_author || $this->role === 'author' || $this->role === 'admin';
    }

    /**
     * Get users that this user follows
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    /**
     * Get users that follow this user
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    /**
     * Check if user follows another user
     */
    public function follows(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Follow a user
     */
    public function follow(User $user): void
    {
        if (!$this->follows($user) && $this->id !== $user->id) {
            $this->following()->attach($user->id);
        }
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user): void
    {
        $this->following()->detach($user->id);
    }

    /**
     * Get badges for this user
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at')
            ->withTimestamps()
            ->orderBy('user_badges.earned_at', 'desc');
    }

    /**
     * Get activities for this user
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get published articles count
     */
    public function getPublishedArticlesCountAttribute(): int
    {
        return $this->articles()->where('status', 'published')->count();
    }

    /**
     * Get total views across all articles
     */
    public function getTotalViewsAttribute(): int
    {
        return $this->articles()->sum('views');
    }

    /**
     * Get total likes across all articles
     */
    public function getTotalLikesAttribute(): int
    {
        return \App\Models\ArticleLike::whereIn('article_id', $this->articles()->pluck('id'))->count();
    }

    /**
     * Get total comments count
     */
    public function getTotalCommentsAttribute(): int
    {
        return $this->comments()->count();
    }

    /**
     * Get followers count
     */
    public function getFollowersCountAttribute(): int
    {
        return $this->followers()->count();
    }

    /**
     * Get following count
     */
    public function getFollowingCountAttribute(): int
    {
        return $this->following()->count();
    }

    /**
     * Get profile URL
     */
    public function getProfileUrlAttribute(): string
    {
        return route('profile.show', $this->username ?? $this->id);
    }

    /**
     * Get avatar URL (with fallback)
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            if (str_starts_with($this->avatar, '/storage/')) {
                return asset($this->avatar);
            }
            return asset('storage/' . $this->avatar);
        }
        
        // Generate default avatar based on name
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=E50914&color=fff&size=200';
    }

    /**
     * Get cover image URL
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }

        if (str_starts_with($this->cover_image, '/storage/')) {
            return asset($this->cover_image);
        }

        return asset('storage/' . $this->cover_image);
    }

    /**
     * Record user activity
     */
    public function recordActivity(string $type, string $description, $subject = null, array $metadata = []): UserActivity
    {
        $this->update(['last_activity_at' => now()]);
        return UserActivity::log($this->id, $type, $description, $subject, $metadata);
    }

    /**
     * Award a badge to user
     */
    public function awardBadge(Badge $badge): void
    {
        if (!$this->badges()->where('badges.id', $badge->id)->exists()) {
            $this->badges()->attach($badge->id, ['earned_at' => now()]);
            $this->recordActivity('badge_earned', "Earned badge: {$badge->name}", $badge);
        }
    }

    /**
     * Get route key for model (use username if available, otherwise ID)
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * Resolve route binding using username or ID
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?: $this->getRouteKeyName();
        
        if ($field === 'username') {
            return $this->where('username', $value)
                ->orWhere('id', $value)
                ->first();
        }
        
        return parent::resolveRouteBinding($value, $field);
    }
}

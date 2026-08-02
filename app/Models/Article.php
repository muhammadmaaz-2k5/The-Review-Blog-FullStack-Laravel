<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\ClearsSitemapCache;

/**
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $excerpt
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $published_at
 */
class Article extends Model
{
    use SoftDeletes, ClearsSitemapCache;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'quick_answer',
        'content',
        'featured_image',
        'featured_image_alt',
        'featured_image_title',
        'category_id',
        'author_id',
        'series_id',
        'series_order',
        'status',
        'views',
        'reading_time',
        'is_featured',
        'allow_comments',
        'published_at',
        'sort_order',
        'meta',
        'short_video_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'views' => 'integer',
        'reading_time' => 'integer',
        'sort_order' => 'integer',
        'series_order' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Get the category that owns the article
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the author of the article
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the series this article belongs to
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get all tags for this article
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag')
            ->withTimestamps();
    }

    /**
     * Get all comments for this article
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get all comments including replies
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get all bookmarks for this article
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Get all views for this article
     */
    public function views(): HasMany
    {
        return $this->hasMany(ArticleView::class);
    }

    /**
     * Get all likes for this article
     */
    public function likes(): HasMany
    {
        return $this->hasMany(ArticleLike::class);
    }

    /**
     * Get reading history for this article
     */
    public function readingHistory(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    /**
     * Get all revisions for this article
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(ArticleRevision::class)->orderBy('revision_number', 'desc');
    }

    /**
     * Create a revision snapshot of the current article state
     */
    public function createRevision($createdBy = null, $changeSummary = null): ArticleRevision
    {
        $revisionNumber = $this->revisions()->max('revision_number') + 1;

        return ArticleRevision::create([
            'article_id' => $this->id,
            'created_by' => $createdBy ?? Auth::id(),
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $this->featured_image,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'allow_comments' => $this->allow_comments,
            'published_at' => $this->published_at,
            'meta' => $this->meta,
            'change_summary' => $changeSummary,
            'revision_number' => $revisionNumber,
        ]);
    }

    /**
     * Check if article is bookmarked by user
     */
    public function isBookmarkedBy($userId): bool
    {
        return $this->bookmarks()->where('user_id', $userId)->exists();
    }

    /**
     * Check if article is liked by user or IP
     */
    public function isLikedBy($userId = null, $ipAddress = null): bool
    {
        $query = $this->likes();
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($ipAddress) {
            $query->where('ip_address', $ipAddress);
        }
        return $query->exists();
    }

    /**
     * Get likes count
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope for featured articles
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for category
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = $article->generateUniqueSlug();
            }
            if (empty($article->reading_time)) {
                $article->reading_time = $article->calculateReadingTime();
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = $article->generateUniqueSlug();
            }
            if ($article->isDirty('content')) {
                $article->reading_time = $article->calculateReadingTime();
            }
        });

        // Clear sitemap cache when article is saved or deleted
        static::saved(function ($article) {
            if (app()->bound(\App\Services\SitemapService::class)) {
                app(\App\Services\SitemapService::class)->clearCache();
            }
        });

        static::deleted(function ($article) {
            if (app()->bound(\App\Services\SitemapService::class)) {
                app(\App\Services\SitemapService::class)->clearCache();
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
     * Calculate reading time in minutes
     */
    public function calculateReadingTime(): int
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return max(1, $readingTime); // Minimum 1 minute
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Increment views
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get the rendered content (decoded HTML)
     */
    public function getRenderedContentAttribute()
    {
        // Decode HTML entities if they exist, otherwise return as-is
        $content = $this->content;
        
        // Check if content is HTML-encoded
        if (strpos($content, '&lt;') !== false || strpos($content, '&gt;') !== false) {
            $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        
        // Fix AMP validation issues: Remove duplicate srcset values
        $content = $this->cleanSrcsetForAmp($content);
        
        // Auto-embed video URLs
        $content = $this->autoEmbedVideoUrls($content);
        
        return $content;
    }

    
    /**
     * Clean srcset attributes to fix AMP validation errors
     * Removes duplicate width/density values in srcset
     */
    private function cleanSrcsetForAmp($content)
    {
        // Pattern to match img tags with srcset attribute
        $pattern = '/<img([^>]*)srcset\s*=\s*["\']([^"\']+)["\']([^>]*)>/i';
        
        $content = preg_replace_callback($pattern, function($matches) {
            $before = $matches[1];
            $srcset = $matches[2];
            $after = $matches[3];
            
            // Parse srcset values
            $sources = array_map('trim', explode(',', $srcset));
            $uniqueSources = [];
            $widths = [];
            
            foreach ($sources as $source) {
                // Extract width descriptor (e.g., "700w")
                if (preg_match('/(\d+)w$/i', $source, $widthMatch)) {
                    $width = $widthMatch[1];
                    // Only keep if we haven't seen this width yet
                    if (!isset($widths[$width])) {
                        $widths[$width] = true;
                        $uniqueSources[] = $source;
                    }
                } else {
                    // Keep sources without width descriptors
                    $uniqueSources[] = $source;
                }
            }
            
            // Rebuild srcset or remove it entirely if empty
            if (empty($uniqueSources)) {
                // Remove srcset entirely
                return '<img' . $before . $after . '>';
            } else {
                $cleanSrcset = implode(', ', $uniqueSources);
                return '<img' . $before . 'srcset="' . $cleanSrcset . '"' . $after . '>';
            }
        }, $content);
        
        // Additional AMP fixes: Remove problematic loading="auto" attribute
        $content = preg_replace('/\s+loading\s*=\s*["\']auto["\']/i', '', $content);
        
        return $content;
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

        // Clean redundant storage prefix
        $path = $this->featured_image;
        if (str_starts_with($path, '/storage/')) $path = substr($path, 9);
        if (str_starts_with($path, 'storage/')) $path = substr($path, 8);
        if (str_starts_with($path, '/')) $path = ltrim($path, '/');

        return asset('storage/' . $path);
    }

    /**
     * Get Meta Title with fallback
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    /**
     * Get Meta Description with fallback
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: ($this->excerpt ?: Str::limit(strip_tags($this->content), 150));
    }

    /**
     * Get Meta Keywords with fallback
     */
    public function getMetaKeywordsAttribute($value)
    {
        if ($value) return $value;

        $keywords = collect(explode(' ', $this->title))
            ->reject(fn($word) => strlen($word) < 3)
            ->implode(', ');
            
        if ($this->category) {
            $keywords .= ', ' . $this->category->name;
        }
        
        return $keywords;
    }

    /**
     * Get OG Title with fallback
     */
    public function getOgTitleAttribute($value)
    {
        return $value ?: $this->meta_title;
    }

    /**
     * Get OG Description with fallback
     */
    public function getOgDescriptionAttribute($value)
    {
        return $value ?: $this->meta_description;
    }

    /**
     * Get OG Image with fallback
     */
    public function getOgImageAttribute($value)
    {
        return $value ?: $this->featured_image;
    }

    /**
     * Get Twitter Title with fallback
     */
    public function getTwitterTitleAttribute($value)
    {
        return $value ?: $this->meta_title;
    }

    /**
     * Get Twitter Description with fallback
     */
    public function getTwitterDescriptionAttribute($value)
    {
        return $value ?: $this->meta_description;
    }

    /**
     * Get Twitter Image with fallback
     */
    public function getTwitterImageAttribute($value)
    {
        return $value ?: $this->featured_image;
    }



    /**
     * Auto-embed supported video URLs found in article content
     */
    private function autoEmbedVideoUrls($content)
    {
        // YouTube patterns
        $ytPattern = '/<p>\s*(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})(?:\S+)?\s*<\/p>/i';
        $content = preg_replace_callback($ytPattern, function($matches) {
            $id = $matches[1];
            return '<div class="video-container my-8 rounded-2xl overflow-hidden shadow-2xl relative aspect-video bg-black ring-1 ring-white/10">
                        <iframe src="https://www.youtube.com/embed/' . $id . '" class="absolute inset-0 w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>';
        }, $content);

        // Doodstream patterns (handles dood.so, doodstream.com, dood.yt, ds2play.com)
        $doodPattern = '/<p>\s*(?:https?:\/\/)?(?:www\.)?(?:dood(?:stream)?\.(?:so|com|yt|la|sh|re|wf)|ds2(?:play|video)\.com)\/(?:[de]|watch)\/([a-zA-Z0-9]+)(?:\S+)?\s*<\/p>/i';
        $content = preg_replace_callback($doodPattern, function($matches) {
            $id = $matches[1];
            return '<div class="video-container my-8 rounded-2xl overflow-hidden shadow-2xl relative aspect-video bg-black ring-1 ring-white/10">
                        <iframe src="https://dood.so/e/' . $id . '" class="absolute inset-0 w-full h-full" frameborder="0" scrolling="no" allowfullscreen allow="autoplay"></iframe>
                    </div>';
        }, $content);

        // Facebook Video and Reels patterns
        $fbPattern = '/<p>\s*(?:https?:\/\/)?(?:www\.)?(?:facebook\.com\/(?:[^\/]+\/videos\/|video\.php\?v=|reels\/))([0-9]+)(?:\S+)?\s*<\/p>/i';
        $content = preg_replace_callback($fbPattern, function($matches) {
            $id = $matches[1];
            return '<div class="video-container my-8 rounded-2xl overflow-hidden shadow-2xl relative aspect-video bg-black ring-1 ring-white/10">
                        <iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Ffacebook%2Fvideos%2F' . $id . '&show_text=0" class="absolute inset-0 w-full h-full" frameborder="0" allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    </div>';
        }, $content);

        // Instagram Reels pattern
        $igPattern = '/<p>\s*(?:https?:\/\/)?(?:www\.)?instagram\.com\/(?:reels?|p)\/([a-zA-Z0-9_-]+)(?:\/)?(?:\S+)?\s*<\/p>/i';
        $content = preg_replace_callback($igPattern, function($matches) {
            $id = $matches[1];
            return '<div class="video-container my-8 rounded-2xl overflow-hidden shadow-2xl relative aspect-video bg-black ring-1 ring-white/10 bg-gradient-to-tr from-purple-600 to-red-400">
                        <iframe src="https://www.instagram.com/p/' . $id . '/embed" class="absolute inset-0 w-full h-full" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
                    </div>';
        }, $content);

        // RPM Play / Universal Player patterns
        $rpmPattern = '/<p>\s*(?:https?:\/\/)?(?:[^\.]+\.)?rpmplay\.xyz\/(?:#)?([a-zA-Z0-9_-]+)(?:\S+)?\s*<\/p>/i';
        $content = preg_replace_callback($rpmPattern, function($matches) {
            $url = trim(strip_tags($matches[0])); // Extract the actual URL
            return '<div class="video-container my-8 rounded-2xl overflow-hidden shadow-2xl relative aspect-square max-w-[711px] mx-auto bg-black ring-1 ring-white/10">
                        <iframe src="' . $url . '" class="absolute inset-0 w-full h-full" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                    </div>';
        }, $content);

        // Global Fix: Remove restrictive sandbox from ALL iframes in the content
        // This ensures manually pasted iframes (like RPM Play) work without being blocked.
        $content = preg_replace('/<iframe([^>]*)\s+sandbox=["\'][^"\']*["\']([^>]*)>/i', '<iframe$1$2>', $content);
        // Also remove empty sandbox attributes
        $content = preg_replace('/<iframe([^>]*)\s+sandbox([^>a-zA-Z]*)([^>]*)>/i', '<iframe$1$3>', $content);

        return $content;
    }


}


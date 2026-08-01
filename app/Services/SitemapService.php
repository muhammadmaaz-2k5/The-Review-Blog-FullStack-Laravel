<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Series;
use App\Models\User;
use App\Models\Game;
use App\Models\Application;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SitemapService
{
    protected $siteUrl;
    protected $cacheDuration;

    public function __construct()
    {
        $this->siteUrl = rtrim(config('app.url', url('/')), '/');
        $this->cacheDuration = config('sitemap.cache_duration', 900); // 15 minutes default
    }

    /**
     * Get all sitemap URLs organized by type
     */
    public function getAllUrls(): array
    {
        return Cache::remember('sitemap_all_urls', $this->cacheDuration, function () {
            return [
                'pages' => $this->getStaticPages(),
                'articles' => $this->getArticlesUrls(),
                'games' => $this->getGamesUrls(),
                'applications' => $this->getApplicationsUrls(),
                'tools' => $this->getToolsUrls(),
                'categories' => $this->getCategoriesUrls(),
                'tags' => $this->getTagsUrls(),
                'series' => $this->getSeriesUrls(),
                'profiles' => $this->getProfilesUrls(),
            ];
        });
    }

    /**
     * Get static pages (home, about, dmca, etc.)
     */
    public function getStaticPages(): array
    {
        return Cache::remember('sitemap_pages_urls', $this->cacheDuration, function () {
        $pages = [
            [
                'loc' => route('home'),
                'lastmod' => $this->getHomePageLastModified(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('articles.index'),
                'lastmod' => $this->getArticlesLastModified(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('games.index'),
                'lastmod' => $this->getGamesLastModified(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('applications.index'),
                'lastmod' => $this->getApplicationsLastModified(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('tools.index'),
                'lastmod' => $this->getToolsLastModified(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('categories.index'),
                'lastmod' => $this->getCategoriesLastModified(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => route('series.index'),
                'lastmod' => $this->getSiteLastModified(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => route('tags.index'),
                'lastmod' => $this->getTagsLastModified(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('about'),
                'lastmod' => $this->getSiteLastModified(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
            [
                'loc' => route('contact'),
                'lastmod' => $this->getSiteLastModified(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ],
            [
                'loc' => route('privacy'),
                'lastmod' => $this->getSiteLastModified(),
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ],
            [
                'loc' => route('terms'),
                'lastmod' => $this->getSiteLastModified(),
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ],
        ];

        return $pages;
        });
    }

    /**
     * Get all article URLs
     */
    public function getArticlesUrls(): array
    {
        return Cache::remember('sitemap_articles_urls', $this->cacheDuration, function () {
        $articles = Article::published()
            ->whereNotNull('slug')
                ->select([
                    'id', 
                    'slug', 
                    'title', 
                    'excerpt', 
                    'content', 
                    'updated_at', 
                    'published_at',
                    'featured_image',
                    'featured_image_alt',
                    'featured_image_title',
                    'is_featured', 
                    'views'
                ])
            ->orderBy('updated_at', 'desc')
            ->limit(10000)
            ->get();

        $urls = [];
        foreach ($articles as $article) {
            $urls[] = [
                'loc' => route('articles.show', $article->slug),
                'lastmod' => $this->formatDate($article->updated_at),
                'changefreq' => $this->getArticleChangeFreq($article),
                'priority' => $this->getArticlePriority($article),
                'images' => $this->getArticleImages($article),
                'videos' => $this->getArticleVideos($article),
            ];
        }

            // Check URL limit
            $this->checkUrlLimit($urls, 'articles');

        return $urls;
        });
    }

    /**
     * Get all game URLs
     */
    public function getGamesUrls(): array
    {
        return Cache::remember('sitemap_games_urls', $this->cacheDuration, function () {
            $games = Game::where('status', 'published')
                ->whereNotNull('slug')
                ->orderBy('updated_at', 'desc')
                ->get();

            $urls = [];
            foreach ($games as $game) {
                $urls[] = [
                    'loc' => route('games.show', $game->slug),
                    'lastmod' => $this->formatDate($game->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
            return $urls;
        });
    }

    /**
     * Get all application URLs
     */
    public function getApplicationsUrls(): array
    {
        return Cache::remember('sitemap_applications_urls', $this->cacheDuration, function () {
            $applications = Application::where('status', 'published')
                ->whereNotNull('slug')
                ->orderBy('updated_at', 'desc')
                ->get();

            $urls = [];
            foreach ($applications as $application) {
                $urls[] = [
                    'loc' => route('applications.show', $application->slug),
                    'lastmod' => $this->formatDate($application->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
            return $urls;
        });
    }

    /**
     * Get all tool URLs
     */
    public function getToolsUrls(): array
    {
        return Cache::remember('sitemap_tools_urls', $this->cacheDuration, function () {
            $tools = Tool::where('status', 'published')
                ->whereNotNull('slug')
                ->orderBy('updated_at', 'desc')
                ->get();

            $urls = [];
            foreach ($tools as $tool) {
                $urls[] = [
                    'loc' => route('tools.show', $tool->slug),
                    'lastmod' => $this->formatDate($tool->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
            return $urls;
        });
    }

    /**
     * Get all category URLs
     */
    public function getCategoriesUrls(): array
    {
        return Cache::remember('sitemap_categories_urls', $this->cacheDuration, function () {
        $categories = Category::where('is_active', true)
            ->whereNotNull('slug')
                ->select(['id', 'slug', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->limit(10000)
            ->get();

        $urls = [];
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('categories.show', $category->slug),
                'lastmod' => $this->formatDate($category->updated_at),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return $urls;
        });
    }

    /**
     * Get all tag URLs
     */
    public function getTagsUrls(): array
    {
        return Cache::remember('sitemap_tags_urls', $this->cacheDuration, function () {
        $tags = Tag::whereNotNull('slug')
            ->has('articles')
                ->select(['id', 'slug', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->limit(10000)
            ->get();

        $urls = [];
        foreach ($tags as $tag) {
            $urls[] = [
                'loc' => route('tags.show', $tag->slug),
                'lastmod' => $this->formatDate($tag->updated_at),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        return $urls;
        });
    }

    /**
     * Get all series URLs
     */
    public function getSeriesUrls(): array
    {
        return Cache::remember('sitemap_series_urls', $this->cacheDuration, function () {
            $series = Series::whereNotNull('slug')
                ->select(['id', 'slug', 'updated_at'])
                ->orderBy('updated_at', 'desc')
                ->limit(10000)
                ->get();

            $urls = [];
            foreach ($series as $item) {
                $urls[] = [
                    'loc' => route('series.show', $item->slug),
                    'lastmod' => $this->formatDate($item->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }

            return $urls;
        });
    }

    /**
     * Get all public profile URLs
     */
    public function getProfilesUrls(): array
    {
        return Cache::remember('sitemap_profiles_urls', $this->cacheDuration, function () {
            $users = User::where('profile_public', true)
                ->whereNotNull('username')
                ->select(['id', 'username', 'updated_at'])
                ->orderBy('updated_at', 'desc')
                ->get();

            $urls = [];
            foreach ($users as $user) {
                $urls[] = [
                    'loc' => route('profile.show', $user->username),
                    'lastmod' => $this->formatDate($user->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            }

            return $urls;
        });
    }

    /**
     * Get sitemap index (for multiple sitemaps)
     */
    public function getSitemapIndex(): array
    {
        return [
            [
                'loc' => route('sitemap.home'),
                'lastmod' => $this->getHomePageLastModified(),
            ],
            [
                'loc' => route('sitemap.pages'),
                'lastmod' => $this->getSiteLastModified(),
            ],
            [
                'loc' => route('sitemap.articles'),
                'lastmod' => $this->getArticlesLastModified(),
            ],
            [
                'loc' => route('sitemap.games'),
                'lastmod' => $this->getGamesLastModified(),
            ],
            [
                'loc' => route('sitemap.applications'),
                'lastmod' => $this->getApplicationsLastModified(),
            ],
            [
                'loc' => route('sitemap.tools'),
                'lastmod' => $this->getToolsLastModified(),
            ],
            [
                'loc' => route('sitemap.categories'),
                'lastmod' => $this->getCategoriesLastModified(),
            ],
            [
                'loc' => route('sitemap.tags'),
                'lastmod' => $this->getTagsLastModified(),
            ],
            [
                'loc' => route('sitemap.series'),
                'lastmod' => $this->getSeriesLastModified(),
            ],
            [
                'loc' => route('sitemap.profiles'),
                'lastmod' => $this->getProfilesLastModified(),
            ],
        ];
    }

    /**
     * Get home page sitemap (includes featured and trending content)
     */
    public function getHomePageUrls(): array
    {
        return Cache::remember('sitemap_home_urls', $this->cacheDuration, function () {
            $urls = [];
            
            // Home page itself
            $urls[] = [
                'loc' => route('home'),
                'lastmod' => $this->getHomePageLastModified(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];
            
            // Featured articles (up to 10)
            $featuredArticles = Article::published()
                ->where('is_featured', true)
                ->whereNotNull('slug')
                ->select(['id', 'slug', 'updated_at', 'is_featured', 'views', 'published_at'])
                ->orderBy('published_at', 'desc')
                ->take(10)
                ->get();
            
            foreach ($featuredArticles as $article) {
                $urls[] = [
                    'loc' => route('articles.show', $article->slug),
                    'lastmod' => $this->formatDate($article->updated_at),
                    'changefreq' => $this->getArticleChangeFreq($article),
                    'priority' => '0.9',
                ];
            }
            
            // Trending articles (most viewed in last 7 days, up to 10)
            $trendingArticles = Article::published()
                ->whereNotNull('slug')
                ->where('published_at', '>=', now()->subDays(7))
                ->select(['id', 'slug', 'updated_at', 'is_featured', 'views', 'published_at'])
                ->orderBy('views', 'desc')
                ->take(10)
                ->get();
            
            // If no trending articles in last 7 days, get popular articles instead
            if ($trendingArticles->isEmpty()) {
                $trendingArticles = Article::published()
                    ->whereNotNull('slug')
                    ->select(['id', 'slug', 'updated_at', 'is_featured', 'views', 'published_at'])
                    ->orderBy('views', 'desc')
                    ->take(10)
                    ->get();
            }
            
            foreach ($trendingArticles as $article) {
                // Skip if already added as featured
                $alreadyAdded = collect($urls)->contains(function ($url) use ($article) {
                    return isset($url['loc']) && $url['loc'] === route('articles.show', $article->slug);
                });
                
                if (!$alreadyAdded) {
                    $urls[] = [
                        'loc' => route('articles.show', $article->slug),
                        'lastmod' => $this->formatDate($article->updated_at),
                        'changefreq' => $this->getArticleChangeFreq($article),
                        'priority' => '0.8',
                    ];
                }
            }
            
            // Popular categories (up to 10)
            $popularCategories = Category::where('is_active', true)
                ->whereNotNull('slug')
                ->select(['id', 'slug', 'updated_at'])
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
            
            foreach ($popularCategories as $category) {
                $urls[] = [
                    'loc' => route('categories.show', $category->slug),
                    'lastmod' => $this->formatDate($category->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }
            
            return $urls;
        });
    }

    /**
     * Get single sitemap by type
     */
    public function getSitemapByType(string $type): array
    {
        return match ($type) {
            'home' => $this->getHomePageUrls(),
            'pages', 'static' => $this->getStaticPages(), // Support both for backward compatibility
            'articles' => $this->getArticlesUrls(),
            'games' => $this->getGamesUrls(),
            'applications' => $this->getApplicationsUrls(),
            'tools' => $this->getToolsUrls(),
            'categories' => $this->getCategoriesUrls(),
            'tags' => $this->getTagsUrls(),
            'series' => $this->getSeriesUrls(),
            'profiles' => $this->getProfilesUrls(),
            default => [],
        };
    }

    /**
     * Get all URLs in a single array (for main sitemap)
     */
    public function getAllUrlsFlat(): array
    {
        $all = $this->getAllUrls();
        return array_merge(
            $all['pages'],
            $all['articles'],
            $all['games'],
            $all['applications'],
            $all['tools'],
            $all['categories'],
            $all['tags'],
            $all['series'],
            $all['profiles']
        );
    }

    /**
     * Get article change frequency based on article attributes
     */
    protected function getArticleChangeFreq(Article $article): string
    {
        // Featured articles change more frequently
        if ($article->is_featured) {
            return 'daily';
        }

        // Recent articles change more frequently
        if ($article->published_at && $article->published_at->gt(now()->subMonths(3))) {
            return 'weekly';
        }

        return 'monthly';
    }

    /**
     * Get article priority based on article attributes
     */
    protected function getArticlePriority(Article $article): string
    {
        // Featured articles have higher priority
        if ($article->is_featured) {
            return '0.9';
        }

        // Popular articles (high views) have higher priority
        if ($article->views > 1000) {
            return '0.8';
        }

        // Recent articles have higher priority
        if ($article->published_at && $article->published_at->gt(now()->subMonths(6))) {
            return '0.7';
        }

        return '0.6';
    }

    /**
     * Format date for sitemap (W3C format)
     */
    protected function formatDate($date): string
    {
        if (!$date) {
            return Carbon::now()->toW3cString();
        }

        return Carbon::parse($date)->toW3cString();
    }

    /**
     * Get site last modified date
     */
    protected function getSiteLastModified(): string
    {
        return Carbon::now()->toW3cString();
    }

    /**
     * Get home page last modified date (based on latest content)
     */
    protected function getHomePageLastModified(): string
    {
        // Get the latest modification from articles, guide articles, how to circle, and categories
        $latestArticle = Article::published()
            ->orderBy('updated_at', 'desc')
            ->first();
        
        $latestCategory = Category::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        // Find the most recent update
        $dates = [];
        if ($latestArticle) {
            $dates[] = $latestArticle->updated_at;
        }
        if ($latestCategory) {
            $dates[] = $latestCategory->updated_at;
        }
        
        if (empty($dates)) {
            return $this->getSiteLastModified();
        }
        
        $latestDate = collect($dates)->max();
        return $this->formatDate($latestDate);
    }

    /**
     * Get articles last modified date
     */
    protected function getArticlesLastModified(): string
    {
        $latest = Article::published()
            ->orderBy('updated_at', 'desc')
            ->first();
        
        return $latest ? $this->formatDate($latest->updated_at) : $this->getSiteLastModified();
    }

    /**
     * Get games last modified date
     */
    protected function getGamesLastModified(): string
    {
        $latest = Game::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->first();
        
        return $latest ? $this->formatDate($latest->updated_at) : $this->getSiteLastModified();
    }

    /**
     * Get applications last modified date
     */
    protected function getApplicationsLastModified(): string
    {
        $latest = Application::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->first();
        
        return $latest ? $this->formatDate($latest->updated_at) : $this->getSiteLastModified();
    }

    /**
     * Get tools last modified date
     */
    protected function getToolsLastModified(): string
    {
        $latest = Tool::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->first();
        
        return $latest ? $this->formatDate($latest->updated_at) : $this->getSiteLastModified();
    }

    /**
     * Get categories last modified date
     */
    protected function getCategoriesLastModified(): string
    {
        $latest = Category::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        return $latest ? $this->formatDate($latest->updated_at) : $this->getSiteLastModified();
    }

    /**
     * Get tags last modified date
     */
    protected function getTagsLastModified(): string
    {
        $latest = Tag::has('articles')
            ->orderBy('updated_at', 'desc')
            ->first();
        
        return $latest ? $this->formatDate($latest->updated_at) : $this->getSiteLastModified();
    }

    /**
     * Get series last modified date
     */
    protected function getSeriesLastModified(): string
    {
        $latest = Series::orderBy('updated_at', 'desc')->first();
        return $latest ? $this->formatDate($latest->updated_at) : $this->getSiteLastModified();
    }

    /**
     * Get profiles last modified date
     */
    protected function getProfilesLastModified(): string
    {
        $latest = User::where('profile_public', true)->orderBy('updated_at', 'desc')->first();
        return $latest ? $this->formatDate($latest->updated_at) : $this->getSiteLastModified();
    }

    /**
     * Get images from article
     */
    protected function getArticleImages(Article $article): array
    {
        $images = [];

        // Add featured image
        if ($article->featured_image) {
            $imageUrl = $article->featured_image;
            
            // Handle storage URLs
            if (!Str::startsWith($imageUrl, ['http://', 'https://'])) {
                // If it looks like a storage path (no leading slash, usually)
                if (!Str::startsWith($imageUrl, '/')) {
                     $imageUrl = Storage::url($imageUrl);
                }
                
                // Ensure absolute URL
                if (!Str::startsWith($imageUrl, ['http://', 'https://'])) {
                     $imageUrl = asset($imageUrl);
                }
            }
            
            $images[] = [
                'loc' => $imageUrl,
                'title' => $article->featured_image_title ?? $article->title,
                'caption' => $article->featured_image_alt ?? $article->title,
            ];
        }

        // Extract images from content
        if ($article->content) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $article->content, $matches);
            if (isset($matches[1])) {
                foreach ($matches[1] as $src) {
                    if (!Str::startsWith($src, ['http://', 'https://'])) {
                         $src = asset($src);
                    }
                    
                    // Avoid duplicates
                    $isDuplicate = false;
                    foreach ($images as $img) {
                        if ($img['loc'] === $src) {
                            $isDuplicate = true;
                            break;
                        }
                    }
                    
                    if (!$isDuplicate) {
                        $images[] = [
                            'loc' => $src,
                            'title' => $article->title,
                        ];
                    }
                }
            }
        }

        return $images;
    }

    /**
     * Get videos from article (YouTube only for now)
     */
    protected function getArticleVideos(Article $article): array
    {
        $videos = [];
        
        if ($article->content) {
            // Match YouTube embeds
            preg_match_all('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $article->content, $matches);
            
            if (isset($matches[1])) {
                foreach (array_unique($matches[1]) as $videoId) {
                    $videos[] = [
                        'thumbnail_loc' => "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg",
                        'title' => $article->title,
                        'description' => Str::limit(strip_tags($article->excerpt ?? $article->content), 150),
                        'player_loc' => "https://www.youtube.com/embed/{$videoId}",
                    ];
                }
            }
        }
        
        return $videos;
    }

    /**
     * Clear sitemap cache
     */
    public function clearCache(): void
    {
        Cache::forget('sitemap_all_urls');
        Cache::forget('sitemap_home_urls');
        Cache::forget('sitemap_pages_urls');
        Cache::forget('sitemap_articles_urls');
        Cache::forget('sitemap_categories_urls');
        Cache::forget('sitemap_tags_urls');
        Cache::forget('sitemap_series_urls');
        Cache::forget('sitemap_profiles_urls');
    }

    /**
     * Check if sitemap exceeds URL limit and log warning
     */
    protected function checkUrlLimit(array $urls, string $type): void
    {
        $limit = config('sitemap.url_limit', 50000);
        if (count($urls) > $limit) {
            Log::warning('Sitemap exceeds URL limit', [
                'type' => $type,
                'count' => count($urls),
                'limit' => $limit,
            ]);
        }
    }
}


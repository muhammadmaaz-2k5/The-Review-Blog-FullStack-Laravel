<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

use App\Services\ArticleService;
use App\Services\SeoService;
use App\Services\YouTubeService;
use App\Services\FacebookService;
use App\Services\InstagramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    protected $articleService;
    protected $seoService;

    public function __construct(ArticleService $articleService, SeoService $seoService)
    {
        $this->articleService = $articleService;
        $this->seoService = $seoService;
    }

    public function index(Request $request)
    {
        // Verify view exists before processing data (helps with production debugging)
        if (!view()->exists('home')) {
            Log::error('Home view not found', [
                'view_paths' => config('view.paths'),
                'expected_path' => resource_path('views/home.blade.php'),
                'file_exists' => file_exists(resource_path('views/home.blade.php')),
            ]);
            
            abort(500, 'Home view not found. Please run: php artisan views:check home');
        }
        
        $perPage = 30;

        $excludedSlugs = ['watch-kdrama', 'watch-movies'];

        $exclusionData = Cache::remember('home_exclusion_data', 1800, function () use ($excludedSlugs) {
            $hotCelebritiesCategoryForExclusion = Category::where('slug', 'hot-celebrity')
                ->orWhere('name', 'LIKE', '%Hot Celebrities%')
                ->first();

            $excludedCategoryId = $hotCelebritiesCategoryForExclusion ? $hotCelebritiesCategoryForExclusion->id : null;
            $watchCategoryIds = Category::whereIn('slug', $excludedSlugs)->pluck('id')->toArray();
            $allExcludedCategoryIds = $excludedCategoryId ? array_merge([$excludedCategoryId], $watchCategoryIds) : $watchCategoryIds;

            return [
                'hotCelebritiesCategoryForExclusion' => $hotCelebritiesCategoryForExclusion,
                'excludedCategoryId' => $excludedCategoryId,
                'allExcludedCategoryIds' => $allExcludedCategoryIds,
            ];
        });

        $excludedCategoryId = $exclusionData['excludedCategoryId'];
        $allExcludedCategoryIds = $exclusionData['allExcludedCategoryIds'];
        $hotCelebritiesCategoryForExclusion = $exclusionData['hotCelebritiesCategoryForExclusion'];

        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        
        $articles = Cache::remember('home_latest_articles_page_' . $currentPage, 600, function () use ($allExcludedCategoryIds, $excludedSlugs, $perPage) {
            $paginator = Article::published()
                ->with(['category', 'author', 'tags'])
                ->when(!empty($allExcludedCategoryIds), function($query) use ($allExcludedCategoryIds) {
                    return $query->whereNotIn('category_id', $allExcludedCategoryIds);
                })
                ->whereNotIn('slug', $excludedSlugs)
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $paginator->getCollection()->transform(function($article) {
                $article->type = 'article';
                return $article;
            });

            return $paginator;
        });

        $featuredArticles = $this->articleService->getFeaturedArticles(5);
        $popularArticles = $this->articleService->getPopularArticles(5);
        $categories = $this->articleService->getCategoriesWithCounts();
        $popularTags = $this->articleService->getPopularTags(10);

        $trendingArticles = Cache::remember('home_trending_articles', 600, function () use ($excludedCategoryId) {
            $trending = Article::published()
                ->with(['category', 'author'])
                ->where('published_at', '>=', now()->subDays(7))
                ->when($excludedCategoryId, function($query) use ($excludedCategoryId) {
                    return $query->where('category_id', '!=', $excludedCategoryId);
                })
                ->orderBy('views', 'desc')
                ->take(10)
                ->get();

            if ($trending->isEmpty()) {
                $trending = Article::published()
                    ->with(['category', 'author'])
                    ->orderBy('views', 'desc')
                    ->take(10)
                    ->get();
            }

            return $trending;
        });

        $recentArticles = Cache::remember('home_recent_articles_sidebar', 600, function () use ($excludedCategoryId) {
            return Article::published()
                ->with(['category', 'author'])
                ->when($excludedCategoryId, function($query) use ($excludedCategoryId) {
                    return $query->where('category_id', '!=', $excludedCategoryId);
                })
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        });

        $featuredGuides = Cache::remember('home_featured_guides', 600, function () use ($allExcludedCategoryIds, $excludedSlugs) {
            return Article::published()
                ->with(['category', 'author'])
                ->when(!empty($allExcludedCategoryIds), function($query) use ($allExcludedCategoryIds) {
                    return $query->whereNotIn('category_id', $allExcludedCategoryIds);
                })
                ->whereNotIn('slug', $excludedSlugs)
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function($article) {
                    $article->type = 'article';
                    return $article;
                });
        });

        $recentStories = Cache::remember('home_recent_stories', 600, function () use ($excludedCategoryId) {
            return Article::published()
                ->with(['category', 'author'])
                ->when($excludedCategoryId, function($query) use ($excludedCategoryId) {
                    return $query->where('category_id', '!=', $excludedCategoryId);
                })
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get()
                ->map(function($article) {
                    $article->type = 'article';
                    return $article;
                });
        });



        $top10Category = Cache::remember('home_top10_category', 1800, function () {
            return Category::where('slug', 'top-10')->first();
        });

        $top10Articles = null;
        if ($top10Category) {
            $top10Articles = Cache::remember('home_top10_articles', 600, function () use ($top10Category) {
                return $top10Category->articles()
                    ->with(['author', 'category'])
                    ->orderBy('published_at', 'desc')
                    ->take(10)
                    ->get();
            });
        }

        $hotCelebritiesCategory = $hotCelebritiesCategoryForExclusion;

        if (!$hotCelebritiesCategory) {
            $hotCelebritiesCategory = Cache::remember('home_hot_celebrities_category', 1800, function () {
                return Category::where('is_active', true)
                    ->withCount('articles')
                    ->orderBy('articles_count', 'desc')
                    ->first();
            });
        }

        $hotCelebritiesArticles = null;
        if ($hotCelebritiesCategory) {
            $hotCelebritiesArticles = Cache::remember('home_hot_celebrities_articles', 600, function () use ($hotCelebritiesCategory) {
                return $hotCelebritiesCategory->articles()
                    ->with(['author', 'category'])
                    ->orderBy('published_at', 'desc')
                    ->take(4)
                    ->get();
            });
        }

        $koreanDramasCategory = Cache::remember('home_korean_dramas_category', 1800, function () {
            return Category::where('slug', 'korean-dramas')
                ->orWhere('name', 'LIKE', '%Korean Drama%')
                ->first();
        });

        $instagramStarCategory = Cache::remember('home_instagram_star_category', 1800, function () {
            return Category::where('slug', 'instagram-star')
                ->orWhere('name', 'LIKE', '%Instagram Star%')
                ->first();
        });

        $biographiesCategory = Cache::remember('home_biographies_category', 1800, function () {
            return Category::where('slug', 'biographies')
                ->orWhere('name', 'LIKE', '%Biograph%')
                ->first();
        });

        $koreanDramasArticles = null;
        $instagramStarArticles = null;
        $biographiesArticles = null;

        if ($koreanDramasCategory) {
            $koreanDramasArticles = Cache::remember('home_korean_dramas_articles', 600, function () use ($koreanDramasCategory) {
                return $koreanDramasCategory->articles()
                    ->with(['author', 'category'])
                    ->orderBy('published_at', 'desc')
                    ->take(4)
                    ->get();
            });
        }

        if ($instagramStarCategory) {
            $instagramStarArticles = Cache::remember('home_instagram_star_articles', 600, function () use ($instagramStarCategory) {
                return $instagramStarCategory->articles()
                    ->with(['author', 'category'])
                    ->orderBy('published_at', 'desc')
                    ->take(4)
                    ->get();
            });
        }

        if ($biographiesCategory) {
            $biographiesArticles = Cache::remember('home_biographies_articles', 600, function () use ($biographiesCategory) {
                return $biographiesCategory->articles()
                    ->with(['author', 'category'])
                    ->orderBy('published_at', 'desc')
                    ->take(4)
                    ->get();
            });
        }
        
        // Get recent articles for sidebar (excluding Hot Celebrities category)
        $recentArticles = Article::published()
            ->with(['category', 'author'])
            ->when($excludedCategoryId, function($query) use ($excludedCategoryId) {
                return $query->where('category_id', '!=', $excludedCategoryId);
            })
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get ALL published articles for top slider (excluding certain categories and specific slugs)
        // Show latest first
        $featuredGuides = Cache::remember('home_featured_guides_slider', 600, function () use ($allExcludedCategoryIds, $excludedSlugs) {
            return Article::published()
                ->with(['category', 'author'])
                ->when(!empty($allExcludedCategoryIds), function($query) use ($allExcludedCategoryIds) {
                    return $query->whereNotIn('category_id', $allExcludedCategoryIds);
                })
                ->whereNotIn('slug', $excludedSlugs)
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function($article) {
                    $article->type = 'article';
                    return $article;
                });
        });

        // Get recent articles for bottom slider (latest first, excluding Hot Celebrities category)
        $recentStories = Cache::remember('home_recent_stories_slider', 600, function () use ($excludedCategoryId) {
            return Article::published()
                ->with(['category', 'author'])
                ->when($excludedCategoryId, function($query) use ($excludedCategoryId) {
                    return $query->where('category_id', '!=', $excludedCategoryId);
                })
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get()
                ->map(function($article) {
                    $article->type = 'article';
                    return $article;
                });
        });



        // Get Top 10 Category (slug: top-10)
        $top10Category = Category::where('slug', 'top-10')->first();
        
        // Get articles from Top 10 category (limit 10)
        $top10Articles = null;
        if ($top10Category) {
            $top10Articles = $top10Category->articles()
                ->with(['author', 'category'])
                ->orderBy('published_at', 'desc')
                ->take(10)
                ->get();
        }

        // Note: Hot Celebrities category already fetched above for exclusion logic
        // Reusing $hotCelebritiesCategoryForExclusion variable
        $hotCelebritiesCategory = $hotCelebritiesCategoryForExclusion;
        
        // Removed fallback logic that was fetching the most popular category (e.g. Korean Dramas)
        // when Hot Celebrities was not found, per user request.

        // Get articles for Hot Celebrities (limit 8)
        $hotCelebritiesArticles = collect();
        if ($hotCelebritiesCategory) {
            $hotCelebritiesArticles = $hotCelebritiesCategory->articles()
                ->with(['author', 'category'])
                ->orderBy('published_at', 'desc')
                ->take(8)
                ->get();
        }
        
        // Get Korean Dramas category
        $koreanDramasCategory = Category::where('slug', 'korean-dramas')
            ->orWhere('name', 'LIKE', '%Korean Drama%')
            ->first();
        
        // Get Instagram Star category
        $instagramStarCategory = Category::where('slug', 'instagram-star')
            ->orWhere('name', 'LIKE', '%Instagram Star%')
            ->first();
        
        // Get Biographies category
        $biographiesCategory = Category::where('slug', 'biographies')
            ->orWhere('name', 'LIKE', '%Biograph%')
            ->first();
        
        // Get articles for each category (limit 4 each)
        $koreanDramasArticles = null;
        $instagramStarArticles = null;
        $biographiesArticles = null;
        
        if ($koreanDramasCategory) {
            $koreanDramasArticles = $koreanDramasCategory->articles()
                ->with(['author', 'category'])
                ->orderBy('published_at', 'desc')
                ->take(4)
                ->get();
        }
        
        if ($instagramStarCategory) {
            $instagramStarArticles = $instagramStarCategory->articles()
                ->with(['author', 'category'])
                ->orderBy('published_at', 'desc')
                ->take(4)
                ->get();
        }
        
        if ($biographiesCategory) {
            $biographiesArticles = $biographiesCategory->articles()
                ->with(['author', 'category'])
                ->orderBy('published_at', 'desc')
                ->take(4)
                ->get();
        }

        return view('home', [
            'articles' => $articles,
            'featuredArticles' => $featuredArticles,
            'popularArticles' => $popularArticles,
            'trendingArticles' => $trendingArticles,
            'recentArticles' => $recentArticles,
            'featuredGuides' => $featuredGuides,
            'recentStories' => $recentStories,
            'categories' => $categories,
            'popularTags' => $popularTags,

            'top10Category' => $top10Category,
            'top10Articles' => $top10Articles,
            'hotCelebritiesCategory' => $hotCelebritiesCategory,
            'hotCelebritiesArticles' => $hotCelebritiesArticles,
            'koreanDramasCategory' => $koreanDramasCategory,
            'koreanDramasArticles' => $koreanDramasArticles,
            'instagramStarCategory' => $instagramStarCategory,
            'instagramStarArticles' => $instagramStarArticles,
            'biographiesCategory' => $biographiesCategory,
            'biographiesArticles' => $biographiesArticles,
            'seo' => $this->seoService->forHome(),
        ]);
    }

    /**
     * Load more articles via AJAX
     */
    public function loadMore(Request $request)
    {
        try {
            $page = (int) $request->get('page', 2);
            $perPage = 30;
            $viewName = $request->get('view', 'articles._load_more');

            $excludedSlugs = ['watch-kdrama', 'watch-movies'];

            $exclusionData = Cache::remember('home_exclusion_data', 1800, function () use ($excludedSlugs) {
                $hotCelebritiesCategoryForExclusion = Category::where('slug', 'hot-celebrity')
                    ->orWhere('name', 'LIKE', '%Hot Celebrities%')
                    ->first();

                $excludedCategoryId = $hotCelebritiesCategoryForExclusion ? $hotCelebritiesCategoryForExclusion->id : null;
                $watchCategoryIds = Category::whereIn('slug', $excludedSlugs)->pluck('id')->toArray();
                $allExcludedCategoryIds = $excludedCategoryId ? array_merge([$excludedCategoryId], $watchCategoryIds) : $watchCategoryIds;

                return [
                    'excludedCategoryId' => $excludedCategoryId,
                    'allExcludedCategoryIds' => $allExcludedCategoryIds,
                ];
            });

            $allExcludedCategoryIds = $exclusionData['allExcludedCategoryIds'];

            $pageArticles = Article::published()
                ->with(['category', 'author', 'tags'])
                ->when(!empty($allExcludedCategoryIds), function($query) use ($allExcludedCategoryIds) {
                    return $query->whereNotIn('category_id', $allExcludedCategoryIds);
                })
                ->whereNotIn('slug', $excludedSlugs)
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            if ($pageArticles->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'html' => '',
                    'hasMore' => false,
                ]);
            }

            $hasMore = $pageArticles->count() === $perPage;

            $html = '';
            foreach ($pageArticles as $article) {
                $articleUrl = route('articles.show', $article->slug);
                $imgHtml = '';
                if ($article->featured_image) {
                    $imgHtml = '<img src="' . e($article->featured_image_url) . '" alt="' . e($article->title) . '" loading="lazy">';
                } else {
                    $imgHtml = '<div class="placeholder-bg"></div>';
                }
                $categoryName = $article->category ? e($article->category->name) : 'Entertainment';
                $title = e(\Illuminate\Support\Str::limit($article->title, 60));
                $excerpt = e(\Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->content), 100));
                $date = $article->published_at ? $article->published_at->format('M d') : 'Recent';
                $readingTime = $article->reading_time ?? 5;

                $html .= '<a href="' . $articleUrl . '" class="ent-card">';
                $html .= '<div class="ent-thumb">' . $imgHtml . '</div>';
                $html .= '<div class="ent-content">';
                $html .= '<div class="ent-category">' . $categoryName . '</div>';
                $html .= '<h3 class="ent-title">' . $title . '</h3>';
                $html .= '<div class="ent-excerpt">' . $excerpt . '</div>';
                $html .= '<div class="ent-meta"><span>' . $date . '</span><span>•</span><span>' . $readingTime . ' min read</span></div>';
                $html .= '</div></a>';
            }

            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $hasMore,
                'currentPage' => $page,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'html' => '',
                'hasMore' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
            ], 500);
        }
    }

    /**
     * Get YouTube subscriber count
     */
    public function getYouTubeSubscribers(YouTubeService $youtubeService, Request $request)
    {
        // Force refresh if requested (for testing)
        $forceRefresh = $request->has('refresh') || $request->has('clear_cache');
        
        $subscriberCount = $youtubeService->getSubscriberCount($forceRefresh);
        $statusMessage = $youtubeService->getStatusMessage();
        
        return response()->json([
            'success' => $subscriberCount !== null,
            'count' => $subscriberCount ?? '100', // Fallback if API fails
            'message' => $statusMessage,
            'configured' => $youtubeService->isConfigured(),
        ]);
    }

    /**
     * Get Facebook follower count
     */
    public function getFacebookFollowers(FacebookService $facebookService, Request $request)
    {
        // Force refresh if requested (for testing)
        $forceRefresh = $request->has('refresh') || $request->has('clear_cache');
        
        $followerCount = $facebookService->getFollowerCount($forceRefresh);
        $statusMessage = $facebookService->getStatusMessage();
        
        return response()->json([
            'success' => $followerCount !== null,
            'count' => $followerCount ?? '100', // Fallback if API fails
            'message' => $statusMessage,
            'configured' => $facebookService->isProfileConfigured(),
        ]);
    }

    /**
     * Get Instagram follower count
     */
    public function getInstagramFollowers(InstagramService $instagramService, Request $request)
    {
        // Force refresh if requested (for testing)
        $forceRefresh = $request->has('refresh') || $request->has('clear_cache');
        
        $followerCount = $instagramService->getFollowerCount($forceRefresh);
        $statusMessage = $instagramService->getStatusMessage();
        
        return response()->json([
            'success' => $followerCount !== null,
            'count' => $followerCount ?? '150K', // Fallback if API fails
            'message' => $statusMessage,
            'configured' => $instagramService->isConfigured(),
        ]);
    }
}

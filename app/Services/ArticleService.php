<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class ArticleService
{
    /**
     * Get featured articles
     */
    public function getFeaturedArticles($limit = 5)
    {
        return Cache::remember("featured_articles_{$limit}", 3600, function () use ($limit) {
            return Article::published()
                ->featured()
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get latest articles
     */
    public function getLatestArticles($limit = 10, $categoryId = null)
    {
        $cacheKey = "latest_articles_{$limit}_" . ($categoryId ?? 'all');
        
        return Cache::remember($cacheKey, 1800, function () use ($limit, $categoryId) {
            $query = Article::published()
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc');
            
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
            
            return $query->limit($limit)->get();
        });
    }

    /**
     * Get popular articles (by views)
     */
    public function getPopularArticles($limit = 10)
    {
        return Cache::remember("popular_articles_{$limit}", 3600, function () use ($limit) {
            return Article::published()
                ->orderBy('views', 'desc')
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get related articles
     */
    public function getRelatedArticles(Article $article, $limit = 5)
    {
        return Cache::remember("related_articles_{$article->id}_{$limit}", 3600, function () use ($article, $limit) {
            return Article::published()
                ->where('id', '!=', $article->id)
                ->where(function($query) use ($article) {
                    // Same category
                    if ($article->category_id) {
                        $query->where('category_id', $article->category_id);
                    }
                })
                ->orWhereHas('tags', function($query) use ($article) {
                    // Same tags
                    $tagIds = $article->tags->pluck('id')->toArray();
                    if (!empty($tagIds)) {
                        $query->whereIn('tags.id', $tagIds);
                    }
                })
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get articles by category
     */
    public function getArticlesByCategory(Category $category, $perPage = 15)
    {
        return Article::published()
            ->where('category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get articles by tag
     */
    public function getArticlesByTag(Tag $tag, $perPage = 15, $page = null)
    {
        $query = Article::published()
            ->whereHas('tags', function($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');
        
        if ($page !== null) {
            return $query->paginate($perPage, ['*'], 'page', $page);
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Search articles
     */
    public function searchArticles($query, $perPage = 15)
    {
        return Article::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all categories with article counts
     */
    public function getCategoriesWithCounts()
    {
        return Cache::remember('categories_with_counts', 3600, function () {
            return Category::where('is_active', true)
                ->withCount(['articles' => function($query) {
                    $query->published();
                }])
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        });
    }

    /**
     * Get popular tags
     */
    public function getPopularTags($limit = 20)
    {
        return Cache::remember("popular_tags_{$limit}", 3600, function () use ($limit) {
            return Tag::withCount('articles')
                ->having('articles_count', '>', 0)
                ->orderBy('articles_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Clear article-related caches
     */
    public function clearCache()
    {
        Cache::forget('featured_articles_5');
        Cache::forget('latest_articles_10_all');
        Cache::forget('popular_articles_10');
        Cache::forget('categories_with_counts');
        Cache::forget('popular_tags_20');
        Cache::forget('home_exclusion_data');
        Cache::forget('home_latest_articles');
        Cache::forget('home_trending_articles');
        Cache::forget('home_recent_articles_sidebar');
        Cache::forget('home_featured_guides');
        Cache::forget('home_recent_stories');
        Cache::forget('home_latest_games');
        Cache::forget('home_latest_apps');
        Cache::forget('home_latest_tools');
        Cache::forget('home_top10_category');
        Cache::forget('home_top10_articles');
        Cache::forget('home_hot_celebrities_category');
        Cache::forget('home_hot_celebrities_articles');
        Cache::forget('home_korean_dramas_category');
        Cache::forget('home_korean_dramas_articles');
        Cache::forget('home_instagram_star_category');
        Cache::forget('home_instagram_star_articles');
        Cache::forget('home_biographies_category');
        Cache::forget('home_biographies_articles');
    }
}


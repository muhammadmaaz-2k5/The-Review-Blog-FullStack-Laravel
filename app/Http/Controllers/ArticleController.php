<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ArticleService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ArticleController extends Controller
{
    protected $articleService;
    protected $seoService;

    public function __construct(ArticleService $articleService, SeoService $seoService)
    {
        $this->articleService = $articleService;
        $this->seoService = $seoService;
    }

    /**
     * Display a listing of articles
     */
    public function index(Request $request)
    {
        $perPage = 15;
        $articles = Article::published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $featuredArticles = $this->articleService->getFeaturedArticles(3);
        $categories = $this->articleService->getCategoriesWithCounts();
        $popularTags = $this->articleService->getPopularTags(10);

        // Get trending articles (most viewed in last 7 days)
        $trendingArticles = Article::published()
            ->with(['category', 'author'])
            ->where('published_at', '>=', now()->subDays(7))
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();
        
        // If no trending articles in last 7 days, get popular articles instead
        if ($trendingArticles->isEmpty()) {
            $trendingArticles = $this->articleService->getPopularArticles(5);
        }

        // Get recent articles for sidebar
        $recentArticles = Article::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get popular articles for trending topics
        $popularArticles = $this->articleService->getPopularArticles(6);

        return view('articles.index', [
            'articles' => $articles,
            'featuredArticles' => $featuredArticles,
            'trendingArticles' => $trendingArticles,
            'recentArticles' => $recentArticles,
            'popularArticles' => $popularArticles,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'seo' => $this->seoService->forArticlesIndex(),
        ]);
    }

    /**
     * Load more articles (AJAX)
     */
    public function loadMore(Request $request)
    {
        $page = $request->get('page', 2);
        $perPage = 15;
        
        $articles = Article::published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        
        if ($articles->isEmpty()) {
            return response()->json([
                'success' => false,
                'html' => '',
                'hasMore' => false,
            ]);
        }
        
        $html = view('articles._load_more_cards', ['articles' => $articles])->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'hasMore' => $articles->hasMorePages(),
            'nextPage' => $articles->currentPage() + 1,
        ]);
    }

    /**
     * Display the specified article
     */
    public function show(Request $request, $slug)
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags', 'likes', 'series', 'comments' => function($query) {
                $query->approved()->with(['replies.user', 'user']);
            }])
            ->firstOrFail();
        
        // Load series articles if article belongs to a series
        $seriesArticles = null;
        $previousArticle = null;
        $nextArticle = null;
        $currentIndex = null;
        
        if ($article->series_id) {
            $seriesArticles = Article::published()
                ->where('series_id', $article->series_id)
                ->orderBy('series_order', 'asc')
                ->get(['id', 'title', 'slug', 'series_order']);
            
            $currentIndex = $seriesArticles->search(function($item) use ($article) {
                return $item->id === $article->id;
            });
            
            if ($currentIndex !== false) {
                if ($currentIndex > 0) {
                    $previousArticle = $seriesArticles[$currentIndex - 1];
                }
                if ($currentIndex < $seriesArticles->count() - 1) {
                    $nextArticle = $seriesArticles[$currentIndex + 1];
                }
            }
        } else {
            // If not in a series, get previous/next by publication date
            $previousArticle = Article::published()
                ->where('published_at', '<', $article->published_at)
                ->orderBy('published_at', 'desc')
                ->first(['id', 'title', 'slug']);

            $nextArticle = Article::published()
                ->where('published_at', '>', $article->published_at)
                ->orderBy('published_at', 'asc')
                ->first(['id', 'title', 'slug']);
        }
        
        // Check if article is liked by current user/IP
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $article->isLikedBy(Auth::id());
        } else {
            $isLiked = $article->isLikedBy(null, request()->ip());
        }
        
        // Check if article is bookmarked by current user
        $isBookmarked = false;
        if (Auth::check()) {
            $isBookmarked = \App\Models\Bookmark::where('user_id', Auth::id())
                ->where('article_id', $article->id)
                ->exists();
        }

        // Increment views
        $article->incrementViews();

        $relatedArticles = $this->articleService->getRelatedArticles($article, 5);
        $featuredArticles = $this->articleService->getFeaturedArticles(5);
        $categories = $this->articleService->getCategoriesWithCounts();
        $popularTags = $this->articleService->getPopularTags(10);

        // Generate CAPTCHA for comment form
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $captchaAnswer = $num1 + $num2;
        Session::put('comment_captcha_answer', $captchaAnswer);
        $captchaQuestion = "$num1 + $num2";

        return view('articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'featuredArticles' => $featuredArticles,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'isLiked' => $isLiked,
            'seriesArticles' => $seriesArticles,
            'previousArticle' => $previousArticle,
            'nextArticle' => $nextArticle,
            'currentSeriesIndex' => $currentIndex !== false ? $currentIndex + 1 : null,
            'totalSeriesArticles' => $seriesArticles ? $seriesArticles->count() : null,
            'isBookmarked' => $isBookmarked,
            'seo' => $this->seoService->forArticle($article),
            'captchaQuestion' => $captchaQuestion,
        ]);
    }
}


<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $articleService;
    protected $seoService;

    public function __construct(ArticleService $articleService, SeoService $seoService)
    {
        $this->articleService = $articleService;
        $this->seoService = $seoService;
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category_id');
        $authorId = $request->get('author_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $tagId = $request->get('tag_id');
        $seriesId = $request->get('series_id');
        $isFeatured = $request->get('is_featured');
        $allowComments = $request->get('allow_comments');
        $yearFrom = $request->get('year_from');
        $yearTo = $request->get('year_to');
        $readingTimeMin = $request->get('reading_time_min');
        $readingTimeMax = $request->get('reading_time_max');
        $viewsMin = $request->get('views_min');
        $viewsMax = $request->get('views_max');
        $orderBy = $request->get('order_by', 'popularity');
        $type = $request->get('type'); // Filter by type: article, app, game, tool

        $results = [];
        
        // Search Articles
        if (!$type || $type === 'article') {
            $articlesQuery = \App\Models\Article::published();

            // Search query
            if ($query) {
                $articlesQuery->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('excerpt', 'like', "%{$query}%");
                });
            }

            // Filter by category
            if ($categoryId) {
                $articlesQuery->where('category_id', $categoryId);
            }

            // Filter by author
            if ($authorId) {
                $articlesQuery->where('author_id', $authorId);
            }

            // Filter by date range
            if ($dateFrom) {
                $articlesQuery->whereDate('published_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $articlesQuery->whereDate('published_at', '<=', $dateTo);
            }

            // Filter by year range
            if ($yearFrom) {
                $articlesQuery->whereYear('published_at', '>=', $yearFrom);
            }
            if ($yearTo) {
                $articlesQuery->whereYear('published_at', '<=', $yearTo);
            }

            // Filter by tag
            if ($tagId) {
                $articlesQuery->whereHas('tags', function($q) use ($tagId) {
                    $q->where('tags.id', $tagId);
                });
            }

            // Filter by series
            if ($seriesId) {
                $articlesQuery->where('series_id', $seriesId);
            }

            // Filter by featured
            if ($isFeatured) {
                $articlesQuery->where('is_featured', true);
            }

            // Filter by comments enabled
            if ($allowComments) {
                $articlesQuery->where('allow_comments', true);
            }

            // Filter by reading time
            if ($readingTimeMin) {
                $articlesQuery->where('reading_time', '>=', $readingTimeMin);
            }
            if ($readingTimeMax) {
                $articlesQuery->where('reading_time', '<=', $readingTimeMax);
            }

            // Filter by views
            if ($viewsMin) {
                $articlesQuery->where('views', '>=', $viewsMin);
            }
            if ($viewsMax) {
                $articlesQuery->where('views', '<=', $viewsMax);
            }

            // Order by
            switch ($orderBy) {
                case 'date':
                    $articlesQuery->orderBy('published_at', 'desc');
                    break;
                case 'date_old':
                    $articlesQuery->orderBy('published_at', 'asc');
                    break;
                case 'views':
                    $articlesQuery->orderBy('views', 'desc');
                    break;
                case 'title':
                    $articlesQuery->orderBy('title', 'asc');
                    break;
                case 'popularity':
                default:
                    $articlesQuery->orderBy('views', 'desc')
                                ->orderBy('published_at', 'desc');
                    break;
            }

            $results['articles'] = $articlesQuery->with(['category', 'author', 'tags'])
                ->paginate(15);
        }



        $categories = $this->articleService->getCategoriesWithCounts();
        $popularTags = $this->articleService->getPopularTags(20);
        $authors = \App\Models\User::where('is_author', true)->orWhere('role', 'author')->get();

        return view('search.index', [
            'results' => $results,
            'query' => $query,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'authors' => $authors,
            'selectedCategory' => $categoryId,
            'selectedAuthor' => $authorId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'selectedType' => $type,
            'seo' => $this->seoService->forSearch($query),
        ]);
    }

    /**
     * Load more search results (AJAX)
     */
    public function loadMore(Request $request)
    {
        $page = $request->get('page', 2);
        $query = $request->get('q');
        $categoryId = $request->get('category_id');
        $authorId = $request->get('author_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $tagId = $request->get('tag_id');
        $seriesId = $request->get('series_id');
        $isFeatured = $request->get('is_featured');
        $allowComments = $request->get('allow_comments');
        $yearFrom = $request->get('year_from');
        $yearTo = $request->get('year_to');
        $readingTimeMin = $request->get('reading_time_min');
        $readingTimeMax = $request->get('reading_time_max');
        $viewsMin = $request->get('views_min');
        $viewsMax = $request->get('views_max');
        $orderBy = $request->get('order_by', 'popularity');

        $articlesQuery = \App\Models\Article::published();

        // Apply all filters (same as search method)
        if ($query) {
            $articlesQuery->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            });
        }
        if ($categoryId) $articlesQuery->where('category_id', $categoryId);
        if ($authorId) $articlesQuery->where('author_id', $authorId);
        if ($dateFrom) $articlesQuery->whereDate('published_at', '>=', $dateFrom);
        if ($dateTo) $articlesQuery->whereDate('published_at', '<=', $dateTo);
        if ($yearFrom) $articlesQuery->whereYear('published_at', '>=', $yearFrom);
        if ($yearTo) $articlesQuery->whereYear('published_at', '<=', $yearTo);
        if ($tagId) {
            $articlesQuery->whereHas('tags', function($q) use ($tagId) {
                $q->where('tags.id', $tagId);
            });
        }
        if ($seriesId) $articlesQuery->where('series_id', $seriesId);
        if ($isFeatured) $articlesQuery->where('is_featured', true);
        if ($allowComments) $articlesQuery->where('allow_comments', true);
        if ($readingTimeMin) $articlesQuery->where('reading_time', '>=', $readingTimeMin);
        if ($readingTimeMax) $articlesQuery->where('reading_time', '<=', $readingTimeMax);
        if ($viewsMin) $articlesQuery->where('views', '>=', $viewsMin);
        if ($viewsMax) $articlesQuery->where('views', '<=', $viewsMax);

        // Order by
        switch ($orderBy) {
            case 'date':
                $articlesQuery->orderBy('published_at', 'desc');
                break;
            case 'date_old':
                $articlesQuery->orderBy('published_at', 'asc');
                break;
            case 'views':
                $articlesQuery->orderBy('views', 'desc');
                break;
            case 'title':
                $articlesQuery->orderBy('title', 'asc');
                break;
            default:
                $articlesQuery->orderBy('views', 'desc')->orderBy('published_at', 'desc');
        }

        $articles = $articlesQuery->with(['category', 'author', 'tags'])
            ->paginate(15, ['*'], 'page', $page);
        
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
}

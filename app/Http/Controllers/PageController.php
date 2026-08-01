<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Career;
use App\Services\SeoService;
use App\Services\SitemapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    protected $seoService;
    protected $sitemapService;

    public function __construct(SeoService $seoService, SitemapService $sitemapService)
    {
        $this->seoService = $seoService;
        $this->sitemapService = $sitemapService;
    }

    public function about()
    {
        return view('pages.about', [
            'seo' => $this->seoService->forPage('about'),
        ]);
    }

    public function contact(Request $request)
    {
        $subject = $request->get('subject', '');
        
        return view('pages.contact', [
            'seo' => $this->seoService->forPage('contact'),
            'presetSubject' => $subject,
        ]);
    }

    public function privacy()
    {
        return view('pages.privacy', [
            'seo' => $this->seoService->forPage('privacy'),
        ]);
    }

    public function disclaimer()
    {
        return view('pages.disclaimer', [
            'seo' => $this->seoService->forPage('disclaimer'),
        ]);
    }



    public function sitemaps()
    {
        try {
            $allUrls = $this->sitemapService->getAllUrls();
        } catch (\Exception $e) {
            // If sitemap service fails, use empty arrays
            Log::error('Sitemap service error: ' . $e->getMessage());
            $allUrls = [
                'pages' => [],
                'articles' => [],
                'categories' => [],
                'tags' => [],
            ];
        }
        
        return view('pages.sitemaps', [
            'seo' => $this->seoService->forPage('sitemaps'),
            'pages' => $allUrls['pages'] ?? [],
            'articles' => $allUrls['articles'] ?? [],
            'categories' => $allUrls['categories'] ?? [],
            'tags' => $allUrls['tags'] ?? [],
        ]);
    }

    public function feedback()
    {
        return view('pages.coming-soon', [
            'seo' => $this->seoService->forPage('feedback'),
            'pageTitle' => 'Feedback',
            'pageDescription' => 'We\'re working on a feedback system to better serve our community. Stay tuned!',
        ]);
    }

    // Advertise method removed

    public function archives(Request $request)
    {
        $selectedYear = $request->get('year');
        $selectedMonth = $request->get('month');
        
        // Get all published articles with dates
        $articlesQuery = Article::published()
            ->with(['category', 'author'])
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc');
        
        // Filter by year if selected
        if ($selectedYear) {
            $articlesQuery->whereYear('published_at', $selectedYear);
        }
        
        // Filter by month if selected
        if ($selectedMonth) {
            $articlesQuery->whereMonth('published_at', $selectedMonth);
        }
        
        // Get articles grouped by year and month
        $articlesByYearMonth = Article::published()
            ->whereNotNull('published_at')
            ->select(
                DB::raw('YEAR(published_at) as year'),
                DB::raw('MONTH(published_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        // Get all unique years
        $years = Article::published()
            ->whereNotNull('published_at')
            ->select(DB::raw('YEAR(published_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        // Get paginated articles
        $articles = $articlesQuery->paginate(20)->withQueryString();
        
        // Organize articles by year/month for display
        $groupedArticles = [];
        foreach ($articles as $article) {
            $year = $article->published_at->format('Y');
            $month = $article->published_at->format('F Y');
            
            if (!isset($groupedArticles[$year])) {
                $groupedArticles[$year] = [];
            }
            if (!isset($groupedArticles[$year][$month])) {
                $groupedArticles[$year][$month] = [];
            }
            $groupedArticles[$year][$month][] = $article;
        }
        
        return view('pages.archives', [
            'seo' => $this->seoService->forPage('archives'),
            'articles' => $articles,
            'groupedArticles' => $groupedArticles,
            'articlesByYearMonth' => $articlesByYearMonth,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
        ]);
    }

    public function ethics()
    {
        return view('pages.ethics', [
            'seo' => $this->seoService->forPage('ethics'),
        ]);
    }

    public function editorialPolicy()
    {
        return view('pages.editorial-policy', [
            'seo' => $this->seoService->forPage('editorial-policy'),
        ]);
    }

    public function complaintRedressal()
    {
        return view('pages.complaint-redressal', [
            'seo' => $this->seoService->forPage('complaint-redressal'),
        ]);
    }

    public function rss()
    {
        $categories = Category::where('is_active', true)->get();
        return view('pages.rss', [
            'seo' => $this->seoService->forPage('rss'),
            'categories' => $categories,
        ]);
    }

    public function careers(Request $request)
    {
        $query = Career::active();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Department filter
        if ($request->has('department') && $request->department) {
            $query->where('department', $request->department);
        }

        // Experience level filter
        if ($request->has('experience') && $request->experience) {
            $query->where('experience_level', $request->experience);
        }

        $careers = $query->orderBy('is_featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $departments = Career::active()
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->filter()
            ->sort()
            ->values();

        return view('pages.careers', [
            'seo' => $this->seoService->forPage('careers'),
            'careers' => $careers,
            'departments' => $departments,
        ]);
    }

    public function careerShow($slug)
    {
        $career = Career::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $relatedCareers = Career::where('id', '!=', $career->id)
            ->where(function($q) use ($career) {
                $q->where('department', $career->department)
                  ->orWhere('type', $career->type);
            })
            ->active()
            ->limit(3)
            ->get();

        return view('pages.career-show', [
            'seo' => $this->seoService->forPage('career', [
                'title' => $career->title,
                'description' => strip_tags($career->description),
            ]),
            'career' => $career,
            'relatedCareers' => $relatedCareers,
        ]);
    }

    public function switchLanguage(Request $request, $locale)
    {
        // Validate locale
        $allowedLocales = ['en', 'hi'];
        if (!in_array($locale, $allowedLocales)) {
            $locale = 'en';
        }

        // Store locale in session
        session(['locale' => $locale]);
        
        // Set app locale
        app()->setLocale($locale);

        // Redirect back to previous page or home
        $previousUrl = url()->previous();
        if ($previousUrl == url()->current()) {
            return redirect()->route('home')->with('success', __('messages.language_changed'));
        }
        
        return redirect()->back()->with('success', __('messages.language_changed'));
    }
}


<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageSeoController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\ClerkAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AuthorDashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\AnalyticsTrackingController;
use App\Http\Controllers\Admin\AnalyticsController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Favicon route - serve icon.png for favicon.ico requests
Route::get('/favicon.ico', function () {
    $iconPath = public_path('icon.png');
    if (file_exists($iconPath)) {
        return response()->file($iconPath, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
    abort(404);
});

// Component output analysis test
Route::get('/test-component-output', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-component-output', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-component-output', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});

// Direct component test for featured video
Route::get('/test-direct-component', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-direct-component', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-direct-component', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});

// Render test for featured video
Route::get('/test-featured-video-render', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-featured-video-render', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-featured-video-render', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});

// Final test for featured video with full layout integration
Route::get('/test-featured-video-final', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-featured-video-final', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-featured-video-final', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});

// Bypass test for featured video (no session storage)
Route::get('/test-featured-video-bypass', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-featured-video-bypass', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-featured-video-bypass', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});

// JavaScript debug test for featured video
Route::get('/test-featured-video-debug-js', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-featured-video-debug-js', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-featured-video-debug-js', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});

// Simple test featured video system (outside admin to avoid auth issues)
Route::get('/test-featured-video-simple', function () {
    try {
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-featured-video-simple', compact('randomFeaturedVideo'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-featured-video-simple', [
            'randomFeaturedVideo' => null
        ], 500);
    }
});

// Detailed debug featured video system (outside admin to avoid auth issues)
Route::get('/debug-featured-video-detailed', function () {
    try {
        // Try to get video count
        $totalVideos = App\Models\FeaturedVideo::count();
        
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('debug-featured-video-detailed', compact('randomFeaturedVideo', 'totalVideos'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('debug-featured-video-detailed', [
            'randomFeaturedVideo' => null,
            'totalVideos' => 'Database Error: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/download/playbox', function () {
    $filePath = public_path('PLAYBOX.apk');
    if (!file_exists($filePath)) {
        abort(404, 'PLAYBOX.apk not found in public directory.');
    }
    return response()->download($filePath, 'PLAYBOX.apk', [
        'Content-Type' => 'application/vnd.android.package-archive',
    ]);
})->name('download.playbox');



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/articles/load-more', [HomeController::class, 'loadMore'])->name('articles.load-more');
Route::get('/api/categories/{slug}/load-more', [CategoryController::class, 'loadMore'])->name('categories.load-more');
Route::get('/api/tags/{slug}/load-more', [TagController::class, 'loadMore'])->name('tags.load-more');
Route::get('/api/search/load-more', [SearchController::class, 'loadMore'])->name('search.load-more');
Route::get('/api/articles-index/load-more', [ArticleController::class, 'loadMore'])->name('articles-index.load-more');
Route::get('/api/profile/{username}/load-more', [ProfileController::class, 'loadMore'])->name('profile.load-more');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login/{any?}', [AuthController::class, 'showLoginForm'])->where('any', '.*')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register/{any?}', [AuthController::class, 'showRegisterForm'])->where('any', '.*')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.store');
    
    // Clerk Authentication Route
    Route::post('/auth/clerk', [ClerkAuthController::class, 'authenticate'])->name('clerk.authenticate');
    
    // Social Authentication Routes (optional - requires Laravel Socialite)
    // Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    // Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Email Verification Routes
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    
    // User Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/dashboard/request-author', [UserDashboardController::class, 'requestAuthor'])->name('user.request-author');
    
    // Author Dashboard
    Route::prefix('author')->middleware('author')->name('author.')->group(function () {
        Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');
    });
});

// SEO routes (must be before other routes for proper matching)
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

// RSS Feed routes
Route::get('/feed', [App\Http\Controllers\RssFeedController::class, 'index'])->name('feed');
Route::get('/feed.xml', [App\Http\Controllers\RssFeedController::class, 'index'])->name('feed.xml'); // Alternative route for Yandex and other services
Route::get('/rss.xml', [App\Http\Controllers\RssFeedController::class, 'index']); // Alternative route

Route::get('/feed/category/{slug}', [App\Http\Controllers\RssFeedController::class, 'category'])->name('feed.category');
Route::get('/feed/author/{username}', [App\Http\Controllers\RssFeedController::class, 'author'])->name('feed.author');

// Sitemaps page (HTML) - must be before sitemap.xml routes
Route::get('/sitemaps', [PageController::class, 'sitemaps'])->name('sitemaps')->middleware('web');

// Sitemap routes - /sitemap.xml is the main sitemap index
Route::get('/sitemap.xml', [SitemapController::class, 'sitemapIndex'])->name('sitemap.index');
Route::get('/sitemap/home.xml', [SitemapController::class, 'home'])->name('sitemap.home');
Route::get('/sitemap/pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap/articles.xml', [SitemapController::class, 'articles'])->name('sitemap.articles');

Route::get('/sitemap/categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap/tags.xml', [SitemapController::class, 'tags'])->name('sitemap.tags');
Route::get('/sitemap/series.xml', [SitemapController::class, 'series'])->name('sitemap.series');
Route::get('/sitemap/profiles.xml', [SitemapController::class, 'profiles'])->name('sitemap.profiles');



// Article routes
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// Article actions (must come before slug route to avoid conflicts)
Route::post('/articles/{article}/like', [App\Http\Controllers\ArticleLikeController::class, 'toggle'])->name('articles.like');
Route::get('/comments/captcha', [CommentController::class, 'refreshCaptcha'])->name('comments.captcha');
Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/articles/{article}/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

// Article show route (must be last to avoid conflicts)
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');



// AMP routes
Route::get('/amp/articles/{slug}', [App\Http\Controllers\AmpController::class, 'article'])->name('amp.article');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Tag routes
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{slug}', [TagController::class, 'show'])->name('tags.show');

// Series routes
Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
Route::get('/series/{slug}', [SeriesController::class, 'show'])->name('series.show');

// Profile routes
Route::get('/profile/{username}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/{username}/articles', [ProfileController::class, 'articles'])->name('profile.articles');

// Bookmark routes (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/articles/{article}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::post('/articles/{article}/bookmark/store', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::put('/bookmarks/{bookmark}', [BookmarkController::class, 'update'])->name('bookmarks.update');
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    Route::delete('/articles/{article}/bookmark', [BookmarkController::class, 'removeByArticle'])->name('bookmarks.remove-by-article');
});

// Follow routes (authenticated)
Route::middleware('auth')->group(function () {
    Route::post('/profile/{user}/follow', [FollowController::class, 'follow'])->name('profile.follow');
    Route::delete('/profile/{user}/unfollow', [FollowController::class, 'unfollow'])->name('profile.unfollow');
    Route::post('/profile/{user}/toggle-follow', [FollowController::class, 'toggle'])->name('profile.toggle-follow');
    Route::get('/profile/{username}/followers', [FollowController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{username}/following', [FollowController::class, 'following'])->name('profile.following');
    
    // Profile edit routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Activity feed routes
    Route::get('/activity', [ActivityController::class, 'timeline'])->name('activity.timeline');
    Route::get('/profile/{username}/activity', [ActivityController::class, 'index'])->name('profile.activity');
});

// Analytics Tracking (public endpoints for JavaScript)
// GET routes for crawlers/bots (must be defined BEFORE POST routes)
// These endpoints should not be indexed - return 204 No Content
Route::get('/analytics/track/view', function() {
    return response('', 204)
        ->header('X-Robots-Tag', 'noindex, nofollow'); // Prevent indexing
});
Route::get('/analytics/track/time', function() {
    return response('', 204)
        ->header('X-Robots-Tag', 'noindex, nofollow');
});
Route::get('/analytics/track/event', function() {
    return response('', 204)
        ->header('X-Robots-Tag', 'noindex, nofollow');
});

// POST routes for actual tracking (called via JavaScript/AJAX)
Route::post('/analytics/track/view', [AnalyticsTrackingController::class, 'trackView'])->name('analytics.track.view');
Route::post('/analytics/track/time', [AnalyticsTrackingController::class, 'trackTimeOnPage'])->name('analytics.track.time');
Route::post('/analytics/track/event', [AnalyticsTrackingController::class, 'trackEvent'])->name('analytics.track.event');

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');

// YouTube Subscriber Count API
Route::get('/api/youtube/subscribers', [HomeController::class, 'getYouTubeSubscribers'])->name('api.youtube.subscribers');
Route::get('/api/facebook/followers', [HomeController::class, 'getFacebookFollowers'])->name('api.facebook.followers');
Route::get('/api/instagram/followers', [HomeController::class, 'getInstagramFollowers'])->name('api.instagram.followers');

// Static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::redirect('/terms', '/privacy')->name('terms');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');
Route::get('/how-to-circle', [PageController::class, 'howToCircle'])->name('how-to-circle');
Route::get('/feedback', [PageController::class, 'feedback'])->name('feedback');
Route::get('/archives', [PageController::class, 'archives'])->name('archives');
// Advertise page removed
Route::get('/ethics', [PageController::class, 'ethics'])->name('ethics');
Route::get('/editorial-policy', [PageController::class, 'editorialPolicy'])->name('editorial-policy');
Route::get('/complaint-redressal', [PageController::class, 'complaintRedressal'])->name('complaint-redressal');
Route::get('/rss', [PageController::class, 'rss'])->name('rss');
Route::get('/language/{locale}', [PageController::class, 'switchLanguage'])->name('language.switch');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/careers/{slug}', [PageController::class, 'careerShow'])->name('careers.show');

// Tips routes
Route::get('/tips', [App\Http\Controllers\TipController::class, 'create'])->name('tips.create');
Route::post('/tips', [App\Http\Controllers\TipController::class, 'store'])->name('tips.store');

// Author article management routes (must be BEFORE admin routes to allow authors access)
Route::prefix('admin')->middleware(['auth', 'author'])->name('admin.')->group(function () {
    // Article routes for authors (create, store, index, edit, update)
    Route::get('articles', [App\Http\Controllers\Admin\ArticleController::class, 'index'])->name('articles.index');
    Route::get('articles/create', [App\Http\Controllers\Admin\ArticleController::class, 'create'])->name('articles.create');
    Route::post('articles', [App\Http\Controllers\Admin\ArticleController::class, 'store'])->name('articles.store');
    Route::get('articles/{article}/edit', [App\Http\Controllers\Admin\ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('articles/{article}', [App\Http\Controllers\Admin\ArticleController::class, 'update'])->name('articles.update');
    Route::patch('articles/{article}', [App\Http\Controllers\Admin\ArticleController::class, 'update']);
    
    // Article auto-save (for authors)
    Route::post('articles/{article?}/auto-save', [App\Http\Controllers\Admin\ArticleController::class, 'autoSave'])->name('articles.auto-save');
    
    // Article revisions (authors can view their own)
    Route::get('articles/{article}/revisions', [App\Http\Controllers\Admin\ArticleRevisionController::class, 'index'])->name('articles.revisions');
    Route::get('articles/{article}/revisions/{revision}', [App\Http\Controllers\Admin\ArticleRevisionController::class, 'show'])->name('articles.revisions.show');
    
    // SEO Audit
    Route::get('articles/{article}/seo-audit', [App\Http\Controllers\Admin\SeoAuditController::class, 'show'])->name('articles.seo-audit');
    Route::get('articles/{article}/seo-audit/json', [App\Http\Controllers\Admin\SeoAuditController::class, 'getAudit'])->name('articles.seo-audit.json');

    // Article Image Upload (for TinyMCE)
    Route::post('articles/upload-image', [App\Http\Controllers\Admin\ArticleController::class, 'uploadImage'])->name('articles.upload-image');
});

// Admin routes - Protected with authentication and admin middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Article Facebook posting (must be before resource route)
    Route::post('articles/{id}/post-to-facebook', [App\Http\Controllers\Admin\ArticleController::class, 'postToFacebook'])->name('articles.post-to-facebook');
    
    // Article management (full resource - includes destroy which only admins can do)
    Route::resource('articles', AdminArticleController::class)->except(['index', 'create', 'store', 'edit', 'update']);
    
    // Article revisions
    Route::get('articles/{article}/revisions', [App\Http\Controllers\Admin\ArticleRevisionController::class, 'index'])->name('articles.revisions');
    Route::get('articles/{article}/revisions/{revision}', [App\Http\Controllers\Admin\ArticleRevisionController::class, 'show'])->name('articles.revisions.show');
    Route::get('articles/{article}/revisions/{revision1}/compare/{revision2?}', [App\Http\Controllers\Admin\ArticleRevisionController::class, 'compare'])->name('articles.revisions.compare');
    Route::post('articles/{article}/revisions/{revision}/restore', [App\Http\Controllers\Admin\ArticleRevisionController::class, 'restore'])->name('articles.revisions.restore');
    
    // Category management
    Route::resource('categories', AdminCategoryController::class);
    
    // Tag management
    Route::resource('tags', AdminTagController::class);
    
    // Series management
    Route::resource('series', App\Http\Controllers\Admin\SeriesController::class);
    // Series article management (must be after resource route)
    Route::post('series/{series}/add-article', [App\Http\Controllers\Admin\SeriesController::class, 'addArticle'])->name('series.add-article');
    Route::post('series/{series}/update-article-order', [App\Http\Controllers\Admin\SeriesController::class, 'updateArticleOrder'])->name('series.update-article-order');
    Route::delete('series/{series}/articles/{article}', [App\Http\Controllers\Admin\SeriesController::class, 'removeArticle'])->name('series.remove-article');
    
    // Settings management
    Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/facebook', [App\Http\Controllers\Admin\SettingsController::class, 'updateFacebook'])->name('settings.facebook.update');
    Route::post('settings/facebook/test', [App\Http\Controllers\Admin\SettingsController::class, 'testFacebook'])->name('settings.facebook.test');
    Route::post('settings/twitter', [App\Http\Controllers\Admin\SettingsController::class, 'updateTwitter'])->name('settings.twitter.update');
    Route::post('settings/twitter/test', [App\Http\Controllers\Admin\SettingsController::class, 'testTwitter'])->name('settings.twitter.test');
    Route::post('settings/instagram', [App\Http\Controllers\Admin\SettingsController::class, 'updateInstagram'])->name('settings.instagram.update');
    Route::post('settings/instagram/test', [App\Http\Controllers\Admin\SettingsController::class, 'testInstagram'])->name('settings.instagram.test');
    Route::post('settings/threads', [App\Http\Controllers\Admin\SettingsController::class, 'updateThreads'])->name('settings.threads.update');
    Route::post('settings/threads/test', [App\Http\Controllers\Admin\SettingsController::class, 'testThreads'])->name('settings.threads.test');
    
    // Author management
    Route::get('authors', [App\Http\Controllers\Admin\AuthorController::class, 'index'])->name('authors.index');
    // Author requests routes (must come before {author} route to avoid conflicts)
    Route::get('authors/requests', [App\Http\Controllers\Admin\AuthorController::class, 'requests'])->name('authors.requests');
    Route::post('authors/requests/{request}/approve', [App\Http\Controllers\Admin\AuthorController::class, 'approveRequest'])->name('authors.requests.approve');
    Route::post('authors/requests/{request}/reject', [App\Http\Controllers\Admin\AuthorController::class, 'rejectRequest'])->name('authors.requests.reject');
    // Author detail routes (must come after requests routes)
    Route::get('authors/{author}', [App\Http\Controllers\Admin\AuthorController::class, 'show'])->name('authors.show');
    Route::put('authors/{author}/permissions', [App\Http\Controllers\Admin\AuthorController::class, 'updatePermissions'])->name('authors.update-permissions');
    Route::delete('authors/{author}/remove-status', [App\Http\Controllers\Admin\AuthorController::class, 'removeAuthorStatus'])->name('authors.remove-status');
    
    // Public Pages SEO Management
    Route::resource('page-seo', PageSeoController::class);
    
    // Article Scraper
    Route::post('articles/scrape', [App\Http\Controllers\Admin\ArticleController::class, 'scrape'])->name('articles.scrape');
    
    // Analytics Dashboard
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/realtime', [AnalyticsController::class, 'realTime'])->name('analytics.realtime');
    Route::get('articles/{article}/analytics', [AnalyticsController::class, 'articlePerformance'])->name('articles.analytics');
    Route::get('analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    
    // Contact Messages Management
    Route::get('contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::post('contacts/{contact}/mark-read', [App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('contacts.mark-read');
    Route::post('contacts/{contact}/reply', [App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('contacts.reply');
    Route::post('contacts/bulk-action', [App\Http\Controllers\Admin\ContactController::class, 'bulkAction'])->name('contacts.bulk-action');
    Route::delete('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    
    // Tips Management
    Route::get('tips', [App\Http\Controllers\Admin\TipController::class, 'index'])->name('tips.index');
    Route::get('tips/{tip}', [App\Http\Controllers\Admin\TipController::class, 'show'])->name('tips.show');
    Route::patch('tips/{tip}/status', [App\Http\Controllers\Admin\TipController::class, 'updateStatus'])->name('tips.update-status');
    Route::delete('tips/{tip}', [App\Http\Controllers\Admin\TipController::class, 'destroy'])->name('tips.destroy');
    
    // Careers Management
    Route::resource('careers', App\Http\Controllers\Admin\CareerController::class);
    
    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Comments Moderation
    Route::get('comments', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
    Route::post('comments/{comment}/approve', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/reject', [App\Http\Controllers\Admin\CommentController::class, 'reject'])->name('comments.reject');
    Route::post('comments/{comment}/spam', [App\Http\Controllers\Admin\CommentController::class, 'markSpam'])->name('comments.mark-spam');
    Route::put('comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'update'])->name('comments.update');
    Route::post('comments/bulk-action', [App\Http\Controllers\Admin\CommentController::class, 'bulkAction'])->name('comments.bulk-action');
    Route::delete('comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');

    // Media Management
    Route::get('media', [App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
    Route::delete('media', [App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');

    // Featured Videos Management
    Route::resource('featured-videos', App\Http\Controllers\Admin\FeaturedVideoController::class);
    Route::post('featured-videos/{featured_video}/toggle-status', [App\Http\Controllers\Admin\FeaturedVideoController::class, 'toggleStatus'])->name('featured-videos.toggle-status');



    // Ad Management - Removed
});

// Debug featured video system (outside admin to avoid auth issues)
Route::get('/debug-featured-video', function () {
    try {
        // Try to get video count
        $totalVideos = App\Models\FeaturedVideo::count();
        
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('debug-featured-video', compact('randomFeaturedVideo', 'totalVideos'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('debug-featured-video', [
            'randomFeaturedVideo' => null,
            'totalVideos' => 'Database Error: ' . $e->getMessage()
        ], 500);
    }
});
Route::get('/test-featured-video', function () {
    try {
        // Try to get video count
        $totalVideos = App\Models\FeaturedVideo::count();
        
        // Try to get a random active video
        $randomFeaturedVideo = App\Models\FeaturedVideo::where('is_active', true)
            ->inRandomOrder()
            ->first();
            
        return view('test-featured-video', compact('randomFeaturedVideo', 'totalVideos'));
    } catch (\Exception $e) {
        // If database fails, show error page
        return response()->view('test-featured-video', [
            'randomFeaturedVideo' => null,
            'totalVideos' => 'Database Error: ' . $e->getMessage()
        ], 500);
    }
});


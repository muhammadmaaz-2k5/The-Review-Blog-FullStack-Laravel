<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\FeaturedVideo;
use App\Policies\ArticlePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Category::class => CategoryPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix URL generation to remove /public from URLs
        $appUrl = config('app.url');
        if ($appUrl) {
            // Remove /public suffix if it exists
            $appUrl = rtrim($appUrl, '/');
            $appUrl = preg_replace('#/public$#', '', $appUrl);
            URL::forceRootUrl($appUrl);
        }

        // For local development on Windows: Fix SSL certificate issues with Firebase
        // This is a workaround for "unable to get local issuer certificate" error
        if (config('app.env') === 'local' || config('app.debug')) {
            // Set cURL CA info to empty string to disable SSL verification
            // This is safe for local development only
            ini_set('curl.cainfo', '');
            
            // Note: For production, ensure proper CA certificates are installed
            // and php.ini is configured with: curl.cainfo = "/path/to/cacert.pem"
        }

        // Share a random featured video with all views using a safe composer
        View::composer('*', \App\View\Composers\FeaturedVideoSafeComposer::class);
    }
}

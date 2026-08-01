<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;

use Illuminate\Http\Request;

class RssFeedController extends Controller
{
    /**
     * Display the main RSS feed
     */
    public function index()
    {
        $articles = Article::published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->limit(20)
            ->get();

        $siteUrl = config('app.url');
        $siteName = config('app.name');
        $logoUrl = asset('icon.png'); // You can change this to your logo path

        $content = view('feed.rss', [
                'articles' => $articles,
            'title' => $siteName . ' - Latest entertainment news',
            'description' => $siteName . ' is your ultimate destination for everything entertainment. This feed contains the latest drama reviews, movie blockbusters, and celebrity news in chronological order.',
            'link' => $siteUrl,
            'siteUrl' => $siteUrl,
            'siteName' => $siteName,
            'logoUrl' => $logoUrl,
            'copyright' => 'Copyright ' . date('Y') . ' ' . $siteName . '. All rights reserved.',
        ])->render();

        // Prepend XML declaration
        $xmlContent = '<?xml version="1.0" encoding="utf-8"?>' . "\n" . $content;

        return response($xmlContent)
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }



    /**
     * Display RSS feed for a specific category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $articles = Article::published()
            ->where('category_id', $category->id)
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->limit(20)
            ->get();

        $siteUrl = config('app.url');
        $siteName = config('app.name');
        $logoUrl = asset('icon.png');

        $content = view('feed.rss', [
                'articles' => $articles,
            'title' => $siteName . ' - ' . $category->name,
            'description' => 'Latest articles in ' . $category->name . ' from ' . $siteName . '. This feed contains the latest articles in chronological order.',
                'link' => route('categories.show', $category->slug),
            'siteUrl' => $siteUrl,
            'siteName' => $siteName,
            'logoUrl' => $logoUrl,
            'copyright' => 'Copyright ' . date('Y') . ' ' . $siteName . '. All rights reserved.',
        ])->render();

        // Prepend XML declaration
        $xmlContent = '<?xml version="1.0" encoding="utf-8"?>' . "\n" . $content;

        return response($xmlContent)
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }

    /**
     * Display RSS feed for a specific author
     */
    public function author($username)
    {
        $author = User::where('username', $username)->where('is_author', true)->firstOrFail();

        $articles = Article::published()
            ->where('author_id', $author->id)
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->limit(20)
            ->get();

        $siteUrl = config('app.url');
        $siteName = config('app.name');
        $logoUrl = asset('icon.png');

        $content = view('feed.rss', [
                'articles' => $articles,
            'title' => $siteName . ' - Articles by ' . $author->name,
            'description' => 'Latest articles by ' . $author->name . ' from ' . $siteName . '. This feed contains the latest articles in chronological order.',
                'link' => route('profile.show', $author->username),
            'siteUrl' => $siteUrl,
            'siteName' => $siteName,
            'logoUrl' => $logoUrl,
            'copyright' => 'Copyright ' . date('Y') . ' ' . $siteName . '. All rights reserved.',
        ])->render();

        // Prepend XML declaration
        $xmlContent = '<?xml version="1.0" encoding="utf-8"?>' . "\n" . $content;

        return response($xmlContent)
            ->header('Content-Type', 'application/rss+xml; charset=utf-8');
    }
}

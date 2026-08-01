<?php

namespace App\Services;

use App\Helpers\SchemaHelper;
use App\Models\PageSeo;
use App\Models\Article;
use App\Models\Category;
use App\Models\Series;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Str;

class SeoService
{
    protected $siteName;
    protected $siteUrl;
    protected $defaultImage;
    protected $twitterHandle;
    protected $facebookAppId;

    public function __construct()
    {
        $this->siteName = config('app.name', 'Nazaara Circle');
        $this->siteUrl = config('app.url', url('/'));
        $this->defaultImage = asset('icon.png');
        $this->twitterHandle = '@nazaaracircle'; // Update with your Twitter handle
        $this->facebookAppId = ''; // Add your Facebook App ID if available
    }

    /**
     * Generate SEO metadata for a page
     * Checks for admin-managed PageSeo first, then uses provided data or defaults
     */
    public function generate(array $data = [], ?string $pageKey = null): array
    {
        // Check for admin-managed PageSeo first (always get fresh data)
        if ($pageKey) {
            $pageSeo = PageSeo::getByPageKey($pageKey);
            if ($pageSeo && $pageSeo->is_active) {
                // Use PageSeo data and merge with any override data from controller
                return $this->fromPageSeo($pageSeo, $data);
            }
        }

        $title = $data['title'] ?? $this->siteName;
        $description = $data['description'] ?? 'Latest entertainment news, movie reviews, and celebrity biographies from Nazaara Circle. Stay updated with the latest trends in pop culture, cinema, TV series, and more.';
        $keywords = $data['keywords'] ?? 'entertainment news, movie reviews, tv series explained, biography, celebrity news, pop culture, film analysis, show recaps, Nazaara Circle';
        $image = $data['image'] ?? $this->defaultImage;
        $url = $data['url'] ?? url()->current();
        $type = $data['type'] ?? 'website';
        $publishedTime = $data['published_time'] ?? null;
        $modifiedTime = $data['modified_time'] ?? null;
        $author = $data['author'] ?? $this->siteName;
        $schema = $data['schema'] ?? null;
        $canonical = $data['canonical'] ?? $url;
        $robots = $data['robots'] ?? 'index, follow';
        $locale = $data['locale'] ?? 'en';
        $alternateLocales = $data['alternate_locales'] ?? [];

        // Ensure image is absolute URL
        if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
            $image = url($image);
        }

        // Always include Organization schema for brand recognition
        $schemas = is_array($schema) ? $schema : ($schema ? [$schema] : []);
        
        // Add Organization schema if not already present
        $hasOrganization = false;
        foreach ($schemas as $existingSchema) {
            if (isset($existingSchema['@type']) && $existingSchema['@type'] === 'Organization') {
                $hasOrganization = true;
                break;
            }
        }
        
        if (!$hasOrganization) {
            $schemas[] = SchemaHelper::organization([
                'name' => $this->siteName,
                'url' => $this->siteUrl,
                'logo' => asset('icon.png'),
                'social_links' => [
                    'https://www.facebook.com/profile.php?id=61585279116089',
                    'https://www.youtube.com/channel/UCOiiIYdcKBeMFDCa42iylmA',
                    'https://www.instagram.com/nazaaracircle',
                ],
            ]);
        }
        
        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'image' => $image,
            'url' => $url,
            'type' => $type,
            'published_time' => $publishedTime,
            'modified_time' => $modifiedTime,
            'author' => $author,
            'schema' => $schemas,
            'canonical' => $canonical,
            'robots' => $robots,
            'locale' => $locale,
            'alternate_locales' => $alternateLocales,
        ];
    }

    /**
     * Generate SEO from admin-managed PageSeo model
     */
    protected function fromPageSeo(PageSeo $pageSeo, array $overrideData = []): array
    {
        // Parse schema markup if exists
        $schema = null;
        if ($pageSeo->schema_markup) {
            $decoded = json_decode($pageSeo->schema_markup, true);
            $schema = is_array($decoded) ? [$decoded] : null;
        }

        // Build data array from PageSeo - prioritize PageSeo fields
        // Use meta_title directly, fallback to og_title only if meta_title is empty
        $title = !empty($pageSeo->meta_title) 
            ? $pageSeo->meta_title 
            : ($pageSeo->og_title ?? $this->siteName);
        
        $description = !empty($pageSeo->meta_description)
            ? $pageSeo->meta_description
            : ($pageSeo->og_description ?? '');
        
        // Parse hreflang tags - ensure it's always an array
        $hreflangTags = [];
        if ($pageSeo->hreflang_tags) {
            // If it's already an array (from cast), use it directly
            if (is_array($pageSeo->hreflang_tags)) {
                $hreflangTags = $pageSeo->hreflang_tags;
            } else {
                // If it's a JSON string, decode it
                $decoded = json_decode($pageSeo->hreflang_tags, true);
                $hreflangTags = is_array($decoded) ? $decoded : [];
            }
        }
        
        $data = [
            'title' => $title,
            'description' => $description,
            'keywords' => $pageSeo->meta_keywords ?? '',
            'image' => $pageSeo->og_image ?? $pageSeo->twitter_image ?? $this->defaultImage,
            'url' => $pageSeo->og_url ?? url()->current(),
            'type' => $pageSeo->og_type ?? 'website',
            'canonical' => $pageSeo->canonical_url ?? url()->current(),
            'robots' => $pageSeo->meta_robots ?? 'index, follow',
            'schema' => $schema,
            'alternate_locales' => $hreflangTags,
        ];

        // Only merge override data for fields that are truly missing (for dynamic content)
        foreach ($overrideData as $key => $value) {
            if (!array_key_exists($key, $data) || (empty($data[$key]) && $value !== null && $value !== '')) {
                $data[$key] = $value;
            }
        }

        // Set OG and Twitter fields from PageSeo (these should always use PageSeo values if set)
        if (!empty($pageSeo->og_title)) {
            $data['og_title'] = $pageSeo->og_title;
        }
        if (!empty($pageSeo->og_description)) {
            $data['og_description'] = $pageSeo->og_description;
        }

        // Generate without checking for PageSeo again (to avoid recursion)
        $title = $data['title'] ?? $this->siteName;
        $description = $data['description'] ?? '';
        $keywords = $data['keywords'] ?? '';
        $image = $data['image'] ?? $this->defaultImage;
        $url = $data['url'] ?? url()->current();
        $type = $data['type'] ?? 'website';
        $canonical = $data['canonical'] ?? $url;
        $robots = $data['robots'] ?? 'index, follow';
        $locale = $data['locale'] ?? 'en';
        $alternateLocales = $data['alternate_locales'] ?? [];
        $schema = $data['schema'] ?? null;

        // Ensure image is absolute URL
        if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
            $image = url($image);
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'image' => $image,
            'url' => $url,
            'type' => $type,
            'canonical' => $canonical,
            'robots' => $robots,
            'locale' => $locale,
            'alternate_locales' => $alternateLocales,
            'schema' => $schema,
            // Twitter Card specific fields from PageSeo
            'twitter_card' => $pageSeo->twitter_card ?? 'summary_large_image',
            'twitter_title' => $pageSeo->twitter_title ?? $title,
            'twitter_description' => $pageSeo->twitter_description ?? $description,
            'twitter_image' => $pageSeo->twitter_image ? (filter_var($pageSeo->twitter_image, FILTER_VALIDATE_URL) ? $pageSeo->twitter_image : url($pageSeo->twitter_image)) : $image,
            // OG specific fields
            'og_title' => $pageSeo->og_title ?? $title,
            'og_description' => $pageSeo->og_description ?? $description,
            'og_image' => $pageSeo->og_image ? (filter_var($pageSeo->og_image, FILTER_VALIDATE_URL) ? $pageSeo->og_image : url($pageSeo->og_image)) : $image,
        ];
    }

    /**
     * Generate SEO for home page
     */
    public function forHome(): array
    {
        return $this->generate([
            'title' => 'Nazaara Circle - Entertainment News, Reviews & Biographies',
            'description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies. Stay updated with the latest pop culture trends.',
            'keywords' => 'entertainment news, movie reviews, tv shows, series explained, celebrity biographies, pop culture, film analysis, show recaps, cinema, streaming guide',
            'type' => 'website',
            'schema' => [
                SchemaHelper::website([
                    'name' => $this->siteName,
                    'url' => $this->siteUrl,
                    'search_url' => route('search') . '?q={search_term_string}',
                ]),
                SchemaHelper::organization([
                    'name' => $this->siteName,
                    'url' => $this->siteUrl,
                ]),
            ],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
            ],
        ], 'home');
    }

    /**
     * Generate SEO for articles listing page
     */
    public function forArticlesIndex(): array
    {
        return $this->generate([
            'title' => 'Articles - Entertainment News & Reviews | Nazaara Circle',
            'description' => 'Explore our complete collection of entertainment articles, movie reviews, and celebrity news. Dive into the world of cinema and pop culture.',
            'keywords' => 'entertainment articles, movie reviews, tv series analysis, celebrity news, pop culture articles, film critiques',
            'type' => 'website',
            'schema' => [
                SchemaHelper::collectionPage([
                    'name' => 'Articles - Nazaara Circle',
                    'url' => route('articles.index'),
                    'description' => 'Explore our collection of entertainment articles and reviews.',
                ]),
            ],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Articles', 'url' => route('articles.index')],
            ],
        ], 'articles.index');
    }

    /**
     * Generate SEO for games listing page
     */
    public function forGamesIndex(): array
    {
        return $this->generate([
            'title' => 'Download Mod Games APK - Latest Android Games | Nazaara Circle',
            'description' => 'Download the latest modified versions of popular Android games. Get unlimited coins, unlocked features, and premium items for free.',
            'keywords' => 'mod games apk, android games mod, download mod games, latest android mods, mobile game cheats, unlocked games apk',
            'type' => 'website',
            'schema' => [
                SchemaHelper::collectionPage([
                    'name' => 'Mod Games Center - Nazaara Circle',
                    'url' => route('games.index'),
                    'description' => 'Download the best modified Android games for free.',
                ]),
            ],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Games', 'url' => route('games.index')],
            ],
        ], 'games.index');
    }

    /**
     * Generate SEO for a single game page
     */
    public function forGameShow($game): array
    {
        $imageUrl = $game->image ? (filter_var($game->image, FILTER_VALIDATE_URL) ? $game->image : url('storage/' . $game->image)) : $this->defaultImage;
        $url = route('games.show', $game->slug);
        
        return $this->generate([
            'title' => $game->title . ' MOD APK Download (Unlocked/Premium) | Nazaara Circle',
            'description' => $game->description ?? Str::limit(strip_tags($game->content), 160),
            'keywords' => $game->title . ' mod apk, ' . $game->title . ' download, ' . $game->title . ' hack, ' . $game->title . ' premium unlocked',
            'image' => $imageUrl,
            'url' => $url,
            'type' => 'video.other',
            'published_time' => $game->created_at?->toIso8601String(),
            'modified_time' => $game->updated_at?->toIso8601String(),
            'schema' => [
                SchemaHelper::videoGame([
                    'name' => $game->title,
                    'description' => $game->description,
                    'image' => $imageUrl,
                    'url' => $url,
                    'download_url' => $game->download_link,
                ]),
                SchemaHelper::breadcrumbList([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Games', 'url' => route('games.index')],
                    ['name' => $game->title, 'url' => $url],
                ]),
            ],
        ], 'games.show');
    }

    /**
     * Generate SEO for applications listing page
     */
    public function forApplicationsIndex(): array
    {
        return $this->generate([
            'title' => 'Download Premium Mod Apps APK - Latest Android Apps | Nazaara Circle',
            'description' => 'Get the latest modified Android applications with premium features unlocked. Download ad-free and pro versions of your favorite apps for free.',
            'keywords' => 'mod apps apk, premium unlocked apps, android modded apps, download pro apps for free, ad-free apk download',
            'type' => 'website',
            'schema' => [
                SchemaHelper::collectionPage([
                    'name' => 'Mod Apps Center - Nazaara Circle',
                    'url' => route('applications.index'),
                    'description' => 'Download premium modified Android applications for free.',
                ]),
            ],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Apps', 'url' => route('applications.index')],
            ],
        ], 'applications.index');
    }

    /**
     * Generate SEO for a single application page
     */
    public function forApplicationShow($application): array
    {
        $imageUrl = $application->image ? (filter_var($application->image, FILTER_VALIDATE_URL) ? $application->image : url('storage/' . $application->image)) : $this->defaultImage;
        $url = route('applications.show', $application->slug);

        return $this->generate([
            'title' => $application->title . ' Premium MOD APK (Pro Unlocked) | Nazaara Circle',
            'description' => $application->description ?? Str::limit(strip_tags($application->content), 160),
            'keywords' => $application->title . ' premium apk, ' . $application->title . ' mod, ' . $application->title . ' pro unlocked, ' . $application->title . ' download',
            'image' => $imageUrl,
            'url' => $url,
            'type' => 'article',
            'published_time' => $application->created_at?->toIso8601String(),
            'modified_time' => $application->updated_at?->toIso8601String(),
            'schema' => [
                SchemaHelper::softwareApplication([
                    'name' => $application->title,
                    'description' => $application->description,
                    'image' => $imageUrl,
                    'url' => $url,
                    'download_url' => $application->download_link,
                    'category' => 'MultimediaApplication',
                ]),
                SchemaHelper::breadcrumbList([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Apps', 'url' => route('applications.index')],
                    ['name' => $application->title, 'url' => $url],
                ]),
            ],
        ], 'applications.show');
    }

    /**
     * Generate SEO for tools listing page
     */
    public function forToolsIndex(): array
    {
        return $this->generate([
            'title' => 'Free Online Web Tools - Nazaara Circle',
            'description' => 'Explore our collection of free online tools, including YouTube thumbnail downloaders and other utility tools for content creators.',
            'keywords' => 'online tools, youtube thumbnail downloader, web utilities, content creator tools, free online apps',
            'type' => 'website',
            'schema' => [
                SchemaHelper::collectionPage([
                    'name' => 'Online Tools - Nazaara Circle',
                    'url' => route('tools.index'),
                    'description' => 'Explore our collection of free online tools and utilities.',
                ]),
            ],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Tools', 'url' => route('tools.index')],
            ],
        ], 'tools.index');
    }

    /**
     * Generate SEO for a single tool page
     */
    public function forToolShow($tool): array
    {
        $imageUrl = $tool->icon ? (filter_var($tool->icon, FILTER_VALIDATE_URL) ? $tool->icon : url('storage/' . $tool->icon)) : $this->defaultImage;
        $url = route('tools.show', $tool->slug);

        return $this->generate([
            'title' => $tool->title . ' - Free Online Tool | Nazaara Circle',
            'description' => $tool->description ?? Str::limit(strip_tags($tool->content), 160),
            'keywords' => $tool->title . ', free online tool, web utility, ' . $tool->title . ' downloader',
            'image' => $imageUrl,
            'url' => $url,
            'type' => 'article',
            'published_time' => $tool->created_at?->toIso8601String(),
            'modified_time' => $tool->updated_at?->toIso8601String(),
            'schema' => [
                SchemaHelper::softwareApplication([
                    'name' => $tool->title,
                    'description' => $tool->description,
                    'image' => $imageUrl,
                    'url' => $url,
                    'category' => 'WebApplication',
                ]),
                SchemaHelper::breadcrumbList([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Tools', 'url' => route('tools.index')],
                    ['name' => $tool->title, 'url' => $url],
                ]),
            ],
        ], 'tools.show');
    }

    /**
     * Generate SEO for an article detail page
     */
    public function forArticle(Article $article): array
    {
        // Use article title as the primary source for all SEO titles
        // Meta Title: Use custom meta_title if set, otherwise use article title
        $title = !empty($article->meta_title) ? $article->meta_title : $article->title;
        
        // Meta Description: Use custom meta_description if set, otherwise use excerpt or content
        $description = !empty($article->meta_description) 
            ? $article->meta_description 
            : (!empty($article->excerpt) 
                ? $article->excerpt 
                : substr(strip_tags($article->content), 0, 160));
        
        // Keywords: Always include article title first, then custom keywords, tags, and category
        $keywordParts = [$article->title]; // Always start with article title
        if (!empty($article->meta_keywords)) {
            $keywordParts[] = $article->meta_keywords;
        }
        $keywordParts = array_merge($keywordParts, $article->tags->pluck('name')->toArray());
        if ($article->category?->name) {
            $keywordParts[] = $article->category->name;
        }
        $keywords = implode(', ', array_unique($keywordParts)); // Remove duplicates
        
        $robots = $article->meta_robots ?: 'index, follow';
        $canonical = $article->canonical_url ?: route('articles.show', $article->slug);

        $publishedDate = $article->published_at ? $article->published_at->format('Y-m-d') : $article->created_at->format('Y-m-d');
        $modifiedDate = $article->updated_at->format('Y-m-d');
        
        // Get image - prioritize featured image for all SEO fields
        // First check for custom OG/Twitter images, then use featured image, then default
        $image = $this->defaultImage;
        $featuredImageUrl = null;
        
        // Process featured image first (will be used as fallback for all image fields)
        if ($article->featured_image) {
            $rawImage = $article->featured_image;
            if (filter_var($rawImage, FILTER_VALIDATE_URL)) {
                $featuredImageUrl = $rawImage;
            } elseif (str_starts_with($rawImage, 'storage/')) {
                $featuredImageUrl = asset($rawImage);
            } elseif (str_starts_with($rawImage, 'public/')) {
                $featuredImageUrl = asset(str_replace('public/', 'storage/', $rawImage));
            } else {
                $featuredImageUrl = \Illuminate\Support\Facades\Storage::url($rawImage);
            }
            $image = $featuredImageUrl; // Use featured image as default
        }
        
        // Check for custom OG image (only if explicitly set)
        if ($article->og_image) {
            $rawImage = $article->og_image;
            if (filter_var($rawImage, FILTER_VALIDATE_URL)) {
                $image = $rawImage;
            } else {
                $image = url($rawImage);
            }
        }

        // Cache-bust images so OG/Twitter cards refresh after updates
        $version = $article->updated_at ? $article->updated_at->timestamp : time();
        if ($featuredImageUrl) {
            $featuredImageUrl .= (str_contains($featuredImageUrl, '?') ? '&' : '?') . 'v=' . $version;
        }
        if ($image && $image !== $featuredImageUrl) {
            $image .= (str_contains($image, '?') ? '&' : '?') . 'v=' . $version;
        }
        
        // Get category
        $category = $article->category ? $article->category->name : null;
        
        // Get tags
        $tags = $article->tags->pluck('name')->toArray();
        
        // Get author
        $author = $article->author ? $article->author->name : $this->siteName;

        $url = route('articles.show', $article->slug);

        // Calculate word count and reading time
        $wordCount = str_word_count(strip_tags($article->content));
        $readingTime = $article->reading_time ?? max(1, ceil($wordCount / 200)); // 200 words per minute
        
        // Calculate likes and comments count early (needed for schema)
        $likesCount = $article->likes()->count();
        $commentsCount = $article->comments()->approved()->count();
        
        // Generate enhanced article schema with more SEO signals
        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'description' => $description,
            'image' => [
                '@type' => 'ImageObject',
                'url' => $featuredImageUrl ?: $image,
                'width' => 1200,
                'height' => 630,
            ],
            'url' => $url,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $url,
            ],
            'datePublished' => $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String(),
            'dateModified' => $article->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $author,
                'url' => $article->author && $article->author->username 
                    ? route('profile.show', $article->author->username) 
                    : ($article->author 
                        ? route('profile.show', $article->author->id) 
                        : $this->siteUrl),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->siteName,
                'url' => $this->siteUrl,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('icon.png'),
                    'width' => 512,
                    'height' => 512,
                ],
            ],
            'articleSection' => $category,
            'keywords' => implode(', ', array_unique(array_merge([$article->title], $tags, $category ? [$category] : []))),
            'inLanguage' => app()->getLocale() ?? 'en',
            'isAccessibleForFree' => true,
            'wordCount' => $wordCount,
            'timeRequired' => 'PT' . $readingTime . 'M',
            'commentCount' => $commentsCount,
        ];
        
        // Add article body (first 500 characters for SEO)
        if (!empty($article->content)) {
            $articleBody = strip_tags($article->content);
            $articleSchema['articleBody'] = mb_substr($articleBody, 0, 500) . (mb_strlen($articleBody) > 500 ? '...' : '');
        }
        
        // Add mentions (tags as entities)
        if (!empty($tags)) {
            $articleSchema['mentions'] = array_map(function($tag) {
                return [
                    '@type' => 'Thing',
                    'name' => $tag,
                ];
            }, $tags);
        }
        
        // Add about (category and main topics)
        $about = [];
        if ($category) {
            $about[] = [
                '@type' => 'Thing',
                'name' => $category,
            ];
        }
        if (!empty($tags)) {
            foreach (array_slice($tags, 0, 5) as $tag) {
                $about[] = [
                    '@type' => 'Thing',
                    'name' => $tag,
                ];
            }
        }
        if (!empty($about)) {
            $articleSchema['about'] = $about;
        }

        // Generate breadcrumbs schema
        $breadcrumbItems = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Articles', 'url' => route('articles.index')],
        ];
        
        if ($article->category) {
            $breadcrumbItems[] = [
                'name' => $article->category->name,
                'url' => route('categories.show', $article->category->slug),
            ];
        }
        
        $breadcrumbItems[] = ['name' => $title, 'url' => $url];
        
        $breadcrumbSchema = SchemaHelper::breadcrumbList($breadcrumbItems);

        // $likesCount and $commentsCount are already calculated above
        
        if ($likesCount > 0 || $commentsCount > 0) {
            // Add interaction statistics for better SEO
            $articleSchema['interactionStatistic'] = [
                [
                    '@type' => 'InteractionCounter',
                    'interactionType' => 'https://schema.org/LikeAction',
                    'userInteractionCount' => $likesCount,
                ],
                [
                    '@type' => 'InteractionCounter',
                    'interactionType' => 'https://schema.org/CommentAction',
                    'userInteractionCount' => $commentsCount,
                ],
            ];
        }

        // Generate aggregate rating if article has likes/comments
        $schemas = [$articleSchema, $breadcrumbSchema];
        
        // Add Organization schema for better brand recognition
        $organizationSchema = SchemaHelper::organization([
            'name' => $this->siteName,
            'url' => $this->siteUrl,
            'logo' => asset('icon.png'),
            'social_links' => [
                'https://www.facebook.com/profile.php?id=61585279116089',
                'https://www.youtube.com/channel/UCOiiIYdcKBeMFDCa42iylmA',
                'https://www.instagram.com/nazaaracircle',
            ],
        ]);
        $schemas[] = $organizationSchema;

        // Use featured image as the primary image for all SEO fields
        $primaryImage = $featuredImageUrl ?: $image;
        
        $seoData = $this->generate([
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'image' => $primaryImage, // Use featured image as primary
            'url' => $url,
            'type' => $article->og_type ?: 'article',
            'published_time' => $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String(),
            'modified_time' => $article->updated_at->toIso8601String(),
            'author' => $author,
            'schema' => $schemas,
            'canonical' => $canonical,
            'robots' => $robots,
            'breadcrumbs' => $breadcrumbItems, // Add this
        ]);
        
        // Open Graph fields - use custom values if set, otherwise use same as meta title/description
        $seoData['og_title'] = !empty($article->og_title) ? $article->og_title : $title;
        $seoData['og_description'] = !empty($article->og_description) ? $article->og_description : $description;
        // OG Image: Use custom og_image if set, otherwise use featured image, then default
        $seoData['og_image'] = !empty($article->og_image) 
            ? (filter_var($article->og_image, FILTER_VALIDATE_URL) ? $article->og_image : url($article->og_image)) 
            : ($featuredImageUrl ?: $image);
        $seoData['og_url'] = !empty($article->og_url) ? $article->og_url : $url;
        $seoData['og_type'] = !empty($article->og_type) ? $article->og_type : 'article';

        // Twitter Card fields - use custom values if set, otherwise use same as meta title/description
        $seoData['twitter_card'] = !empty($article->twitter_card) ? $article->twitter_card : 'summary_large_image';
        $seoData['twitter_title'] = !empty($article->twitter_title) ? $article->twitter_title : $title;
        $seoData['twitter_description'] = !empty($article->twitter_description) ? $article->twitter_description : $description;
        // Twitter Image: Use custom twitter_image if set, otherwise use featured image, then default
        $seoData['twitter_image'] = !empty($article->twitter_image) 
            ? (filter_var($article->twitter_image, FILTER_VALIDATE_URL) ? $article->twitter_image : url($article->twitter_image)) 
            : ($featuredImageUrl ?: $image);
        
        return $seoData;
    }

    /**
     * Generate SEO for categories listing page
     */
    public function forCategoriesIndex(): array
    {
        return $this->generate([
            'title' => 'Categories - Browse Entertainment Topics | Nazaara Circle',
            'description' => 'Browse articles by category. Find movie reviews, TV show analysis, biographies, and more entertainment topics.',
            'keywords' => 'entertainment categories, movie genres, tv show genres, celebrity biographies, pop culture topics',
            'type' => 'website',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Categories', 'url' => route('categories.index')],
            ],
        ], 'categories.index');
    }

    /**
     * Generate SEO for a category page
     */
    public function forCategory(Category $category): array
    {
        $title = $category->name;
        $description = $category->description ?: "Browse articles in {$title} category. Latest entertainment news, reviews, and features.";
        
        $url = route('categories.show', $category->slug);
        $keywords = "{$title}, entertainment news, reviews, pop culture, movies, tv series";

        return $this->generate([
            'title' => "{$title} - Entertainment Topics | Nazaara Circle",
            'description' => $description,
            'keywords' => $keywords,
            'url' => $url,
            'type' => 'website',
            'schema' => [
                SchemaHelper::collectionPage([
                    'name' => $title,
                    'url' => $url,
                    'description' => $description,
                ]),
            ],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Categories', 'url' => route('categories.index')],
                ['name' => $title, 'url' => $url],
            ],
        ]);
    }

    /**
     * Generate SEO for a tag page
     */
    public function forTag(Tag $tag): array
    {
        $title = $tag->name;
        $description = $tag->description ?: "Browse articles tagged with {$title}. Latest entertainment news, reviews, and features.";
        
        $url = route('tags.show', $tag->slug);
        $keywords = "{$title}, entertainment tags, pop culture topics, {$title} articles";

        return $this->generate([
            'title' => "{$title} - Entertainment Topics | Nazaara Circle",
            'description' => $description,
            'keywords' => $keywords,
            'url' => $url,
            'type' => 'website',
            'schema' => [
                SchemaHelper::collectionPage([
                    'name' => $title,
                    'url' => $url,
                    'description' => $description,
                ]),
            ],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Tags', 'url' => route('tags.index')],
                ['name' => $title, 'url' => $url],
            ],
        ]);
    }

    /**
     * Generate SEO for series listing page
     */
    public function forSeriesIndex(): array
    {
        return $this->generate([
            'title' => 'TV Series & Collections - Nazaara Circle',
            'description' => 'Explore our curated TV series collections and entertainment anthologies. Binge-worthy content organized for you.',
            'keywords' => 'tv series, entertainment collections, movie series, anthologies, binge watch',
            'type' => 'website',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Series', 'url' => route('series.index')],
            ],
        ], 'series.index');
    }

    /**
     * Generate SEO for a series page
     */
    public function forSeries(Series $series): array
    {
        $title = $series->title;
        $description = $series->description ?: "Browse articles in the {$title} series. A curated collection of entertainment analysis and reviews.";
        
        $url = route('series.show', $series->slug);
        $keywords = "{$title}, entertainment series, movie collection, tv show analysis, binge watch guide";
        
        $image = $series->featured_image 
            ? (filter_var($series->featured_image, FILTER_VALIDATE_URL) ? $series->featured_image : url($series->featured_image))
            : $this->defaultImage;

        // Calculate series stats
        $numberOfEpisodes = $series->articles()->count();
        $startDate = $series->created_at->format('Y-m-d');

        return $this->generate([
            'title' => "{$title} - Article Series | Nazaara Circle",
            'description' => $description,
            'keywords' => $keywords,
            'image' => $image,
            'url' => $url,
            'type' => 'video.tv_show', // Entertainment-focused Open Graph type
            'schema' => [
                SchemaHelper::tvSeries([
                    'name' => $title,
                    'url' => $url,
                    'description' => $description,
                    'image' => $image,
                    'number_of_episodes' => $numberOfEpisodes,
                    'start_date' => $startDate,
                ]),
            ],
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Series', 'url' => route('series.index')],
                ['name' => $title, 'url' => $url],
            ],
        ]);
    }

    /**
     * Generate SEO for a user profile page
     */
    public function forProfile(User $user): array
    {
        $title = $user->name;
        $description = $user->bio ?: "View {$user->name}'s profile. Entertainment articles, reviews, and more.";
        
        $url = route('profile.show', $user->username ?? $user->id);
        $keywords = "{$user->name}, author profile, entertainment articles, movie reviews, {$user->name} articles";
        
        $image = $user->avatar_url ?? $this->defaultImage;

        return $this->generate([
            'title' => "{$title} - Author Profile | Nazaara Circle",
            'description' => $description,
            'keywords' => $keywords,
            'image' => $image,
            'url' => $url,
            'type' => 'profile',
        ]);
    }

    /**
     * Generate SEO for tags listing page
     */
    public function forTagsIndex(): array
    {
        return $this->generate([
            'title' => 'Tags - Browse All Tags | Nazaara Circle',
            'description' => 'Browse articles by tags. Find articles about specific entertainment topics, celebrity news, and pop culture events.',
            'keywords' => 'tags, entertainment tags, movie tags, celebrity topics',
            'type' => 'website',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Tags', 'url' => route('tags.index')],
            ],
        ], 'tags.index');
    }

    /**
     * Generate SEO for search page
     */
    public function forSearch($query = null): array
    {
        $title = $query ? "Search Results for '{$query}' - Nazaara Circle" : 'Search Articles - Nazaara Circle';
        $description = $query 
            ? "Search results for '{$query}'. Find entertainment articles, movie reviews, and biographies matching your search."
            : 'Search for entertainment articles, movie reviews, and biographies. Find what you need quickly.';

        return $this->generate([
            'title' => $title,
            'description' => $description,
            'keywords' => 'search, find articles, entertainment search, movie search',
            'type' => 'website',
            'robots' => 'noindex, follow', // Don't index search pages
        ], 'search');
    }

    /**
     * Generate SEO for static pages
     */
    public function forPage($pageKey, $title = null, $description = null): array
    {
        $pages = [
            'about' => [
                'title' => 'About Us - Nazaara Circle',
                'description' => 'Learn more about Nazaara Circle. Your destination for entertainment news, movie reviews, and pop culture insights.',
            ],
            'contact' => [
                'title' => 'Contact Us - Nazaara Circle',
                'description' => 'Get in touch with Nazaara Circle. We\'d love to hear from you.',
            ],
            'privacy' => [
                'title' => 'Privacy Terms - Nazaara Circle',
                'description' => 'Privacy policy and data protection information for Nazaara Circle.',
            ],
            'terms' => [
                'title' => 'Terms of Service - Nazaara Circle',
                'description' => 'Terms of service and usage policy for Nazaara Circle.',
            ],
            'disclaimer' => [
                'title' => 'Disclaimer - Nazaara Circle',
                'description' => 'Disclaimer and liability information for Nazaara Circle.',
            ],
        ];

        $pageData = $pages[$pageKey] ?? [
            'title' => $title ?? ucfirst($pageKey) . ' - Nazaara Circle',
            'description' => $description ?? '',
        ];

        return $this->generate([
            'title' => $title ?? $pageData['title'],
            'description' => $description ?? $pageData['description'],
            'type' => 'website',
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => $title ?? $pageData['title'], 'url' => url()->current()],
            ],
        ], $pageKey);
    }

    /**
     * Get image URL (handles custom images)
     */
    protected function getImageUrl($path): string
    {
        if (!$path) {
            return $this->defaultImage;
        }

        // If it's already a full URL, return it
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Otherwise, treat as relative URL
        return url($path);
    }

    /**
     * Get Twitter handle
     */
    public function getTwitterHandle(): string
    {
        return $this->twitterHandle;
    }

    /**
     * Get Facebook App ID
     */
    public function getFacebookAppId(): string
    {
        return $this->facebookAppId;
    }

    /**
     * Get sitemap URL
     */
    public function getSitemapUrl(): string
    {
        return route('sitemap.index');
    }

    /**
     * Get sitemap index URL
     */
    public function getSitemapIndexUrl(): string
    {
        return route('sitemap.sitemap-index');
    }

    /**
     * Automatically detect and generate SEO based on current route
     */
    public function forCurrentRoute(): array
    {
        $routeName = request()->route()?->getName();
        
        if (!$routeName) {
            return $this->forHome();
        }

        // Map route names to page keys and methods
        $routeMap = [
            'home' => ['pageKey' => 'home', 'method' => 'forHome'],
            'articles.index' => ['pageKey' => 'articles.index', 'method' => 'forArticlesIndex'],
            'games.index' => ['pageKey' => 'games.index', 'method' => 'forGamesIndex'],
            'applications.index' => ['pageKey' => 'applications.index', 'method' => 'forApplicationsIndex'],
            'tools.index' => ['pageKey' => 'tools.index', 'method' => 'forToolsIndex'],
            'categories.index' => ['pageKey' => 'categories.index', 'method' => 'forCategoriesIndex'],
            'tags.index' => ['pageKey' => 'tags.index', 'method' => 'forTagsIndex'],
            'series.index' => ['pageKey' => 'series.index', 'method' => 'forSeriesIndex'],
            'search' => ['pageKey' => 'search', 'method' => 'forSearch'],
            'about' => ['pageKey' => 'about', 'method' => 'forPage'],
            'contact' => ['pageKey' => 'contact', 'method' => 'forPage'],
            'privacy' => ['pageKey' => 'privacy', 'method' => 'forPage'],
            'terms' => ['pageKey' => 'terms', 'method' => 'forPage'],
        ];

        if (isset($routeMap[$routeName])) {
            $config = $routeMap[$routeName];
            
            // Use specific method if available
            if ($config['method'] === 'forPage') {
                return $this->forPage($config['pageKey']);
            }
            
            // Call the specific method
            if (method_exists($this, $config['method'])) {
                return $this->{$config['method']}();
            }
        }

        // Fallback: try to use page key directly
        if (str_contains($routeName, '.')) {
            $pageKey = str_replace(['articles.', 'categories.', 'tags.'], ['articles.', 'categories.', 'tags.'], $routeName);
            return $this->generate([], $pageKey);
        }

        // Final fallback
        return $this->forHome();
    }
}


<?php

namespace App\Console\Commands;

use App\Models\PageSeo;
use Illuminate\Console\Command;

class InitializeAllPageSeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'page-seo:init-all {--force : Force update existing configurations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize default SEO configurations for all available pages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        $availablePages = PageSeo::getAvailablePageKeys();
        $existingPages = PageSeo::pluck('page_key')->toArray();
        
        $this->info('🚀 Initializing SEO configurations for all pages...');
        $this->newLine();
        
        $created = 0;
        $updated = 0;
        $skipped = 0;
        
        foreach ($availablePages as $pageKey => $pageName) {
            $existing = PageSeo::where('page_key', $pageKey)->first();
            
            if ($existing) {
                if ($force) {
                    $this->updatePageSeo($existing, $pageKey, $pageName);
                    $updated++;
                    $this->line("✅ Updated: {$pageName} ({$pageKey})");
                } else {
                    $skipped++;
                    $this->line("⏭️  Skipped: {$pageName} ({$pageKey}) - already exists");
                }
            } else {
                $this->createPageSeo($pageKey, $pageName);
                $created++;
                $this->line("✨ Created: {$pageName} ({$pageKey})");
            }
        }
        
        $this->newLine();
        $this->info("📊 Summary:");
        $this->line("   Created: {$created}");
        $this->line("   Updated: {$updated}");
        $this->line("   Skipped: {$skipped}");
        $this->newLine();
        
        if ($created > 0 || $updated > 0) {
            $this->info('✅ All page SEO configurations have been processed!');
        } else {
            $this->info('ℹ️  All pages are already configured. Use --force to update existing configurations.');
        }
        
        return 0;
    }

    /**
     * Create page SEO configuration
     */
    protected function createPageSeo(string $pageKey, string $pageName)
    {
        $siteUrl = config('app.url', url('/'));
        $siteName = config('app.name', 'Nazaara Circle');
        $defaults = $this->getDefaultsForPage($pageKey, $pageName, $siteUrl, $siteName);
        
        PageSeo::create($defaults);
    }

    /**
     * Update existing page SEO configuration
     */
    protected function updatePageSeo(PageSeo $pageSeo, string $pageKey, string $pageName)
    {
        $siteUrl = config('app.url', url('/'));
        $siteName = config('app.name', 'Nazaara Circle');
        $defaults = $this->getDefaultsForPage($pageKey, $pageName, $siteUrl, $siteName);
        
        // Update all fields with new defaults (force update)
        foreach ($defaults as $key => $value) {
            if ($key === 'page_key' || $key === 'is_active') {
                continue; // Skip these fields
            }
            
            // Always update URL-related fields to ensure they use the correct domain
            if (in_array($key, ['canonical_url', 'og_url']) || !empty($value)) {
                $pageSeo->$key = $value;
            }
        }
        
        $pageSeo->save();
    }

    /**
     * Get default SEO values for a specific page
     */
    protected function getDefaultsForPage(string $pageKey, string $pageName, string $siteUrl, string $siteName): array
    {
        $baseDefaults = [
            'page_key' => $pageKey,
            'page_name' => $pageName,
            'meta_robots' => 'index, follow',
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'canonical_url' => $siteUrl . $this->getPagePath($pageKey),
            'is_active' => true,
        ];

        $pageSpecificDefaults = [
            'home' => [
                'meta_title' => "{$siteName} - Entertainment News, Reviews & Biographies",
                'meta_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies. Stay updated with the latest pop culture trends.',
                'meta_keywords' => 'entertainment news, movie reviews, tv shows, series explained, celebrity biographies, pop culture, film analysis, show recaps, cinema, streaming guide',
                'og_title' => "{$siteName} - Entertainment News, Reviews & Biographies",
                'og_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.',
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl,
                'twitter_title' => "{$siteName} - Entertainment News, Reviews & Biographies",
                'twitter_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.',
                'twitter_image' => asset('icon.png'),
                'schema_markup' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => $siteName,
                    'url' => $siteUrl,
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => $siteUrl . '/search?q={search_term_string}'
                        ],
                        'query-input' => 'required name=search_term_string'
                    ]
                ], JSON_PRETTY_PRINT),
            ],
            'articles.index' => [
                'meta_title' => "Articles - Entertainment News & Reviews | {$siteName}",
                'meta_description' => 'Browse our complete collection of entertainment articles, movie reviews, and TV series analysis. Stay updated with pop culture trends and celebrity news.',
                'meta_keywords' => 'entertainment news, movie reviews, tv series explained, celebrity biographies, pop culture, film analysis',
                'og_title' => "Articles - Entertainment News & Reviews | {$siteName}",
                'og_description' => 'Browse our complete collection of entertainment articles, movie reviews, and TV series analysis.',
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/articles',
                'twitter_title' => "Articles - Entertainment News & Reviews | {$siteName}",
                'twitter_description' => 'Browse our complete collection of entertainment articles, movie reviews, and TV series analysis.',
                'twitter_image' => asset('icon.png'),
            ],
            'categories.index' => [
                'meta_title' => "Categories - Entertainment Topics | {$siteName}",
                'meta_description' => 'Browse articles by category. Find movie reviews, TV show analysis, celebrity biographies, and more entertainment topics.',
                'meta_keywords' => 'entertainment categories, movie reviews, tv shows, celebrity biographies, pop culture',
                'og_title' => "Categories - Entertainment Topics | {$siteName}",
                'og_description' => 'Browse articles by category. Find movie reviews, TV show analysis, celebrity biographies, and more.',
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/categories',
                'twitter_title' => "Categories - Entertainment Topics | {$siteName}",
                'twitter_description' => 'Browse articles by category. Find movie reviews, TV show analysis, celebrity biographies, and more.',
                'twitter_image' => asset('icon.png'),
            ],
            'tags.index' => [
                'meta_title' => "Tags - Browse Entertainment Topics | {$siteName}",
                'meta_description' => 'Browse articles by tags. Find articles about specific movies, TV shows, celebrities, and entertainment topics.',
                'meta_keywords' => 'tags, entertainment tags, movie tags, tv show tags, celebrity tags',
                'og_title' => "Tags - Browse Entertainment Topics | {$siteName}",
                'og_description' => 'Browse articles by tags. Find articles about specific movies, TV shows, celebrities, and entertainment topics.',
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/tags',
                'twitter_title' => "Tags - Browse Entertainment Topics | {$siteName}",
                'twitter_description' => 'Browse articles by tags. Find articles about specific movies, TV shows, celebrities, and entertainment topics.',
                'twitter_image' => asset('icon.png'),
            ],
            'series.index' => [
                'meta_title' => "TV Series & Collections - {$siteName}",
                'meta_description' => 'Explore our curated TV series collections and entertainment anthologies. Binge-worthy content organized for you.',
                'meta_keywords' => 'tv series, entertainment collections, movie series, anthologies, binge watch',
                'og_title' => "TV Series & Collections - {$siteName}",
                'og_description' => 'Explore our curated TV series collections and entertainment anthologies.',
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/series',
                'twitter_title' => "TV Series & Collections - {$siteName}",
                'twitter_description' => 'Explore our curated TV series collections and entertainment anthologies.',
                'twitter_image' => asset('icon.png'),
            ],
            'search' => [
                'meta_title' => "Search Articles | {$siteName}",
                'meta_description' => 'Search for entertainment news, reviews, and biographies. Find exactly what you\'re looking for with our powerful search feature.',
                'meta_keywords' => 'search articles, find movie reviews, search tv shows, celebrity search',
                'og_title' => "Search Articles | {$siteName}",
                'og_description' => 'Search for entertainment news, reviews, and biographies. Find exactly what you\'re looking for.',
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/search',
                'twitter_title' => "Search Articles | {$siteName}",
                'twitter_description' => 'Search for entertainment news, reviews, and biographies. Find exactly what you\'re looking for.',
                'twitter_image' => asset('icon.png'),
                'meta_robots' => 'noindex, follow',
            ],
            'about' => [
                'meta_title' => "About Us | {$siteName}",
                'meta_description' => "Learn more about {$siteName}. Your destination for entertainment news, movie reviews, and celebrity biographies.",
                'meta_keywords' => 'about us, company information, mission, values, Nazaara Circle',
                'og_title' => "About Us | {$siteName}",
                'og_description' => "Learn more about {$siteName}. Your destination for entertainment news, movie reviews, and celebrity biographies.",
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/about',
                'twitter_title' => "About Us | {$siteName}",
                'twitter_description' => "Learn more about {$siteName}. Your destination for entertainment news, movie reviews, and celebrity biographies.",
                'twitter_image' => asset('icon.png'),
            ],
            'contact' => [
                'meta_title' => "Contact Us | {$siteName}",
                'meta_description' => "Get in touch with {$siteName}. We'd love to hear from you. Send us your questions, feedback, or suggestions.",
                'meta_keywords' => 'contact, contact us, get in touch, support, feedback',
                'og_title' => "Contact Us | {$siteName}",
                'og_description' => "Get in touch with {$siteName}. We'd love to hear from you.",
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/contact',
                'twitter_title' => "Contact Us | {$siteName}",
                'twitter_description' => "Get in touch with {$siteName}. We'd love to hear from you.",
                'twitter_image' => asset('icon.png'),
            ],
            'privacy' => [
                'meta_title' => "Privacy Terms | {$siteName}",
                'meta_description' => "Privacy policy and data protection information for {$siteName}. Learn how we protect your personal information and use cookies.",
                'meta_keywords' => 'privacy, policy, data protection, cookies, privacy terms',
                'og_title' => "Privacy Terms | {$siteName}",
                'og_description' => "Privacy policy and data protection information for {$siteName}.",
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/privacy',
                'twitter_title' => "Privacy Terms | {$siteName}",
                'twitter_description' => "Privacy policy and data protection information for {$siteName}.",
                'twitter_image' => asset('icon.png'),
                'meta_robots' => 'noindex, follow',
            ],
            'terms' => [
                'meta_title' => "Terms of Service | {$siteName}",
                'meta_description' => "Read our terms of service to understand the rules and guidelines for using our website.",
                'meta_keywords' => 'terms of service, terms and conditions, usage policy, terms',
                'og_title' => "Terms of Service | {$siteName}",
                'og_description' => "Read our terms of service to understand the rules and guidelines for using our website.",
                'og_image' => asset('icon.png'),
                'og_url' => $siteUrl . '/terms',
                'twitter_title' => "Terms of Service | {$siteName}",
                'twitter_description' => "Read our terms of service to understand the rules and guidelines for using our website.",
                'twitter_image' => asset('icon.png'),
                'meta_robots' => 'noindex, follow',
            ],
        ];

        return array_merge(
            $baseDefaults,
            $pageSpecificDefaults[$pageKey] ?? []
        );
    }

    /**
     * Get the URL path for a page key
     */
    protected function getPagePath(string $pageKey): string
    {
        $paths = [
            'home' => '/',
            'articles.index' => '/articles',
            'categories.index' => '/categories',
            'tags.index' => '/tags',
            'series.index' => '/series',
            'search' => '/search',
            'about' => '/about',
            'contact' => '/contact',
            'privacy' => '/privacy',
            'terms' => '/terms',
        ];

        return $paths[$pageKey] ?? '/';
    }
}


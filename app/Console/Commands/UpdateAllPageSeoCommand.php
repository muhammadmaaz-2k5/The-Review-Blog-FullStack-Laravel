<?php

namespace App\Console\Commands;

use App\Models\PageSeo;
use Illuminate\Console\Command;

class UpdateAllPageSeoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:update-all {--force : Force update even if SEO exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update SEO settings for all pages with Nazaara Circle branding';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        $siteUrl = config('app.url', 'https://nazaaracircle.com');
        $siteName = 'Nazaara Circle';
        
        $this->info('🚀 Updating SEO settings for all pages...');
        $this->newLine();
        
        $availablePages = PageSeo::getAvailablePageKeys();
        $updated = 0;
        $created = 0;
        
        foreach ($availablePages as $pageKey => $pageName) {
            $existing = PageSeo::where('page_key', $pageKey)->first();
            
            $seoData = $this->getSeoDataForPage($pageKey, $pageName, $siteUrl, $siteName);
            
            if ($existing) {
                if ($force) {
                    $existing->update($seoData);
                    $updated++;
                    $this->line("✅ Updated: {$pageName} ({$pageKey})");
                } else {
                    $this->line("⏭️  Skipped: {$pageName} ({$pageKey}) - already exists (use --force to update)");
                }
            } else {
                PageSeo::create($seoData);
                $created++;
                $this->line("✨ Created: {$pageName} ({$pageKey})");
            }
        }
        
        $this->newLine();
        $this->info("📊 Summary:");
        $this->line("   Created: {$created}");
        $this->line("   Updated: {$updated}");
        $this->newLine();
        
        if ($created > 0 || $updated > 0) {
            $this->info('✅ All page SEO settings have been updated!');
        } else {
            $this->info('ℹ️  All pages are already configured. Use --force to update existing configurations.');
        }
        
        return 0;
    }

    /**
     * Get SEO data for a specific page
     */
    protected function getSeoDataForPage(string $pageKey, string $pageName, string $siteUrl, string $siteName): array
    {
        $baseData = [
            'page_key' => $pageKey,
            'page_name' => $pageName,
            'meta_robots' => 'index, follow',
            'og_type' => 'website',
            'og_image' => $siteUrl . '/icon.png',
            'og_url' => $this->getPageUrl($pageKey, $siteUrl),
            'twitter_card' => 'summary_large_image',
            'twitter_image' => $siteUrl . '/icon.png',
            'canonical_url' => $this->getPageUrl($pageKey, $siteUrl),
            'is_active' => true,
        ];

        // Page-specific SEO data
        $pageSpecificData = [
            'home' => [
                'meta_title' => 'Nazaara Circle - Entertainment News, Reviews & Biographies',
                'meta_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies. Stay updated with the latest pop culture trends.',
                'meta_keywords' => 'entertainment news, movie reviews, tv shows, series explained, celebrity biographies, pop culture, film analysis, show recaps, cinema, streaming guide',
                'og_title' => 'Nazaara Circle - Entertainment News, Reviews & Biographies',
                'og_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.',
                'twitter_title' => 'Nazaara Circle - Entertainment News, Reviews & Biographies',
                'twitter_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.',
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
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                'hreflang_tags' => json_encode(['en' => $siteUrl], JSON_PRETTY_PRINT),
            ],
            'articles.index' => [
                'meta_title' => 'Articles - Entertainment News & Reviews | Nazaara Circle',
                'meta_description' => 'Browse our complete collection of entertainment articles, movie reviews, and TV series analysis. Stay updated with pop culture trends and celebrity news.',
                'meta_keywords' => 'entertainment news, movie reviews, tv series explained, celebrity biographies, pop culture, film analysis',
                'og_title' => 'Articles - Entertainment News & Reviews | Nazaara Circle',
                'og_description' => 'Browse our complete collection of entertainment articles, movie reviews, and TV series analysis.',
                'twitter_title' => 'Articles - Entertainment News & Reviews | Nazaara Circle',
                'twitter_description' => 'Browse our complete collection of entertainment articles, movie reviews, and TV series analysis.',
            ],
            'articles.show' => [
                'meta_title' => '{title} | Nazaara Circle',
                'meta_description' => 'Read this comprehensive article on Nazaara Circle. Expert insights, reviews, and entertainment news.',
                'meta_keywords' => 'entertainment, movie review, tv series, biography, article',
                'og_title' => '{title} | Nazaara Circle',
                'og_description' => 'Read this comprehensive article on Nazaara Circle.',
                'twitter_title' => '{title} | Nazaara Circle',
                'twitter_description' => 'Read this comprehensive article on Nazaara Circle.',
            ],
            'categories.index' => [
                'meta_title' => 'Categories - Entertainment Topics | Nazaara Circle',
                'meta_description' => 'Explore entertainment categories on Nazaara Circle. Find articles organized by topics including movies, TV shows, biographies, and pop culture.',
                'meta_keywords' => 'entertainment categories, movie reviews, tv shows, celebrity biographies, pop culture',
                'og_title' => 'Categories - Entertainment Topics | Nazaara Circle',
                'og_description' => 'Explore entertainment categories on Nazaara Circle.',
                'twitter_title' => 'Categories - Entertainment Topics | Nazaara Circle',
                'twitter_description' => 'Explore entertainment categories on Nazaara Circle.',
            ],
            'categories.show' => [
                'meta_title' => '{title} - Entertainment Articles | Nazaara Circle',
                'meta_description' => 'Browse articles in this category on Nazaara Circle. Expert entertainment content and reviews.',
                'meta_keywords' => 'category, entertainment, articles',
                'og_title' => '{title} - Entertainment Articles | Nazaara Circle',
                'og_description' => 'Browse articles in this category on Nazaara Circle.',
                'twitter_title' => '{title} - Entertainment Articles | Nazaara Circle',
                'twitter_description' => 'Browse articles in this category on Nazaara Circle.',
            ],
            'tags.index' => [
                'meta_title' => 'Tags - Browse All Tags | Nazaara Circle',
                'meta_description' => 'Browse all tags on Nazaara Circle. Find articles by topics, movies, TV shows, and celebrities.',
                'meta_keywords' => 'tags, entertainment tags, movie tags, tv show tags, celebrity tags',
                'og_title' => 'Tags - Browse All Tags | Nazaara Circle',
                'og_description' => 'Browse all tags on Nazaara Circle.',
                'twitter_title' => 'Tags - Browse All Tags | Nazaara Circle',
                'twitter_description' => 'Browse all tags on Nazaara Circle.',
            ],
            'tags.show' => [
                'meta_title' => '{title} - Articles | Nazaara Circle',
                'meta_description' => 'Browse articles tagged with this topic on Nazaara Circle.',
                'meta_keywords' => 'tag, articles, entertainment',
                'og_title' => '{title} - Articles | Nazaara Circle',
                'og_description' => 'Browse articles tagged with this topic on Nazaara Circle.',
                'twitter_title' => '{title} - Articles | Nazaara Circle',
                'twitter_description' => 'Browse articles tagged with this topic on Nazaara Circle.',
            ],
            'search' => [
                'meta_title' => 'Search Articles - Nazaara Circle',
                'meta_description' => 'Search for entertainment news, reviews, and biographies on Nazaara Circle.',
                'meta_keywords' => 'search, entertainment search, article search',
                'og_title' => 'Search Articles - Nazaara Circle',
                'og_description' => 'Search for entertainment news, reviews, and biographies on Nazaara Circle.',
                'twitter_title' => 'Search Articles - Nazaara Circle',
                'twitter_description' => 'Search for entertainment news, reviews, and biographies on Nazaara Circle.',
            ],
            'about' => [
                'meta_title' => 'About Us - Nazaara Circle',
                'meta_description' => 'Learn more about Nazaara Circle. Your destination for entertainment news, movie reviews, and celebrity biographies.',
                'meta_keywords' => 'about us, company information, mission, values, Nazaara Circle',
                'og_title' => 'About Us - Nazaara Circle',
                'og_description' => 'Learn more about Nazaara Circle. Your destination for entertainment news, movie reviews, and celebrity biographies.',
                'twitter_title' => 'About Us - Nazaara Circle',
                'twitter_description' => 'Learn more about Nazaara Circle. Your destination for entertainment news, movie reviews, and celebrity biographies.',
            ],
            'contact' => [
                'meta_title' => 'Contact Us - Nazaara Circle',
                'meta_description' => 'Get in touch with Nazaara Circle. We\'d love to hear from you.',
                'meta_keywords' => 'contact, get in touch, support, Nazaara Circle',
                'og_title' => 'Contact Us - Nazaara Circle',
                'og_description' => 'Get in touch with Nazaara Circle. We\'d love to hear from you.',
                'twitter_title' => 'Contact Us - Nazaara Circle',
                'twitter_description' => 'Get in touch with Nazaara Circle. We\'d love to hear from you.',
            ],
            'privacy' => [
                'meta_title' => 'Privacy Terms - Nazaara Circle',
                'meta_description' => 'Privacy policy and data protection information for Nazaara Circle. Learn how we protect your personal information and use cookies.',
                'meta_keywords' => 'privacy, policy, data protection, cookies, privacy terms',
                'og_title' => 'Privacy Terms - Nazaara Circle',
                'og_description' => 'Privacy policy and data protection information for Nazaara Circle.',
                'twitter_title' => 'Privacy Terms - Nazaara Circle',
                'twitter_description' => 'Privacy policy and data protection information for Nazaara Circle.',
            ],
            'terms' => [
                'meta_title' => 'Terms of Service - Nazaara Circle',
                'meta_description' => 'Terms of service and usage policy for Nazaara Circle.',
                'meta_keywords' => 'terms of service, terms, usage policy, Nazaara Circle',
                'og_title' => 'Terms of Service - Nazaara Circle',
                'og_description' => 'Terms of service and usage policy for Nazaara Circle.',
                'twitter_title' => 'Terms of Service - Nazaara Circle',
                'twitter_description' => 'Terms of service and usage policy for Nazaara Circle.',
            ],
        ];

        return array_merge($baseData, $pageSpecificData[$pageKey] ?? []);
    }

    /**
     * Get URL for a page key
     */
    protected function getPageUrl(string $pageKey, string $siteUrl): string
    {
        $paths = [
            'home' => '/',
            'articles.index' => '/articles',
            'categories.index' => '/categories',
            'tags.index' => '/tags',
            'search' => '/search',
            'about' => '/about',
            'contact' => '/contact',
            'privacy' => '/privacy',
            'terms' => '/terms',
        ];

        return $siteUrl . ($paths[$pageKey] ?? '/');
    }
}


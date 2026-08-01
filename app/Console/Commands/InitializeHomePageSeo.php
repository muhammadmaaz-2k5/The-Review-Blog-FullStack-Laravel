<?php

namespace App\Console\Commands;

use App\Models\PageSeo;
use Illuminate\Console\Command;

class InitializeHomePageSeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'page-seo:init-home';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize default SEO configuration for the home page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if home page SEO already exists
        $existing = PageSeo::where('page_key', 'home')->first();
        
        if ($existing) {
            $this->info('Home page SEO configuration already exists.');
            if ($this->confirm('Do you want to update it with default values?', false)) {
                $this->updateHomePageSeo($existing);
            }
            return 0;
        }

        // Create new home page SEO
        $this->createHomePageSeo();
        
        $this->info('✅ Home page SEO configuration has been created successfully!');
        return 0;
    }

    /**
     * Create home page SEO configuration
     */
    protected function createHomePageSeo()
    {
        $siteUrl = config('app.url', url('/'));
        $siteName = config('app.name', 'Nazaara Circle');

        // Create schema markup
        $schemaMarkup = [
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
        ];

        PageSeo::create([
            'page_key' => 'home',
            'page_name' => 'Home Page',
            'meta_title' => $siteName . ' - Entertainment News, Reviews & Biographies',
            'meta_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies. Stay updated with the latest pop culture trends.',
            'meta_keywords' => 'entertainment news, movie reviews, tv shows, series explained, celebrity biographies, pop culture, film analysis, show recaps, cinema, streaming guide',
            'meta_robots' => 'index, follow',
            'og_title' => $siteName . ' - Entertainment News, Reviews & Biographies',
            'og_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.',
            'og_image' => asset('icon.png'),
            'og_type' => 'website',
            'og_url' => $siteUrl,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $siteName . ' - Entertainment News, Reviews & Biographies',
            'twitter_description' => 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.',
            'twitter_image' => asset('icon.png'),
            'canonical_url' => $siteUrl,
            'schema_markup' => json_encode($schemaMarkup, JSON_PRETTY_PRINT),
            'hreflang_tags' => null,
            'is_active' => true,
        ]);
    }

    /**
     * Update existing home page SEO with defaults
     */
    protected function updateHomePageSeo(PageSeo $pageSeo)
    {
        $siteUrl = config('app.url', url('/'));
        $siteName = config('app.name', 'Nazaara Circle');

        // Create schema markup
        $schemaMarkup = [
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
        ];

        $pageSeo->update([
            'page_name' => 'Home Page',
            'meta_title' => $pageSeo->meta_title ?: ($siteName . ' - Entertainment News, Reviews & Biographies'),
            'meta_description' => $pageSeo->meta_description ?: 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies. Stay updated with the latest pop culture trends.',
            'meta_keywords' => $pageSeo->meta_keywords ?: 'entertainment news, movie reviews, tv shows, series explained, celebrity biographies, pop culture, film analysis, show recaps, cinema, streaming guide',
            'meta_robots' => $pageSeo->meta_robots ?: 'index, follow',
            'og_title' => $pageSeo->og_title ?: ($siteName . ' - Entertainment News, Reviews & Biographies'),
            'og_description' => $pageSeo->og_description ?: 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.',
            'og_image' => $pageSeo->og_image ?: asset('icon.png'),
            'og_type' => $pageSeo->og_type ?: 'website',
            'og_url' => $pageSeo->og_url ?: $siteUrl,
            'twitter_card' => $pageSeo->twitter_card ?: 'summary_large_image',
            'twitter_title' => $pageSeo->twitter_title ?: ($siteName . ' - Entertainment News, Reviews & Biographies'),
            'twitter_description' => $pageSeo->twitter_description ?: 'Your ultimate destination for entertainment news, in-depth movie reviews, TV series explained, and celebrity biographies.',
            'twitter_image' => $pageSeo->twitter_image ?: asset('icon.png'),
            'canonical_url' => $pageSeo->canonical_url ?: $siteUrl,
            'schema_markup' => $pageSeo->schema_markup ?: json_encode($schemaMarkup, JSON_PRETTY_PRINT),
            'is_active' => true,
        ]);

        $this->info('✅ Home page SEO configuration has been updated!');
    }
}


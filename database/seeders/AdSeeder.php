<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'name' => 'Global Popunder (Home/Articles)',
                'slug' => 'global-popunder-home',
                'placement' => 'global_popunder',
                'type' => 'adsterra_popunder',
                'ad_code' => '<script src="https://pl26946271.profitablecpmratenetwork.com/57/6e/45/576e4529ecf3c1cd93025b1f1fac0f58.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Popunder ad for home and articles pages (pl26946271)',
            ],
            [
                'name' => 'Popunder (Game/App/Tool Detail)',
                'slug' => 'detail-popunder',
                'placement' => 'detail_popunder',
                'type' => 'adsterra_popunder',
                'ad_code' => '<script src="https://pl28716042.profitablecpmratenetwork.com/d2/64/82/d2648253838b979ecd9d16f6454c2468.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Popunder for game/app/tool detail pages (pl28716042)',
            ],
            [
                'name' => 'Social Bar (Home/Articles)',
                'slug' => 'social-bar-home-articles',
                'placement' => 'page_social_bar',
                'type' => 'adsterra_social_bar',
                'ad_code' => '<script src="https://pl26946241.profitablecpmratenetwork.com/2c/7d/d6/2c7dd686677edaaee5222431ade8f17b.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Social bar for articles/home pages (pl26946241)',
            ],
            [
                'name' => 'Home - 728x90 Banner After Editor\'s Picks',
                'slug' => 'home-after-editors-picks',
                'placement' => 'home_after_editors_picks',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'5bc757b77888b5f4d01d2142b6a8b789\',
        \'format\' : \'iframe\',
        \'height\' : 90,
        \'width\' : 728,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/5bc757b77888b5f4d01d2142b6a8b789/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '728x90 iframe banner',
            ],
            [
                'name' => 'Home - Native Banner After Reviews',
                'slug' => 'home-after-reviews',
                'placement' => 'home_after_reviews',
                'type' => 'adsterra_native_banner',
                'ad_code' => '<script async="async" data-cfasync="false" src="https://pl26946571.profitablecpmratenetwork.com/315d3f293e1f88529f0a192b22f25591/invoke.js"></script>
<div id="container-315d3f293e1f88529f0a192b22f25591"></div>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Native banner (pl26946571)',
            ],
            [
                'name' => 'Home - 468x60 Banner After Games',
                'slug' => 'home-after-games',
                'placement' => 'home_after_games',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'84c2c60a3b7e96e1f44b1c1fe3da4608\',
        \'format\' : \'iframe\',
        \'height\' : 60,
        \'width\' : 468,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/84c2c60a3b7e96e1f44b1c1fe3da4608/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '468x60 iframe banner',
            ],
            [
                'name' => 'Home - 300x250 Banner After Apps',
                'slug' => 'home-after-apps',
                'placement' => 'home_after_apps',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'21d9d33f772d29ab441509f59e3161da\',
        \'format\' : \'iframe\',
        \'height\' : 250,
        \'width\' : 300,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/21d9d33f772d29ab441509f59e3161da/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '300x250 iframe banner',
            ],
            [
                'name' => 'Home - 320x50 Banner After Tools',
                'slug' => 'home-after-tools',
                'placement' => 'home_after_tools',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'977e38c7f521698969463047598372b4\',
        \'format\' : \'iframe\',
        \'height\' : 50,
        \'width\' : 320,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/977e38c7f521698969463047598372b4/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '320x50 iframe banner',
            ],
            [
                'name' => 'Home - 160x300 Banner After Dramas',
                'slug' => 'home-after-dramas',
                'placement' => 'home_after_dramas',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'0f1fc2d35d731b787f42fab1b28d5d18\',
        \'format\' : \'iframe\',
        \'height\' : 300,
        \'width\' : 160,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/0f1fc2d35d731b787f42fab1b28d5d18/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '160x300 iframe banner',
            ],
            [
                'name' => 'Home - AdSense Download Timer',
                'slug' => 'home-download-timer',
                'placement' => 'home_download_timer',
                'type' => 'adsense',
                'ad_code' => '@if(config(\'services.adsense.client_id\'))
    <ins class="adsbygoogle" style="display:block"
        data-ad-client="{{ config(\'services.adsense.client_id\') }}"
        data-ad-slot="{{ config(\'services.adsense.unit_1\') }}" data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endif',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'AdSense unit_1 in download timer',
            ],
            [
                'name' => 'Article - 728x90 Top Banner',
                'slug' => 'article-banner-top',
                'placement' => 'article_banner_top',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'5bc757b77888b5f4d01d2142b6a8b789\',
        \'format\' : \'iframe\',
        \'height\' : 90,
        \'width\' : 728,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/5bc757b77888b5f4d01d2142b6a8b789/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '728x90 banner below article hero',
            ],
            [
                'name' => 'Article - In Content AdSense',
                'slug' => 'article-in-content',
                'placement' => 'article_in_content',
                'type' => 'adsense',
                'ad_code' => '@if(config(\'services.adsense.client_id\'))
    <ins class="adsbygoogle" style="display:block"
        data-ad-client="{{ config(\'services.adsense.client_id\') }}"
        data-ad-slot="{{ config(\'services.adsense.unit_4\') }}" data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endif',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'AdSense unit_4 in article content',
            ],
            [
                'name' => 'Article - Native Banner',
                'slug' => 'article-native',
                'placement' => 'article_native',
                'type' => 'adsterra_native_banner',
                'ad_code' => '<script async="async" data-cfasync="false" src="https://pl26946571.profitablecpmratenetwork.com/315d3f293e1f88529f0a192b22f25591/invoke.js"></script>
<div id="container-315d3f293e1f88529f0a192b22f25591"></div>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Native banner after article content (pl26946571)',
            ],
            [
                'name' => 'Article - Sidebar Smartlink',
                'slug' => 'article-sidebar-smartlink',
                'placement' => 'article_sidebar_smartlink',
                'type' => 'adsterra_smartlink',
                'ad_code' => '<a href="https://www.profitablecpmratenetwork.com/s04vb8sfx1?key=7028545a3c7dc91b99874f6a999a3fd4"
    target="_blank" rel="noopener noreferrer"
    class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg transition-all text-sm font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
    </svg>
    <span>Sponsored</span>
</a>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Smartlink button in article share section',
            ],
            [
                'name' => 'Article - Before Comments AdSense',
                'slug' => 'article-before-comments',
                'placement' => 'article_before_comments',
                'type' => 'adsense',
                'ad_code' => '@if(config(\'services.adsense.client_id\'))
    <ins class="adsbygoogle" style="display:block"
        data-ad-client="{{ config(\'services.adsense.client_id\') }}"
        data-ad-slot="{{ config(\'services.adsense.unit_4\') }}" data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endif',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'AdSense unit_4 before comments',
            ],
            [
                'name' => 'Article - Before Comments 300x250 Banner',
                'slug' => 'article-before-comments-banner',
                'placement' => 'article_before_comments_banner',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'21d9d33f772d29ab441509f59e3161da\',
        \'format\' : \'iframe\',
        \'height\' : 250,
        \'width\' : 300,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/21d9d33f772d29ab441509f59e3161da/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '300x250 banner before comments',
            ],
            [
                'name' => 'Articles List - Top 728x90 Banner',
                'slug' => 'articles-top-banner',
                'placement' => 'articles_top_banner',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'5bc757b77888b5f4d01d2142b6a8b789\',
        \'format\' : \'iframe\',
        \'height\' : 90,
        \'width\' : 728,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/5bc757b77888b5f4d01d2142b6a8b789/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '728x90 banner on articles list page',
            ],
            [
                'name' => 'Articles List - Native Banner',
                'slug' => 'articles-native',
                'placement' => 'articles_native',
                'type' => 'adsterra_native_banner',
                'ad_code' => '<script async="async" data-cfasync="false" src="https://pl26946571.profitablecpmratenetwork.com/315d3f293e1f88529f0a192b22f25591/invoke.js"></script>
<div id="container-315d3f293e1f88529f0a192b22f25591"></div>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Native banner on articles list',
            ],
            [
                'name' => 'Articles List - Sidebar 300x250',
                'slug' => 'articles-sidebar',
                'placement' => 'articles_sidebar',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'21d9d33f772d29ab441509f59e3161da\',
        \'format\' : \'iframe\',
        \'height\' : 250,
        \'width\' : 300,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/21d9d33f772d29ab441509f59e3161da/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '300x250 sidebar banner on articles list',
            ],
            [
                'name' => 'Articles List - Sidebar 160x300',
                'slug' => 'articles-sidebar-2',
                'placement' => 'articles_sidebar_2',
                'type' => 'adsterra_banner',
                'ad_code' => '<script>
    atOptions = {
        \'key\' : \'0f1fc2d35d731b787f42fab1b28d5d18\',
        \'format\' : \'iframe\',
        \'height\' : 300,
        \'width\' : 160,
        \'params\' : {}
    };
</script>
<script src="https://www.highperformanceformat.com/0f1fc2d35d731b787f42fab1b28d5d18/invoke.js"></script>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => '160x300 sidebar banner on articles list',
            ],
            [
                'name' => 'Articles List - Smartlink Button',
                'slug' => 'articles-smartlink',
                'placement' => 'articles_smartlink',
                'type' => 'adsterra_smartlink',
                'ad_code' => '<a href="https://www.profitablecpmratenetwork.com/s04vb8sfx1?key=7028545a3c7dc91b99874f6a999a3fd4" target="_blank" rel="noopener noreferrer" class="px-8 py-3 bg-accent text-white font-bold rounded-full hover:bg-red-700 transition-all shadow-lg text-sm inline-block">Partner Offers</a>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Partner Offers smartlink button',
            ],
            [
                'name' => 'Detail Pages - Native Banner',
                'slug' => 'detail-native',
                'placement' => 'detail_native',
                'type' => 'adsterra_native_banner',
                'ad_code' => '<script async="async" data-cfasync="false" src="https://pl28716085.profitablecpmratenetwork.com/2dcde4021f774cd421ead66efa5652b0/invoke.js"></script>
<div id="container-2dcde4021f774cd421ead66efa5652b0"></div>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Native banner on game/app/tool detail (pl28716085)',
            ],
            [
                'name' => 'Detail Pages - Smartlink Button',
                'slug' => 'detail-smartlink',
                'placement' => 'detail_smartlink',
                'type' => 'adsterra_smartlink',
                'ad_code' => '<a href="https://www.profitablecpmratenetwork.com/hha7ew3gzg?key=1612bd47acc7712bf3f3c562d5814ea4" 
   target="_blank" 
   rel="noopener noreferrer"
   class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl transition-all text-sm font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
    </svg>
    <span>Sponsored</span>
</a>',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'Smartlink button on game/app/tool detail',
            ],
            [
                'name' => 'Detail Pages - Sidebar AdSense',
                'slug' => 'detail-sidebar-adsense',
                'placement' => 'detail_sidebar_adsense',
                'type' => 'adsense',
                'ad_code' => '@if(config(\'services.adsense.client_id\'))
    <ins class="adsbygoogle" style="display:block"
        data-ad-client="{{ config(\'services.adsense.client_id\') }}"
        data-ad-slot="{{ config(\'services.adsense.unit_1\') }}" data-ad-format="auto"
        data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
@endif',
                'is_active' => true,
                'sort_order' => 0,
                'description' => 'AdSense in game/app/tool detail sidebar',
            ],
        ];

        foreach ($ads as $ad) {
            Ad::updateOrCreate(['slug' => $ad['slug']], $ad);
        }

        $this->command->info('Seeded ' . count($ads) . ' ads.');
    }
}

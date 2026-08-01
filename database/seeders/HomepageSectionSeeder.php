<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSection;
use App\Models\Category;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sections
        HomepageSection::truncate();

        // Get category IDs by slug
        $biographies = Category::where('slug', 'biographies')->first();
        $movieReviews = Category::where('slug', 'movie-reviews')->first();
        $celebrityNews = Category::where('slug', 'celebrity-news')->first();
        $koreanDramas = Category::where('slug', 'korean-dramas')->first();
        $webSeries = Category::where('slug', 'web-series')->first();

        // Create homepage sections
        $sections = [
            [
                'name' => 'Biographies',
                'slug' => 'biographies',
                'display_order' => 1,
                'is_active' => true,
                'category_ids' => $biographies ? [$biographies->id] : [],
                'articles_per_section' => 4,
                'section_title' => 'Biographies',
            ],
            [
                'name' => 'Movie Reviews',
                'slug' => 'movie-reviews',
                'display_order' => 2,
                'is_active' => true,
                'category_ids' => $movieReviews ? [$movieReviews->id] : [],
                'articles_per_section' => 4,
                'section_title' => 'Movie Reviews',
            ],
            [
                'name' => 'Celebrity News',
                'slug' => 'celebrity-news',
                'display_order' => 3,
                'is_active' => true,
                'category_ids' => $celebrityNews ? [$celebrityNews->id] : [],
                'articles_per_section' => 4,
                'section_title' => 'Celebrity News',
            ],
            [
                'name' => 'K-Dramas',
                'slug' => 'k-dramas',
                'display_order' => 4,
                'is_active' => true,
                'category_ids' => $koreanDramas ? [$koreanDramas->id] : [],
                'articles_per_section' => 4,
                'section_title' => 'Korean Dramas',
            ],
            [
                'name' => 'Web Series',
                'slug' => 'web-series',
                'display_order' => 5,
                'is_active' => true,
                'category_ids' => $webSeries ? [$webSeries->id] : [],
                'articles_per_section' => 4,
                'section_title' => 'Web Series',
            ],
        ];

        foreach ($sections as $sectionData) {
            HomepageSection::create($sectionData);
        }

        $this->command->info('Homepage sections seeded successfully!');
    }
}

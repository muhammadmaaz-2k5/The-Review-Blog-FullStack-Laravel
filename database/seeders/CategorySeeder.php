<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pakistani Dramas',
                'slug' => 'pakistani-dramas',
                'description' => 'Reviews, updates, and discussions about the latest Pakistani drama serials.',
                'color' => '#10B981', // Emerald
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Indian Dramas',
                'slug' => 'indian-dramas',
                'description' => 'Updates and reviews of trending Indian television serials and web series.',
                'color' => '#F59E0B', // Amber
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Korean Dramas',
                'slug' => 'korean-dramas',
                'description' => 'K-Drama reviews, recommendations, and celebrity news.',
                'color' => '#EC4899', // Pink
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Turkish Series',
                'slug' => 'turkish-series',
                'description' => 'Reviews and updates on popular Turkish dramas and historical series.',
                'color' => '#EF4444', // Red
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Web Series',
                'slug' => 'web-series',
                'description' => 'Reviews of digital content from platforms like Netflix, Prime, and Zee5.',
                'color' => '#8B5CF6', // Violet
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Movie Reviews',
                'slug' => 'movie-reviews',
                'description' => 'In-depth reviews of the latest Lollywood, Bollywood, and Hollywood releases.',
                'color' => '#3B82F6', // Blue
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Celebrity News',
                'slug' => 'celebrity-news',
                'description' => 'Latest updates, gossip, and interviews from the entertainment world.',
                'color' => '#F97316', // Orange
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'OST & Music',
                'slug' => 'ost-music',
                'description' => 'Reviews and lyrics of trending drama OSTs and music videos.',
                'color' => '#14B8A6', // Teal
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Biographies',
                'slug' => 'biographies',
                'description' => 'Life stories of famous personalities in the entertainment industry.',
                'color' => '#6366F1', // Indigo
                'sort_order' => 9,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}


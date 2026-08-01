<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Channels & Platforms
            'Hum TV', 'ARY Digital', 'Geo Entertainment', 'Green TV', 'Express TV',
            'Netflix', 'Amazon Prime', 'Zee5', 'Sony LIV', 'Disney+',
            'YouTube', 'UrduFlix', 'Tamasha',

            // Content Types
            'Episode Review', 'Drama Review', 'Movie Review', 'Teaser Review', 
            'Trailer', 'OST', 'First Impression', 'Full Review', 'Recap',
            'Prediction', 'Analysis', 'Ending Explained',

            // Genres
            'Romance', 'Thriller', 'Comedy', 'Tragedy', 'Family Drama', 
            'Social Issue', 'Period Drama', 'Action', 'Horror', 'Mystery',
            'Fantasy', 'Biographical',

            // Industries
            'Pakistani Drama', 'Indian Drama', 'K-Drama', 'Turkish Drama',
            'Lollywood', 'Bollywood', 'Hollywood', 'Web Series',

            // Celebrities (Generic)
            'Celebrity News', 'Interview', 'Lifestyle', 'Wedding', 'Controversy',
            'Awards', 'Red Carpet', 'Fashion', 'Viral', 'Trending',

            // Specific Terms
            'TRP', 'Blockbuster', 'Flop', 'Hit', 'Must Watch', 'Underrated',
            'Binge Watch', 'Season Finale', 'New Season', 'Cast & Crew',
            'Jim Curtis', 'Biography',
            'Harry Styles', 'Amex Presale', 'Concert', 'Tickets', 'Madison Square Garden',
            'Patrick Dempsey', 'Grey\'s Anatomy', 'Memory of a Killer',
            'Travis Scott', 'Christopher Nolan', 'The Odyssey',
            'Selma Blair',
            'Quinton Aaron', 'Sandra Bullock', 'The Blind Side',
        ];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($tagName)],
                ['name' => $tagName]
            );
        }
    }
}

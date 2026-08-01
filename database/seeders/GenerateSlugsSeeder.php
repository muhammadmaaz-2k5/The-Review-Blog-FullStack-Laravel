<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;

class GenerateSlugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate slugs for existing articles
        $articles = Article::whereNull('slug')->get();
        foreach ($articles as $article) {
            $article->slug = $article->generateUniqueSlug();
            $article->save();
            $this->command->info("Generated slug for article: {$article->title} -> {$article->slug}");
        }

        // Generate slugs for existing categories
        $categories = Category::whereNull('slug')->get();
        foreach ($categories as $category) {
            $category->slug = $category->generateUniqueSlug();
            $category->save();
            $this->command->info("Generated slug for category: {$category->name} -> {$category->slug}");
        }

        // Generate slugs for existing tags
        $tags = Tag::whereNull('slug')->get();
        foreach ($tags as $tag) {
            $tag->slug = $tag->generateUniqueSlug();
            $tag->save();
            $this->command->info("Generated slug for tag: {$tag->name} -> {$tag->slug}");
        }

        $this->command->info("✅ All slugs generated successfully!");
    }
}


<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Str;

class SeoAuditService
{
    /**
     * Audit an article for SEO issues
     */
    public function auditArticle(Article $article): array
    {
        $issues = [];
        $score = 100;
        $recommendations = [];

        // Title check
        if (empty($article->meta_title) && empty($article->title)) {
            $issues[] = ['type' => 'critical', 'field' => 'title', 'message' => 'Article has no title'];
            $score -= 20;
        } elseif (!empty($article->title)) {
            $titleLength = mb_strlen($article->title);
            if ($titleLength < 30) {
                $issues[] = ['type' => 'warning', 'field' => 'title', 'message' => "Title is too short ({$titleLength} chars). Recommended: 50-60 characters"];
                $score -= 5;
            } elseif ($titleLength > 60) {
                $issues[] = ['type' => 'warning', 'field' => 'title', 'message' => "Title is too long ({$titleLength} chars). Recommended: 50-60 characters"];
                $score -= 3;
            }
        }

        // Meta description check
        if (empty($article->meta_description) && empty($article->excerpt)) {
            $issues[] = ['type' => 'warning', 'field' => 'meta_description', 'message' => 'No meta description set. Auto-generate from content or add manually'];
            $score -= 10;
        } elseif (!empty($article->meta_description)) {
            $descLength = mb_strlen($article->meta_description);
            if ($descLength < 120) {
                $issues[] = ['type' => 'warning', 'field' => 'meta_description', 'message' => "Meta description is too short ({$descLength} chars). Recommended: 150-160 characters"];
                $score -= 5;
            } elseif ($descLength > 160) {
                $issues[] = ['type' => 'warning', 'field' => 'meta_description', 'message' => "Meta description is too long ({$descLength} chars). Recommended: 150-160 characters"];
                $score -= 3;
            }
        }

        // Content check
        if (empty($article->content)) {
            $issues[] = ['type' => 'critical', 'field' => 'content', 'message' => 'Article has no content'];
            $score -= 30;
        } else {
            $wordCount = str_word_count(strip_tags($article->content));
            if ($wordCount < 300) {
                $issues[] = ['type' => 'warning', 'field' => 'content', 'message' => "Content is too short ({$wordCount} words). Recommended: 1000+ words for better SEO"];
                $score -= 10;
            } elseif ($wordCount < 1000) {
                $issues[] = ['type' => 'info', 'field' => 'content', 'message' => "Content length is good ({$wordCount} words). Consider expanding to 1000+ words for better SEO"];
                $score -= 2;
            }
        }

        // Featured image check
        if (empty($article->featured_image)) {
            $issues[] = ['type' => 'warning', 'field' => 'featured_image', 'message' => 'No featured image set. Images improve engagement and social sharing'];
            $score -= 10;
        }

        // Category check
        if (empty($article->category_id)) {
            $issues[] = ['type' => 'warning', 'field' => 'category', 'message' => 'Article is not assigned to a category'];
            $score -= 5;
        }

        // Tags check
        if ($article->tags->isEmpty()) {
            $issues[] = ['type' => 'warning', 'field' => 'tags', 'message' => 'No tags assigned. Tags help with discoverability'];
            $score -= 5;
        } elseif ($article->tags->count() < 3) {
            $issues[] = ['type' => 'info', 'field' => 'tags', 'message' => 'Consider adding more tags (currently: ' . $article->tags->count() . ')'];
            $score -= 2;
        }

        // Slug check
        if (empty($article->slug)) {
            $issues[] = ['type' => 'critical', 'field' => 'slug', 'message' => 'Article has no slug'];
            $score -= 15;
        } else {
            // Check if slug contains keywords
            $titleWords = str_word_count(strtolower($article->title), 1);
            $slugWords = explode('-', $article->slug);
            $matches = count(array_intersect($titleWords, $slugWords));
            if ($matches < 2) {
                $issues[] = ['type' => 'info', 'field' => 'slug', 'message' => 'Slug should contain main keywords from title'];
                $score -= 2;
            }
        }

        // Keywords check
        if (empty($article->meta_keywords)) {
            $issues[] = ['type' => 'info', 'field' => 'meta_keywords', 'message' => 'Consider adding meta keywords for better targeting'];
            $score -= 2;
        }

        // OG Image check
        if (empty($article->og_image) && empty($article->featured_image)) {
            $issues[] = ['type' => 'warning', 'field' => 'og_image', 'message' => 'No Open Graph image set. This affects social media sharing'];
            $score -= 5;
        }

        // Reading time check
        if (empty($article->reading_time) || $article->reading_time < 1) {
            $issues[] = ['type' => 'info', 'field' => 'reading_time', 'message' => 'Reading time not calculated'];
            $score -= 1;
        }

        // Generate recommendations
        if ($score < 70) {
            $recommendations[] = 'Priority: Fix critical issues first';
        }
        if (empty($article->featured_image)) {
            $recommendations[] = 'Add a high-quality featured image (1200x630px recommended)';
        }
        if ($wordCount < 1000 && isset($wordCount)) {
            $recommendations[] = 'Expand content to 1000+ words for better SEO performance';
        }
        if ($article->tags->count() < 5) {
            $recommendations[] = 'Add 5-10 relevant tags for better discoverability';
        }
        if (empty($article->meta_description)) {
            $recommendations[] = 'Write a compelling meta description (150-160 characters)';
        }

        return [
            'score' => max(0, $score),
            'grade' => $this->getGrade($score),
            'issues' => $issues,
            'recommendations' => $recommendations,
            'stats' => [
                'title_length' => mb_strlen($article->title ?? ''),
                'meta_description_length' => mb_strlen($article->meta_description ?? $article->excerpt ?? ''),
                'content_word_count' => isset($wordCount) ? $wordCount : 0,
                'tags_count' => $article->tags->count(),
                'has_featured_image' => !empty($article->featured_image),
                'has_og_image' => !empty($article->og_image),
                'has_category' => !empty($article->category_id),
            ],
        ];
    }

    /**
     * Get SEO grade from score
     */
    protected function getGrade(int $score): string
    {
        if ($score >= 90) return 'A+';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'F';
    }

    /**
     * Calculate keyword density
     */
    public function calculateKeywordDensity(string $content, string $keyword): float
    {
        $content = strtolower(strip_tags($content));
        $keyword = strtolower($keyword);
        
        $wordCount = str_word_count($content);
        $keywordCount = substr_count($content, $keyword);
        
        if ($wordCount === 0) return 0;
        
        return round(($keywordCount / $wordCount) * 100, 2);
    }

    /**
     * Extract keywords from content
     */
    public function extractKeywords(string $content, int $limit = 10): array
    {
        $content = strtolower(strip_tags($content));
        $words = str_word_count($content, 1);
        
        // Remove common stop words
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'should', 'could', 'may', 'might', 'must', 'can', 'this', 'that', 'these', 'those'];
        
        $words = array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 3 && !in_array($word, $stopWords);
        });
        
        $wordFreq = array_count_values($words);
        arsort($wordFreq);
        
        return array_slice(array_keys($wordFreq), 0, $limit);
    }

    /**
     * Check content readability
     */
    public function checkReadability(string $content): array
    {
        $text = strip_tags($content);
        $sentences = preg_split('/[.!?]+/', $text);
        $words = str_word_count($text);
        $syllables = $this->countSyllables($text);
        
        $avgSentenceLength = $words / max(count($sentences), 1);
        $avgSyllablesPerWord = $syllables / max($words, 1);
        
        // Flesch Reading Ease Score
        $fleschScore = 206.835 - (1.015 * $avgSentenceLength) - (84.6 * $avgSyllablesPerWord);
        
        return [
            'flesch_score' => round($fleschScore, 2),
            'readability_level' => $this->getReadabilityLevel($fleschScore),
            'avg_sentence_length' => round($avgSentenceLength, 2),
            'avg_syllables_per_word' => round($avgSyllablesPerWord, 2),
        ];
    }

    /**
     * Count syllables in text
     */
    protected function countSyllables(string $text): int
    {
        $text = strtolower($text);
        $words = str_word_count($text, 1);
        $syllables = 0;
        
        foreach ($words as $word) {
            $word = preg_replace('/[^a-z]/', '', $word);
            if (strlen($word) <= 3) {
                $syllables += 1;
                continue;
            }
            
            $word = preg_replace('/e$/', '', $word);
            $vowels = preg_match_all('/[aeiouy]+/', $word);
            $syllables += max(1, $vowels);
        }
        
        return $syllables;
    }

    /**
     * Get readability level from Flesch score
     */
    protected function getReadabilityLevel(float $score): string
    {
        if ($score >= 90) return 'Very Easy';
        if ($score >= 80) return 'Easy';
        if ($score >= 70) return 'Fairly Easy';
        if ($score >= 60) return 'Standard';
        if ($score >= 50) return 'Fairly Difficult';
        if ($score >= 30) return 'Difficult';
        return 'Very Difficult';
    }
}


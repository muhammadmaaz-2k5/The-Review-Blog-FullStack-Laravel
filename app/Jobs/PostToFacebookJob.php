<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\FacebookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostToFacebookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    /**
     * Create a new job instance.
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(FacebookService $facebookService): void
    {
        // Only post if article is published
        if ($this->article->status !== 'published') {
            Log::info('Skipping Facebook post - article is not published', [
                'article_id' => $this->article->id,
                'status' => $this->article->status,
            ]);
            return;
        }

        // Check if Facebook is enabled
        if (!config('services.facebook.enabled', false)) {
            Log::info('Skipping Facebook post - Facebook integration is disabled');
            return;
        }

        $result = $facebookService->postArticle($this->article);

        if ($result['success']) {
            Log::info('Article posted to Facebook via job', [
                'article_id' => $this->article->id,
                'post_id' => $result['post_id'] ?? null,
            ]);
        } else {
            Log::warning('Failed to post article to Facebook via job', [
                'article_id' => $this->article->id,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
        }
    }
}


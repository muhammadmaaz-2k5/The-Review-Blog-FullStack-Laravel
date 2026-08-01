<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\InstagramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostToInstagramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function handle(InstagramService $instagramService): void
    {
        if ($this->article->status !== 'published') {
            Log::info('Skipping Instagram post - article is not published', [
                'article_id' => $this->article->id,
                'status' => $this->article->status,
            ]);
            return;
        }

        if (!config('services.instagram.enabled', false)) {
            Log::info('Skipping Instagram post - Instagram integration is disabled');
            return;
        }

        $result = $instagramService->postArticle($this->article);

        if ($result['success']) {
            Log::info('Article posted to Instagram via job', [
                'article_id' => $this->article->id,
                'post_id' => $result['post_id'] ?? null,
            ]);
        } else {
            Log::warning('Failed to post article to Instagram via job', [
                'article_id' => $this->article->id,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
        }
    }
}


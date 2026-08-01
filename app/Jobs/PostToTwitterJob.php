<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\TwitterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostToTwitterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function handle(TwitterService $twitterService): void
    {
        if ($this->article->status !== 'published') {
            Log::info('Skipping Twitter post - article is not published', [
                'article_id' => $this->article->id,
                'status' => $this->article->status,
            ]);
            return;
        }

        if (!config('services.twitter.enabled', false)) {
            Log::info('Skipping Twitter post - Twitter integration is disabled');
            return;
        }

        $result = $twitterService->postArticle($this->article);

        if ($result['success']) {
            Log::info('Article posted to Twitter via job', [
                'article_id' => $this->article->id,
                'tweet_id' => $result['tweet_id'] ?? null,
            ]);
        } else {
            Log::warning('Failed to post article to Twitter via job', [
                'article_id' => $this->article->id,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
        }
    }
}


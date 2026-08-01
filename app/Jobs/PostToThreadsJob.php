<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\ThreadsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostToThreadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function handle(ThreadsService $threadsService): void
    {
        if ($this->article->status !== 'published') {
            Log::info('Skipping Threads post - article is not published', [
                'article_id' => $this->article->id,
                'status' => $this->article->status,
            ]);
            return;
        }

        if (!config('services.threads.enabled', false)) {
            Log::info('Skipping Threads post - Threads integration is disabled');
            return;
        }

        $result = $threadsService->postArticle($this->article);

        if ($result['success']) {
            Log::info('Article posted to Threads via job', [
                'article_id' => $this->article->id,
                'thread_id' => $result['thread_id'] ?? null,
            ]);
        } else {
            Log::warning('Failed to post article to Threads via job', [
                'article_id' => $this->article->id,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
        }
    }
}


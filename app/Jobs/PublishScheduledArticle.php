<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishScheduledArticle implements ShouldQueue
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
    public function handle(): void
    {
        $article = Article::find($this->article->id);

        if (!$article) {
            Log::warning("Scheduled article #{$this->article->id} not found for publishing.");
            return;
        }

        if ($article->status === 'scheduled' && $article->published_at && $article->published_at->isPast()) {
            $article->update([
                'status' => 'published',
            ]);

            Log::info("Article #{$article->id} '{$article->title}' has been published successfully.");

            // Note: Social media posting is optional and only happens when explicitly selected
            // via checkboxes during manual article creation/editing. Scheduled articles
            // will not automatically post to social media unless configured to do so.
        }
    }
}

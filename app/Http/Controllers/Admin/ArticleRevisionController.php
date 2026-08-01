<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleRevision;
use Illuminate\Http\Request;

class ArticleRevisionController extends Controller
{
    /**
     * Show revision history for an article
     */
    public function index(Article $article)
    {
        $revisions = $article->revisions()->with('creator')->get();
        
        return view('admin.articles.revisions', compact('article', 'revisions'));
    }

    /**
     * Show a specific revision
     */
    public function show(Article $article, ArticleRevision $revision)
    {
        return view('admin.articles.revision-show', compact('article', 'revision'));
    }

    /**
     * Compare two revisions
     */
    public function compare(Article $article, ArticleRevision $revision1, ArticleRevision $revision2 = null)
    {
        if (!$revision2) {
            // Compare with current article
            $current = [
                'title' => $article->title,
                'content' => $article->content,
                'excerpt' => $article->excerpt,
            ];
            $revision = [
                'title' => $revision1->title,
                'content' => $revision1->content,
                'excerpt' => $revision1->excerpt,
            ];
        } else {
            $revision = [
                'title' => $revision1->title,
                'content' => $revision1->content,
                'excerpt' => $revision1->excerpt,
            ];
            $current = [
                'title' => $revision2->title,
                'content' => $revision2->content,
                'excerpt' => $revision2->excerpt,
            ];
        }

        return view('admin.articles.revision-compare', compact('article', 'revision1', 'revision2', 'revision', 'current'));
    }

    /**
     * Restore a revision
     */
    public function restore(Article $article, ArticleRevision $revision)
    {
        // Create a revision of current state before restoring
        $article->createRevision(auth()->id(), 'Before restoring revision #' . $revision->revision_number);

        // Restore from revision
        $article->update([
            'title' => $revision->title,
            'excerpt' => $revision->excerpt,
            'content' => $revision->content,
            'featured_image' => $revision->featured_image,
            'category_id' => $revision->category_id,
            'status' => $revision->status,
            'is_featured' => $revision->is_featured,
            'allow_comments' => $revision->allow_comments,
            'published_at' => $revision->published_at,
            'meta' => $revision->meta,
        ]);

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Revision restored successfully. A new revision was created from the previous state.');
    }
}

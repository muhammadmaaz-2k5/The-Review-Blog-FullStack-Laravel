<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Article;

class ArticlePolicy
{
    /**
     * Determine if the user can view any articles.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAuthor();
    }

    /**
     * Determine if the user can view the article.
     */
    public function view(User $user, Article $article): bool
    {
        // Users can view their own articles, or if they're admin/author
        return $user->isAdmin() || $user->isAuthor() || $article->author_id === $user->id;
    }

    /**
     * Determine if the user can create articles.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAuthor();
    }

    /**
     * Determine if the user can update the article.
     */
    public function update(User $user, Article $article): bool
    {
        // Admins can update any article
        if ($user->isAdmin()) {
            return true;
        }

        // Authors can update their own articles
        return $user->isAuthor() && $article->author_id === $user->id;
    }

    /**
     * Determine if the user can delete the article.
     */
    public function delete(User $user, Article $article): bool
    {
        // Only admins can delete articles
        return $user->isAdmin();
    }

    /**
     * Determine if the user can restore the article.
     */
    public function restore(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the article.
     */
    public function forceDelete(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }
}


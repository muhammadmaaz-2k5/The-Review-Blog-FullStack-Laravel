<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

class CommentPolicy
{
    /**
     * Determine if the user can view any comments.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAuthor();
    }

    /**
     * Determine if the user can view the comment.
     */
    public function view(User $user, Comment $comment): bool
    {
        return $user->isAdmin() || $user->isAuthor();
    }

    /**
     * Determine if the user can create comments.
     */
    public function create(User $user): bool
    {
        // Authenticated users can create comments
        return true;
    }

    /**
     * Determine if the user can update the comment.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Users can update their own comments, admins can update any
        return $user->isAdmin() || ($comment->user_id && $comment->user_id === $user->id);
    }

    /**
     * Determine if the user can delete the comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Admins can delete any comment, users can delete their own
        return $user->isAdmin() || ($comment->user_id && $comment->user_id === $user->id);
    }
}


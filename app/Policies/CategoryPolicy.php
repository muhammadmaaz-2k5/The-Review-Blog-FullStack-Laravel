<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    /**
     * Determine if the user can view any categories.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can view the category.
     */
    public function view(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can create categories.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can update the category.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete the category.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can restore the category.
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->isAdmin();
    }
}


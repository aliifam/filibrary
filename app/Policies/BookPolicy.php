<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        //if super admin can see all books if not he can see only his books
        return $user->can('view_any_book');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return bool
     */
    public function view(User $user, Book $book): bool
    {
        //if super admin can see all books
        // if (!$user->hasRole('super-admin')) {
        //     //if user is not super admin, he can see only his books
        //     dd($user->can('view_book') && $user->id === $book->user_id);
        //     return $user->can('view_book') && $user->id === $book->user_id;
        // }
        return $user->can('view_book');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_book');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return bool
     */
    public function update(User $user, Book $book): bool
    {
        return $user->can('update_book');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return bool
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->can('delete_book');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_book');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return bool
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return $user->can('force_delete_book');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_book');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return bool
     */
    public function restore(User $user, Book $book): bool
    {
        return $user->can('restore_book');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_book');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return bool
     */
    public function replicate(User $user, Book $book): bool
    {
        return $user->can('replicate_book');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_book');
    }
}

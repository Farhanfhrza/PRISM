<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Request_detail;
use Illuminate\Auth\Access\HandlesAuthorization;

class Request_detailPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_request::detail');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Request_detail $requestDetail): bool
    {
        return $user->can('view_request::detail');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_request::detail');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Request_detail $requestDetail): bool
    {
        return $user->can('update_request::detail');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Request_detail $requestDetail): bool
    {
        return $user->can('delete_request::detail');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_request::detail');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Request_detail $requestDetail): bool
    {
        return $user->can('force_delete_request::detail');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_request::detail');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Request_detail $requestDetail): bool
    {
        return $user->can('restore_request::detail');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_request::detail');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Request_detail $requestDetail): bool
    {
        return $user->can('replicate_request::detail');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_request::detail');
    }
}

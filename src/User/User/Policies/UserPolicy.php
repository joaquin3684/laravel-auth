<?php

namespace Hitocean\LaravelAuth\User\User\Policies;

use Hitocean\LaravelAuth\User\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view_any_user');
    }

    public function view(User $user, User $u)
    {
        if ($u->hasRole('super_admin')) {
            return $user->hasRole('super_admin');
        }

        return $user->can('view_user');
    }

    public function create(User $user)
    {
        return $user->can('create_user');
    }

    public function update(User $user)
    {
        return $user->can('update_user');
    }

    public function delete(User $user)
    {
        return $user->can('delete_user');
    }

    public function deleteAny(User $user)
    {
        return $user->can('delete_any_user');
    }
}

<?php

namespace Hitocean\LaravelAuth\User\Role\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Hitocean\LaravelAuth\User\User\Models\User;

class RolePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return $user->can('view_any_role');
    }


    public function view(User $user): bool
    {
        return $user->can('view_role');
    }


    public function create(User $user)
    {
        return $user->can('create_role');
    }


    public function update(User $user)
    {
        return $user->can('update_role');
    }


    public function delete(User $user)
    {
        return $user->can('delete_role');
    }


    public function deleteAny(User $user)
    {
        return $user->can('delete_any_role');
    }

}

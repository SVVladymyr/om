<?php

namespace App\Policies;

use App\User;
use App\Limit;
use Illuminate\Auth\Access\HandlesAuthorization;

class LimitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the limit.
     *
     * @param  \App\User  $user
     * @param  \App\Limit  $limit
     * @return mixed
     */
    public function view(User $user, Limit $limit)
    {
        //
    }

    /**
     * Determine whether the user can create limits.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the limit.
     *
     * @param  \App\User  $user
     * @param  \App\Limit  $limit
     * @return mixed
     */
    public function update(User $user, Limit $limit)
    {
        //
    }

    /**
     * Determine whether the user can delete the limit.
     *
     * @param  \App\User  $user
     * @param  \App\Limit  $limit
     * @return mixed
     */
    public function delete(User $user, Limit $limit)
    {
        //
    }
}

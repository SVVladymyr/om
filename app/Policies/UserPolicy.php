<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $authenticated)
    {
        return $authenticated->isClientAdmin() || 
                $authenticated->isCompanyAdmin();
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $authenticated, User $user)
    {
        return $authenticated->isClientAdmin() && 
                    $authenticated->employer->id == $user->employer->id || 
                $authenticated->isCompanyAdmin() ||
                $authenticated->isManager() && 
                    $authenticated->client->id == $user->employer->id;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $authenticated)
    {
        return $authenticated->isCompanyAdmin() || 
                $authenticated->isClientAdmin() && $authenticated->subject;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $authenticated, User $user)
    {
        return (($authenticated->isClientAdmin() && 
                    $authenticated->employer->id == $user->employer->id) ||
                 $authenticated->isCompanyAdmin()) &&
                    $authenticated->id != $user->id;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        //
    }
}

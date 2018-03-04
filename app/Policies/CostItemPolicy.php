<?php

namespace App\Policies;

use App\User;
use App\CostItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the costItem.
     *
     * @param  \App\User  $user
     * @param  \App\CostItem  $costItem
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->isClientAdmin();
    }

    public function view(User $user, CostItem $costItem)
    {
        return $user->isClientAdmin() &&
                $costItem->client->id =  $user->subject->id;
    }

    /**
     * Determine whether the user can create costItems.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isClientAdmin() && $user->subject;
    }

    /**
     * Determine whether the user can update the costItem.
     *
     * @param  \App\User  $user
     * @param  \App\CostItem  $costItem
     * @return mixed
     */
    public function update(User $user, CostItem $costItem)
    {
        return $user->isClientAdmin() && $user->subject && $costItem->client->id == $user->subject->id;
    }

    /**
     * Determine whether the user can delete the costItem.
     *
     * @param  \App\User  $user
     * @param  \App\CostItem  $costItem
     * @return mixed
     */
    public function delete(User $user, CostItem $costItem)
    {
        return $user->isClientAdmin() && $costItem->client->master->id == $user->id;
    }
}

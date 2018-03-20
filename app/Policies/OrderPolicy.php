<?php

namespace App\Policies;

use App\User;
use App\Order;
use App\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return $user->isCompanyAdmin() ||
                ($user->isClientAdmin() && $user->subject &&
                    $order->client->root->id == $user->subject->id) ||
                ($user->isManager() && $order->client->root->manager->id == $user->id) ||
                (($user->isSublevel() && $user->subject) &&
                    ($order->client->id == $user->subject->id ||
                    (in_array($order->client->id, 
                            ($user->subject->expand_network())
                                    ->pluck('id')->all())))) ||
                ($user->isConsumer() && $user->subject &&
                    $order->client->id == $user->subject->id)
         ;
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        return ($user->isClientAdmin() && $user->subject &&
                    $order->client->root->id == $user->subject->id) ||
                (($user->isSublevel() && $user->subject) &&
                    ($order->client->id == $user->subject->id ||
                    (in_array($order->client->id, 
                            ($user->subject->expand_network())
                                    ->pluck('id')->all())))) ||
                ($user->isConsumer() && $user->subject &&
                    $order->client->id == $user->subject->id)
         ;
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        //
    }

    public function mass_set_status_date(User $user, Order $order)//maybe check status if can change one
    {
        return ($user->isClientAdmin() && $user->subject &&
                    $order->client->root->id == $user->subject->id) ||
                ($user->isManager() && $order->client->root->manager->id == $user->id) ||
                (($user->isSublevel() && $user->subject) &&
                    ($order->client->id == $user->subject->id ||
                    (in_array($order->client->id, 
                            ($user->subject->expand_network())
                                    ->pluck('id')->all())))) ||
                ($user->isConsumer() && $user->subject &&
                    $order->client->id == $user->subject->id)
         ;
    }
}

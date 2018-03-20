<?php

namespace App\Policies;

use App\User;
use App\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

use App\Http\Cntrollers\ClientController;

class ClientPolicy
{
    use HandlesAuthorization;

    public function limits(User $user, Client $client)
    {
        return ($user->isClientAdmin() && $user->subject &&
                    $client->root->id == $user->subject->id);
    }

    public function user_orders(User $user)
    {
        return $user->isCompanyAdmin() ||
                ($user->isClientAdmin() && $user->subject) ||
                ($user->isManager() && count($user->clients)) ||
                ($user->isSublevel() && $user->subject) ||
                ($user->isConsumer() && $user->subject)
        ;
    }

    public function orders(User $user, Client $client)
    {
        return $user->isCompanyAdmin() ||
                ($user->isClientAdmin() && $user->subject &&
                    $client->root->id == $user->subject->id) ||
                ($user->isManager() && $client->root->manager->id == $user->id) ||
                ($user->isSublevel() && $user->subject &&
                    ($client->id == $user->subject->id ||
                    (in_array($client->id, 
                            ($user->subject->expand_network())
                                    ->pluck('id')->all())))) ||
                ($user->isConsumer() && $user->subject &&
                    $client->id == $user->subject->id)
        ;
    }

    public function create_order(User $user, Client $client)
    {
        return ($user->isClientAdmin() && $user->subject &&
                    $client->root->id == $user->subject->id) ||
                ($user->isSublevel() && $user->subject &&
                    ($client->id == $user->subject->id ||
                    (in_array($client->id, 
                            ($user->subject->expand_network())
                                    ->pluck('id')->all())))) ||
                ($user->isConsumer() && $user->subject &&
                    $client->id == $user->subject->id)
        ;
    }

    public function limit_increases(User $user, Client $client)
    {
        return ($user->isClientAdmin() && $user->subject &&
                    $client->root->id == $user->subject->id)
        ;
    }

    public function limit_increase_request(User $user, Client $client)
    {
        return ($user->isSublevel() && $user->subject &&
                    ($client->id == $user->subject->id ||
                    (in_array($client->id, 
                            ($user->subject->expand_network())
                                    ->pluck('id')->all())))) ||
                ($user->isConsumer() && $user->subject &&
                    $client->id == $user->subject->id)
        ;
    }
    /**
     * Determine whether the user can view the client.
     *
     * @param  \App\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function view(User $user, Client $client)//??
    {
        return $user->isCompanyAdmin() ||
                ($user->isClientAdmin() && $user->subject &&
                    $client->root->id == $user->subject->id) ||
                ($user->isManager() && $client->root->manager->id == $user->id) ||
                ($user->isSublevel() && $user->subject &&
                    ($client->id == $user->subject->id ||
                    (in_array($client->id, 
                            ($user->subject->expand_network())
                                    ->pluck('id')->all())))) ||
                ($user->isConsumer() && $user->subject &&
                    $client->id == $user->subject->id) ||
                $user->employer && $user->employer->id == $client->id
         ;
    }

    public function network(User $user, Client $client)//??
    {
        return $user->isCompanyAdmin() ||
                ($user->isClientAdmin() && $user->subject &&
                    $client->root->id == $user->subject->id) ||
                ($user->isManager() && $client->root->manager->id == $user->id) ||
                ($user->isSublevel() && $user->subject &&
                    ($client->id == $user->subject->id ||
                    (in_array($client->id, 
                            ($user->subject->expand_network())
                                    ->pluck('id')->all())))) ||
                ($user->isConsumer() && $user->subject &&
                    $client->id == $user->subject->id)
         ;
    }

    /**
     * Determine whether the user can create clients.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isClientAdmin() && $user->subject || $user->isCompanyAdmin();
    }

    /**
     * Determine whether the user can update the client.
     *
     * @param  \App\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function update(User $user, Client $client)
    {
        return ($user->isCompanyAdmin()) ||
                ($user->isManager() && $client->root->manager->id == $user->id && $client->manager !== null) ||
                ($user->isClientAdmin() && $user->subject &&
                    $client->root->id == $user->subject->id &&
                    $client->id != $user->subject->id);
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @param  \App\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function delete(User $user, Client $client)
    {
        //
    }
}

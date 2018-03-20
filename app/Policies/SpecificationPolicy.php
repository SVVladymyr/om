<?php

namespace App\Policies;

use App\User;
use App\Specification;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecificationPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isClientAdmin() || 
                $user->isManager();
    }

    public function upload(User $user, Specification $specification)
    {
        return $user->isManager() && 
                in_array($specification->id,
                $user->specifications()->pluck('id')->all());
    }

    /**
     * Determine whether the user can view the specification.
     *
     * @param  \App\User  $user
     * @param  \App\Specification  $specification
     * @return mixed
     */
    public function view(User $user, Specification $specification)
    {
        return ($user->isClientAdmin() && 
                $user->subject && $user->subject->specification &&
                (in_array($specification->id, 
                    $user->subject->specification->sub_specifications()->pluck('id')->all()) ||
                $specification->id == $user->subject->specification->id)) || 

                ($user->isManager() && 
                    in_array($specification->id, 
                        $user->specifications()->pluck('id')->all()));
    }

    /**
     * Determine whether the user can create specifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isClientAdmin() && 
                $user->subject && $user->subject->specification || 
                $user->isManager();
    }

    /**
     * Determine whether the user can update the specification.
     *
     * @param  \App\User  $user
     * @param  \App\Specification  $specification
     * @return mixed
     */
    public function update(User $user, Specification $specification)
    {
        return ($user->isClientAdmin() && 
                (in_array($specification->id, 
                    $user->employer->specification->sub_specifications()->pluck('id')->all()) ||
                $specification->id == $user->employer->specification->id)) || 

                ($user->isManager() && 
                    in_array($specification->id, 
                        $user->specifications()->pluck('id')->all()));
    }

    /**
     * Determine whether the user can delete the specification.
     *
     * @param  \App\User  $user
     * @param  \App\Specification  $specification
     * @return mixed
     */
    public function delete(User $user, Specification $specification)
    {
        return ($user->isClientAdmin() && 
                (in_array($specification->id, 
                    $user->employer->specification->sub_specifications()->pluck('id')->all()))) || 

                ($user->isManager() && 
                    in_array($specification->id, 
                        $user->specifications()->pluck('id')->all()));
    }
}

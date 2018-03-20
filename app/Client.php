<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Client
 *
 * @property int $id
 * @property int|null $1c_id
 * @property string $name
 * @property string|null $code
 * @property int|null $manager_id
 * @property int $master_id
 * @property int|null $ancestor_id
 * @property int|null $root_id
 * @property int|null $specification_id
 * @property string $phone_number
 * @property string $address
 * @property string|null $main_contractor
 * @property string|null $organization
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Client|null $ancestor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CostItem[] $cost_items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Limit[] $limits
 * @property-read \App\User|null $manager
 * @property-read \App\User $master
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Client[] $network
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @property-read \App\Client|null $root
 * @property-read \App\Specification $specification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Client[] $successors
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client where1cId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereAncestorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereMainContractor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereRootId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereSpecificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $one_c_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $hired
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereOneCId($value)
 */
class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guid', '1c_id', 'name', 'code', 'manager_id',
        'master_id', 'ancestor_id', 'root_id', 'specification_id',
        'phone_number', 'address', 'main_contractor', 'organization',
    ];

    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function hired()
    {
        return $this->hasMany(User::class, 'employer_id');
    }

    public function ancestor()
    {
        return $this->belongsTo(Client::class, 'ancestor_id');
    }

    public function successors()
    {
        return $this->hasMany(Client::class, 'ancestor_id');
    }

    public function root()
    {
        return $this->belongsTo(Client::class, 'root_id');
    }

    public function network()
    {
        return $this->hasMany(Client::class, 'root_id');
    }

    public function cost_items()
    {
        return $this->hasMany(CostItem::class);
    }

    public function limits()
    {
        return $this->hasMany(Limit::class);
    }

    public function specification()
    {
        return $this->belongsTo(Specification::class, 'specification_id');
    }

    public function real_specification()
    {
        return $this->specification ?
            $this->specification() :
            $this->root->specification();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    public function limit_increases()
    {
        return $this->hasMany(LimitIncrease::class, 'client_id');
    }

    public function expand_network()
    {
        $successors = $this->successors;

        $clients = collect();

            if($successors) {
                foreach ($successors as $successor) {              
                    $clients = $clients->push($successor);

                    if($successor->successors){
                        $clients = $clients->merge($successor->expand_network());
                    }
                }
            }

        return $clients;        
    }


}

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $phone_number
 * @property int $show_price_status
 * @property int $role_id
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Client[] $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @property-read \App\Role $role
 * @property-read \App\Client $subject
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereShowPriceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $employer_id
 * @property-read \App\Client|null $employer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmployerId($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'first_name', 'email', 'phone_number', 'password', 'show_price_status', 'role_id', 'employer_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function subject()
    {
        return $this->hasOne(Client::class, 'master_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'manager_id');
    }

    public function employer()
    {
        return $this->belongsTo(Client::class, 'employer_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function specifications()
    {
        return $this->hasMany(Specification::class, 'manager_id');
    }

    public function isClientAdmin()
    {
        return $this->role->name == 'client_admin';
    }

    public function isCompanyAdmin()
    {
        return $this->role->name == 'company_admin';
    }

    public function isSublevel()
    {
        return $this->role->name == 'sublevel';
    }

    public function isConsumer()
    {
        return $this->role->name == 'consumer';
    }

    public function isManager()
    {
        return $this->role->name == 'manager';
    }
    
}

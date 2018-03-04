<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Order
 *
 * @property int $id
 * @property int $client_id
 * @property float $sum
 * @property int $status_id
 * @property string|null $expected_delivery_date
 * @property string|null $delivery_date
 * @property int $customer_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Client $client
 * @property-read \App\User $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property-read \App\OrderStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereExpectedDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guid', 'client_id', 'sum', 'order_status_id', 'expected_delivery_date',
        'delivery_date', 'customer_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
}

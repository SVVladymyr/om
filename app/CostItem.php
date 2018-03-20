<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CostItem
 *
 * @property int $id
 * @property string $name
 * @property int $client_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Limit[] $limits
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostItem whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CostItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'client_id',
    ];

    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    
    public function limits()
    {
        return $this->morphMany(Limit::class, 'limitable');
    }


}

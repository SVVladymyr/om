<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Limit
 *
 * @property int $id
 * @property int $value
 * @property int $client_id
 * @property int $limitable_id
 * @property string|null $limitable_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Client $client
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $limitable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Limit whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Limit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Limit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Limit whereLimitableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Limit whereLimitableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Limit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Limit whereValue($value)
 * @mixin \Eloquent
 */
class Limit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'current_value', 'client_id', 'limitable_id', 'limitable_type', 'active' 
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function limitable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LimitIncrease extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'consumer_id', 'item', 'amount_asked', 'amount_increased',
        'created_at', 'confirmed_at', 'aborted_at'
    ];

    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}

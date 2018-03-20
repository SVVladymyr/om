<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Events\SpecificationDeleted;

/**
 * App\Specification
 *
 * @property int $id
 * @property string $name
 * @property int $main_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Client $client
 * @property-read \App\Specification $main_specification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Specification[] $sub_specifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Specification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Specification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Specification whereMainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Specification whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Specification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Specification extends Model
{
     /**
     * The event map for the model.
     *
     * @var array
     */
    protected $events = [
        'deleted' => SpecificationDeleted::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'main_id', 'manager_id', 'order_begin', 'order_end'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'specification_id');
    }

    public function main_specification()
    {
        return $this->belongsTo(Specification::class, 'main_id');
    }

    public function sub_specifications()
    {
        return $this->hasMany(Specification::class, 'main_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public static function products(Specification $specification)
    {
        $products_limits = DB::table('product_specification')
                                ->where('specification_id', '=', $specification->id)
                                ->get(['product_id', 'limit', 'period']);

        if($specification->main_specification) {
            $products_prices = DB::table('product_specification')
                                    ->where('specification_id', '=', $specification->main_specification->id)
                                    ->get(['product_id', 'price', 'cost_item_id']);

        }else {
            $products_prices = DB::table('product_specification')
                                    ->where('specification_id', '=', $specification->id)
                                    ->get(['product_id', 'price', 'cost_item_id']);
        }

        $product_ids = DB::table('product_specification')
                                    ->where('specification_id', '=', $specification->id)
                                    ->pluck('product_id')
                                    ->toArray();
        $products = Product::whereIn('product_id', $product_ids)->get();   

        foreach ($products as $product) {
            $product->price = $products_prices
                                ->where('product_id', $product->product_id)
                                ->pluck('price')
                                ->first();
            $product->cost_item = $products_prices
                                ->where('product_id', $product->product_id)
                                ->pluck('cost_item_id')
                                ->first();

            $product->limit = $products_limits
                                ->where('product_id', $product->product_id)
                                ->pluck('limit')
                                ->first();
            $product->period = $products_limits
                                ->where('product_id', $product->product_id)
                                ->pluck('period')
                                ->first();

        } 
        return $products;
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Product
 *
 * @property int $product_id
 * @property string $model
 * @property string $sku
 * @property string $upc
 * @property string $ean
 * @property string $jan
 * @property string $isbn
 * @property string $mpn
 * @property string $location
 * @property int $quantity
 * @property int $stock_status_id
 * @property string|null $image
 * @property int $manufacturer_id
 * @property int $shipping
 * @property float $price
 * @property int $points
 * @property int $tax_class_id
 * @property string $date_available
 * @property float $weight
 * @property int $weight_class_id
 * @property float $length
 * @property float $width
 * @property float $height
 * @property int $length_class_id
 * @property int $subtract
 * @property int $minimum
 * @property int $sort_order
 * @property int $status
 * @property string $date_added
 * @property string $date_modified
 * @property int $viewed
 * @property int|null $days_pod_zakaz
 * @property int|null $metka_actia
 * @property int|null $metka-top-prodazh
 * @property int|null $metka-novinki
 * @property int $quantity_in_package
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CostItem[] $cost_items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Limit[] $limits
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Specification[] $specifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDateAdded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDateAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDateModified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDaysPodZakaz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereEan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereJan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereLengthClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereMetkaActia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereMetkaNovinki($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereMetkaTopProdazh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereMinimum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereMpn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereQuantityInPackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereStockStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereSubtract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereTaxClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereWeightClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereWidth($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql1';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oc_product';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';

    

    public function description()
    {
        return $this->hasOne(Description::class, 'product_id', 'product_id');
    }
    
}

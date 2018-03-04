<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
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
    protected $table = 'oc_product_description';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}

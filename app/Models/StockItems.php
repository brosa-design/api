<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItems extends Model {

    protected $table = 'stock_items';
    protected $fillable = ['sku', 'status', 'physical_status', 'order_id', 'package_id', 'created_by', 'product_variant_id'];

    public function order() 
    {

        return $this->belongsTo(Orders::class);
    }

    public function stockable() 
    {

        return $this->morphTo();
    }

    /**
     * Updates and attribute's value or Creates an attribute if it does not exist
     * depeding on the request
     * 
     * @param       String  $sku 
     * @response    Stock Items object 
     */
    public function matchSku($sku) 
    {

        return $this->where(['sku' => $sku, 'status' => 'Available'])->get();
    }

}
?>

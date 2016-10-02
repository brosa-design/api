<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockItems extends Model {

    protected $table = 'stock_items';
    protected $fillable = ['sku', 'status', 'physical_status', 'order_id', 'package_id', 'created_by', 'product_variant_id'];

    public function order() {
        return $this->belongsTo(Orders::class);
    }

    public function stockable() {
        return $this->morphTo();
    }

}

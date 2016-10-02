<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model {

    protected $table = 'product_variants';

    public function product() {
        return $this->belongsTo(Products::class);
    }

    public function attributables() {
        return $this->morphMany('App\Attributables', 'attributable');
    }

    public function stockables() {
        return $this->morphMany('App\StockItems', 'stockable');
    }

    public function packables() {
        return $this->morphMany('App\Packages', 'packable');
    }

}

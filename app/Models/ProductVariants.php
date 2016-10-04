<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Attributes;
use App\Models\StockItems;

class ProductVariants extends Model 
{

    protected $table = 'product_variants';

    public function product() 
    {

        return $this->belongsTo(Products::class);
    }

    public function attributables() 
    {

        return $this->morphMany('App\Models\Attributables', 'attributable');
    }

    public function stockables() 
    {

        return $this->morphMany('App\Models\StockItems', 'stockable');
    }

    public function packables() 
    {

        return $this->morphMany('App\Models\Packages', 'packable');
    }

}
?>

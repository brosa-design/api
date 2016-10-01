<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
    protected $table = 'product_variants';
    
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    
    public function attributables()
    {
        return $this->morphMany('App\Attributables', 'attributable');
    }
    
}

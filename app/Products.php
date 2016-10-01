<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    
    public function collection()
    {
        return $this->belongsTo(Collections::class);
    }
    
    public function variant()
    {
        return $this->hasMany(ProductVariants::class);
    }
}
